<?php

namespace App\Http\Controllers;

use App\Services\MobiloxImporter;
use Illuminate\Http\Request;

class MobiloxController extends Controller
{
    /**
     * Incrementele voorraadkoppeling (EWI): Hexon/Mobilox stuurt per
     * voertuigmutatie (add/change/delete) een rauwe XML-POST hierheen.
     * Bij succes verwacht Hexon exact "1" terug; anders een foutmelding.
     */
    public function incremental(Request $request, MobiloxImporter $importer)
    {
        // Beveiliging: token in de URL (?key=...) of HTTP Basic Auth (gebruikersnaam/wachtwoord).
        // Fail-closed: zonder ingestelde token weigert het endpoint alles.
        $token = config('services.mobilox.token');
        if (empty($token)) {
            return response('Koppeling nog niet geconfigureerd', 503)
                ->header('Content-Type', 'text/plain; charset=UTF-8');
        }
        $provided = $request->query('key') ?? $request->getPassword() ?? $request->getUser();
        if (! hash_equals($token, (string) $provided)) {
            return response('Niet geautoriseerd', 401)
                ->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        $result = $importer->handle($request->getContent());

        return response($result, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
