<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobiloxController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobilox/Hexon incrementele voorraadkoppeling (EWI): ontvangt per voertuig
// een XML-POST (add/change/delete). Beveiligd via ?key=<MOBILOX_TOKEN>.
Route::post('/mobilox/incremental', [MobiloxController::class, 'incremental']);

// Vriendelijke melding bij een GET (bv. URL in de browser of validatie),
// zodat er geen 405-foutpagina verschijnt.
Route::get('/mobilox/incremental', function () {
    return response('Mobilox EWI-endpoint actief. Verstuur voertuigmutaties via een POST (XML).', 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
});

