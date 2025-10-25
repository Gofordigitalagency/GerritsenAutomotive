@extends('admin.layout')
@section('title','Reserveringen – '.ucfirst($type))
@section('page_title','Reserveringen – '.ucfirst($type))

@section('content')
  <div class="page-actions">
    <div class="spacer"></div>
    <a href="{{ route('admin.'.$type.'.create') }}" class="btn primary">+ Nieuwe reservering</a>
  </div>

  @if(session('ok')) <div class="flash-ok">{{ session('ok') }}</div> @endif

  <div class="form-card">
    <div class="form-card-head"><h3>Overzicht</h3></div>
    <div class="form-card-body">
      <table class="table">
        <thead>
          <tr>
            <th>Wanneer</th>
            <th>Klant</th>
            <th>Contact</th>
            <th>Status</th>
            <th style="width:200px;"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($reservations as $r)
            <tr>
             <td>
              {{ $r->start_at->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}
              –
              {{ $r->end_at->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}
            </td>
              <td>
                @if($r->phone) 📞 {{ $r->phone }}<br>@endif
                @if($r->email) ✉️ {{ $r->email }} @endif
              </td>
              <td>{{ ucfirst($r->status) }}</td>
              <td class="actions">
                <a href="{{ route('admin.'.$type.'.edit', $r) }}" class="btn">Bewerken</a>
                <form action="{{ route('admin.'.$type.'.destroy', $r) }}" method="post" onsubmit="return confirm('Verwijderen?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger">Verwijderen</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="muted">Nog geen reserveringen.</td></tr>
          @endforelse
        </tbody>
      </table>

      <div style="margin-top:12px;">{{ $reservations->links() }}</div>
    </div>
  </div>
@endsection
