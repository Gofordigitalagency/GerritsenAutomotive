@component('mail::message')
# Nieuwe reservering

**Onderdeel:** {{ ucfirst($data['type']) }}  
**Van:** {{ $data['start_at'] }}  
**Tot:** {{ $data['end_at'] }}

**Naam:** {{ $data['name'] }}  
**E-mail:** {{ $data['email'] }}  
**Telefoon:** {{ $data['phone'] ?? '—' }}


Met vriendelijke groet,<br>
{{ config('mail.from.name') }}
@endcomponent
