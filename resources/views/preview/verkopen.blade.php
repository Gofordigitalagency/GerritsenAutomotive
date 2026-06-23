<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('verkopen_page.title') }} — Gerritsen Automotive</title>

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
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('verkopen_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('verkopen_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('verkopen_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('verkopen_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ STAPPEN ============ --}}
<section class="px-section">
  <div class="px-container">
    <div class="px-vk-steps">
      @foreach([1, 2, 3] as $i)
        <div class="px-vk-step px-reveal" style="--rd: {{ 0.05 + ($i - 1) * 0.1 }}s">
          <span class="px-vk-step-num">0{{ $i }}</span>
          <h3 class="px-vk-step-title">{{ setting('verkopen_page.step'.$i.'_title') }}</h3>
          <p class="px-vk-step-body">{{ setting('verkopen_page.step'.$i.'_body') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ============ FORM ============ --}}
<section class="px-section px-section-alt" id="formulier">
  <div class="px-container">

    @if(session('success'))
      <div class="px-form-success" style="margin-bottom: 22px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="px-form-error" style="margin-bottom: 22px;">
        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
      </div>
    @endif

    <div class="px-vk-card px-reveal">
      <form method="POST" action="{{ route('sellcar.store') }}" enctype="multipart/form-data" class="px-vk-form" id="pxVkForm">
        @csrf

        <div class="px-vk-section">
          <h2 class="px-vk-section-title">Gegevens van je auto</h2>

          <div class="px-vk-plate-row">
            <label class="px-plate-big" for="pxVkPlate">
              <span class="px-plate-nl-big"><span class="px-plate-stars">★★★</span> NL</span>
              <input type="text" id="pxVkPlate" name="license_plate" required placeholder="00-XXX-0" maxlength="10" autocapitalize="characters" spellcheck="false" value="{{ old('license_plate') }}">
            </label>
            <button type="button" class="px-btn px-btn-ghost" id="pxVkLookup">RDW ophalen</button>
          </div>

          <div class="px-form-row">
            <div class="px-input-wrap">
              <label for="pxVkBrand">Merk</label>
              <input type="text" id="pxVkBrand" name="brand" maxlength="100" value="{{ old('brand') }}">
            </div>
            <div class="px-input-wrap">
              <label for="pxVkModel">Model</label>
              <input type="text" id="pxVkModel" name="model" maxlength="100" value="{{ old('model') }}">
            </div>
          </div>

          <div class="px-input-wrap">
            <label for="pxVkMileage">KM-stand</label>
            <input type="number" id="pxVkMileage" name="mileage" required min="0" placeholder="bijv. 142.500" value="{{ old('mileage') }}">
          </div>

          <div class="px-input-wrap">
            <label for="pxVkRemarks">Bijzonderheden (optioneel)</label>
            <textarea id="pxVkRemarks" name="remarks" rows="4" maxlength="5000" placeholder="Bijvoorbeeld: APK tot mei 2027, kleine deuk linkerportier…">{{ old('remarks') }}</textarea>
          </div>
        </div>

        <div class="px-vk-section">
          <h2 class="px-vk-section-title">Foto's (optioneel)</h2>
          <p class="px-vk-section-sub">Voorkant, achterkant, dashboard en eventuele schade. Maximaal 20 foto's.</p>

          <label class="px-vk-uploader" for="pxVkPhotos">
            <input type="file" id="pxVkPhotos" name="photos[]" accept="image/*" multiple hidden>
            <span class="px-vk-uploader-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
            </span>
            <span class="px-vk-uploader-text">
              <strong>Klik om foto's te kiezen</strong>
              <span>of sleep ze hierheen</span>
            </span>
          </label>
          <div class="px-vk-uploader-preview" id="pxVkPreview"></div>
        </div>

        <div class="px-vk-section">
          <h2 class="px-vk-section-title">Jouw gegevens</h2>

          <div class="px-form-row">
            <div class="px-input-wrap">
              <label for="pxVkName">Naam</label>
              <input type="text" id="pxVkName" name="name" required maxlength="120" value="{{ old('name') }}">
            </div>
            <div class="px-input-wrap">
              <label for="pxVkPhone">Telefoon</label>
              <input type="tel" id="pxVkPhone" name="phone" required maxlength="60" value="{{ old('phone') }}">
            </div>
          </div>

          <div class="px-input-wrap">
            <label for="pxVkEmail">E-mail</label>
            <input type="email" id="pxVkEmail" name="email" required maxlength="190" value="{{ old('email') }}">
          </div>

          <div class="px-input-wrap">
            <label for="pxVkMessage">Aanvullende info (optioneel)</label>
            <textarea id="pxVkMessage" name="message" rows="3" maxlength="2000">{{ old('message') }}</textarea>
          </div>

          <label class="px-vk-checkbox">
            <input type="checkbox" name="privacy" value="1" required>
            <span>Ik ga akkoord met het privacybeleid.</span>
          </label>
        </div>

        <div class="px-form-foot">
          <button type="submit" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Aanvraag versturen</button>
          <span class="px-form-foot-meta">
            Liever bellen? <a href="tel:{{ setting_tel('contact.phone_sales') }}">{{ setting_phone('contact.phone_sales') }}</a>
          </span>
        </div>
      </form>
    </div>

    <p class="px-rsv-help">{{ setting('verkopen_page.help_text') }} <a href="tel:{{ setting_tel('contact.phone_sales') }}">{{ setting_phone('contact.phone_sales') }}</a></p>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>

<script>
  /* ===== KENTEKEN — auto-format ===== */
  (function () {
    const plate = document.getElementById('pxVkPlate');
    if (!plate) return;
    plate.addEventListener('input', () => {
      plate.value = plate.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
    });
  })();

  /* ===== RDW lookup — vul merk + model ===== */
  (function () {
    const btn = document.getElementById('pxVkLookup');
    const plate = document.getElementById('pxVkPlate');
    const brand = document.getElementById('pxVkBrand');
    const model = document.getElementById('pxVkModel');
    if (!btn || !plate) return;

    btn.addEventListener('click', async () => {
      const raw = plate.value.replace(/[^A-Z0-9]/g, '');
      if (raw.length < 4) {
        btn.textContent = 'Vul kenteken in';
        setTimeout(() => btn.textContent = 'RDW ophalen', 1500);
        return;
      }
      const orig = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Bezig…';
      try {
        const url = `/api/rdw/${encodeURIComponent(raw)}`;
        const res = await fetch(url);
        if (!res.ok) throw new Error('niet gevonden');
        const data = await res.json();
        if (data.merk && !brand.value)  brand.value = data.merk;
        if (data.model && !model.value) model.value = data.model;
        btn.textContent = 'Gevonden ✓';
      } catch (_) {
        btn.textContent = 'Niet gevonden';
      }
      setTimeout(() => { btn.textContent = orig; btn.disabled = false; }, 2000);
    });
  })();

  /* ===== FOTO PREVIEW ===== */
  (function () {
    const input = document.getElementById('pxVkPhotos');
    const preview = document.getElementById('pxVkPreview');
    if (!input || !preview) return;

    input.addEventListener('change', () => {
      preview.innerHTML = '';
      const files = Array.from(input.files || []).slice(0, 20);
      files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const url = URL.createObjectURL(file);
        const fig = document.createElement('figure');
        fig.className = 'px-vk-thumb';
        fig.innerHTML = `<img src="${url}" alt="">`;
        preview.appendChild(fig);
      });
    });
  })();
</script>

</body>
</html>
