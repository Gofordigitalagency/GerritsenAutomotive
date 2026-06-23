@php
  /* ============================================================
     INLINE HELPERS — alleen voor /preview op productie
     ============================================================
     De officiële setting()/setting_image()/setting_tel() helpers
     vereisen een autoload-registratie + een site_settings table.
     Op productie staat nu alleen de blade gepusht, dus we
     definieren hier global fallback-functies met hardgecodeerde
     defaults zodat /preview standalone werkt.
  */
  if (! function_exists('setting')) {
    function setting($key, $default = null) {
      static $defaults = [
        'theme.bg'           => '#0b0c10',
        'theme.bg_alt'       => '#11131a',
        'theme.surface'      => '#161922',
        'theme.fg'           => '#f4f5f8',
        'theme.fg_muted'     => '#8a8d99',
        'theme.accent'       => '#e63946',
        'theme.accent_soft'  => '#ff6b6b',
        'theme.border'       => 'rgba(255,255,255,.08)',

        'hero.eyebrow'       => 'Gerritsen Automotive · Arnhem',
        'hero.title_line1'   => 'Uw partner in',
        'hero.title_accent'  => 'betrouwbare',
        'hero.title_line2'   => 'occasions.',
        'hero.sub'           => "Zorgvuldig geselecteerde auto's, eerlijk advies en een eigen werkplaats.\nAlles onder één dak in Arnhem.",
        'hero.cta_primary'   => 'Bekijk occasions',
        'hero.cta_secondary' => 'Contact',
        'hero.bg_image'      => 'images/backgroundhome.jpg',

        'over.eyebrow'       => 'Over ons',
        'over.title'         => 'Een klein team. Een hele garage.',
        'over.body_p1'       => 'Bij Gerritsen Automotive in Arnhem ben je geen klantnummer. Je hebt direct contact met de mensen die de auto kennen, repareren en verkopen.',
        'over.body_p2'       => 'Persoonlijk advies, duidelijke prijzen en alles op één locatie: verkoop, werkplaats en verhuur. Loop binnen, bel of stuur een berichtje, we helpen je graag.',
        'over.image'         => 'images/handshake.jpg',
        'over.person1_name'  => 'Shania',
        'over.person1_role'  => 'Verkoop',
        'over.person2_name'  => 'Mick',
        'over.person2_role'  => 'Werkplaats',

        'contact.address'        => 'Gelderse Rooslaan 14 A, 6841 BE Arnhem',
        'contact.phone_sales'    => '0638257987',
        'contact.phone_workshop' => '0649951874',
        'contact.email'          => 'info@gerritsenautomotive.nl',
        'contact.hours_weekday'  => 'Ma t/m vr 08:30 – 17:30',
        'contact.hours_saturday' => 'Za 09:00 – 16:00',
        'contact.hours_sunday'   => 'Zo gesloten',

        'leenauto.eyebrow'    => 'Leenauto',
        'leenauto.title'      => 'Toyota Aygo Premium Edition',
        'leenauto.subtitle'   => 'Compact rijden. Premium gevoel.',
        'leenauto.price'      => 'Vanaf € 35 per dag',
        'leenauto.usps'       => "Apple CarPlay\nLederen interieur\nAirco\n5-deurs comfort\nElektrische ramen\nHandgeschakeld\nZuinig in verbruik\nOnbeperkte KM",
        'leenauto.location'   => 'Direct beschikbaar in Arnhem',
        'leenauto.image_main' => 'images/WhatsApp Image 2026-02-25 at 08.05.40.jpeg',
        'leenauto.image_2'    => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (1).jpeg',
        'leenauto.image_3'    => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (2).jpeg',
        'leenauto.image_4'    => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (3).jpeg',
        'leenauto.image_5'    => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (4).jpeg',
        'leenauto.cta_primary'   => 'Reserveer nu',
        'leenauto.cta_secondary' => 'Bel direct',

        'werkplaats.eyebrow' => 'Werkplaats',
        'werkplaats.title'   => 'APK, beurt of reparatie?',
        'werkplaats.title2'  => 'Vul je kenteken, wij doen de rest.',
        'werkplaats.image'   => 'images/afspraak-banner.jpg',
      ];
      return $defaults[$key] ?? $default ?? '';
    }
  }

  if (! function_exists('setting_image')) {
    function setting_image($key, $default = null) {
      $value = setting($key, $default) ?: '';
      if ($value === '') return $default ? asset($default) : '';
      if (str_starts_with($value, 'site/') || str_starts_with($value, 'uploads/')) {
        return asset('storage/' . $value);
      }
      return asset($value);
    }
  }

  if (! function_exists('setting_tel')) {
    function setting_tel($key) {
      $raw = setting($key, '') ?? '';
      $clean = preg_replace('/[^\d+]/', '', $raw);
      if ($clean === '') return '';
      if (str_starts_with($clean, '06')) {
        $clean = '+31' . substr($clean, 1);
      }
      return $clean;
    }
  }

  if (! function_exists('setting_phone')) {
    function setting_phone($key) {
      $raw = setting($key, '') ?? '';
      $clean = preg_replace('/[^\d]/', '', $raw);
      if ($clean === '') return '';
      if (str_starts_with($clean, '316')) {
        $clean = '0' . substr($clean, 2);
      }
      if (str_starts_with($clean, '06') && strlen($clean) === 10) {
        return '06 ' . substr($clean, 2, 2) . ' ' . substr($clean, 4, 2)
             . ' ' . substr($clean, 6, 2) . ' ' . substr($clean, 8, 2);
      }
      if (str_starts_with($clean, '0') && strlen($clean) >= 9) {
        return substr($clean, 0, 3) . ' ' . substr($clean, 3);
      }
      return $raw;
    }
  }
@endphp
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="robots" content="index,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gerritsen Automotive · Betrouwbare occasions in Arnhem</title>

    <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/preview.css') }}?v={{ filemtime(public_path('css/preview.css')) }}">

    {{-- DB-driven theme overrides — admin kan kleuren wijzigen zonder code --}}
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

@php
  // Alleen niet-verkochte auto's tellen mee in "occasions" displays
  $availableCount = $nieuw->reject(fn ($c) => stripos($c->model ?? '', '(VERKOCHT)') !== false)->count();
@endphp

{{-- ============ NAV (gedeelde partial — werkt cross-page) ============ --}}
@include('preview.partials.header')

{{-- ============ 1 · HERO ============ --}}
<section class="px-hero" id="pxHero">
  <div class="px-hero-bg" style="background-image: url('{{ setting_image('hero.bg_image') }}');"></div>
  <div class="px-hero-overlay"></div>
  <div class="px-hero-grain"></div>
  <div class="px-hero-cursor-light" id="pxHeroCursor" aria-hidden="true"></div>

  <div class="px-hero-inner">
    <div class="px-hero-content">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('hero.eyebrow') }}</div>
      <h1 class="px-hero-title px-reveal" style="--rd: .1s">
        {{ setting('hero.title_line1') }}<br>
        <span class="px-accent-soft">{{ setting('hero.title_accent') }}</span> {{ setting('hero.title_line2') }}
      </h1>
      <p class="px-hero-sub px-reveal" style="--rd: .2s">
        {!! nl2br(e(setting('hero.sub'))) !!}
      </p>
      <div class="px-hero-cta px-reveal" style="--rd: .3s">
        <a href="#aanbod" class="px-btn px-btn-primary px-btn-lg" data-magnetic><span class="px-counter" data-target="{{ $availableCount }}">0</span> {{ setting('hero.cta_primary') }}</a>
        <a href="#contact" class="px-btn px-btn-ghost px-btn-lg">{{ setting('hero.cta_secondary') }}</a>
      </div>

      <div class="px-trust-row px-reveal" style="--rd: .4s">
        <div class="px-trust-item">
          <div class="px-stars" aria-label="4.9 sterren">
            @for($i=0;$i<5;$i++)<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
          </div>
          <span><b><span class="px-counter" data-target="4.9" data-decimals="1">0,0</span></b> · Google reviews</span>
        </div>
        <span class="px-trust-divider"></span>
        <div class="px-trust-item"><span>BOVAG aangesloten</span></div>
        <span class="px-trust-divider"></span>
        <div class="px-trust-item"><span>Eigen werkplaats</span></div>
      </div>
    </div>
  </div>

  <div class="px-hero-scroll-hint" aria-hidden="true">
    <span>Scroll</span>
    <span class="px-scroll-line"></span>
  </div>
</section>

{{-- ============ 2 · AANBOD SHOWCASE ============ --}}
<section class="px-section px-section-alt" id="aanbod">
  <div class="px-container">
    <div class="px-section-head px-section-head-row">
      <div>
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Live aanbod</div>
        <h2 class="px-h2">Onze occasions</h2>
      </div>
      <div class="px-search-wrap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
        <input type="search" id="pxSearch" placeholder="Zoek op merk of model…">
      </div>
    </div>

    <div class="px-chips" id="pxChips">
      <button type="button" class="px-chip px-chip-active" data-filter="all">Alle <span>{{ $availableCount }}</span></button>
      <button type="button" class="px-chip" data-filter="price:0-1500">Tot € 1.500</button>
      <button type="button" class="px-chip" data-filter="price:1500-2500">€ 1.500 – 2.500</button>
      <button type="button" class="px-chip" data-filter="price:2500-4000">€ 2.500 – 4.000</button>
      <button type="button" class="px-chip" data-filter="price:4000-99999999">€ 4.000+</button>
      <button type="button" class="px-chip" data-filter="trans:auto">Automaat</button>
      <button type="button" class="px-chip" data-filter="sale">Aanbieding</button>
    </div>

    <div class="px-grid" id="pxGrid">
      @foreach($nieuw->take(6) as $car)
        @php
          $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));
          if ($merkModel === '' && !empty($car->titel)) $merkModel = $car->titel;
          $hasDiscount = !empty($car->oude_prijs) && $car->oude_prijs > $car->prijs;
          $sold = stripos($car->model ?? '', '(VERKOCHT)') !== false;
        @endphp
        <a class="px-card {{ $sold ? 'px-card-sold' : '' }}"
           href="{{ route('occasions.show', $car->slug) }}"
           data-brandstof="{{ $car->brandstof ?? '' }}"
           data-trans="{{ strtolower($car->transmissie ?? '') }}"
           data-prijs="{{ $car->prijs ?? 0 }}"
           data-bouwjaar="{{ $car->bouwjaar ?? 0 }}"
           data-merk="{{ strtolower($car->merk ?? '') }}"
           data-model="{{ strtolower($car->model ?? '') }}"
           data-type="{{ strtolower($car->type ?? '') }}"
           data-sale="{{ $hasDiscount ? '1' : '0' }}">

          <div class="px-card-photo">
            <img loading="lazy"
              src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}"
              alt="{{ $merkModel }}">
            @if($sold)
              <span class="px-card-badge px-card-badge-sold">Verkocht</span>
            @elseif($hasDiscount)
              <span class="px-card-badge px-card-badge-sale">Aanbieding</span>
            @endif
          </div>

          <div class="px-card-body">
            <h3 class="px-card-title">{{ $merkModel }}</h3>
            @if(!empty($car->type))
              <div class="px-card-type">{{ $car->type }}</div>
            @endif

            <ul class="px-card-meta">
              <li>{{ $car->bouwjaar ?? '·' }}</li>
              <li>{{ number_format($car->tellerstand ?? 0, 0, ',', '.') }} km</li>
              <li>{{ ucfirst($car->brandstof ?? '·') }}</li>
              @if(!empty($car->transmissie))
                <li>{{ ucfirst($car->transmissie) }}</li>
              @endif
            </ul>

            <div class="px-card-foot">
              @if($hasDiscount)
                <div class="px-card-prices">
                  <span class="px-card-old">€ {{ number_format($car->oude_prijs, 0, ',', '.') }}</span>
                  <span class="px-card-price px-card-price-sale">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }}</span>
                </div>
              @else
                <div class="px-card-price">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }}</div>
              @endif
              <span class="px-card-arrow" aria-hidden="true">→</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="px-grid-empty" id="pxGridEmpty" hidden>
      <p>Geen auto's binnen deze filters.</p>
      <button type="button" class="px-btn px-btn-ghost" id="pxClearFilters">Filters wissen</button>
    </div>

    @if($availableCount > 6)
      <div class="px-grid-more">
        <a href="{{ route('occasions.index') }}" class="px-btn px-btn-ghost px-btn-lg">
          Bekijk alle {{ $availableCount }} occasions →
        </a>
      </div>
    @endif
  </div>
</section>

@php
  // Spotlight: eerste niet-verkochte auto met foto (geen prijs-cap)
  $spotlight = $nieuw->first(function($c){
      $sold = stripos($c->model ?? '', '(VERKOCHT)') !== false;
      return !$sold && !empty($c->hoofdfoto_path);
  });
@endphp

@if($spotlight)
{{-- ============ 3 · SPOTLIGHT OCCASION ============ --}}
<section class="px-section">
  <div class="px-container">
    @php
      $smm = trim(($spotlight->merk ?? '').' '.($spotlight->model ?? ''));
      if ($smm === '' && !empty($spotlight->titel)) $smm = $spotlight->titel;
      $sDiscount = !empty($spotlight->oude_prijs) && $spotlight->oude_prijs > $spotlight->prijs;
    @endphp

    <a href="{{ route('occasions.show', $spotlight->slug) }}" class="px-spotlight px-reveal">
      <div class="px-spotlight-image">
        <img class="px-parallax-img" src="{{ asset('storage/'.$spotlight->hoofdfoto_path) }}" alt="{{ $smm }}">
        <span class="px-spotlight-tag">In de spotlight</span>
      </div>
      <div class="px-spotlight-body">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Uitgelicht</div>
        <h2 class="px-spotlight-title">{{ $smm }}</h2>
        @if(!empty($spotlight->type))
          <div class="px-spotlight-type">{{ $spotlight->type }}</div>
        @endif

        <ul class="px-spotlight-meta">
          <li>
            <span class="px-meta-label">Bouwjaar</span>
            <span class="px-meta-value">{{ $spotlight->bouwjaar ?? '·' }}</span>
          </li>
          <li>
            <span class="px-meta-label">Tellerstand</span>
            <span class="px-meta-value">{{ number_format($spotlight->tellerstand ?? 0, 0, ',', '.') }} km</span>
          </li>
          <li>
            <span class="px-meta-label">Brandstof</span>
            <span class="px-meta-value">{{ ucfirst($spotlight->brandstof ?? '·') }}</span>
          </li>
          @if(!empty($spotlight->transmissie))
          <li>
            <span class="px-meta-label">Transmissie</span>
            <span class="px-meta-value">{{ ucfirst($spotlight->transmissie) }}</span>
          </li>
          @endif
        </ul>

        <div class="px-spotlight-foot">
          @if($sDiscount)
            <div>
              <span class="px-spotlight-old">€ {{ number_format($spotlight->oude_prijs, 0, ',', '.') }}</span>
              <span class="px-spotlight-price px-accent">€ {{ number_format($spotlight->prijs ?? 0, 0, ',', '.') }}</span>
            </div>
          @else
            <span class="px-spotlight-price">€ {{ number_format($spotlight->prijs ?? 0, 0, ',', '.') }}</span>
          @endif
          <span class="px-btn px-btn-primary" data-magnetic>Bekijk auto</span>
        </div>
      </div>
    </a>
  </div>
</section>
@endif

{{-- ============ 4 · WAAROM GERRITSEN ============ --}}
<section class="px-section px-section-alt" id="waarom">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Waarom Gerritsen</div>
      <h2 class="px-h2">Geen verrassingen. Alleen vertrouwen.</h2>
    </div>

    <div class="px-why-grid">
      <div class="px-why-card">
        <div class="px-why-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3>BOVAG-zekerheid</h3>
        <p>Volledige BOVAG-garantie, NAP-controle en eerlijke historie.</p>
      </div>
      <div class="px-why-card">
        <div class="px-why-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-6 0v4"/><rect x="3" y="9" width="18" height="12" rx="2"/></svg>
        </div>
        <h3>Eigen werkplaats</h3>
        <p>Onderhoud, APK en reparaties direct bij ons. Geen tussenpartijen.</p>
      </div>
      <div class="px-why-card">
        <div class="px-why-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </div>
        <h3>Persoonlijk advies</h3>
        <p>Geen verkooppraatjes. We luisteren en denken met je mee.</p>
      </div>
    </div>
  </div>
</section>

{{-- ============ 5 · REVIEWS ============ --}}
<section class="px-section" id="reviews">
  <div class="px-container">
    <div class="px-section-head px-section-head-row">
      <div>
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Reviews</div>
        <h2 class="px-h2">Wat klanten over ons zeggen.</h2>
      </div>
      <div class="px-reviews-head">
        <div class="px-stars" aria-label="4.9 sterren">
          @for($i=0;$i<5;$i++)<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
        </div>
        <span class="px-reviews-score"><b><span class="px-counter" data-target="4.9" data-decimals="1">0,0</span></b> gemiddeld · gebaseerd op Google reviews</span>
      </div>
    </div>

    <div class="px-reviews-track" id="pxReviews">
      @php
        $reviews = [
          ['n' => 'Mark',   'l' => 'Arnhem', 'r' => 5, 't' => 'Snelle service, eerlijke prijs en goede communicatie. Auto reed direct lekker en de afhandeling was strak. Aanrader.'],
          ['n' => 'Linda',  'l' => 'Velp',   'r' => 5, 't' => 'Persoonlijk advies zonder gedoe. Geen verkooppraatjes, gewoon eerlijk. Vond een goede tweedehands binnen mijn budget.'],
          ['n' => 'Jeroen', 'l' => 'Duiven', 'r' => 5, 't' => 'Werkplaats top. APK en kleine reparatie binnen één dag, prijs klopte met de afspraak. Niets meer, niets minder.'],
        ];
      @endphp
      @foreach($reviews as $rv)
        <article class="px-review">
          <div class="px-stars" aria-label="{{ $rv['r'] }} sterren">
            @for($i=0;$i<$rv['r'];$i++)<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
          </div>
          <p class="px-review-text">"{{ $rv['t'] }}"</p>
          <div class="px-review-author">
            <span class="px-review-avatar">{{ substr($rv['n'], 0, 1) }}</span>
            <div>
              <div class="px-review-name">{{ $rv['n'] }}</div>
              <div class="px-review-loc">{{ $rv['l'] }}</div>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

{{-- ============ 6 · AUTO-ZOEKER ============ --}}
<section class="px-section px-section-alt" id="finder">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Auto-zoeker</div>
      <h2 class="px-h2">Niet zoeken. Vinden.</h2>
      <p class="px-section-sub">Beantwoord 4 korte vragen. Wij matchen je met de meest geschikte occasions uit ons aanbod.</p>
    </div>

    <div class="px-finder">
      <div class="px-finder-progress">
        <div class="px-finder-progress-bar" id="pxFinderBar"></div>
        <div class="px-finder-steps">
          <span class="active" data-s="1">Budget</span>
          <span data-s="2">Brandstof</span>
          <span data-s="3">Schakeling</span>
          <span data-s="4">Type</span>
          <span data-s="5">Bouwjaar</span>
          <span data-s="6">Resultaat</span>
        </div>
      </div>

      <div class="px-finder-step px-active" data-step="1">
        <h3 class="px-step-q">Wat is je budget?</h3>
        <div class="px-budget">
          <div class="px-budget-display">
            <div class="px-budget-pair">
              <span class="px-budget-prefix">Vanaf</span>
              <span class="px-budget-value">€ <span id="pxBudgetMinVal">1.500</span></span>
            </div>
            <span class="px-budget-arrow">→</span>
            <div class="px-budget-pair">
              <span class="px-budget-prefix">Tot</span>
              <span class="px-budget-value">€ <span id="pxBudgetMaxVal">3.000</span></span>
            </div>
          </div>
          <div class="px-range-dual">
            <div class="px-range-bg"></div>
            <div class="px-range-fill" id="pxRangeFill"></div>
            <input type="range" id="pxBudgetMin" min="500" max="7500" step="100" value="1500" aria-label="Minimum budget">
            <input type="range" id="pxBudgetMax" min="500" max="7500" step="100" value="3000" aria-label="Maximum budget">
          </div>
          <div class="px-range-ticks">
            <span>€ 500</span>
            <span>€ 7.500</span>
          </div>
          <div class="px-budget-presets">
            <button type="button" data-min="500"  data-max="1500">Tot € 1.500</button>
            <button type="button" data-min="1500" data-max="2500">€ 1.500 – 2.500</button>
            <button type="button" data-min="2500" data-max="4000">€ 2.500 – 4.000</button>
            <button type="button" data-min="4000" data-max="7500">€ 4.000+</button>
          </div>
        </div>
        <div class="px-finder-actions"><button type="button" class="px-btn px-btn-primary" data-next>Volgende →</button></div>
      </div>

      <div class="px-finder-step" data-step="2">
        <h3 class="px-step-q">Welke brandstof?</h3>
        <div class="px-options">
          <button type="button" class="px-opt" data-key="brandstof" data-val="">Maakt niet uit</button>
          <button type="button" class="px-opt" data-key="brandstof" data-val="Benzine">Benzine</button>
          <button type="button" class="px-opt" data-key="brandstof" data-val="Diesel">Diesel</button>
          <button type="button" class="px-opt" data-key="brandstof" data-val="Hybride">Hybride</button>
          <button type="button" class="px-opt" data-key="brandstof" data-val="Elektrisch">Elektrisch</button>
        </div>
        <div class="px-finder-actions">
          <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
          <button type="button" class="px-btn px-btn-primary" data-next>Volgende →</button>
        </div>
      </div>

      <div class="px-finder-step" data-step="3">
        <h3 class="px-step-q">Handgeschakeld of automaat?</h3>
        <div class="px-options">
          <button type="button" class="px-opt" data-key="transmissie" data-val="">Maakt niet uit</button>
          <button type="button" class="px-opt" data-key="transmissie" data-val="hand">Handgeschakeld</button>
          <button type="button" class="px-opt" data-key="transmissie" data-val="auto">Automaat</button>
        </div>
        <div class="px-finder-actions">
          <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
          <button type="button" class="px-btn px-btn-primary" data-next>Volgende →</button>
        </div>
      </div>

      <div class="px-finder-step" data-step="4">
        <h3 class="px-step-q">Welk type auto?</h3>
        <div class="px-options">
          <button type="button" class="px-opt" data-key="type" data-val="">Maakt niet uit</button>
          <button type="button" class="px-opt" data-key="type" data-val="hatchback">Hatchback</button>
          <button type="button" class="px-opt" data-key="type" data-val="suv">SUV</button>
          <button type="button" class="px-opt" data-key="type" data-val="stationwagon">Stationwagon</button>
          <button type="button" class="px-opt" data-key="type" data-val="sedan">Sedan</button>
          <button type="button" class="px-opt" data-key="type" data-val="mpv">MPV</button>
        </div>
        <div class="px-finder-actions">
          <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
          <button type="button" class="px-btn px-btn-primary" data-next>Volgende →</button>
        </div>
      </div>

      <div class="px-finder-step" data-step="5">
        <h3 class="px-step-q">Hoe nieuw moet de auto minimaal zijn?</h3>
        <div class="px-options">
          <button type="button" class="px-opt" data-key="minYear" data-val="0">Maakt niet uit</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2005">Vanaf 2005</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2008">Vanaf 2008</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2010">Vanaf 2010</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2012">Vanaf 2012</button>
        </div>
        <div class="px-finder-actions">
          <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
          <button type="button" class="px-btn px-btn-primary" id="pxFinderGo">Toon resultaat</button>
        </div>
      </div>

      <div class="px-finder-step px-finder-result" data-step="6">
        <div class="px-result-head">
          <div>
            <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Top match</div>
            <h3 class="px-result-title" id="pxResultTitle">We vonden 0 occasions</h3>
          </div>
          <button type="button" class="px-btn px-btn-ghost px-btn-sm" id="pxFinderRestart">Opnieuw zoeken</button>
        </div>
        <div class="px-result-grid" id="pxResultGrid"></div>
        <div class="px-result-empty" id="pxResultEmpty" hidden>
          <p>Geen perfecte match. <a href="#contact">Neem contact op</a>, we helpen je persoonlijk verder.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ============ 7 · WERKPLAATS — SMART BOOKING ============ --}}
<section class="px-werkplaats-smart" id="werkplaats">
  <div class="px-werkplaats-bg" style="background-image: url('{{ setting_image('werkplaats.image') }}');"></div>
  <div class="px-werkplaats-bg-overlay"></div>

  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>{{ setting('werkplaats.eyebrow') }}</div>
      <h2 class="px-h2">{{ setting('werkplaats.title') }}<br>{{ setting('werkplaats.title2') }}</h2>
    </div>

    @include('preview.partials.workshop-booking')
  </div>
</section>

{{-- ============ 8 · DIENSTEN (leenauto featured + 3 service-cards) ============ --}}
@php
  $leenautoUsps = collect(explode("\n", setting('leenauto.usps') ?? ''))->map(fn ($l) => trim($l))->filter()->values();
  $leenautoThumbs = collect(['leenauto.image_2','leenauto.image_3','leenauto.image_4','leenauto.image_5'])
      ->map(fn ($k) => setting_image($k))
      ->filter()
      ->values();
@endphp

<section class="px-section px-section-alt" id="diensten">
  <div class="px-container">

    <div class="px-section-head px-section-head-row">
      <div>
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Diensten</div>
        <h2 class="px-h2">Méér dan alleen verkoop.</h2>
      </div>
      <p class="px-section-sub px-section-sub-right">Naast verkoop en werkplaats verhuren we praktische spullen voor thuis en rondom de auto. Direct online te reserveren.</p>
    </div>

    {{-- Featured: leenauto --}}
    <div class="px-leenauto px-reveal">

      <div class="px-leenauto-photos">
        <div class="px-leenauto-main">
          <img id="pxLeenautoMain" src="{{ setting_image('leenauto.image_main') }}" alt="{{ setting('leenauto.title') }}">
          <span class="px-leenauto-price">{{ setting('leenauto.price') }}</span>
        </div>
        @if($leenautoThumbs->isNotEmpty())
          <div class="px-leenauto-thumbs" id="pxLeenautoThumbs">
            <button type="button" class="px-leenauto-thumb active" data-src="{{ setting_image('leenauto.image_main') }}">
              <img src="{{ setting_image('leenauto.image_main') }}" alt="">
            </button>
            @foreach($leenautoThumbs as $thumb)
              <button type="button" class="px-leenauto-thumb" data-src="{{ $thumb }}">
                <img src="{{ $thumb }}" alt="">
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <div class="px-leenauto-content">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>{{ setting('leenauto.eyebrow') }}</div>
        <h2 class="px-h2">{{ setting('leenauto.title') }}</h2>
        <p class="px-leenauto-sub">{{ setting('leenauto.subtitle') }}</p>

        <div class="px-leenauto-block">
          <span class="px-leenauto-block-title">Uitrusting &amp; comfort</span>
          <ul class="px-leenauto-usps">
            @foreach($leenautoUsps as $usp)
              <li>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg>
                {{ $usp }}
              </li>
            @endforeach
          </ul>
        </div>

        <p class="px-leenauto-loc">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ setting('leenauto.location') }}
        </p>

        <div class="px-leenauto-cta">
          <a href="{{ route('booking.show', ['type' => 'leenauto']) }}" class="px-btn px-btn-primary px-btn-lg" data-magnetic>{{ setting('leenauto.cta_primary') }}</a>
          <a href="tel:{{ setting_tel('contact.phone_workshop') }}" class="px-btn px-btn-ghost px-btn-lg">{{ setting('leenauto.cta_secondary') }}</a>
        </div>
      </div>

    </div>

    {{-- Subhead voor de andere 3 verhuurdiensten --}}
    <div class="px-services-divider">
      <span class="px-services-divider-label">Ook beschikbaar</span>
    </div>

    <div class="px-services-grid">
      <a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="px-service-card">
        <div class="px-service-photo">
          <img loading="lazy" src="{{ asset('images/cargo-trailers-passenger-car-parked-spacious-lot.jpg') }}" alt="Aanhanger huren">
          <span class="px-service-tag">Verhuur</span>
        </div>
        <div class="px-service-body">
          <h3 class="px-service-name">Aanhanger</h3>
          <p class="px-service-desc">Veilig, schoon en direct beschikbaar. 130 × 250 cm laadbak.</p>
          <div class="px-service-foot">
            <div class="px-service-prijs">
              <span class="px-service-prijs-label">vanaf</span>
              <span class="px-service-prijs-amt">€ 15</span>
              <span class="px-service-prijs-meta">/ 4 uur</span>
            </div>
            <span class="px-service-arrow">→</span>
          </div>
        </div>
      </a>

      <a href="{{ route('booking.show', ['type' => 'stofzuiger']) }}" class="px-service-card">
        <div class="px-service-photo">
          <img loading="lazy" src="{{ asset('images/1200x810.jpg') }}" alt="Tapijtreiniger huren">
          <span class="px-service-tag">Verhuur</span>
        </div>
        <div class="px-service-body">
          <h3 class="px-service-name">Numatic George</h3>
          <p class="px-service-desc">Krachtige tapijtreiniger voor meubels, vloerkleden en interieur.</p>
          <div class="px-service-foot">
            <div class="px-service-prijs">
              <span class="px-service-prijs-label">vanaf</span>
              <span class="px-service-prijs-amt">€ 25</span>
              <span class="px-service-prijs-meta">/ dag</span>
            </div>
            <span class="px-service-arrow">→</span>
          </div>
        </div>
      </a>

      <a href="{{ route('booking.show', ['type' => 'koplampen']) }}" class="px-service-card">
        <div class="px-service-photo">
          <img loading="lazy" src="{{ asset('images/head-lights-car.jpg') }}" alt="Koplampen polijsten">
          <span class="px-service-tag">Service</span>
        </div>
        <div class="px-service-body">
          <h3 class="px-service-name">Koplampen polijsten</h3>
          <p class="px-service-desc">Doffe of vergeelde koplampen weer helder. Resultaat binnen één behandeling.</p>
          <div class="px-service-foot">
            <div class="px-service-prijs">
              <span class="px-service-prijs-label">op afspraak</span>
            </div>
            <span class="px-service-arrow">→</span>
          </div>
        </div>
      </a>

      <a href="{{ route('werkplaats') }}" class="px-service-card">
        <div class="px-service-photo">
          <img loading="lazy" src="{{ asset('images/afspraak-banner.jpg') }}" alt="Airco service">
          <span class="px-service-tag">Service</span>
        </div>
        <div class="px-service-body">
          <h3 class="px-service-name">Airco service</h3>
          <p class="px-service-desc">Airco bijvullen, controleren of een complete onderhoudsbeurt. Weer fris en koel onderweg.</p>
          <div class="px-service-foot">
            <div class="px-service-prijs">
              <span class="px-service-prijs-label">op afspraak</span>
            </div>
            <span class="px-service-arrow">→</span>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>

{{-- ============ 9 · OVER ONS ============ --}}
<section class="px-section px-section-alt" id="over">
  <div class="px-container">
    <div class="px-over">
      <div class="px-over-content">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>{{ setting('over.eyebrow') }}</div>
        <h2 class="px-h2">{{ setting('over.title') }}</h2>

        <p>{{ setting('over.body_p1') }}</p>
        <p>{{ setting('over.body_p2') }}</p>

        <div class="px-people">
          <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-person">
            <div class="px-person-avatar">{{ Str::upper(Str::substr(setting('over.person1_name'), 0, 1)) }}</div>
            <div class="px-person-body">
              <div class="px-person-role">{{ setting('over.person1_role') }}</div>
              <div class="px-person-name">{{ setting('over.person1_name') }}</div>
              <div class="px-person-phone">{{ setting('contact.phone_sales') }}</div>
            </div>
          </a>
          <a href="tel:{{ setting_tel('contact.phone_workshop') }}" class="px-person">
            <div class="px-person-avatar">{{ Str::upper(Str::substr(setting('over.person2_name'), 0, 1)) }}</div>
            <div class="px-person-body">
              <div class="px-person-role">{{ setting('over.person2_role') }}</div>
              <div class="px-person-name">{{ setting('over.person2_name') }}</div>
              <div class="px-person-phone">{{ setting('contact.phone_workshop') }}</div>
            </div>
          </a>
        </div>

        <div class="px-over-meta">
          <div class="px-over-meta-item">
            <span class="px-over-meta-label">Adres</span>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(setting('contact.address')) }}" target="_blank" rel="noopener" class="px-over-meta-value">
              {{ setting('contact.address') }}
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7M17 7H7M17 7v10"/></svg>
            </a>
          </div>
          <div class="px-over-meta-item">
            <span class="px-over-meta-label">Openingstijden</span>
            <span class="px-over-meta-value">{{ setting('contact.hours_weekday') }} · {{ setting('contact.hours_saturday') }} · {{ setting('contact.hours_sunday') }}</span>
          </div>
        </div>
      </div>

      <div class="px-over-image">
        <img class="px-parallax-img" src="{{ setting_image('over.image') }}" alt="Gerritsen Automotive in Arnhem">
      </div>
    </div>
  </div>
</section>

{{-- ============ FOOTER (gedeelde partial) ============ --}}
@include('preview.partials.footer')

<script id="pxOccasionData" type="application/json">
@php
  $forFinder = $nieuw->map(function($car){
      $type = strtolower($car->type ?? '');
      $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));
      if ($merkModel === '' && !empty($car->titel)) $merkModel = $car->titel;
      $sold = stripos($car->model ?? '', '(VERKOCHT)') !== false;
      return [
          'slug' => $car->slug,
          'title' => $merkModel,
          'type' => $type,
          'merk' => strtolower($car->merk ?? ''),
          'brandstof' => $car->brandstof ?? '',
          'transmissie' => $car->transmissie ?? '',
          'bouwjaar' => (int)($car->bouwjaar ?? 0),
          'prijs' => (int)($car->prijs ?? 0),
          'oude_prijs' => (int)($car->oude_prijs ?? 0),
          'km' => (int)($car->tellerstand ?? 0),
          'foto' => $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg'),
          'sold' => $sold,
          'url' => route('occasions.show', $car->slug),
      ];
  })->values();
@endphp
{!! $forFinder->toJson() !!}
</script>

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
