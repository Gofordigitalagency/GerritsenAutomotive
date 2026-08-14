<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
 public function store(Request $request)
{
    // honeypot
    if ($request->filled('website')) {
        return back()->with('success', 'Bedankt!');
    }

    // Vakantie/sluiting: leenauto-aanvraag met een ophaaldatum binnen de
    // vakantieperiode weigeren (werkplaats-widget blokkeert al client-side).
    if (function_exists('vacation_blocks') && $request->filled('leenauto_from')
        && vacation_blocks($request->input('leenauto_from'))) {
        $weer = \Illuminate\Support\Carbon::parse(config('vacation.to'))->addDay()->format('d-m-Y');
        return back()->withInput()
            ->withErrors(['leenauto_from' => "In deze periode zijn wij met vakantie. Een leenauto kan weer vanaf {$weer}."]);
    }

    $validated = $request->validate([
        'name'         => ['required','string','max:120'],
        'email'        => ['required','email','max:190'],
        'phone'        => ['nullable','string','max:40'],
        'message'      => ['required','string','max:5000'],
        'occasion'     => ['nullable','string','max:200'],
        'occasion_url' => ['nullable','url','max:500'],
        'privacy'      => ['accepted'],
    ]);

    $data = [
        'name'         => $validated['name'],
        'email'        => $validated['email'],
        'phone'        => $validated['phone'] ?? null,
        'message'      => $validated['message'],
        'occasion'     => $validated['occasion'] ?? null,
        'occasion_url' => $validated['occasion_url'] ?? null,
        'ip'           => $request->ip(),
        'ua'           => $request->userAgent(),
        'submitted_at' => now()->format('d-m-Y H:i'),
    ];

    try {
        // De ontvanger (services.contact.to_email) wordt in de Mailable zelf
        // gezet; hier niet nog eens ->to() zodat het adres niet dubbel wordt.
        Mail::send(new \App\Mail\ContactSubmitted($data));
    } catch (\Throwable $e) {
        Log::error('Contact mail failed: '.$e->getMessage(), ['exception' => $e]);
        return back()->withErrors(['mail' => 'Verzenden mislukt. Probeer het later nog eens.']);
    }

    return back()->with('success', 'Bedankt! We nemen zo snel mogelijk contact met je op.');
}
}
