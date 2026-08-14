<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Ontvanger voor formulier-mails (contact, auto verkopen, reserveringen).
    // Fallback naar het vaste bedrijfsadres zodat dit NOOIT null is — anders
    // crasht de mailverzending (Symfony Mime Address vereist een string).
    // Zowel 'to_email' (top-level) als 'contact.to_email' worden gebruikt in
    // de code; beide wijzen naar hetzelfde adres.
    'to_email' => env('CONTACT_TO_EMAIL') ?: 'Handelsonderneming@mgerritsen.nl',

    'contact' => [
        'to_email' => env('CONTACT_TO_EMAIL') ?: 'Handelsonderneming@mgerritsen.nl',
    ],

    // Mobilox/Hexon incrementele voorraadkoppeling (EWI).
    // 'token' beveiligt het inkomende endpoint: Mobilox roept de URL aan met ?key=<token>.
    // TIJDELIJK: fallback-token zodat de productietest werkt zonder .env-aanpassing.
    // Wordt teruggedraaid na de test; daarna alleen via env(MOBILOX_TOKEN).
    'mobilox' => [
        'token' => env('MOBILOX_TOKEN', 'mbx_live_test_7Hq2Lx9Rk4Zt'),
    ],

];
