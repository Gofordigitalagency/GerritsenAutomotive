@php
$tz = 'Europe/Amsterdam';
@endphp
<p>{{ $toCustomer ? 'Bedankt voor je reservering!' : 'Er is een nieuwe reservering geplaatst.' }}</p>

<p>
<strong>Onderdeel:</strong> {{ ucfirst($reservation->resource_type) }} <br>
<strong>Datum & tijd:</strong>
{{ $reservation->start_at->timezone($tz)->format('d-m-Y H:i') }}
t/m
{{ $reservation->end_at->timezone($tz)->format('H:i') }} <br>
<strong>Naam:</strong> {{ $reservation->reserved_by }} <br>
<strong>Telefoon:</strong> {{ $reservation->phone }} <br>
<strong>E-mail:</strong> {{ $reservation->email }}
</p>

@if($toCustomer)
<p>Betaling vindt plaats bij Gerritsen Automotive. Tot dan!</p>
@endif
