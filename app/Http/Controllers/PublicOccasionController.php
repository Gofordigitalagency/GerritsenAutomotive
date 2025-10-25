<?php

namespace App\Http\Controllers;

use App\Models\Occasion;
use Illuminate\Http\Request;

class PublicOccasionController extends Controller
{
    public function home()
    {
        $nieuw = Occasion::latest()->take(12)->get();
        return view('home', compact('nieuw'));
    }

    public function index(Request $request)
    {
        $occasions = Occasion::latest()->paginate(12);
        return view('occasions.index', compact('occasions'));
    }

    public function show($slug)
    {
        $occasion = Occasion::where('slug', $slug)->firstOrFail();
        return view('occasions.show', compact('occasion'));
    }
}
