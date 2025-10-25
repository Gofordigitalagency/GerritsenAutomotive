<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/nl.js"></script>

<div class="page-actions">
  <a href="{{ route('admin.'.$type.'.index') }}" class="btn">← Terug</a>
  <div class="spacer"></div>
  <button form="resForm" class="btn primary" type="submit">Opslaan</button>
</div>

@if ($errors->any())
  <div class="alert error" style="margin-bottom:14px;">{{ $errors->first() }}</div>
@endif

<form id="resForm" action="{{ $action }}" method="post" class="form-card">
  @csrf
  @if($method!=='POST') @method($method) @endif

  <div class="form-card-head"><h3>{{ ucfirst($type) }}</h3></div>

  <div class="form-card-body grid-2">
    <label class="input-row">
      <span>Klantnaam</span>
      <input name="reserved_by" value="{{ old('reserved_by', $reservation->reserved_by) }}">
    </label>

    <label class="input-row">
      <span>Telefoon</span>
      <input name="phone" value="{{ old('phone', $reservation->phone) }}">
    </label>

    <label class="input-row">
      <span>E-mail</span>
      <input type="email" name="email" value="{{ old('email', $reservation->email) }}">
    </label>

    <label class="input-row">
      <span>Status</span>
      <select name="status">
        @foreach(['confirmed','pending','cancelled'] as $st)
          <option value="{{ $st }}" @selected(old('status',$reservation->status ?? 'confirmed')===$st)>{{ ucfirst($st) }}</option>
        @endforeach
      </select>
    </label>

    <label class="input-row">
      <span>Start</span>
      <input type="text" id="start_at" name="start_at"
            value="{{ old('start_at', optional($reservation->start_at)->format('Y-m-d H:i')) }}" required>
    </label>

    <label class="input-row">
      <span>Eind</span>
      <input type="text" id="end_at" name="end_at"
            value="{{ old('end_at', optional($reservation->end_at)->format('Y-m-d H:i')) }}" required>
    </label>


    <label class="input-row" style="grid-column:1/-1">
      <span>Notities</span>
      <textarea name="notes" rows="4">{{ old('notes', $reservation->notes) }}</textarea>
    </label>
  </div>

  <div class="page-actions" style="margin-top:14px;">
    <a href="{{ route('admin.'.$type.'.index') }}" class="btn">Annuleren</a>
    <div class="spacer"></div>
    <button class="btn primary" type="submit">Opslaan</button>
  </div>
</form>


<script>
  const baseOpts = {
    enableTime: true,
    time_24hr: true,            // 24-uurs weergave
    minuteIncrement: 15,        // stappen: 15 min (zet op 60 voor hele uren)
    dateFormat: "Y-m-d H:i",    // waarde die naar je backend gaat
    altInput: true,
    altFormat: "d-m-Y H:i",     // mooie weergave voor de gebruiker
    locale: "nl",
    // Optioneel: werktijden beperken (08:00 - 18:00)
    // minTime: "08:00",
    // maxTime: "18:00",
  };

  flatpickr("#start_at", baseOpts);
  flatpickr("#end_at",   baseOpts);
</script>
