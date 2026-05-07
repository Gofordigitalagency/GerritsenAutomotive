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
