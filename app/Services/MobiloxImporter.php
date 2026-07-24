<?php

namespace App\Services;

use App\Models\Occasion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Verwerkt incrementele voorraadmutaties van Hexon/Mobilox (EWI).
 * Elke POST bevat één voertuig met een attribuut `actie` (add/change/delete).
 *
 * NB: zolang we nog geen echte productie-XML hebben, loggen we elke payload
 * (storage/app/mobilox/incoming) zodat de mapping daarna exact afgestemd kan
 * worden. De veldnamen volgen het Hexon voertuig.xsd-schema.
 */
class MobiloxImporter
{
    /** Voertuigen waarvan de foto's ná de HTTP-respons opgehaald worden. */
    private array $pendingPhotos = [];

    public function handle(string $raw): string
    {
        $this->logRaw($raw);

        if (trim($raw) === '') {
            return 'Geen XML ontvangen';
        }

        $xml = @simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            Log::warning('Mobilox: ongeldige XML ontvangen.');
            return 'Geen geldige XML ontvangen';
        }

        $actie   = strtolower(trim((string) $xml['actie']));
        $hexonNr = (int) $this->val($xml, 'voertuignr_hexon');

        if ($hexonNr <= 0) {
            Log::warning('Mobilox: voertuignr_hexon ontbreekt.', ['actie' => $actie]);
            return 'voertuignr_hexon ontbreekt';
        }

        try {
            switch ($actie) {
                case 'add':
                case 'change':
                    $this->upsert($xml, $hexonNr);
                    break;
                case 'delete':
                    $this->remove($hexonNr);
                    break;
                default:
                    Log::warning('Mobilox: onbekende actie.', ['actie' => $actie, 'hexon' => $hexonNr]);
                    return 'Onbekende actie: ' . $actie;
            }
        } catch (\Throwable $e) {
            Log::error('Mobilox verwerking mislukt: ' . $e->getMessage(), [
                'hexon' => $hexonNr, 'actie' => $actie, 'exception' => $e,
            ]);
            return 'Verwerking mislukt';
        }

        return '1';
    }

    // ------------------------------------------------------------------ add/change

    private function upsert(\SimpleXMLElement $xml, int $hexonNr): void
    {
        $occasion = Occasion::firstOrNew(['hexon_nr' => $hexonNr]);

        // Verkocht-status vaststellen vóór we velden overschrijven. Mobilox stuurt
        // alleen het kale model; als de auto in de admin als verkocht is gemarkeerd
        // (verkocht_datum of "(VERKOCHT)"-marker) moet die markering behouden blijven,
        // anders duikt een verkochte auto weer op in het publieke aanbod.
        $wasSold = $occasion->exists && $occasion->is_sold;

        $occasion->merk        = $this->val($xml, 'merk');
        $model = $this->val($xml, 'model');
        if ($wasSold && $model !== null && stripos($model, '(VERKOCHT)') === false) {
            $model = trim($model . ' (VERKOCHT)');
        }
        $occasion->model       = $model;
        $occasion->type        = $this->val($xml, 'type');
        $occasion->kenteken    = $this->val($xml, 'kenteken');
        $occasion->carrosserie = $this->val($xml, 'carrosserie');
        $occasion->kleur       = $this->val($xml, 'kleur_nederlands') ?? $this->val($xml, 'basiskleur');
        $occasion->bouwjaar    = $this->intVal($xml, 'bouwjaar');
        $occasion->aantal_deuren = $this->intVal($xml, 'aantal_deuren');
        $occasion->vermogen_pk = $this->intVal($xml, 'vermogen_motor_pk');
        $occasion->tellerstand = $this->intVal($xml, 'tellerstand');
        $occasion->brandstof   = $this->mapBrandstof($this->val($xml, 'brandstof'));
        $occasion->transmissie = $this->mapTransmissie($this->val($xml, 'transmissie'));
        $occasion->omschrijving = $this->descriptionFromXml($xml);

        // Prijzen: bedrag uit <verkoopprijs_particulier>/<actieprijs>/<inkoopprijs>.
        // Actieprijs lager dan vraagprijs => korting tonen.
        $verkoop = $this->priceFromNode($xml->verkoopprijs_particulier ?? null);
        $actie   = $this->priceFromNode($xml->actieprijs ?? null);
        if ($actie && $verkoop && $actie < $verkoop) {
            $occasion->prijs      = $actie;
            $occasion->oude_prijs = $verkoop;
        } else {
            $occasion->prijs      = $verkoop ?: $actie;
            $occasion->oude_prijs = null;
        }
        $inkoop = $this->priceFromNode($xml->inkoopprijs ?? null);
        if ($inkoop) {
            $occasion->inkoop_prijs = $inkoop;
        }

        if ($apk = $this->apkDate($xml)) {
            $occasion->apk_tot = $apk;
        }

        $mapped = $this->mapOptions($xml);
        $occasion->exterieur_options  = $mapped['exterieur']  ?: null;
        $occasion->interieur_options  = $mapped['interieur']  ?: null;
        $occasion->veiligheid_options = $mapped['veiligheid'] ?: null;
        $occasion->overige_options    = $mapped['overige']    ?: null;
        $occasion->binnenkort = false;

        $occasion->save(); // slug wordt automatisch gegenereerd bij nieuw

        // Foto's pas ná de respons downloaden (zie flushPhotos): houdt de
        // respons naar Mobilox snel, zodat de sync niet vastloopt op downloads.
        $this->pendingPhotos[] = ['xml' => $xml, 'occasion' => $occasion];
    }

    /** Download de foto's van zojuist verwerkte voertuigen (na de HTTP-respons). */
    public function flushPhotos(): void
    {
        if (empty($this->pendingPhotos)) {
            return;
        }
        @set_time_limit(180);
        foreach ($this->pendingPhotos as $p) {
            try {
                $this->syncPhotos($p['xml'], $p['occasion']);
            } catch (\Throwable $e) {
                Log::warning('Mobilox: foto-sync mislukt (#' . $p['occasion']->hexon_nr . '): ' . $e->getMessage());
            }
        }
        $this->pendingPhotos = [];
    }

    // ------------------------------------------------------------------ delete

    private function remove(int $hexonNr): void
    {
        $occasion = Occasion::where('hexon_nr', $hexonNr)->first();
        if (! $occasion) {
            return;
        }
        Storage::disk('public')->deleteDirectory("occasions/mobilox/{$hexonNr}");
        $occasion->delete();
    }

    // ------------------------------------------------------------------ foto's

    private function syncPhotos(\SimpleXMLElement $xml, Occasion $occasion): void
    {
        $urls = $this->photoUrls($xml);
        if (empty($urls)) {
            return; // geen foto's meegestuurd: bestaande laten staan
        }

        $dir = "occasions/mobilox/{$occasion->hexon_nr}";
        Storage::disk('public')->deleteDirectory($dir); // oude downloads opruimen

        $paths = [];
        foreach (array_values($urls) as $i => $url) {
            try {
                $resp = Http::timeout(20)->get($url);
                if (! $resp->ok()) {
                    continue;
                }
                $ext  = $this->guessExtension($url, $resp->header('Content-Type'));
                $path = "{$dir}/" . ($i + 1) . ".{$ext}";
                Storage::disk('public')->put($path, $resp->body());
                $paths[] = $path;
            } catch (\Throwable $e) {
                Log::warning("Mobilox: foto-download mislukt ({$url}): " . $e->getMessage());
            }
        }

        if ($paths) {
            $occasion->hoofdfoto_path = $paths[0];
            $occasion->galerij        = array_slice($paths, 1);
            $occasion->save();
        }
    }

    private function photoUrls(\SimpleXMLElement $xml): array
    {
        $urls = [];
        if (isset($xml->afbeeldingen)) {
            $this->collectUrls($xml->afbeeldingen, $urls);
        }
        return array_values(array_unique($urls));
    }

    private function collectUrls(\SimpleXMLElement $node, array &$urls): void
    {
        foreach ($node->attributes() as $attr) {
            $v = trim((string) $attr);
            if (Str::startsWith($v, ['http://', 'https://'])) {
                $urls[] = $v;
            }
        }
        $text = trim((string) $node);
        if (Str::startsWith($text, ['http://', 'https://'])) {
            $urls[] = $text;
        }
        foreach ($node->children() as $child) {
            $this->collectUrls($child, $urls);
        }
    }

    private function guessExtension(string $url, ?string $contentType): string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return $ext === 'jpeg' ? 'jpg' : $ext;
        }
        if ($contentType && str_contains($contentType, 'png'))  return 'png';
        if ($contentType && str_contains($contentType, 'webp')) return 'webp';
        return 'jpg';
    }

    // ------------------------------------------------------------------ helpers

    private function val(\SimpleXMLElement $xml, string $field): ?string
    {
        if (! isset($xml->$field)) {
            return null;
        }
        $v = trim((string) $xml->$field);
        return $v === '' ? null : $v;
    }

    private function intVal(\SimpleXMLElement $xml, string $field): ?int
    {
        $v = $this->val($xml, $field);
        if ($v === null) {
            return null;
        }
        $digits = preg_replace('/[^\d]/', '', $v);
        return $digits === '' ? null : (int) $digits;
    }

    /**
     * De omschrijving levert Hexon aan als HTML-advertentietekst in <opmerkingen>
     * (soms in <opmerkingen_nederlands>). Die tekst begint meestal met een
     * automatisch gegenereerd specificatie-/optieblok in <ul>-lijsten, gevolgd
     * door de eigenlijke verkooptekst. Specs en opties tonen we al apart op de
     * pagina, dus we knippen dat gegenereerde blok weg en houden de vrije tekst
     * over. Het resultaat is platte tekst (de pagina rendert via nl2br(e(...))).
     */
    private function descriptionFromXml(\SimpleXMLElement $xml): ?string
    {
        $html = $this->val($xml, 'opmerkingen')
            ?? $this->val($xml, 'opmerkingen_nederlands');
        if ($html === null) {
            return null;
        }

        // Gegenereerd specs/opties-blok vooraan weghalen: pak alles ná de
        // laatste </ul>. Blijft er dan geen tekst over, gebruik toch alles.
        if (stripos($html, '</ul>') !== false) {
            $rest = substr($html, strripos($html, '</ul>') + 5);
            if (trim(strip_tags($rest)) !== '') {
                $html = $rest;
            }
        }

        return $this->htmlToText($html);
    }

    /** Zet Hexon-advertentie-HTML om naar nette platte tekst met regeleindes. */
    private function htmlToText(string $html): ?string
    {
        // Lijstitems -> bullets; regel-/blok-tags -> regeleinde.
        $text = preg_replace('/<\s*li[^>]*>/i', "\n• ", $html);
        $text = preg_replace('/<\s*br\s*\/?>/i', "\n", $text);
        $text = preg_replace('/<\/\s*(p|div|ul|ol|tr|h[1-6])\s*>/i', "\n", $text);
        $text = strip_tags($text);

        // HTML-entiteiten (&euro; &egrave; &bull; &nbsp; …) decoderen.
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xC2\xA0", ' ', $text); // non-breaking space -> spatie

        // Overtollige witruimte opschonen.
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/ *\n */', "\n", $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        $text = trim($text);

        return $text === '' ? null : $text;
    }

    /**
     * Haal het bedrag (hele euro's) uit een prijs-node. De Hexon-structuur is
     * <verkoopprijs_particulier><prijzen land="nl"><prijs nr="1"><bedrag>2745</bedrag>…
     * dus we pakken specifiek het eerste <bedrag>, niet zomaar het eerste getal.
     */
    private function priceFromNode($node): ?int
    {
        if ($node === null) {
            return null;
        }
        foreach ($node->xpath('.//bedrag') as $bedrag) {
            $clean = preg_replace('/[^\d.,]/', '', (string) $bedrag);
            $clean = preg_replace('/[.,]\d{1,2}$/', '', $clean); // centen weghalen
            $clean = str_replace([',', '.'], '', $clean);        // duizendtallen weghalen
            if ($clean !== '') {
                return (int) $clean;
            }
        }
        return null;
    }

    private function apkDate(\SimpleXMLElement $xml): ?string
    {
        $cand = isset($xml->apk) ? trim((string) ($xml->apk['tot'] ?? '')) : '';
        if ($cand === '') {
            return null;
        }
        try {
            return Carbon::createFromFormat('d-m-Y', $cand)->toDateString();
        } catch (\Throwable) {
            try {
                return Carbon::parse($cand)->toDateString();
            } catch (\Throwable) {
                return null;
            }
        }
    }

    /** Alle ruwe Hexon-optienamen verzamelen (accessoires/optiepakketten/zoekaccessoires). */
    private function rawOptionNames(\SimpleXMLElement $xml): array
    {
        $opts = [];
        foreach (['accessoires', 'optiepakketten', 'zoekaccessoires'] as $group) {
            if (! isset($xml->$group)) {
                continue;
            }
            foreach ($xml->$group->children() as $child) {
                // Elk item is bv. <accessoire><naam>Airco</naam>…</accessoire>
                $naam = isset($child->naam) ? trim((string) $child->naam) : trim((string) $child);
                if ($naam !== '') {
                    $opts[] = $naam;
                }
            }
        }
        return array_values(array_unique(array_filter($opts)));
    }

    /**
     * Verdeel de Hexon-opties over de vier categorieën (exterieur/interieur/
     * veiligheid/overige) en normaliseer ze naar de canonieke labels uit
     * config/occasion_options, zodat de admin-checkboxes correct aangevinkt
     * worden.
     *
     * Hexon levert dezelfde feature vaak in meerdere schrijfwijzen (ruwe naam,
     * gestandaardiseerde naam én zoekaccessoire). Niet-herkende namen laten we
     * daarom vallen i.p.v. ze als losse tekst te bewaren — anders ontstaan
     * duplicaten ("ABS" naast "Antiblokkeersysteem"). De canonieke variant is er
     * dan al; ontdubbeling gebeurt op canoniek label.
     */
    private function mapOptions(\SimpleXMLElement $xml): array
    {
        $result = ['exterieur' => [], 'interieur' => [], 'veiligheid' => [], 'overige' => []];
        $index  = $this->canonicalOptionIndex();

        foreach ($this->rawOptionNames($xml) as $naam) {
            $hit = $this->matchOption($naam, $index);
            if ($hit === null) {
                continue; // onbekende/duplicaat-schrijfwijze overslaan
            }
            [$cat, $label] = $hit;
            if (! in_array($label, $result[$cat], true)) {
                $result[$cat][] = $label;
            }
        }
        return $result;
    }

    /** Canonieke opties uit config indexeren: genormaliseerde vorm => [categorie, label]. */
    private function canonicalOptionIndex(): array
    {
        static $index = null;
        if ($index !== null) {
            return $index;
        }
        $index = [];
        foreach ((array) config('occasion_options', []) as $cat => $labels) {
            foreach ((array) $labels as $label) {
                $norm = $this->normOpt($label);
                if ($norm === '') {
                    continue;
                }
                $index[$norm] = [$cat, $label];
                $index[str_replace(' ', '', $norm)] = [$cat, $label];
            }
        }
        return $index;
    }

    /** Eén Hexon-optienaam matchen op een canoniek label. Retour: [categorie, label] of null. */
    private function matchOption(string $naam, array $index): ?array
    {
        $n = $this->normOpt($naam);
        if ($n === '') {
            return null;
        }
        // 1) exacte (genormaliseerde) match, incl. variant zonder spaties
        if (isset($index[$n]))                        return $index[$n];
        $nospace = str_replace(' ', '', $n);
        if (isset($index[$nospace]))                  return $index[$nospace];

        // 2) alias-tabel voor bekende Hexon-afwijkingen
        $aliases = $this->optionAliases();
        if (isset($aliases[$n])) {
            return $index[$this->normOpt($aliases[$n])] ?? null;
        }

        // 3) prefix-match: "lichtmetalen velgen 16" => "Lichtmetalen velgen"
        foreach ($index as $normLabel => $catLabel) {
            if (strlen($normLabel) >= 5 && str_starts_with($n, $normLabel . ' ')) {
                return $catLabel;
            }
        }
        return null;
    }

    /** Normaliseer een optienaam voor vergelijking (lowercase, accenten/leestekens weg). */
    private function normOpt(string $s): string
    {
        $s = mb_strtolower($s, 'UTF-8');
        $s = strtr($s, [
            'á'=>'a','à'=>'a','ä'=>'a','â'=>'a','é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
            'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i','ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o',
            'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u','ç'=>'c','ñ'=>'n',
        ]);
        // veelvoorkomende afkortingen uitschrijven
        $s = preg_replace('/\belek\.?\b/u', 'elektrisch', $s);
        $s = preg_replace('/\baut\.?\b/u', 'automatisch', $s);
        // leestekens -> spatie, witruimte normaliseren
        $s = preg_replace('/[^a-z0-9]+/u', ' ', $s);
        return trim(preg_replace('/\s+/', ' ', $s));
    }

    /** Bekende Hexon-namen die afwijken van de canonieke labels. Keys worden genormaliseerd. */
    private function optionAliases(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $raw = [
            // Veiligheid
            'Anti Blokkeer Systeem' => 'ABS',
            'Airbags' => 'Airbag',
            'Bestuurdersairbag' => 'Airbag bestuurder',
            'Passagiersairbag' => 'Airbag passagier',
            'Zij airbag(s) voor' => 'Zij-airbags',
            'Zij airbag(s) achter' => 'Zij-airbags',
            'Zij airbags' => 'Zij-airbags',
            'Hoofdairbags' => 'Hoofd airbag',
            'Isofix bevestiging voor kinderzitjes' => 'Isofix',
            'Alarmsysteem' => 'Alarm',
            'Alarm klasse 1(startblokkering)' => 'Alarm',
            'Startblokkering' => 'Startonderbreker',
            'Bandenspanningscontrolesysteem' => 'Bandenspanningscontrole',
            'Elektronisch stabiliteitsprogramma' => 'Electronic Stability Program',
            'ESP' => 'Electronic Stability Program',
            // Exterieur
            'Panoramadak' => 'Panorama dak',
            'Panoramadak var. transparantie' => 'Panorama dak',
            'Metaalkleur' => 'Metallic lak',
            'Getint glas' => 'Getinte ramen',
            'Zonwerend glas' => 'Getinte ramen',
            // Interieur / klimaat / media
            'Airco' => 'Airconditioning',
            'Airco automatisch' => 'Automatische klimaatregeling',
            'Climate control' => 'Automatische klimaatregeling',
            'Climatronic' => 'Automatische klimaatregeling',
            'Elektrische ramen voor' => 'Elektrische ramen',
            'Elektrische ramen achter' => 'Elektrische ramen',
            'Elek. bedienbare ramen' => 'Elektrische ramen',
            'Buitenspiegels elektrisch verstelbaar' => 'Elektrisch verstelbare buitenspiegels',
            'Buitenspiegels elektrisch verstel- en verwarmbaar' => 'Elektrisch verstelbare buitenspiegels',
            'Elek. verstelbare spiegels' => 'Elektrisch verstelbare buitenspiegels',
            'Radio CD speler' => 'Radio',
            'Radio/CD-speler' => 'Radio',
            'Navigatie' => 'Navigatiesysteem',
            'Cruise controle' => 'Cruise control',
            // Overige
            'Centrale vergrendeling met afstandsbediening' => 'Centrale deurvergrendeling met afstandsbediening',
            'Binnenspiegel aut. dimmend' => 'Binnenspiegel automatisch dimmend',
        ];
        $out = [];
        foreach ($raw as $k => $v) {
            $out[$this->normOpt($k)] = $v;
        }
        return $cache = $out;
    }

    private function mapBrandstof(?string $v): ?string
    {
        if (! $v) {
            return null;
        }
        $map = ['B' => 'Benzine', 'D' => 'Diesel', 'L' => 'LPG', 'E' => 'Elektrisch', 'H' => 'Hybride', 'W' => 'Waterstof', 'O' => 'Overig'];
        return $map[strtoupper($v)] ?? $v;
    }

    private function mapTransmissie(?string $v): ?string
    {
        if (! $v) {
            return null;
        }
        $map = ['H' => 'Handgeschakeld', 'A' => 'Automaat', 'S' => 'Semi-automaat', 'C' => 'Automaat'];
        return $map[strtoupper($v)] ?? $v;
    }

    private function logRaw(string $raw): void
    {
        try {
            Storage::disk('local')->put(
                'mobilox/incoming/' . now()->format('Ymd_His') . '_' . Str::random(6) . '.xml',
                $raw
            );
        } catch (\Throwable) {
            // logging mag nooit de verwerking breken
        }
    }
}
