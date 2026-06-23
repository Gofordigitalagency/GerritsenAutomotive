@extends('admin.layout')
@section('title', ($reclame->exists ? 'Reclame bewerken' : 'Nieuwe reclame') . ' — Gerritsen Admin')
@section('page_title', $reclame->exists ? 'Reclame bewerken' : 'Nieuwe reclame')

@section('content')
<div class="adm-dash">

  <form method="POST" action="{{ $reclame->exists ? route('admin.reclame.update',$reclame) : route('admin.reclame.store') }}">
    @csrf
    @if($reclame->exists) @method('PUT') @endif

    <div class="form-card">
      <div class="form-card-head"><h3>Tekst</h3></div>
      <div class="form-card-body">
        <div class="form-card-body grid-2" style="padding:0">
          <label class="input-row">
            <span>Titel</span>
            <input name="title" value="{{ old('title', $reclame->title ?? 'WEKENAANBIEDING') }}" required>
          </label>
          <label class="input-row">
            <span>Subtitel</span>
            <input name="subtitle" value="{{ old('subtitle', $reclame->subtitle ?? 'Alleen deze week scherp geprijsd!') }}" required>
          </label>
        </div>
      </div>
    </div>

    <div class="form-card">
      <div class="form-card-head">
        <h3>Selecteer auto's <small style="color:var(--muted);font-weight:400">— maximaal 4</small></h3>
      </div>
      <div class="form-card-body">
        <div class="adm-reclame-grid">
          @foreach($occasions as $o)
            @php
              $checked = in_array($o->id, old('occasion_ids', $selected ?? []));
              $title   = trim(($o->merk ?? '').' '.($o->model ?? '').' '.($o->type ?? ''));
              $img     = $o->hoofdfoto_path ? asset('storage/'.ltrim($o->hoofdfoto_path,'/')) : null;
            @endphp
            <label class="adm-reclame-card">
              <input class="adm-reclame-check" type="checkbox" name="occasion_ids[]" value="{{ $o->id }}" {{ $checked ? 'checked' : '' }}>
              <div class="adm-reclame-thumb">
                @if($img)
                  <img src="{{ $img }}" alt="">
                @else
                  <span class="adm-reclame-noimg">Geen foto</span>
                @endif
              </div>
              <div class="adm-reclame-info">
                <div class="adm-reclame-title">{{ $title ?: 'Occasion #'.$o->id }}</div>
                <div class="adm-reclame-meta">
                  € {{ number_format((float)($o->prijs ?? 0), 0, ',', '.') }}
                  @if($o->tellerstand) · {{ number_format($o->tellerstand, 0, ',', '.') }} km @endif
                  @if($o->bouwjaar) · {{ $o->bouwjaar }} @endif
                </div>
              </div>
              <div class="adm-reclame-mark">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg>
              </div>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <div class="page-actions">
      <a href="{{ route('admin.reclame.index') }}" class="btn">Annuleren</a>
      <div class="spacer"></div>
      @if($reclame->exists)
        <a href="{{ route('admin.reclame.pdf', $reclame) }}" target="_blank" class="btn">PDF preview →</a>
      @endif
      <button type="submit" class="btn primary">{{ $reclame->exists ? 'Opslaan' : 'Aanmaken' }}</button>
    </div>
  </form>
</div>

<script>
  // max 4 selecteren (client-side)
  document.addEventListener('change', (e) => {
    if (e.target.matches('input[type="checkbox"][name="occasion_ids[]"]')) {
      const checked = document.querySelectorAll('input[name="occasion_ids[]"]:checked');
      if (checked.length > 4) {
        e.target.checked = false;
        alert('Maximaal 4 auto\'s selecteren.');
      }
    }
  });
</script>
@endsection
