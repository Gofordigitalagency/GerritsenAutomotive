<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Occasion;

class SitemapController extends Controller
{
    /** Dynamische XML-sitemap voor zoekmachines. */
    public function index()
    {
        $urls = [];

        // Vaste pagina's
        $static = [
            ['home',                 'daily',   '1.0'],
            ['aanbod',               'daily',   '0.9'],
            ['werkplaats',           'monthly', '0.6'],
            ['diensten',             'monthly', '0.6'],
            ['sellcar.show',         'monthly', '0.5'],
            ['over',                 'monthly', '0.4'],
            ['contact',              'monthly', '0.4'],
            ['occasions.binnenkort', 'weekly',  '0.5'],
        ];
        foreach ($static as [$name, $freq, $prio]) {
            $urls[] = ['loc' => route($name), 'changefreq' => $freq, 'priority' => $prio];
        }

        // Beschikbare occasions (niet binnenkort, niet verkocht)
        Occasion::where('binnenkort', false)
            ->where(function ($q) {
                $q->whereNull('model')->orWhere('model', 'NOT LIKE', '%(VERKOCHT)%');
            })
            ->whereNotNull('slug')
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at'])
            ->each(function ($o) use (&$urls) {
                $urls[] = [
                    'loc'        => route('occasions.show', $o->slug),
                    'lastmod'    => optional($o->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                ];
            });

        // Gepubliceerde landingspagina's
        LandingPage::where('is_published', true)
            ->get(['slug', 'updated_at'])
            ->each(function ($p) use (&$urls) {
                $urls[] = [
                    'loc'        => url('/' . $p->slug),
                    'lastmod'    => optional($p->updated_at)->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority'   => '0.6',
                ];
            });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . '</loc>' . "\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>' . $u['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $u['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
