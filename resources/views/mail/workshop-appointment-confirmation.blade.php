<p>Beste {{ trim(($data['first_name'] ?? '') . ' ' . ($data['middle_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) }},</p>

<p>Bedankt voor uw afspraakaanvraag bij Gerritsen Automotive. Hieronder vindt u een overzicht van uw aanvraag. Wij nemen zo spoedig mogelijk contact met u op om de afspraak definitief te bevestigen.</p>

<h3>Uw afspraak</h3>
<p>
  <strong>Datum &amp; tijd:</strong> {{ $data['appointment_date'] ?? '-' }} {{ $data['appointment_time'] ?? '' }}<br>
  <strong>Wachten tijdens onderhoud/reparatie:</strong> {{ !empty($data['wait_while_service']) ? 'Ja' : 'Nee' }}
</p>

<h3>Voertuig</h3>
<p>
  <strong>Kenteken:</strong> {{ $data['license_plate'] ?? '-' }}<br>
  <strong>Kilometerstand:</strong>
  {{ !empty($data['mileage']) ? number_format($data['mileage'],0,',','.') . ' km' : '-' }}
</p>

<h3>Werkzaamheden</h3>
<p>
  <strong>Onderhoud:</strong> {{ $data['maintenance_option'] ?? '-' }}<br>
  <strong>Aanvullende werkzaamheden:</strong>
  {{ !empty($data['extra_services']) ? implode(', ', $data['extra_services']) : '-' }}
</p>

@if(!empty($data['remarks']))
  <h3>Opmerkingen</h3>
  <p>{!! nl2br(e($data['remarks'])) !!}</p>
@endif

<hr>

<p>
  <strong>Gerritsen Automotive</strong><br>
  Heeft u nog vragen? Neem gerust contact met ons op.
</p>

<p style="color:#888;font-size:12px;">
  Dit is een automatisch verzonden bevestiging van uw aanvraag. U hoeft hier niet op te reageren.
</p>
