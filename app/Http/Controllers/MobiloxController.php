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

        // Foto's ná het verzenden van de respons downloaden (op de achtergrond),
        // zodat Mobilox direct "1" terugkrijgt en de sync niet timeout op downloads.
        app()->terminating(fn () => $importer->flushPhotos());

        return response($result, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * TIJDELIJK (debug): toon de laatst ontvangen Mobilox-XML, zodat de
     * veld-mapping op echte data afgestemd kan worden. Beveiligd met token.
     */
    public function lastPayload(Request $request)
    {
        $token = config('services.mobilox.token');
        if (empty($token) || ! hash_equals($token, $this->providedToken($request))) {
            return response('Niet geautoriseerd', 401)->header('Content-Type', 'text/plain; charset=UTF-8');
        }
        $files = collect(\Illuminate\Support\Facades\Storage::disk('local')->files('mobilox/incoming'))->sort()->values();
        if ($files->isEmpty()) {
            return response('Geen payloads gelogd', 404)->header('Content-Type', 'text/plain; charset=UTF-8');
        }
        return response(\Illuminate\Support\Facades\Storage::disk('local')->get($files->last()), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * TIJDELIJK (backfill): draai alle reeds gelogde payloads opnieuw door de
     * importer, zodat bestaande auto's de nieuwe veld-mapping (o.a. omschrijving)
     * overnemen zonder dat Mobilox opnieuw hoeft te pushen. Foto's worden niet
     * opnieuw gedownload (bestaande blijven staan). Beveiligd met token.
     */
    public function reprocess(Request $request, MobiloxImporter $importer)
    {
        $token = config('services.mobilox.token');
        if (empty($token) || ! hash_equals($token, $this->providedToken($request))) {
            return response('Niet geautoriseerd', 401)->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        $disk  = \Illuminate\Support\Facades\Storage::disk('local');
        $files = collect($disk->files('mobilox/incoming'))
            ->filter(fn ($f) => \Illuminate\Support\Str::endsWith($f, ['.xml']))
            ->sort()->values();

        $done = 0;
        $log  = [];
        foreach ($files as $f) {
            $result = $importer->handle($disk->get($f));
            $log[]  = basename($f) . ' => ' . $result;
            if ($result === '1') {
                $done++;
            }
        }

        return response(
            "Herverwerkt: {$done}/{$files->count()} payloads\n" . implode("\n", $log),
            200
        )->header('Content-Type', 'text/plain; charset=UTF-8');
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
