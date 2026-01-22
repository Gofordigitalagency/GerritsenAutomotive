<div class="wa-wrap" style="max-width:1200px;margin:40px auto;padding:0 18px;font-family:Arial, sans-serif;">
  <h1 style="font-size:34px;margin:0 0 10px;font-weight:800;">Afspraak aangevraagd ✅</h1>
  <p style="color:#666;margin:0 0 20px;">
    Bedankt! We hebben je werkplaatsafspraak ontvangen. Je krijgt zo snel mogelijk een bevestiging.
  </p>

  <div style="border:1px solid #e8e8e8;background:#fff;padding:18px;border-radius:3px;">
    <h3 style="margin:0 0 10px;font-size:16px;">Overzicht</h3>

    <p style="margin:0;line-height:1.7;">
      <b>Kenteken:</b> {{ $appointment->license_plate }}<br>
      <b>Kilometerstand:</b> {{ $appointment->mileage ? number_format($appointment->mileage,0,',','.') . ' km' : '-' }}<br>
      <b>Datum:</b> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d-m-Y') }}<br>
      <b>Tijd:</b> {{ $appointment->appointment_time }}<br>
      <b>Onderhoud:</b> {{ $appointment->maintenance_option ?? '-' }}<br>

      @php
        $extras = $appointment->extra_services;
        if (is_string($extras)) $extras = json_decode($extras, true) ?: [];
        if (!is_array($extras)) $extras = [];
      @endphp

      <b>Aanvullend:</b> {{ count($extras) ? implode(', ', $extras) : '-' }}<br>
    </p>
  </div>

  <div style="margin-top:18px;">
    <a href="/" style="display:inline-block;background:#2aa9df;color:#fff;text-decoration:none;padding:12px 18px;border-radius:3px;font-weight:800;">
      Terug naar home
    </a>
  </div>
</div>
