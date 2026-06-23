@extends('admin.layout')
@section('title', 'Werkplaats afspraken — Gerritsen Admin')
@section('page_title', 'Werkplaats afspraken')

@php
  $labels = ['pending'=>'Nieuw','confirmed'=>'Bevestigd','done'=>'Afgerond','cancelled'=>'Geannuleerd'];
  $statusToBadge = ['pending'=>'pending','confirmed'=>'confirmed','done'=>'confirmed','cancelled'=>'cancelled'];
@endphp

@section('content')
<div class="adm-dash">

  <div class="page-actions">
    <a href="{{ route('admin.bookings.index', ['type' => 'werkplaats']) }}" class="btn">← Naar overzicht</a>
    <div class="spacer"></div>
    <form method="get" action="{{ route('admin.workshop.index') }}" class="adm-workshop-filter">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Zoek op kenteken, naam, e-mail of telefoon">
      <select name="status">
        <option value="">Alle statussen</option>
        @foreach($labels as $value => $label)
          <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
      </select>
      <button class="btn primary" type="submit">Filteren</button>
      @if(request('q') || request('status'))
        <a href="{{ route('admin.workshop.index') }}" class="btn">Reset</a>
      @endif
    </form>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Online aangevraagde afspraken</h3></div>
    @if($appointments->isEmpty())
      <div class="adm-panel-empty" style="padding:48px 20px">
        <div class="adm-panel-empty-icon">🔧</div>
        <p style="margin:0">Nog geen afspraken{{ request('q') || request('status') ? ' binnen deze filter' : '' }}.</p>
      </div>
    @else
      <div class="table-wrap" style="border:0;border-radius:0;border-top:1px solid var(--border)">
        <table class="table">
          <thead>
            <tr>
              <th>Wanneer</th>
              <th>Kenteken</th>
              <th>Klant</th>
              <th>Contact</th>
              <th>Status</th>
              <th style="width:120px"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($appointments as $a)
              <tr>
                <td>
                  {{ $a->appointment_date?->format('d-m-Y') }}
                  @if($a->appointment_time)
                    <span style="color:var(--muted)">·</span>
                    {{ \Illuminate\Support\Str::of($a->appointment_time)->limit(5,'') }}
                  @endif
                </td>
                <td><code style="background:var(--bg-2);padding:3px 8px;border-radius:6px;font-size:12.5px;font-weight:600">{{ $a->license_plate }}</code></td>
                <td>
                  {{ trim($a->first_name.' '.($a->middle_name ? $a->middle_name.' ' : '').$a->last_name) }}
                  @if($a->company_name)<br><span style="color:var(--muted);font-size:12px">{{ $a->company_name }}</span>@endif
                </td>
                <td>
                  @if($a->phone)<div style="font-size:13px">📞 {{ $a->phone }}</div>@endif
                  @if($a->email)<div style="font-size:13px;color:var(--muted)">✉ {{ $a->email }}</div>@endif
                </td>
                <td>
                  <span class="badge {{ $statusToBadge[$a->status] ?? 'pending' }}">{{ $labels[$a->status] ?? ucfirst($a->status) }}</span>
                </td>
                <td>
                  <a href="{{ route('admin.workshop.show', $a) }}" class="btn sm">Bekijk →</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div style="padding:14px 18px">{{ $appointments->links() }}</div>
    @endif
  </div>

</div>

<style>
  .adm-workshop-filter {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
  }
  .adm-workshop-filter input[type="text"] {
    min-width: 260px;
    padding: 9px 14px;
    background: var(--bg-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-size: 13.5px;
  }
  .adm-workshop-filter select {
    padding: 9px 14px;
    background: var(--bg-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-size: 13.5px;
  }
</style>
@endsection
