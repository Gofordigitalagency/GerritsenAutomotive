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
  $abs = $o->hoofdfoto_path ? public_path('storage/'.$o->hoofdfoto_path) : null;
  $hasImg = $abs && file_exists($abs);
@endphp

<div class="img">
  @if($hasImg)
    <img src="{{ $abs }}" alt="">
  @else
    <div class="noimg">Geen foto</div>
  @endif
</div>  

            <label class="occ-card">
            <input type="checkbox" name="occasion_ids[]" value="{{ $o->id }}" {{ $checked ? 'checked' : '' }}>

            <div class="occ-inner">

                <div class="occ-thumb">
                @if($img)
                    <img src="{{ $img }}">
                @else
                    <div class="occ-noimg">Geen foto</div>
                @endif
                </div>

                <div class="occ-info">
                <div class="occ-title">{{ $title ?: 'Occasion #'.$o->id }}</div>
                <div class="occ-meta">
                    € {{ number_format((float)($o->prijs ?? 0), 0, ',', '.') }}
                    • {{ $o->tellerstand ?? '-' }} km
                    • {{ $o->bouwjaar ?? '-' }}
                </div>
                </div>

            </div>
            </label>

        @endforeach
      </div>
      <small style="display:block;margin-top:6px;color:#666">Tip: checkbox-limit kun je ook client-side afdwingen.</small>
    </div>

<button class="btn" type="submit">Opslaan</button>

      @if($reclame->exists)
        <a class="btn" href="{{ route('admin.reclame.pdf',$reclame) }}" target="_blank">Export PDF</a>
      @endif
    </div>
  </form>

  <style>
    .form-row{
  margin-bottom: 14px;
}
.occ-card{
  display:block;
  border:1px solid #e7e7e7;
  border-radius:12px;
  overflow:hidden;
  background:#fff;
}
.occ-card input{ margin:12px; }
.occ-inner{
  display:flex;
  gap:12px;
  align-items:center;
  padding:10px 12px 12px 0;
}
.occ-thumb{
  width:110px;height:70px;
  background:#f3f3f3;
  border-radius:10px;
  overflow:hidden;
  display:flex;align-items:center;justify-content:center;
  margin-left:10px;
}
.occ-thumb img{ width:100%; height:100%; object-fit:cover; }
.occ-noimg{ font-size:12px; color:#999; }
.occ-title{ font-weight:800; }
.occ-meta{ font-size:12px; color:#666; }
</style>


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
