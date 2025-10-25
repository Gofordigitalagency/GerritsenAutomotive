<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TotalUploadSize;

class StoreOccasionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'type' => 'nullable|string|max:100',
            'transmissie' => 'required|string|max:50',
            'brandstof' => 'required|string|max:50',
            'kenteken' => 'nullable|string|max:20',
            'interieurkleur' => 'nullable|string|max:50',
            'kleur' => 'nullable|string|max:50',
            'btw_marge' => 'nullable|string|max:20',
            'cilinderinhoud' => 'nullable|integer',
            'carrosserie' => 'nullable|string|max:50',
            'max_trekgewicht' => 'nullable|integer',
            'apk_tot' => 'nullable|date',
            'energielabel' => 'nullable|string|max:5',
            'wegenbelasting_min' => 'nullable|string|max:50',
            'aantal_deuren' => 'nullable|integer',
            'tellerstand' => 'nullable|integer',
            'bouwjaar' => 'nullable|integer',
            'prijs' => 'nullable|integer',
            'bekleding' => 'nullable|string|max:50',
            'aantal_cilinders' => 'nullable|integer',
            'topsnelheid' => 'nullable|integer',
            'gewicht' => 'nullable|integer',
            'laadvermogen' => 'nullable|integer',
            'bijtelling' => 'nullable|string|max:50',
            'gemiddeld_verbruik' => 'nullable|numeric',

            // bestanden
            'hoofdfoto'  => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:4096'], // 4MB

            // multi-upload: array + per file + totaal limiet
            'gallery'    => ['nullable','array','max:15', new TotalUploadSize(30)], // max 15 files, totaal ≤ 30MB
            'gallery.*'  => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:6144'], // ≤ 6MB per foto

            // textareas
            'exterieur_options_text' => 'nullable|string',
            'interieur_options_text' => 'nullable|string',
            'veiligheid_options_text'=> 'nullable|string',
            'overige_options_text'   => 'nullable|string',
            'omschrijving'           => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'gallery.max' => 'Je mag maximaal :max foto’s in de galerij uploaden.',
        ];
    }
}