<?php

namespace App\Http\Controllers;

use App\Models\Occasion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublicOccasionController extends Controller
{
public function home()
{
    $nieuw = Occasion::query()
        ->where('binnenkort', false)
        ->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC")
        ->latest()
        ->get();

    return view('home', compact('nieuw'));
}

public function preview()
{
    $nieuw = Occasion::query()
        ->where('binnenkort', false)
        ->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC")
        ->latest()
        ->get();

    return view('preview', compact('nieuw'));
}

public function previewAdmin()
{
    return view('preview-admin');
}

public function rdwFull(string $kenteken)
{
    $kenteken = strtoupper(preg_replace('/[^A-Z0-9]/', '', $kenteken));

    if (strlen($kenteken) < 4 || strlen($kenteken) > 8) {
        return response()->json(['message' => 'Ongeldig kenteken'], 422);
    }

    $mainRes = \Illuminate\Support\Facades\Http::timeout(6)->get('https://opendata.rdw.nl/resource/m9d7-ebf2.json', [
        'kenteken' => $kenteken, '$limit' => 1,
    ]);
    if ($mainRes->failed()) return response()->json(['message' => 'RDW niet bereikbaar'], 502);
    $v = $mainRes->json()[0] ?? null;
    if (!$v) return response()->json(['message' => 'Kenteken niet gevonden'], 404);

    $fuelRes = \Illuminate\Support\Facades\Http::timeout(6)->get('https://opendata.rdw.nl/resource/8ys7-d773.json', [
        'kenteken' => $kenteken, '$limit' => 1, '$order' => 'brandstof_volgnummer ASC',
    ]);
    $fuel0 = $fuelRes->ok() ? ($fuelRes->json()[0] ?? []) : [];

    $toDate = function ($yyyymmdd) {
        if (!$yyyymmdd || strlen((string)$yyyymmdd) !== 8) return null;
        $s = (string)$yyyymmdd;
        return substr($s, 0, 4) . '-' . substr($s, 4, 2) . '-' . substr($s, 6, 2);
    };

    $bouwjaar = null;
    if (!empty($v['datum_eerste_toelating']) && strlen($v['datum_eerste_toelating']) >= 4) {
        $bouwjaar = (int) substr($v['datum_eerste_toelating'], 0, 4);
    }

    $massaLedig    = isset($v['massa_ledig_voertuig']) ? (int) $v['massa_ledig_voertuig'] : null;
    $toegestaanMax = isset($v['toegestane_maximum_massa_voertuig']) ? (int) $v['toegestane_maximum_massa_voertuig'] : null;
    $laadvermogen  = isset($v['laadvermogen']) ? (int) $v['laadvermogen'] : null;
    if (!$laadvermogen && $massaLedig && $toegestaanMax) {
        $laadvermogen = max(0, $toegestaanMax - $massaLedig);
    }

    $brandstofTekst = strtolower($fuel0['brandstof_omschrijving'] ?? '');
    $brandstof = null;
    if (str_contains($brandstofTekst, 'benzine')) $brandstof = 'Benzine';
    elseif (str_contains($brandstofTekst, 'diesel')) $brandstof = 'Diesel';
    elseif (str_contains($brandstofTekst, 'elektr')) $brandstof = 'Elektrisch';
    elseif (str_contains($brandstofTekst, 'hybr')) $brandstof = 'Hybride';
    elseif (str_contains($brandstofTekst, 'lpg')) $brandstof = 'LPG';

    return response()->json([
        'kenteken'           => $kenteken,
        'merk'               => $v['merk'] ?? null,
        'model'              => $v['handelsbenaming'] ?? null,
        'kleur'              => $v['eerste_kleur'] ?? null,
        'bouwjaar'           => $bouwjaar,
        'cilinderinhoud'     => isset($v['cilinderinhoud']) ? (int) $v['cilinderinhoud'] : null,
        'aantal_cilinders'   => isset($v['aantal_cilinders']) ? (int) $v['aantal_cilinders'] : null,
        'aantal_deuren'      => isset($v['aantal_deuren']) ? (int) $v['aantal_deuren'] : null,
        'apk_tot'            => $toDate($v['vervaldatum_apk'] ?? null),
        'energielabel'       => $v['zuinigheidsclassificatie'] ?? null,
        'carrosserie'        => $v['inrichting'] ?? null,
        'max_trekgewicht'    => isset($v['maximum_trekken_massa_geremd']) ? (int) $v['maximum_trekken_massa_geremd'] : null,
        'topsnelheid'        => isset($v['maximale_constructiesnelheid']) ? (int) $v['maximale_constructiesnelheid'] : null,
        'gewicht'            => $massaLedig,
        'laadvermogen'       => $laadvermogen,
        'brandstof'          => $brandstof,
        'gemiddeld_verbruik' => $fuel0['brandstofverbruik_gecombineerd'] ?? null,
        'vermogen'           => isset($fuel0['nettomaximumvermogen']) ? (int) round((float) $fuel0['nettomaximumvermogen']) : null,
        'co2_uitstoot'       => isset($fuel0['co2_uitstoot_gecombineerd']) ? (int) round((float) $fuel0['co2_uitstoot_gecombineerd']) : null,
        'aantal_zitplaatsen' => isset($v['aantal_zitplaatsen']) ? (int) $v['aantal_zitplaatsen'] : null,
    ]);
}

public function aiDescribe(\Illuminate\Http\Request $request)
{
    $data = $request->validate([
        'merk'      => 'nullable|string',
        'model'     => 'nullable|string',
        'bouwjaar'  => 'nullable|integer',
        'tellerstand' => 'nullable|integer',
        'brandstof' => 'nullable|string',
        'transmissie' => 'nullable|string',
        'kleur'     => 'nullable|string',
        'opties'    => 'nullable|array',
        'tone'      => 'nullable|string',
    ]);

    $merk    = $data['merk'] ?? 'Auto';
    $model   = $data['model'] ?? '';
    $jaar    = $data['bouwjaar'] ?? null;
    $km      = isset($data['tellerstand']) ? number_format((int)$data['tellerstand'], 0, ',', '.') : null;
    $brand   = $data['brandstof'] ?? null;
    $trans   = $data['transmissie'] ?? null;
    $kleur   = $data['kleur'] ? strtolower($data['kleur']) : null;
    $opts    = $data['opties'] ?? [];
    $tone    = $data['tone'] ?? 'verkooppunt';

    $auto = trim($merk . ' ' . $model);

    // Tone-templates die met de data spelen
    $intros = [
        'feitelijk' => [
            "{$auto}" . ($jaar ? " uit {$jaar}" : '') . ($km ? " met {$km} km op de teller." : '.'),
            ($jaar ? "Bouwjaar {$jaar}: " : '') . "{$auto}" . ($km ? " — {$km} km gereden." : '.'),
            "Aangeboden: {$auto}" . ($jaar ? " ({$jaar})" : '') . ".",
        ],
        'verkooppunt' => [
            "Een betrouwbare {$auto}" . ($jaar ? " uit {$jaar}" : '') . ($km ? ", met slechts {$km} km" : '') . " — klaar voor de volgende eigenaar.",
            "Maak kennis met deze {$auto}" . ($jaar ? " uit {$jaar}" : '') . ": " . ($brand ? "een zuinige " . strtolower($brand) . "-rijder" : 'een fijne dagelijkse auto') . " met karakter.",
            "Op zoek naar comfort en zekerheid? Deze {$auto}" . ($jaar ? " ({$jaar})" : '') . " biedt het allebei.",
        ],
        'premium' => [
            "Onderhouden {$auto}" . ($jaar ? " ({$jaar})" : '') . " — een tijdloze keuze voor wie kwaliteit boven hype stelt.",
            "Stijlvolle {$auto}" . ($jaar ? " uit {$jaar}" : '') . " met aandacht voor detail. " . ($km ? "{$km} km, " : '') . "in nette staat.",
            "Klassieke {$auto}" . ($jaar ? " uit {$jaar}" : '') . " — een auto die zijn waarde behoudt.",
        ],
    ];

    $tonsBlok = [
        'feitelijk' => [
            ($brand && $trans) ? "Brandstof: {$brand}. Transmissie: " . strtolower($trans) . "." : null,
            $kleur ? "Kleur: " . ucfirst($kleur) . "." : null,
            "Volledige historie en NAP-controle beschikbaar.",
        ],
        'verkooppunt' => [
            $brand ? "Met " . strtolower($brand) . "-motor is hij zuinig en betrouwbaar in dagelijks gebruik." : null,
            "Volledig onderhouden, NAP-controleerbaar en BOVAG-zekerheid.",
            "Direct rijklaar mee te nemen — geen verrassingen achteraf.",
        ],
        'premium' => [
            $kleur ? "Uitgevoerd in een tijdloze " . strtolower($kleur) . "e tint." : null,
            "Met volledige historie en eerste-eigenaar-zorg gepresenteerd.",
            "Een auto met smaak voor wie weet wat hij zoekt.",
        ],
    ];

    $afsluitend = [
        'feitelijk'   => "Interesse? Plan een proefrit via de site of kom langs.",
        'verkooppunt' => "Maak een afspraak voor een proefrit — je voelt direct het verschil.",
        'premium'     => "Bezoek onze showroom in Arnhem voor een persoonlijk advies.",
    ];

    $intro = $intros[$tone][array_rand($intros[$tone])];
    $blok  = array_filter($tonsBlok[$tone]);
    $blokTekst = implode(' ', $blok);
    $eind  = $afsluitend[$tone];

    $optsTekst = '';
    if (!empty($opts)) {
        $optsTekst = "\n\nVoorzien van: " . implode(', ', array_slice($opts, 0, 8)) . '.';
    }

    $beschrijving = trim($intro . "\n\n" . $blokTekst . $optsTekst . "\n\n" . $eind);

    return response()->json([
        'tone' => $tone,
        'text' => $beschrijving,
    ]);
}

public function priceSuggest(\Illuminate\Http\Request $request)
{
    $merk = trim((string) $request->get('merk', ''));
    $model = trim((string) $request->get('model', ''));
    $bouwjaar = (int) $request->get('bouwjaar', 0);

    if ($merk === '') {
        return response()->json(['message' => 'merk vereist'], 422);
    }

    $q = Occasion::query()
        ->whereNotNull('prijs')
        ->where('prijs', '>', 0)
        ->where('merk', $merk);

    if ($model !== '') {
        $q->where('model', 'like', $model . '%');
    }
    if ($bouwjaar > 0) {
        $q->whereBetween('bouwjaar', [$bouwjaar - 2, $bouwjaar + 2]);
    }

    $rows = $q->limit(50)->pluck('prijs')->map(fn($p) => (int) $p)->all();

    if (empty($rows)) {
        return response()->json(['count' => 0, 'message' => 'Geen vergelijkbare data — stel zelf een prijs in.']);
    }

    sort($rows);
    $count = count($rows);
    $min = $rows[0];
    $max = $rows[$count - 1];
    $avg = (int) round(array_sum($rows) / $count);

    return response()->json([
        'count' => $count,
        'min'   => $min,
        'max'   => $max,
        'avg'   => $avg,
    ]);
}

public function rdwPublic(string $kenteken)
{
    $kenteken = strtoupper(preg_replace('/[^A-Z0-9]/', '', $kenteken));

    if (strlen($kenteken) < 4 || strlen($kenteken) > 8) {
        return response()->json(['message' => 'Ongeldig kenteken'], 422);
    }

    $mainRes = Http::timeout(6)->get('https://opendata.rdw.nl/resource/m9d7-ebf2.json', [
        'kenteken' => $kenteken,
        '$limit'   => 1,
    ]);

    if ($mainRes->failed()) {
        return response()->json(['message' => 'RDW niet bereikbaar'], 502);
    }

    $v = $mainRes->json()[0] ?? null;
    if (!$v) {
        return response()->json(['message' => 'Kenteken niet gevonden'], 404);
    }

    $fuelRes = Http::timeout(6)->get('https://opendata.rdw.nl/resource/8ys7-d773.json', [
        'kenteken' => $kenteken,
        '$limit'   => 1,
        '$order'   => 'brandstof_volgnummer ASC',
    ]);
    $fuel0 = $fuelRes->ok() ? ($fuelRes->json()[0] ?? []) : [];

    $bouwjaar = null;
    if (!empty($v['datum_eerste_toelating']) && strlen($v['datum_eerste_toelating']) >= 4) {
        $bouwjaar = (int) substr($v['datum_eerste_toelating'], 0, 4);
    }

    // APK-vervaldatum (RDW levert YYYYMMDD)
    $apkTot = null;
    if (!empty($v['vervaldatum_apk']) && strlen($v['vervaldatum_apk']) === 8) {
        $s = (string) $v['vervaldatum_apk'];
        $apkTot = substr($s, 0, 4) . '-' . substr($s, 4, 2) . '-' . substr($s, 6, 2);
    }

    $brandstofTekst = strtolower($fuel0['brandstof_omschrijving'] ?? '');
    $brandstof = null;
    if (str_contains($brandstofTekst, 'benzine')) $brandstof = 'Benzine';
    elseif (str_contains($brandstofTekst, 'diesel')) $brandstof = 'Diesel';
    elseif (str_contains($brandstofTekst, 'elektr')) $brandstof = 'Elektrisch';
    elseif (str_contains($brandstofTekst, 'hybr')) $brandstof = 'Hybride';
    elseif (str_contains($brandstofTekst, 'lpg')) $brandstof = 'LPG';

    return response()->json([
        'kenteken'  => $kenteken,
        'merk'      => $v['merk'] ?? null,
        'model'     => $v['handelsbenaming'] ?? null,
        'kleur'     => $v['eerste_kleur'] ?? null,
        'bouwjaar'  => $bouwjaar,
        'carrosserie' => $v['inrichting'] ?? null,
        'brandstof' => $brandstof,
        'apk_tot'   => $apkTot,
    ]);
}

public function binnenkort()
{
    $nieuw = Occasion::query()
        ->where('binnenkort', true)
        ->latest()
        ->get();

    return view('occasions.binnenkort', compact('nieuw'));
}

public function index(Request $request)
{
    $sort = $request->get('sort', 'best');

    $q = Occasion::query()->where('binnenkort', false);

    // ✅ Verkocht altijd onderaan houden
    $q->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC");

    // ✅ Sorteren (whitelist)
    switch ($sort) {
        case 'price_asc':
            $q->orderBy('prijs', 'asc');
            break;

        case 'price_desc':
            $q->orderBy('prijs', 'desc');
            break;

        case 'newest':
            $q->orderBy('created_at', 'desc');
            break;

        case 'km_asc':
            $q->orderBy('tellerstand', 'asc');
            break;

        case 'km_desc':
            $q->orderBy('tellerstand', 'desc');
            break;

        case 'year_asc':
            $q->orderBy('bouwjaar', 'asc');
            break;

        case 'year_desc':
            $q->orderBy('bouwjaar', 'desc');
            break;

        // Vermogen alleen toevoegen als je echt een kolom hebt (bijv. 'vermogen' of 'pk')
        // case 'power_asc':
        //     $q->orderBy('vermogen', 'asc');
        //     break;
        // case 'power_desc':
        //     $q->orderBy('vermogen', 'desc');
        //     break;

        default: // best
            $q->orderBy('created_at', 'desc');
            break;
    }

    $occasions = $q->get();

    return view('occasions.index', compact('occasions', 'sort'));
}

    public function show($slug)
    {
        $occasion = Occasion::where('slug', $slug)->firstOrFail();
        return view('occasions.show', compact('occasion'));
    }

    public function cards(Request $request)
{
    $sort = $request->get('sort', 'best');

    $q = Occasion::query()->where('binnenkort', false);
    $q->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC");

    switch ($sort) {
        case 'price_asc':  $q->orderBy('prijs', 'asc'); break;
        case 'price_desc': $q->orderBy('prijs', 'desc'); break;
        case 'newest':     $q->orderBy('created_at', 'desc'); break;
        case 'km_asc':     $q->orderBy('tellerstand', 'asc'); break;
        case 'km_desc':    $q->orderBy('tellerstand', 'desc'); break;
        case 'year_asc':   $q->orderBy('bouwjaar', 'asc'); break;
        case 'year_desc':  $q->orderBy('bouwjaar', 'desc'); break;
        default:           $q->orderBy('created_at', 'desc');
    }

    $nieuw = $q->get();

    return view('occasions.partials.home_cards', compact('nieuw'));
}
}
