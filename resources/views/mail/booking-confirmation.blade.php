@component('mail::message')
# Bedankt voor je reservering

Beste {{ $data['name'] }},

We hebben je reservering ontvangen. Hieronder de details:

**Onderdeel:** {{ ucfirst($data['type']) }}  
**Van:** {{ $data['start_at'] }}  
**Tot:** {{ $data['end_at'] }}

Heb je vragen of wil je wijzigen? Reageer op deze e-mail.

@component('mail::button', ['url' => url('/')])
Bezoek onze website
@endcomponent

Met vriendelijke groet,<br>
{{ config('mail.from.name') }}
@endcomponent
