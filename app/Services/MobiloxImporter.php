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

        $occasion->merk        = $this->val($xml, 'merk');
        $occasion->model       = $this->val($xml, 'model');
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
        $occasion->omschrijving = $this->val($xml, 'opmerkingen_nederlands');

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

        $occasion->overige_options = $this->extractOptions($xml);
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

    private function extractOptions(\SimpleXMLElement $xml): array
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
