@component('mail::message')
# {{ !empty($data['occasion']) ? 'Reactie op een auto' : 'Nieuwe contactaanvraag' }}

@if(!empty($data['occasion']))
- **Auto:** {{ $data['occasion'] }}
@if(!empty($data['occasion_url']))
- **Link:** [{{ $data['occasion_url'] }}]({{ $data['occasion_url'] }})
@endif
@endif
- **Naam:** {{ $data['name'] ?? '—' }}
- **E-mail:** {{ $data['email'] ?? '—' }}
- **Telefoon:** {{ $data['phone'] ?? '—' }}

**Bericht:**
> {{ $data['message'] ?? '—' }}

Met vriendelijke groet,<br>
{{ config('mail.from.name') }}
@endcomponent
