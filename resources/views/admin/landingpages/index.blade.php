@extends('admin.layout')
@section('title', 'Landingspagina\'s — Gerritsen Admin')
@section('page_title', 'Landingspagina\'s')

@section('content')
<div class="adm-dash">

  <div class="page-actions">
    <span style="color:var(--muted);font-size:13.5px">{{ $pages->count() }} {{ $pages->count() === 1 ? 'pagina' : 'pagina\'s' }}</span>
    <div class="spacer"></div>
    <a href="{{ route('admin.landingpages.create') }}" class="btn primary">+ Nieuwe landingspagina</a>
  </div>

  <div class="form-card">
    <div class="form-card-head"><h3>Overzicht</h3></div>
    @if($pages->count() === 0)
      <div class="adm-panel-empty" style="padding:48px 20px;text-align:center">
        <div class="adm-panel-empty-icon" style="font-size:32px">🔍</div>
        <p style="margin:8px 0 0">Nog geen landingspagina's. Maak er één aan voor SEO.</p>
      </div>
    @else
      <div class="table-wrap" style="border:0;border-radius:0;border-top:1px solid var(--border)">
        <table class="table">
          <thead>
            <tr>
              <th>Titel</th>
              <th>URL</th>
              <th style="width:110px">Status</th>
              <th style="width:140px">Bijgewerkt</th>
              <th style="width:230px"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($pages as $p)
              <tr>
                <td style="font-weight:600">{{ $p->title }}</td>
                <td>
                  <a href="{{ $p->url() }}" target="_blank" rel="noopener" style="color:var(--accent,#e63946)">
                    /{{ $p->slug }} ↗
                  </a>
                </td>
                <td>
                  @if($p->is_published)
                    <span class="badge" style="background:rgba(34,197,94,.15);color:#22c55e;padding:3px 9px;border-radius:999px;font-size:12px;font-weight:600">Live</span>
                  @else
                    <span class="badge" style="background:rgba(148,163,184,.15);color:var(--muted);padding:3px 9px;border-radius:999px;font-size:12px;font-weight:600">Concept</span>
                  @endif
                </td>
                <td style="color:var(--muted);font-size:13px">{{ $p->updated_at?->format('d-m-Y H:i') }}</td>
                <td>
                  <div style="display:flex;gap:6px;justify-content:flex-end">
                    <a href="{{ route('admin.landingpages.edit', $p) }}" class="btn sm">Bewerken</a>
                    <form action="{{ route('admin.landingpages.destroy', $p) }}" method="post"
                          onsubmit="return confirm('Landingspagina &quot;{{ $p->title }}&quot; verwijderen?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn sm" style="color:#ef4444">Verwijderen</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
@endsection
