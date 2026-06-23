@extends('admin.layout')
@section('title', 'Reserveringen — Gerritsen Admin')
@section('page_title', 'Reserveringen')

@php
  $tabs = [
    'aanhanger'  => ['label' => 'Aanhanger',          'icon' => 'M3 13l2-5a2 2 0 0 1 2-1h10a2 2 0 0 1 2 1l2 5M3 13h18M3 13v4a1 1 0 0 0 1 1h2M21 13v4a1 1 0 0 1-1 1h-2'],
    'stofzuiger' => ['label' => 'Tapijtreiniger',     'icon' => 'M9 3v6h6V3 M12 9v8 M9 20a3 3 0 1 0 6 0 3 3 0 0 0-6 0z'],
    'koplampen'  => ['label' => 'Koplampen',          'icon' => 'M12 2v6 M12 16v6 M4 12h6 M14 12h6 M19.07 4.93l-4.24 4.24 M9.17 14.83l-4.24 4.24 M19.07 19.07l-4.24-4.24 M9.17 9.17 4.93 4.93'],
    'werkplaats' => ['label' => 'Werkplaats',         'icon' => 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z'],
  ];
@endphp

@section('content')
<div class="adm-dash">

  {{-- Tabs --}}
  <div class="adm-tab-row">
    @foreach($tabs as $key => $t)
      <a href="{{ route('admin.bookings.index', ['type' => $key]) }}"
         class="adm-tab @if($type === $key) is-active @endif">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $t['icon'] }}"/></svg>
        {{ $t['label'] }}
        @if($counts[$key] > 0)
          <span class="adm-tab-count">{{ $counts[$key] }}</span>
        @endif
      </a>
    @endforeach
  </div>

  {{-- Action bar --}}
  <div class="page-actions">
    <span style="color:var(--muted);font-size:13.5px">{{ $items->total() }} {{ $items->total() === 1 ? 'reservering' : 'reserveringen' }} totaal</span>
    <div class="spacer"></div>
    @if($type !== 'werkplaats')
      <a href="{{ route('admin.'.$type.'.create') }}" class="btn primary">+ Nieuwe reservering</a>
    @endif
  </div>

  {{-- Lijst --}}
  <div class="form-card">
    <div class="form-card-head"><h3>{{ $tabs[$type]['label'] }}</h3></div>
    @if($items->isEmpty())
      <div class="adm-panel-empty" style="padding:40px 20px">
        <div class="adm-panel-empty-icon">📋</div>
        <p style="margin:0">Geen reserveringen voor {{ strtolower($tabs[$type]['label']) }}.</p>
      </div>
    @else
      <div class="table-wrap" style="border:0;border-radius:0;border-top:1px solid var(--border)">
        <table class="table">
          @if($type === 'werkplaats')
            <thead>
              <tr>
                <th>Aangevraagd</th>
                <th>Klant</th>
                <th>Kenteken</th>
                <th>Contact</th>
                <th>Status</th>
                <th style="width:140px"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($items as $a)
                <tr>
                  <td>{{ $a->created_at->format('d-m-Y H:i') }}</td>
                  <td>
                    {{ trim(($a->first_name ?? '').' '.($a->middle_name ? $a->middle_name.' ' : '').($a->last_name ?? '')) }}
                    @if($a->company_name)<br><span style="color:var(--muted);font-size:12px">{{ $a->company_name }}</span>@endif
                  </td>
                  <td><code style="background:var(--bg-2);padding:3px 8px;border-radius:6px;font-size:12.5px">{{ $a->kenteken ?? '—' }}</code></td>
                  <td>
                    @if($a->phone)<div style="font-size:13px">📞 {{ $a->phone }}</div>@endif
                    @if($a->email)<div style="font-size:13px;color:var(--muted)">✉ {{ $a->email }}</div>@endif
                  </td>
                  <td>
                    <span class="badge {{ $a->status }}">{{ ucfirst($a->status) }}</span>
                  </td>
                  <td>
                    <a href="{{ route('admin.workshop.show', $a) }}" class="btn sm">Bekijk →</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          @else
            <thead>
              <tr>
                <th>Wanneer</th>
                <th>Klant</th>
                <th>Contact</th>
                <th>Status</th>
                <th style="width:200px"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($items as $r)
                <tr>
                  <td>
                    {{ $r->start_at->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}
                    <span style="color:var(--muted)">–</span>
                    {{ $r->end_at->timezone('Europe/Amsterdam')->format('H:i') }}
                  </td>
                  <td>{{ $r->reserved_by ?? '—' }}</td>
                  <td>
                    @if($r->phone)<div style="font-size:13px">📞 {{ $r->phone }}</div>@endif
                    @if($r->email)<div style="font-size:13px;color:var(--muted)">✉ {{ $r->email }}</div>@endif
                  </td>
                  <td>
                    <span class="badge {{ $r->status }}">{{ ucfirst($r->status) }}</span>
                  </td>
                  <td>
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                      <a href="{{ route('admin.'.$type.'.edit', $r) }}" class="btn sm">Bewerken</a>
                      <form action="{{ route('admin.'.$type.'.destroy', $r) }}" method="post" onsubmit="return confirm('Verwijderen?')" style="margin:0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn sm danger">×</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          @endif
        </table>
      </div>
      <div style="padding:14px 18px">{{ $items->links() }}</div>
    @endif
  </div>

</div>
@endsection
