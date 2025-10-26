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

    $validated = $request->validate([
        'name'    => ['required','string','max:120'],
        'email'   => ['required','email','max:190'],
        'phone'   => ['nullable','string','max:40'],
        'message' => ['required','string','max:5000'],
        'privacy' => ['accepted'],
    ]);

    $data = [
        'name'         => $validated['name'],
        'email'        => $validated['email'],
        'phone'        => $validated['phone'] ?? null,
        'message'      => $validated['message'],
        'ip'           => $request->ip(),
        'ua'           => $request->userAgent(),
        'submitted_at' => now()->format('d-m-Y H:i'),
    ];

    try {
        \Mail::send(new \App\Mail\ContactSubmitted($data));
    } catch (\Throwable $e) {
        \Log::error('Contact mail failed: '.$e->getMessage(), ['exception' => $e]);
        return back()->withErrors(['mail' => 'Verzenden mislukt. Probeer het later nog eens.']);
    }

    return back()->with('success', 'Bedankt! We nemen zo snel mogelijk contact met je op.');
}
}
