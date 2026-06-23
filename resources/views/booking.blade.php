<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('reserveren_page.title') }} — Gerritsen Automotive</title>

    <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/preview.css') }}?v={{ filemtime(public_path('css/preview.css')) }}">

    <style>
      :root {
        --px-bg:        {{ setting('theme.bg') }};
        --px-bg-2:      {{ setting('theme.bg_alt') }};
        --px-surface:   {{ setting('theme.surface') }};
        --px-fg:        {{ setting('theme.fg') }};
        --px-fg-muted:  {{ setting('theme.fg_muted') }};
        --px-accent:        {{ setting('theme.accent') }};
        --px-accent-soft:   {{ setting('theme.accent_soft') }};
        --px-border:    {{ setting('theme.border') }};
      }
    </style>
</head>
<body class="px-body">

@include('preview.partials.header')

{{-- ============ PAGE HERO ============ --}}
<section class="px-page-hero px-page-hero-photo">
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('reserveren_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('reserveren_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('reserveren_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('reserveren_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ RESOURCE TABS + FORM ============ --}}
<section class="px-section">
  <div class="px-container">

    @if(session('ok'))
      <div class="px-form-success" style="margin-bottom: 22px;">{{ session('ok') }}</div>
    @endif
    @if(session('success'))
      <div class="px-form-success" style="margin-bottom: 22px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="px-form-error" style="margin-bottom: 22px;">
        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
      </div>
    @endif

    <div class="px-rsv-tabs">
      @php
        $tabs = [
          'aanhanger'  => ['label' => 'Aanhanger',          'icon' => '<rect x="3" y="11" width="14" height="6" rx="1"/><circle cx="7" cy="19" r="2"/><circle cx="14" cy="19" r="2"/><path d="M17 14h4"/>'],
          'stofzuiger' => ['label' => 'Tapijtreiniger',     'icon' => '<path d="M9 3v6h6V3"/><path d="M12 9v8"/><circle cx="12" cy="20" r="2"/>'],
          'koplampen'  => ['label' => 'Koplampen polijsten','icon' => '<circle cx="12" cy="12" r="9"/><path d="M9 12h6M12 9v6"/>'],
          'leenauto'   => ['label' => 'Leenauto',           'icon' => '<path d="M3 13l2-5a2 2 0 0 1 2-1h10a2 2 0 0 1 2 1l2 5"/><rect x="3" y="13" width="18" height="6" rx="1"/><circle cx="7" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/>'],
        ];
      @endphp

      @foreach($tabs as $key => $t)
        <a href="{{ route('booking.show', ['type' => $key]) }}"
           class="px-rsv-tab {{ $type === $key ? 'is-active' : '' }}">
          <span class="px-rsv-tab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $t['icon'] !!}</svg>
          </span>
          <span class="px-rsv-tab-label">{{ $t['label'] }}</span>
        </a>
      @endforeach
    </div>

    <div class="px-rsv-card px-reveal">

      @if(!empty($isLeenauto))
        {{-- ============ LEENAUTO: aanvraag-formulier (geen slots) ============ --}}
        <div class="px-rsv-head">
          <div>
            <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>{{ setting('leenauto.eyebrow') }}</div>
            <h2 class="px-rsv-title">{{ setting('leenauto.title') }}</h2>
            <p class="px-rsv-sub">{{ setting('leenauto.subtitle') }} · {{ setting('leenauto.price') }}</p>
          </div>
          @if(setting_image('leenauto.image_main'))
            <div class="px-rsv-head-photo">
              <img loading="lazy" src="{{ setting_image('leenauto.image_main') }}" alt="{{ setting('leenauto.title') }}">
            </div>
          @endif
        </div>

        <form method="POST" action="{{ route('contact.store') }}" class="px-rsv-form" id="pxLeenautoForm">
          @csrf
          <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
          <input type="hidden" name="privacy" value="1">
          <input type="hidden" name="message" id="pxLeenautoMessage">

          <div class="px-form-row">
            <div class="px-input-wrap">
              <label for="pxLeenFrom">Ophalen op</label>
              <input type="date" id="pxLeenFrom" name="leenauto_from" required min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}">
            </div>
            <div class="px-input-wrap">
              <label for="pxLeenTo">Terugbrengen op</label>
              <input type="date" id="pxLeenTo" name="leenauto_to" required min="{{ now()->toDateString() }}" value="{{ now()->addDay()->toDateString() }}">
            </div>
          </div>

          <div class="px-form-row">
            <div class="px-input-wrap">
              <label for="pxLeenName">Naam</label>
              <input type="text" id="pxLeenName" name="name" required maxlength="120" value="{{ old('name') }}">
            </div>
            <div class="px-input-wrap">
              <label for="pxLeenPhone">Telefoon</label>
              <input type="tel" id="pxLeenPhone" name="phone" required maxlength="40" value="{{ old('phone') }}">
            </div>
          </div>

          <div class="px-input-wrap">
            <label for="pxLeenEmail">E-mail</label>
            <input type="email" id="pxLeenEmail" name="email" required maxlength="190" value="{{ old('email') }}">
          </div>

          <div class="px-input-wrap">
            <label for="pxLeenNotes">Aanvullende info (optioneel)</label>
            <textarea id="pxLeenNotes" name="leenauto_notes" rows="4" maxlength="2000" placeholder="Bijvoorbeeld: doel van de huur, gewenste ophaaltijd…"></textarea>
          </div>

          <div class="px-form-foot">
            <button type="submit" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Aanvraag versturen</button>
            <span class="px-form-foot-meta">
              Liever bellen? <a href="tel:{{ setting_tel('contact.phone_workshop') }}">{{ setting('contact.phone_workshop') }}</a>
            </span>
          </div>
        </form>

      @else
        {{-- ============ AANHANGER / TAPIJTREINIGER / KOPLAMPEN: slot-flow ============ --}}
        <div class="px-rsv-head">
          <div>
            <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Reservering</div>
            <h2 class="px-rsv-title">{{ $typeLabel }}</h2>
            <p class="px-rsv-sub">Kies een datum en tijd. Bevestig met je gegevens.</p>
          </div>
        </div>

        <div class="px-rsv-grid">
          <div class="px-input-wrap">
            <label for="pxRsvDate">Datum</label>
            <input class="px-rsv-control" type="date" id="pxRsvDate" min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}">
          </div>
          <div class="px-input-wrap">
            <label>Beschikbare tijden</label>
            <div id="pxRsvTimes" class="px-rsv-times">Laden…</div>
          </div>
        </div>

        <div class="px-rsv-pickhint" id="pxRsvPickHint">
          Selecteer eerst een starttijd, daarna een eindtijd.
        </div>

        <form id="pxRsvForm" method="POST" action="{{ route('booking.store') }}" class="px-rsv-form" style="display:none">
          @csrf
          <input type="hidden" name="type" value="{{ $type }}">
          <input type="hidden" name="start_at" id="pxRsvStart">
          <input type="hidden" name="end_at" id="pxRsvEnd">

          <div class="px-rsv-summary" id="pxRsvSummary">Nog geen tijd geselecteerd</div>

          <div class="px-form-row">
            <div class="px-input-wrap">
              <label for="pxRsvName">Naam</label>
              <input type="text" id="pxRsvName" name="name" required maxlength="120" placeholder="Voor- en achternaam" value="{{ old('name') }}">
            </div>
            <div class="px-input-wrap">
              <label for="pxRsvPhone">Telefoon</label>
              <input type="tel" id="pxRsvPhone" name="phone" required maxlength="30" placeholder="06…" value="{{ old('phone') }}">
            </div>
          </div>

          <div class="px-input-wrap">
            <label for="pxRsvEmail">E-mail</label>
            <input type="email" id="pxRsvEmail" name="email" required maxlength="160" value="{{ old('email') }}">
          </div>

          <div class="px-form-foot">
            <button type="submit" class="px-btn px-btn-primary px-btn-lg" id="pxRsvSubmit" disabled data-magnetic>Reservering bevestigen</button>
            <span class="px-form-foot-meta">Betaling vindt plaats bij Gerritsen Automotive.</span>
          </div>
        </form>
      @endif
    </div>

    <p class="px-rsv-help">{{ setting('reserveren_page.help_text') }} <a href="tel:{{ setting_tel('contact.phone_workshop') }}">{{ setting('contact.phone_workshop') }}</a></p>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>

@if(empty($isLeenauto))
<script>
  /* ===== SLOT-BOOKING (aanhanger / tapijtreiniger / koplampen) ===== */
  (function () {
    const type = @json($type);
    const durationMin = @json($durationMin ?? 60);
    const oneClick = (type === 'koplampen');

    const dateEl    = document.getElementById('pxRsvDate');
    const timesEl   = document.getElementById('pxRsvTimes');
    const form      = document.getElementById('pxRsvForm');
    const startInp  = document.getElementById('pxRsvStart');
    const endInp    = document.getElementById('pxRsvEnd');
    const summary   = document.getElementById('pxRsvSummary');
    const submitBtn = document.getElementById('pxRsvSubmit');
    const hintEl    = document.getElementById('pxRsvPickHint');

    if (hintEl) {
      hintEl.textContent = oneClick
        ? 'Selecteer een tijd om je reservering te plannen.'
        : 'Selecteer eerst een starttijd, daarna een eindtijd.';
    }

    let slots = [];
    let selStart = null, selEnd = null;

    const pad = n => String(n).padStart(2, '0');
    const toShort = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;

    function reset() {
      selStart = selEnd = null;
      startInp.value = endInp.value = '';
      submitBtn.disabled = true;
      summary.textContent = 'Nog geen tijd geselecteerd';
      form.style.display = 'none';
      [...timesEl.children].forEach(b => b.classList?.remove('sel-start','range','selected'));
    }

    async function loadSlots() {
      reset();
      timesEl.innerHTML = '<span class="px-rsv-loading">Laden…</span>';
      const url = @json(route('booking.slots')) + `?type=${encodeURIComponent(type)}&date=${encodeURIComponent(dateEl.value)}`;
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        slots = await res.json();
        render();
      } catch (e) {
        console.error(e);
        timesEl.innerHTML = '<span class="px-rsv-empty">Kon tijden niet laden.</span>';
      }
    }

    function render() {
      if (!Array.isArray(slots) || slots.length === 0) {
        timesEl.innerHTML = '<span class="px-rsv-empty">Geen tijden beschikbaar voor deze dag.</span>';
        return;
      }
      timesEl.innerHTML = '';
      slots.forEach((s, i) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'px-rsv-time';
        b.textContent = s.label;
        b.addEventListener('click', () => pick(i, b));
        timesEl.appendChild(b);
      });
    }

    function contiguous(from, to) {
      if (to <= from) return false;
      for (let j = from; j < to; j++) if (!slots[j] || !slots[j+1]) return false;
      return true;
    }

    function pick(i, btn) {
      if (oneClick) {
        [...timesEl.children].forEach(b => b.classList.remove('sel-start','range','selected'));
        selStart = i;
        btn.classList.add('selected');
        const start = new Date(slots[i].start.replace(' ', 'T'));
        const end   = new Date(start.getTime() + durationMin * 60000);
        startInp.value = toShort(start);
        endInp.value   = toShort(end);
        summary.textContent = `Gekozen tijd: ${slots[i].label} – ${pad(end.getHours())}:${pad(end.getMinutes())}`;
        form.style.display = '';
        submitBtn.disabled = false;
        return;
      }
      if (selStart === null) {
        selStart = i;
        btn.classList.add('sel-start','selected');
        summary.textContent = `Start: ${slots[i].label}`;
        form.style.display = 'none';
        submitBtn.disabled = true;
        return;
      }
      if (i <= selStart || !contiguous(selStart, i)) { reset(); pick(i, btn); return; }
      selEnd = i;
      [...timesEl.children].forEach(b => b.classList.remove('range','selected'));
      for (let k = selStart; k <= selEnd; k++) timesEl.children[k].classList.add('range');
      timesEl.children[selStart].classList.add('sel-start','selected');
      timesEl.children[selEnd].classList.add('selected');

      const start   = new Date(slots[selStart].start.replace(' ', 'T'));
      const endBase = new Date(slots[selEnd].start.replace(' ', 'T'));
      const end     = new Date(endBase.getTime() + 30 * 60000);
      startInp.value = toShort(start);
      endInp.value   = toShort(end);
      summary.textContent = `Gekozen tijd: ${slots[selStart].label} – ${pad(end.getHours())}:${pad(end.getMinutes())}`;
      form.style.display = '';
      submitBtn.disabled = false;
    }

    dateEl.addEventListener('change', loadSlots);
    loadSlots();
  })();
</script>
@else
<script>
  /* ===== LEENAUTO-AANVRAAG: bouw bericht-string voor contact.store ===== */
  (function () {
    const form  = document.getElementById('pxLeenautoForm');
    const from  = document.getElementById('pxLeenFrom');
    const to    = document.getElementById('pxLeenTo');
    const notes = document.getElementById('pxLeenNotes');
    const msg   = document.getElementById('pxLeenautoMessage');
    if (!form) return;

    const fmtDate = (s) => {
      const [y, m, d] = s.split('-');
      return `${d}-${m}-${y}`;
    };

    form.addEventListener('submit', () => {
      const lines = [];
      lines.push(`[Leenauto-aanvraag]`);
      if (from.value) lines.push(`Ophalen op: ${fmtDate(from.value)}`);
      if (to.value)   lines.push(`Terugbrengen op: ${fmtDate(to.value)}`);
      if (notes.value.trim()) lines.push(`\nAanvullende info:\n${notes.value.trim()}`);
      msg.value = lines.join('\n');
    });

    // Auto-set min van 'tot' bij verandering 'van'
    from.addEventListener('change', () => {
      if (to.value < from.value) to.value = from.value;
      to.min = from.value;
    });
  })();
</script>
@endif

</body>
</html>
