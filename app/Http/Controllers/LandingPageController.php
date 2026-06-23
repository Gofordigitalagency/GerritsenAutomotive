<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Occasion;

class LandingPageController extends Controller
{
    /**
     * Toon een publieke SEO-landingspagina op /{slug}.
     */
    public function show(string $slug)
    {
        $page = LandingPage::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        abort_if(!$page, 404);

        // Optioneel het actuele aanbod tonen (zelfde selectie als de homepage)
        $nieuw = collect();
        if ($page->show_occasions) {
            $nieuw = Occasion::query()
                ->where('binnenkort', false)
                ->orderByRaw("CASE WHEN model LIKE '%(VERKOCHT)%' THEN 1 ELSE 0 END ASC")
                ->latest()
                ->get();
        }

        return view('landing.show', compact('page', 'nieuw'));
    }
}
