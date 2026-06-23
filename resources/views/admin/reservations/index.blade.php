@extends('admin.layout')
@section('title', 'Reserveringen — '.ucfirst($type))
@section('page_title', 'Reserveringen — '.ucfirst($type))

@section('content')
<div class="adm-dash">

  <div class="page-actions">
    <a href="{{ route('admin.bookings.index', ['type' => $type]) }}" class="btn">← Naar overzicht</a>
    <div class="spacer"></div>
    <a href="{{ route('admin.'.$type.'.create') }}" class="btn primary">+ Nieuwe reservering</a>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>{{ ucfirst($type) }}</h3></div>
    @if($reservations->isEmpty())
      <div class="adm-panel-empty" style="padding:48px 20px">
        <div class="adm-panel-empty-icon">📋</div>
        <p style="margin:0">Nog geen reserveringen.</p>
      </div>
    @else
      <div class="table-wrap" style="border:0;border-radius:0;border-top:1px solid var(--border)">
        <table class="table">
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
            @foreach($reservations as $r)
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
                <td><span class="badge {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
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
        </table>
      </div>
      <div style="padding:14px 18px">{{ $reservations->links() }}</div>
    @endif
  </div>

</div>
@endsection
