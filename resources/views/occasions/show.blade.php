@php
    $pageTitle = trim(($occasion->merk ?? '').' '.($occasion->model ?? '')) ?: $occasion->titel;

    $cover = $occasion->hoofdfoto_path
        ? asset('storage/'.$occasion->hoofdfoto_path)
        : asset('images/placeholder-car.jpg');

    $galerijRaw = $occasion->galerij ?? [];
    if (is_string($galerijRaw)) {
        $decoded = json_decode($galerijRaw, true);
        $galerijRaw = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }
    $galerijUrls = collect((array)$galerijRaw)
        ->filter()
        ->map(fn ($p) => is_string($p) && !str_starts_with($p, 'http') ? asset('storage/'.$p) : $p);

    $galleryAll = collect([$cover])->merge($galerijUrls)->filter()->unique()->values();

    // OG/share-meta opbouwen
    $parts = [];
    if (!empty($occasion->bouwjaar))     $parts[] = $occasion->bouwjaar;
    if (!empty($occasion->tellerstand))  $parts[] = number_format($occasion->tellerstand, 0, ',', '.') . ' km';
    if (!empty($occasion->brandstof))    $parts[] = ucfirst($occasion->brandstof);
    if (!empty($occasion->transmissie))  $parts[] = ucfirst($occasion->transmissie);
    if (!empty($occasion->prijs))        $parts[] = '€ ' . number_format($occasion->prijs, 0, ',', '.');
    $ogDescription = implode(' · ', $parts);
    if ($ogDescription === '' && !empty($occasion->omschrijving)) {
        $ogDescription = \Illuminate\Support\Str::limit(strip_tags($occasion->omschrijving), 150);
    }

    $hasDiscount = !empty($occasion->oude_prijs) && $occasion->oude_prijs > $occasion->prijs;
    $sold = stripos($occasion->model ?? '', '(VERKOCHT)') !== false;

    $opties_flat = preg_split('/\r\n|\r|\n/', (string)($occasion->opties ?? ''), -1, PREG_SPLIT_NO_EMPTY);
    $exterieur   = $occasion->exterieur_options  ?? [];
    $interieur   = $occasion->interieur_options  ?? [];
    $veiligheid  = $occasion->veiligheid_options ?? [];
    $overige     = $occasion->overige_options    ?? [];
    $hasOpties   = !empty($exterieur) || !empty($interieur) || !empty($veiligheid) || !empty($overige) || !empty($opties_flat);

    $vermogen = $occasion->vermogen_pk ?? $occasion->pk ?? $occasion->vermogen ?? null;

    $waMessage = 'Hoi! Ik heb interesse in de ' . $pageTitle
        . (!empty($occasion->bouwjaar) ? ' (' . $occasion->bouwjaar . ')' : '')
        . '. Is deze nog beschikbaar?';
@endphp
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }} — Gerritsen Automotive</title>

    <meta property="og:title" content="{{ $pageTitle }} – Gerritsen Automotive">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Gerritsen Automotive">
    <meta property="og:image" content="{{ $cover }}">
    <meta property="og:image:alt" content="{{ $pageTitle }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }} – Gerritsen Automotive">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $cover }}">

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

<section class="px-section px-od-section">
  <div class="px-container">

    <a href="{{ route('aanbod') }}" class="px-od-back">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Terug naar aanbod
    </a>

    <div class="px-od-shell">

      {{-- ============ LINKER KOLOM: GALLERY ============ --}}
      <div class="px-od-left">
        <div class="px-od-stage" data-urls='@json($galleryAll)'>
          <img id="pxOdMain" src="{{ $galleryAll->first() ?? $cover }}" alt="{{ $pageTitle }}">

          @if($sold)
            <span class="px-od-badge px-od-badge-sold">Verkocht</span>
          @elseif($hasDiscount)
            <span class="px-od-badge px-od-badge-sale">Aanbieding</span>
          @endif

          @if($galleryAll->count() > 1)
            <button class="px-od-nav px-od-prev" type="button" aria-label="Vorige foto">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button class="px-od-nav px-od-next" type="button" aria-label="Volgende foto">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
            </button>
            <span class="px-od-counter"><span id="pxOdIdx">1</span> / {{ $galleryAll->count() }}</span>
          @endif
        </div>

        @if($galleryAll->count() > 1)
          <div class="px-od-thumbs" id="pxOdThumbs">
            @foreach($galleryAll as $i => $url)
              <button type="button" class="px-od-thumb {{ $i === 0 ? 'is-active' : '' }}" data-idx="{{ $i }}">
                <img loading="lazy" src="{{ $url }}" alt="Foto {{ $i + 1 }}">
              </button>
            @endforeach
          </div>
        @endif
      </div>

      {{-- ============ RECHTER KOLOM: INFO + STICKY CTA ============ --}}
      <aside class="px-od-right">
        <h1 class="px-od-title">{{ $pageTitle }}</h1>
        @if(!empty($occasion->type))
          <p class="px-od-sub">{{ $occasion->type }}</p>
        @endif

        <div class="px-od-price-row">
          @if($hasDiscount)
            <span class="px-od-price-old">€ {{ number_format($occasion->oude_prijs, 0, ',', '.') }}</span>
            <span class="px-od-price px-od-price-sale">€ {{ number_format($occasion->prijs ?? 0, 0, ',', '.') }}</span>
            @php $korting = $occasion->oude_prijs - $occasion->prijs; @endphp
            <span class="px-od-saving">€ {{ number_format($korting, 0, ',', '.') }} korting</span>
          @else
            <span class="px-od-price">€ {{ number_format($occasion->prijs ?? 0, 0, ',', '.') }}</span>
          @endif
        </div>

        <div class="px-od-quickspecs">
          <div class="px-od-quickspec"><span class="k">Bouwjaar</span><span class="v">{{ $occasion->bouwjaar ?? '·' }}</span></div>
          <div class="px-od-quickspec"><span class="k">KM-stand</span><span class="v">{{ isset($occasion->tellerstand) ? number_format($occasion->tellerstand, 0, ',', '.') : '·' }}</span></div>
          <div class="px-od-quickspec"><span class="k">Brandstof</span><span class="v">{{ $occasion->brandstof ? ucfirst($occasion->brandstof) : '·' }}</span></div>
          <div class="px-od-quickspec"><span class="k">Transmissie</span><span class="v">{{ $occasion->transmissie ? ucfirst($occasion->transmissie) : '·' }}</span></div>
          @if(!empty($occasion->kleur) || !empty($occasion->exterieurkleur))
            <div class="px-od-quickspec"><span class="k">Kleur</span><span class="v">{{ $occasion->kleur ?? $occasion->exterieurkleur }}</span></div>
          @endif
          @if(!empty($vermogen))
            <div class="px-od-quickspec"><span class="k">Vermogen</span><span class="v">{{ $vermogen }} PK</span></div>
          @endif
          @if(!empty($occasion->energielabel))
            <div class="px-od-quickspec"><span class="k">Energielabel</span><span class="v"><span class="px-od-energy">{{ $occasion->energielabel }}</span></span></div>
          @endif
          @if(!empty($occasion->btw_marge))
            <div class="px-od-quickspec"><span class="k">BTW/MARGE</span><span class="v">{{ $occasion->btw_marge }}</span></div>
          @endif
        </div>

        <div class="px-od-cta">
          <a href="#contact" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Plan een proefrit</a>
          <button type="button" class="px-btn px-btn-ghost px-btn-lg" id="pxOdShare">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16,6 12,2 8,6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
            Delen
          </button>
        </div>

        <div class="px-od-sellers">
          <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-od-seller">
            <div class="px-od-seller-avatar">{{ Str::upper(Str::substr(setting('over.person1_name'), 0, 1)) }}</div>
            <div class="px-od-seller-body">
              <span class="px-od-seller-role">{{ setting('over.person1_role') }}</span>
              <span class="px-od-seller-name">{{ setting('over.person1_name') }}</span>
              <span class="px-od-seller-phone">{{ setting('contact.phone_sales') }}</span>
            </div>
          </a>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting_tel('contact.phone_sales')) }}?text={{ urlencode($waMessage) }}" target="_blank" rel="noopener" class="px-od-seller px-od-seller-wa">
            <div class="px-od-seller-avatar px-od-seller-avatar-wa">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
            </div>
            <div class="px-od-seller-body">
              <span class="px-od-seller-role">Direct vraag stellen</span>
              <span class="px-od-seller-name">WhatsApp</span>
              <span class="px-od-seller-phone">Klik om te chatten</span>
            </div>
          </a>
        </div>
      </aside>
    </div>

    {{-- ============ TABS ============ --}}
    <div class="px-od-tabs" role="tablist">
      <button class="px-od-tab is-active" data-tab="specs" role="tab">Kenmerken</button>
      <button class="px-od-tab" data-tab="options" role="tab">Opties</button>
      <button class="px-od-tab" data-tab="desc" role="tab">Omschrijving</button>
    </div>

    <section class="px-od-tabpanel is-active" id="px-od-tab-specs" role="tabpanel">
      <div class="px-od-spec-cols">
        <ul class="px-od-spec-list">
          <li><span class="k">Merk</span><span class="v">{{ $occasion->merk ?? '·' }}</span></li>
          <li><span class="k">Model</span><span class="v">{{ $occasion->model ?? '·' }}</span></li>
          <li><span class="k">Type</span><span class="v">{{ $occasion->type ?? '·' }}</span></li>
          <li><span class="k">Bouwjaar</span><span class="v">{{ $occasion->bouwjaar ?? '·' }}</span></li>
          <li><span class="k">Transmissie</span><span class="v">{{ $occasion->transmissie ? ucwords($occasion->transmissie) : '·' }}</span></li>
          <li><span class="k">Brandstof</span><span class="v">{{ $occasion->brandstof ? ucwords($occasion->brandstof) : '·' }}</span></li>
          <li><span class="k">Kleur</span><span class="v">{{ $occasion->kleur ?? $occasion->exterieurkleur ?? '·' }}</span></li>
          <li><span class="k">Carrosserie</span><span class="v">{{ $occasion->carrosserie ?? '·' }}</span></li>
          <li><span class="k">Aantal cilinders</span><span class="v">{{ $occasion->aantal_cilinders ?? '·' }}</span></li>
          <li><span class="k">Vermogen</span><span class="v">{{ !empty($vermogen) ? $vermogen.' PK' : '·' }}</span></li>
          <li><span class="k">Topsnelheid</span><span class="v">{{ !empty($occasion->topsnelheid) ? $occasion->topsnelheid.' km/u' : '·' }}</span></li>
          <li><span class="k">Gewicht</span><span class="v">{{ !empty($occasion->gewicht) ? $occasion->gewicht.' kg' : '·' }}</span></li>
        </ul>
        <ul class="px-od-spec-list">
          <li><span class="k">Tellerstand</span><span class="v">{{ !empty($occasion->tellerstand) ? number_format($occasion->tellerstand,0,',','.').' km' : '·' }}</span></li>
          <li><span class="k">Aantal deuren</span><span class="v">{{ $occasion->aantal_deuren ?? '·' }}</span></li>
          <li><span class="k">Bekleding</span><span class="v">{{ $occasion->bekleding ?? '·' }}</span></li>
          <li><span class="k">Interieurkleur</span><span class="v">{{ $occasion->interieurkleur ?? '·' }}</span></li>
          <li><span class="k">BTW/Marge</span><span class="v">{{ $occasion->btw_marge ?? '·' }}</span></li>
          <li><span class="k">Cilinderinhoud</span><span class="v">{{ !empty($occasion->cilinderinhoud) ? $occasion->cilinderinhoud.' cc' : '·' }}</span></li>
          <li><span class="k">Gem. verbruik</span><span class="v">{{ $occasion->gemiddeld_verbruik ?? '·' }} / 100km</span></li>
          <li><span class="k">Energielabel</span><span class="v">@if(!empty($occasion->energielabel))<span class="px-od-energy">{{ $occasion->energielabel }}</span>@else · @endif</span></li>
          <li><span class="k">Wegenbelasting</span><span class="v">{{ !empty($occasion->wegenbelasting_min) ? '€ '.$occasion->wegenbelasting_min.' /kw' : '·' }}</span></li>
          <li><span class="k">Prijs</span><span class="v">{{ !empty($occasion->prijs) ? '€ '.number_format($occasion->prijs,0,',','.') : '·' }}</span></li>
        </ul>
      </div>
    </section>

    <section class="px-od-tabpanel" id="px-od-tab-options" role="tabpanel">
      @if($hasOpties)
        <div class="px-od-opts-grid">
          @if(!empty($exterieur))
            <div class="px-od-opts-col">
              <h4>Exterieur</h4>
              <ul class="px-od-bullets">@foreach($exterieur as $o)<li>{{ $o }}</li>@endforeach</ul>
            </div>
          @endif
          @if(!empty($interieur))
            <div class="px-od-opts-col">
              <h4>Interieur</h4>
              <ul class="px-od-bullets">@foreach($interieur as $o)<li>{{ $o }}</li>@endforeach</ul>
            </div>
          @endif
          @if(!empty($veiligheid))
            <div class="px-od-opts-col">
              <h4>Veiligheid</h4>
              <ul class="px-od-bullets">@foreach($veiligheid as $o)<li>{{ $o }}</li>@endforeach</ul>
            </div>
          @endif
          @if(!empty($overige))
            <div class="px-od-opts-col">
              <h4>Infotainment / Overige</h4>
              <ul class="px-od-bullets">@foreach($overige as $o)<li>{{ $o }}</li>@endforeach</ul>
            </div>
          @endif
          @if(empty($exterieur) && empty($interieur) && empty($veiligheid) && empty($overige) && !empty($opties_flat))
            <div class="px-od-opts-col px-od-opts-col-wide">
              <ul class="px-od-bullets px-od-bullets-cols">@foreach($opties_flat as $o)<li>{{ trim($o) }}</li>@endforeach</ul>
            </div>
          @endif
        </div>
      @else
        <p class="px-od-empty">{{ setting('occasion_page.empty_text') }}</p>
      @endif
    </section>

    <section class="px-od-tabpanel" id="px-od-tab-desc" role="tabpanel">
      @if(!empty($occasion->omschrijving))
        <div class="px-od-desc">{!! nl2br(e($occasion->omschrijving)) !!}</div>
      @else
        <p class="px-od-empty">Geen omschrijving aanwezig.</p>
      @endif
    </section>

  </div>
</section>

{{-- ============ BOTTOM CTA ============ --}}
<section class="px-section px-section-alt" id="contact">
  <div class="px-container">
    <div class="px-od-bottom-cta px-reveal">
      <div>
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Geïnteresseerd</div>
        <h2 class="px-h2">{{ setting('occasion_page.cta_title') }}</h2>
        <p>{{ setting('occasion_page.cta_sub') }}</p>
      </div>
      <div class="px-od-bottom-cta-actions">
        <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-btn px-btn-primary px-btn-lg" data-magnetic>{{ setting('occasion_page.cta_btn') }}</a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting_tel('contact.phone_sales')) }}?text={{ urlencode($waMessage) }}" target="_blank" rel="noopener" class="px-btn px-btn-ghost px-btn-lg">
          WhatsApp
        </a>
      </div>
    </div>

    {{-- Reactieformulier — stuurt automatisch mee om welke auto het gaat --}}
    <form class="px-contact-form px-reveal" method="POST" action="{{ route('contact.store') }}"
          style="--rd:.1s; margin-top:34px; max-width:680px;">
      @csrf
      <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
      <input type="hidden" name="privacy" value="1">
      <input type="hidden" name="occasion" value="{{ $pageTitle }}{{ !empty($occasion->bouwjaar) ? ' ('.$occasion->bouwjaar.')' : '' }}">
      <input type="hidden" name="occasion_url" value="{{ url()->current() }}">

      @if(session('success'))
        <div class="px-form-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="px-form-error">
          @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
      @endif

      <div class="px-form-row">
        <div class="px-input-wrap">
          <label for="rcName">Naam</label>
          <input type="text" id="rcName" name="name" required maxlength="120" value="{{ old('name') }}">
        </div>
        <div class="px-input-wrap">
          <label for="rcEmail">E-mail</label>
          <input type="email" id="rcEmail" name="email" required maxlength="190" value="{{ old('email') }}">
        </div>
      </div>

      <div class="px-input-wrap">
        <label for="rcPhone">Telefoon (optioneel)</label>
        <input type="tel" id="rcPhone" name="phone" maxlength="40" value="{{ old('phone') }}">
      </div>

      <div class="px-input-wrap">
        <label for="rcMessage">Bericht</label>
        <textarea id="rcMessage" name="message" required maxlength="5000" rows="4">{{ old('message', 'Ik heb interesse in de '.$pageTitle.'. Ik hoor graag meer.') }}</textarea>
      </div>

      <div class="px-form-foot">
        <button type="submit" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Verstuur reactie</button>
      </div>
    </form>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>

<script>
  /* ===== GALLERY ===== */
  (function () {
    const stage = document.querySelector('.px-od-stage');
    if (!stage) return;
    const main = document.getElementById('pxOdMain');
    const idxLabel = document.getElementById('pxOdIdx');
    const prev = stage.querySelector('.px-od-prev');
    const next = stage.querySelector('.px-od-next');
    const thumbs = document.querySelectorAll('#pxOdThumbs .px-od-thumb');
    const urls = JSON.parse(stage.getAttribute('data-urls') || '[]');
    let cur = 0;

    const show = (i) => {
      if (!urls.length) return;
      cur = (i + urls.length) % urls.length;
      if (main) { main.src = urls[cur]; main.decoding = 'async'; }
      if (idxLabel) idxLabel.textContent = cur + 1;
      thumbs.forEach((t, j) => t.classList.toggle('is-active', j === cur));
      const active = thumbs[cur];
      if (active && active.scrollIntoView) {
        active.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
      }
    };
    thumbs.forEach((t, i) => t.addEventListener('click', () => show(i)));
    prev?.addEventListener('click', () => show(cur - 1));
    next?.addEventListener('click', () => show(cur + 1));

    // Keyboard
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') show(cur - 1);
      if (e.key === 'ArrowRight') show(cur + 1);
    });

    // Swipe op mobiel
    let startX = 0, dx = 0;
    main?.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; dx = 0; }, { passive: true });
    main?.addEventListener('touchmove', (e) => { dx = e.touches[0].clientX - startX; }, { passive: true });
    main?.addEventListener('touchend', () => {
      if (Math.abs(dx) > 40) show(cur + (dx < 0 ? 1 : -1));
    });
  })();

  /* ===== TABS ===== */
  (function () {
    const tabs = document.querySelectorAll('.px-od-tab');
    const panels = {
      specs: document.getElementById('px-od-tab-specs'),
      options: document.getElementById('px-od-tab-options'),
      desc: document.getElementById('px-od-tab-desc'),
    };
    tabs.forEach(btn => {
      btn.addEventListener('click', () => {
        tabs.forEach(b => b.classList.remove('is-active'));
        Object.values(panels).forEach(p => p?.classList.remove('is-active'));
        btn.classList.add('is-active');
        panels[btn.dataset.tab]?.classList.add('is-active');
      });
    });
  })();

  /* ===== SHARE ===== */
  (function () {
    const btn = document.getElementById('pxOdShare');
    if (!btn) return;
    btn.addEventListener('click', async () => {
      const url = window.location.href;
      const title = document.title;
      if (navigator.share) {
        try { await navigator.share({ title, url }); } catch (_) {}
      } else if (navigator.clipboard) {
        try {
          await navigator.clipboard.writeText(url);
          btn.classList.add('is-copied');
          const lbl = btn.querySelector('span') || btn;
          const orig = btn.innerHTML;
          btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5L20 7"/></svg> Link gekopieerd';
          setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('is-copied'); }, 2000);
        } catch (_) {}
      }
    });
  })();
</script>
</body>
</html>
