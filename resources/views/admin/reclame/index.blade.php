@extends('admin.layout')
@section('title', 'Reclame export — Gerritsen Admin')
@section('page_title', 'Reclame export')

@section('content')
<div class="adm-dash">

  <div class="page-actions">
    <span style="color:var(--muted);font-size:13.5px">{{ $reclames->total() }} {{ $reclames->total() === 1 ? 'reclame' : 'reclames' }}</span>
    <div class="spacer"></div>
    <a href="{{ route('admin.reclame.create') }}" class="btn primary">+ Nieuwe reclame</a>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Overzicht</h3></div>
    @if($reclames->count() === 0)
      <div class="adm-panel-empty" style="padding:48px 20px">
        <div class="adm-panel-empty-icon">📰</div>
        <p style="margin:0">Nog geen reclames aangemaakt.</p>
      </div>
    @else
      <div class="table-wrap" style="border:0;border-radius:0;border-top:1px solid var(--border)">
        <table class="table">
          <thead>
            <tr>
              <th style="width:60px">#</th>
              <th>Titel</th>
              <th>Subtitel</th>
              <th>Aangemaakt</th>
              <th style="width:200px"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($reclames as $r)
              <tr>
                <td style="color:var(--muted);font-size:13px">{{ $r->id }}</td>
                <td style="font-weight:600">{{ $r->title }}</td>
                <td style="color:var(--muted)">{{ $r->subtitle }}</td>
                <td style="color:var(--muted);font-size:13px">{{ $r->created_at?->format('d-m-Y H:i') }}</td>
                <td>
                  <div style="display:flex;gap:6px;justify-content:flex-end">
                    <a href="{{ route('admin.reclame.edit', $r) }}" class="btn sm">Bewerken</a>
                    <a href="{{ route('admin.reclame.pdf', $r) }}" target="_blank" class="btn sm primary">PDF →</a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div style="padding:14px 18px">{{ $reclames->links() }}</div>
    @endif
  </div>

</div>
@endsection
