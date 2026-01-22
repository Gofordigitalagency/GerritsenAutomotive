<h2>Nieuwe werkplaatsafspraak</h2>

<p>
  <strong>Kenteken:</strong> {{ $data['license_plate'] }}<br>
  <strong>Kilometerstand:</strong>
  {{ !empty($data['mileage']) ? number_format($data['mileage'],0,',','.') . ' km' : '-' }}<br>
  <strong>Datum & tijd:</strong> {{ $data['appointment_date'] }} {{ $data['appointment_time'] }}
</p>

<p><strong>Wachten tijdens onderhoud/reparatie:</strong> {{ !empty($data['wait_while_service']) ? 'Ja' : 'Nee' }}</p>

<hr>

<h3>Werkzaamheden</h3>

<p><strong>Onderhoud:</strong> {{ $data['maintenance_option'] ?? '-' }}</p>

<p><strong>Aanvullende werkzaamheden:</strong>
  {{ !empty($data['extra_services']) ? implode(', ', $data['extra_services']) : '-' }}
</p>

<hr>

<h3>Klantgegevens</h3>
<p>
  <strong>Naam:</strong> {{ $data['first_name'] }} {{ $data['middle_name'] ?? '' }} {{ $data['last_name'] }}<br>
  <strong>Bedrijf:</strong> {{ $data['company_name'] ?? '-' }}<br>
  <strong>Telefoon:</strong> {{ $data['phone'] ?? '-' }}<br>
  <strong>Email:</strong> {{ $data['email'] }}
</p>

<p>
  <strong>Adres:</strong>
  {{ $data['street'] ?? '' }} {{ $data['house_number'] ?? '' }} {{ $data['addition'] ?? '' }}<br>
  {{ $data['postal_code'] ?? '' }} {{ $data['city'] ?? '' }}
</p>

@if(!empty($data['remarks']))
  <p><strong>Opmerkingen:</strong><br>{!! nl2br(e($data['remarks'])) !!}</p>
@endif
