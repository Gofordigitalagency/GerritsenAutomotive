@extends('admin.layout')
@section('title','Werkplaats afspraken')
@section('page_title','Werkplaats afspraken')

@section('content')
  <div class="page-actions">
    <form method="get" action="{{ route('admin.workshop.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Zoek op kenteken, naam, e-mail of telefoon"
             class="input" style="min-width:280px;">
      <select name="status" class="input">
        <option value="">Alle statussen</option>
        @foreach(['pending' => 'Nieuw', 'confirmed' => 'Bevestigd', 'done' => 'Afgerond', 'cancelled' => 'Geannuleerd'] as $value => $label)
          <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
      </select>
      <button class="btn primary" type="submit">Filteren</button>
      @if(request('q') || request('status'))
        <a href="{{ route('admin.workshop.index') }}" class="btn">Reset</a>
      @endif
    </form>
  </div>

  @if(session('ok')) <div class="flash-ok">{{ session('ok') }}</div> @endif

  <div class="form-card">
    <div class="form-card-head"><h3>Online aangevraagde afspraken</h3></div>
    <div class="form-card-body">
      <table class="table">
        <thead>
          <tr>
            <th>Wanneer</th>
            <th>Kenteken</th>
            <th>Klant</th>
            <th>Contact</th>
            <th>Status</th>
            <th style="width:160px;"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($appointments as $a)
            <tr>
              <td>
                {{ $a->appointment_date?->format('d-m-Y') }}
                @if($a->appointment_time) {{ \Illuminate\Support\Str::of($a->appointment_time)->limit(5,'') }} @endif
              </td>
              <td><strong>{{ $a->license_plate }}</strong></td>
              <td>
                {{ trim($a->first_name.' '.($a->middle_name ? $a->middle_name.' ' : '').$a->last_name) }}
                @if($a->company_name) <br><span class="muted">{{ $a->company_name }}</span> @endif
              </td>
              <td>
                @if($a->phone) 📞 {{ $a->phone }}<br>@endif
                @if($a->email) ✉️ {{ $a->email }} @endif
              </td>
              <td>
                @php
                  $labels = ['pending'=>'Nieuw','confirmed'=>'Bevestigd','done'=>'Afgerond','cancelled'=>'Geannuleerd'];
                @endphp
                {{ $labels[$a->status] ?? ucfirst($a->status) }}
              </td>
              <td class="actions">
                <a href="{{ route('admin.workshop.show', $a) }}" class="btn">Bekijken</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="muted">Nog geen afspraken.</td></tr>
          @endforelse
        </tbody>
      </table>

      <div style="margin-top:12px;">{{ $appointments->links() }}</div>
    </div>
  </div>
@endsection
