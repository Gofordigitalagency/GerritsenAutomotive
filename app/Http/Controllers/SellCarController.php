<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SellCarController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'brand'          => ['nullable','string','max:100'],
            'model'          => ['nullable','string','max:100'],
            'license_plate'  => ['required','string','max:20'],
            'mileage'        => ['required','integer','min:0'],
            'options'        => ['nullable','array'],
            'options.*'      => ['string','max:100'],
            'remarks'        => ['nullable','string','max:5000'],
            'name'           => ['required','string','max:120'],
            'phone'          => ['required','string','max:60'],
            'email'          => ['required','email','max:190'],
            'message'        => ['nullable','string','max:2000'],
            'privacy'        => ['accepted'],
            'photos'         => ['nullable','array','max:20'],
            'photos.*'       => ['image','mimes:jpg,jpeg,png,webp','max:6144'],
        ]);

        // Foto’s opslaan
        $stored = [];
        if($r->hasFile('photos')){
            foreach($r->file('photos') as $file){
                $stored[] = $file->store('sellcar/'.date('Y/m'), 'public');
            }
        }

        // Mail verzenden (pas e-mailadres aan)
   Mail::send('mail.sellcar', ['data'=>$data, 'photos'=>$stored], function ($m) {
    $m->to('handelsonderneming@mgerritsen.nl')
      ->subject('Nieuwe inkoop-aanmelding (Auto verkopen)');
});

        return back()->with('success','Bedankt! We hebben uw gegevens ontvangen en nemen snel contact op.');
    }
}
