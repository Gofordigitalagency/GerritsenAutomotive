@extends('admin.layout')
@section('title','Werkplaats afspraak')
@section('page_title','Werkplaats afspraak')

@section('content')
  <div class="page-actions">
    <a href="{{ route('admin.workshop.index') }}" class="btn">← Terug naar overzicht</a>
    <div class="spacer"></div>
    <form action="{{ route('admin.workshop.destroy', $appointment) }}" method="post"
          onsubmit="return confirm('Deze afspraak verwijderen?')">
      @csrf @method('DELETE')
      <button class="btn btn-danger" type="submit">Verwijderen</button>
    </form>
  </div>

  @if(session('ok')) <div class="flash-ok">{{ session('ok') }}</div> @endif

  <div class="form-card">
    <div class="form-card-head"><h3>Status</h3></div>
    <div class="form-card-body">
      <form action="{{ route('admin.workshop.status', $appointment) }}" method="post"
            style="display:flex;gap:8px;align-items:center;">
        @csrf
        <select name="status" class="input">
          @foreach(['pending'=>'Nieuw','confirmed'=>'Bevestigd','done'=>'Afgerond','cancelled'=>'Geannuleerd'] as $value => $label)
            <option value="{{ $value }}" @selected($appointment->status === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <button class="btn primary" type="submit">Opslaan</button>
      </form>
    </div>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Afspraak</h3></div>
    <div class="form-card-body">
      <p>
        <strong>Datum &amp; tijd:</strong>
        {{ $appointment->appointment_date?->format('d-m-Y') }}
        {{ $appointment->appointment_time }}<br>
        <strong>Wachten tijdens onderhoud/reparatie:</strong>
        {{ $appointment->wait_while_service ? 'Ja' : 'Nee' }}<br>
        <strong>Aangevraagd op:</strong>
        {{ $appointment->created_at?->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}
      </p>
    </div>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Voertuig &amp; werkzaamheden</h3></div>
    <div class="form-card-body">
      <p>
        <strong>Kenteken:</strong> {{ $appointment->license_plate }}<br>
        <strong>Kilometerstand:</strong>
        {{ $appointment->mileage ? number_format($appointment->mileage,0,',','.').' km' : '-' }}<br>
        <strong>Onderhoud:</strong> {{ $appointment->maintenance_option ?: '-' }}<br>
        <strong>Aanvullende werkzaamheden:</strong>
        {{ !empty($appointment->extra_services) ? implode(', ', $appointment->extra_services) : '-' }}
      </p>
    </div>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Klantgegevens</h3></div>
    <div class="form-card-body">
      <p>
        <strong>Naam:</strong>
        {{ trim(($appointment->salutation ? ($appointment->salutation === 'mevr' ? 'Mevr.' : 'Dhr.').' ' : '') . $appointment->first_name.' '.($appointment->middle_name ? $appointment->middle_name.' ' : '').$appointment->last_name) }}<br>
        @if($appointment->company_name)<strong>Bedrijf:</strong> {{ $appointment->company_name }}<br>@endif
        <strong>Telefoon:</strong>
        @if($appointment->phone) <a href="tel:{{ $appointment->phone }}">{{ $appointment->phone }}</a> @else - @endif<br>
        <strong>E-mail:</strong>
        @if($appointment->email) <a href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a> @else - @endif
      </p>
      <p>
        <strong>Adres:</strong><br>
        {{ trim(($appointment->street ?? '').' '.($appointment->house_number ?? '').' '.($appointment->addition ?? '')) ?: '-' }}<br>
        {{ trim(($appointment->postal_code ?? '').' '.($appointment->city ?? '')) }}
      </p>
      @if($appointment->remarks)
        <p><strong>Opmerkingen klant:</strong><br>{!! nl2br(e($appointment->remarks)) !!}</p>
      @endif
      <p class="muted" style="font-size:12px;">
        Marketing opt-in: {{ $appointment->marketing_opt_in ? 'Ja' : 'Nee' }} ·
        Voorwaarden geaccepteerd: {{ $appointment->terms_accepted ? 'Ja' : 'Nee' }}
      </p>
    </div>
  </div>
@endsection
