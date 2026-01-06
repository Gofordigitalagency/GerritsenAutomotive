<?php

namespace App\Http\Controllers;

use App\Models\Occasion;
use Illuminate\Http\Request;

class PublicOccasionController extends Controller
{
public function home()
{
    $nieuw = Occasion::query()
        ->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC")
        ->latest()
        ->get();

    return view('home', compact('nieuw'));
}

public function index(Request $request)
{
    $sort = $request->get('sort', 'best');

    $q = Occasion::query();

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

    $q = Occasion::query();
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
