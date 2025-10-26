@component('mail::message')
# Nieuwe contactaanvraag

**Naam:** {{ $data['name'] ?? '—' }}  
**E-mail:** {{ $data['email'] ?? '—' }}  
**Telefoon:** {{ $data['phone'] ?? '—' }}

**Bericht:**
> {{ $data['message'] ?? '—' }}



Met vriendelijke groet,<br>
{{ config('mail.from.name') }}
@endcomponent
