@extends('admin.layout')
@section('title', 'Werkplaats afspraak — Gerritsen Admin')
@section('page_title', 'Werkplaats afspraak')

@php
  $labels = ['pending'=>'Nieuw','confirmed'=>'Bevestigd','done'=>'Afgerond','cancelled'=>'Geannuleerd'];
  $statusToBadge = ['pending'=>'pending','confirmed'=>'confirmed','done'=>'confirmed','cancelled'=>'cancelled'];
  $statusBadge = $statusToBadge[$appointment->status] ?? 'pending';
  $statusLabel = $labels[$appointment->status] ?? ucfirst($appointment->status);

  $fullName = trim(
    ($appointment->salutation ? ($appointment->salutation === 'mevr' ? 'Mevr. ' : 'Dhr. ') : '')
    . $appointment->first_name.' '
    . ($appointment->middle_name ? $appointment->middle_name.' ' : '')
    . $appointment->last_name
  );
@endphp

@section('content')
<div class="adm-dash">

  <div class="page-actions">
    <a href="{{ route('admin.workshop.index') }}" class="btn">← Terug naar overzicht</a>
    <div class="spacer"></div>
    <span class="badge {{ $statusBadge }}" style="font-size:12px;padding:6px 12px">{{ $statusLabel }}</span>
    <form action="{{ route('admin.workshop.destroy', $appointment) }}" method="post" onsubmit="return confirm('Deze afspraak verwijderen?')" style="margin:0">
      @csrf @method('DELETE')
      <button type="submit" class="btn danger">Verwijderen</button>
    </form>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Status wijzigen</h3></div>
    <div class="form-card-body">
      <form action="{{ route('admin.workshop.status', $appointment) }}" method="post" class="adm-status-form">
        @csrf
        <select name="status">
          @foreach($labels as $value => $label)
            <option value="{{ $value }}" @selected($appointment->status === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn primary">Status opslaan</button>
      </form>
    </div>
  </div>

  <div class="adm-grid-2">
    <div class="form-card" style="margin:0">
      <div class="form-card-head"><h3>Afspraak</h3></div>
      <div class="form-card-body">
        <dl class="adm-deflist">
          <dt>Datum &amp; tijd</dt>
          <dd>
            {{ $appointment->appointment_date?->format('d-m-Y') }}
            @if($appointment->appointment_time) · {{ $appointment->appointment_time }} @endif
          </dd>
          <dt>Wachten tijdens onderhoud</dt>
          <dd>{{ $appointment->wait_while_service ? 'Ja' : 'Nee' }}</dd>
          <dt>Aangevraagd op</dt>
          <dd>{{ $appointment->created_at?->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</dd>
        </dl>
      </div>
    </div>

    <div class="form-card" style="margin:0">
      <div class="form-card-head"><h3>Voertuig &amp; werkzaamheden</h3></div>
      <div class="form-card-body">
        <dl class="adm-deflist">
          <dt>Kenteken</dt>
          <dd><code style="background:var(--bg-2);padding:3px 9px;border-radius:6px;font-size:13px;font-weight:600">{{ $appointment->license_plate }}</code></dd>
          <dt>Kilometerstand</dt>
          <dd>{{ $appointment->mileage ? number_format($appointment->mileage,0,',','.').' km' : '—' }}</dd>
          <dt>Onderhoud</dt>
          <dd>{{ $appointment->maintenance_option ?: '—' }}</dd>
          <dt>Aanvullende werkzaamheden</dt>
          <dd>{{ !empty($appointment->extra_services) ? implode(', ', $appointment->extra_services) : '—' }}</dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Klantgegevens</h3></div>
    <div class="form-card-body">
      <dl class="adm-deflist adm-deflist-2col">
        <dt>Naam</dt>
        <dd>{{ $fullName }}</dd>
        @if($appointment->company_name)
          <dt>Bedrijf</dt>
          <dd>{{ $appointment->company_name }}</dd>
        @endif
        <dt>Telefoon</dt>
        <dd>
          @if($appointment->phone)
            <a href="tel:{{ $appointment->phone }}" style="color:var(--accent)">{{ $appointment->phone }}</a>
          @else — @endif
        </dd>
        <dt>E-mail</dt>
        <dd>
          @if($appointment->email)
            <a href="mailto:{{ $appointment->email }}" style="color:var(--accent)">{{ $appointment->email }}</a>
          @else — @endif
        </dd>
        <dt>Adres</dt>
        <dd>
          {{ trim(($appointment->street ?? '').' '.($appointment->house_number ?? '').' '.($appointment->addition ?? '')) ?: '—' }}<br>
          <span style="color:var(--muted)">{{ trim(($appointment->postal_code ?? '').' '.($appointment->city ?? '')) }}</span>
        </dd>
        @if($appointment->remarks)
          <dt>Opmerkingen klant</dt>
          <dd style="white-space:pre-wrap;line-height:1.5">{{ $appointment->remarks }}</dd>
        @endif
      </dl>
      <p style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);color:var(--muted);font-size:12px">
        Marketing opt-in: <strong style="color:var(--text)">{{ $appointment->marketing_opt_in ? 'Ja' : 'Nee' }}</strong>
        ·
        Voorwaarden geaccepteerd: <strong style="color:var(--text)">{{ $appointment->terms_accepted ? 'Ja' : 'Nee' }}</strong>
      </p>
    </div>
  </div>
</div>
@endsection
