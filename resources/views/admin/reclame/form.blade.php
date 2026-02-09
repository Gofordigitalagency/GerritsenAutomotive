@extends('admin.layout')
@section('title', 'Reclame export')

@section('content')
  <h1>Reclame export</h1>

  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST"
        action="{{ $reclame->exists ? route('admin.reclame.update',$reclame) : route('admin.reclame.store') }}">
    @csrf
    @if($reclame->exists) @method('PUT') @endif

    <div class="form-row">
      <label>Titel</label>
      <input name="title" value="{{ old('title',$reclame->title ?? 'WEKENAANBIEDING') }}" required>
    </div>

    <div class="form-row">
      <label>Subtitel</label>
      <input name="subtitle" value="{{ old('subtitle',$reclame->subtitle ?? 'Alleen deze week scherp geprijsd!') }}" required>
    </div>

    <div class="form-row">
      <label>Kies max. 4 occasions</label>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;max-height:360px;overflow:auto;padding:8px;border:1px solid #ddd;border-radius:8px;">
        @foreach($occasions as $o)
          @php
            $checked = in_array($o->id, old('occasion_ids',$selected));
            $title = trim(($o->brand ?? '').' '.($o->model ?? '')); // pas aan naar jouw velden
          @endphp

          <label style="display:flex;gap:10px;align-items:flex-start;border:1px solid #eee;border-radius:8px;padding:10px;">
            <input type="checkbox" name="occasion_ids[]" value="{{ $o->id }}"
                   {{ $checked ? 'checked' : '' }} style="margin-top:4px">
            <div>
              <div style="font-weight:700">{{ $title ?: ($o->title ?? 'Occasion #'.$o->id) }}</div>
              <div style="font-size:12px;color:#555">
                € {{ number_format((float)($o->price ?? 0), 0, ',', '.') }}
                • {{ $o->km ?? $o->mileage ?? '-' }} km
                • {{ $o->year ?? $o->bouwjaar ?? '-' }}
              </div>
            </div>
          </label>
        @endforeach
      </div>
      <small style="display:block;margin-top:6px;color:#666">Tip: checkbox-limit kun je ook client-side afdwingen.</small>
    </div>

    <div style="display:flex;gap:10px;margin-top:16px;">
      <button class="btn-primary" type="submit">Opslaan</button>

      @if($reclame->exists)
        <a class="btn" href="{{ route('admin.reclame.pdf',$reclame) }}" target="_blank">Export PDF</a>
      @endif
    </div>
  </form>

  <script>
    // max 4 selecteren (client-side)
    document.addEventListener('change', (e) => {
      if (e.target.matches('input[type="checkbox"][name="occasion_ids[]"]')) {
        const checked = document.querySelectorAll('input[name="occasion_ids[]"]:checked');
        if (checked.length > 4) {
          e.target.checked = false;
          alert('Maximaal 4 auto’s selecteren.');
        }
      }
    });
  </script>
@endsection
