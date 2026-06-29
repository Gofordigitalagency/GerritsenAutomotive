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
        if (! hash_equals($token, $this->providedToken($request))) {
            return response('Niet geautoriseerd', 401)
                ->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        $result = $importer->handle($request->getContent());

        return response($result, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Token uit het verzoek halen: ?key=, Basic Auth (gebruiker/wachtwoord),
     * of handmatig uit de Authorization-header (sommige servers geven
     * PHP_AUTH_PW niet door aan PHP).
     */
    private function providedToken(\Illuminate\Http\Request $request): string
    {
        if (filled($request->query('key')))   return (string) $request->query('key');
        if (filled($request->getPassword()))  return (string) $request->getPassword();
        if (filled($request->getUser()))      return (string) $request->getUser();

        $auth = (string) $request->header('Authorization', '');
        if (stripos($auth, 'Basic ') === 0) {
            $decoded = base64_decode(substr($auth, 6), true);
            if ($decoded !== false && str_contains($decoded, ':')) {
                [$user, $pass] = explode(':', $decoded, 2);
                return $pass !== '' ? $pass : $user;
            }
        }
        return '';
    }
}
