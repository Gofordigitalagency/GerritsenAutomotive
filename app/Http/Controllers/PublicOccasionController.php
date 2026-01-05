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
    $occasions = Occasion::query()
        ->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC")
        ->latest()
        ->get();

    return view('occasions.index', compact('occasions'));
}

    public function show($slug)
    {
        $occasion = Occasion::where('slug', $slug)->firstOrFail();
        return view('occasions.show', compact('occasion'));
    }
}
