<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'message' => 'required|max:2000',
        ]);

        Mail::raw("Naam: {$validated['name']}\nE-mail: {$validated['email']}\n\nBericht:\n{$validated['message']}", function ($message) use ($validated) {
            $message->to('Umutcandemirez2@gmail.com')
                    ->subject('Nieuw contactbericht via Gerritsen Automotive')
                    ->replyTo($validated['email']);
        });

        return back()->with('success', 'Bedankt voor je bericht! We nemen snel contact met je op.');
    }
}