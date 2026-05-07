<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gerritsen Automotive — Preview</title>

    <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/preview.css') }}?v={{ filemtime(public_path('css/preview.css')) }}">
</head>
<body class="px-body">

{{-- ============ NAV ============ --}}
<header class="px-nav" id="pxNav">
  <div class="px-nav-inner">
    <a href="/" class="px-logo" aria-label="Gerritsen Automotive">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
    </a>

    <nav class="px-nav-links" aria-label="Hoofdmenu">
      <a href="#aanbod" class="px-link">Aanbod</a>
      <a href="#waarom" class="px-link">Waarom</a>
      <a href="#finder" class="px-link">Auto-zoeker</a>
      <a href="#werkplaats" class="px-link">Werkplaats</a>
      <a href="#contact" class="px-link">Contact</a>
    </nav>

    <div class="px-nav-actions">
      <a href="tel:+31638257987" class="px-btn px-btn-ghost px-phone-pill">06 38 25 79 87</a>
      <a href="#aanbod" class="px-btn px-btn-primary" data-magnetic>Bekijk aanbod</a>

      <button class="px-menu-btn" id="pxMenuBtn" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <div class="px-mobile-menu" id="pxMobileMenu" aria-hidden="true">
    <a href="#aanbod">Aanbod</a>
    <a href="#waarom">Waarom Gerritsen</a>
    <a href="#finder">Auto-zoeker</a>
    <a href="#werkplaats">Werkplaats</a>
    <a href="#contact">Contact</a>
    <a href="tel:+31638257987" class="px-mobile-call">Bel ons — 06 38 25 79 87</a>
  </div>
</header>

{{-- ============ 1 · HERO ============ --}}
<section class="px-hero">
  <div class="px-hero-bg" style="background-image: url('{{ asset('images/backgroundhome.jpg') }}');"></div>
  <div class="px-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-hero-inner">
    <div class="px-hero-content">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>Gerritsen Automotive · Arnhem</div>
      <h1 class="px-hero-title px-reveal" style="--rd: .1s">
        Uw partner in<br>
        <span class="px-accent-soft">betrouwbare</span> occasions.
      </h1>
      <p class="px-hero-sub px-reveal" style="--rd: .2s">
        Zorgvuldig geselecteerde auto's, eerlijk advies en een eigen werkplaats.
        Alles onder één dak in Arnhem.
      </p>
      <div class="px-hero-cta px-reveal" style="--rd: .3s">
        <a href="#aanbod" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Bekijk {{ count($nieuw) }} occasions</a>
        <a href="#contact" class="px-btn px-btn-ghost px-btn-lg">Contact</a>
      </div>

      <div class="px-trust-row px-reveal" style="--rd: .4s">
        <div class="px-trust-item">
          <div class="px-stars" aria-label="4.9 sterren">
            @for($i=0;$i<5;$i++)<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
          </div>
          <span><b>4.9</b> · Google reviews</span>
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
      <button type="button" class="px-chip px-chip-active" data-filter="all">Alle <span>{{ count($nieuw) }}</span></button>
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
              <li>{{ $car->bouwjaar ?? '—' }}</li>
              <li>{{ number_format($car->tellerstand ?? 0, 0, ',', '.') }} km</li>
              <li>{{ ucfirst($car->brandstof ?? '—') }}</li>
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

    @if(count($nieuw) > 6)
      <div class="px-grid-more">
        <a href="{{ route('occasions.index') }}" class="px-btn px-btn-ghost px-btn-lg">
          Bekijk alle {{ count($nieuw) }} occasions →
        </a>
      </div>
    @endif
  </div>
</section>

@php
  $spotlight = $nieuw->first(function($c){
      $sold = stripos($c->model ?? '', '(VERKOCHT)') !== false;
      return !$sold && !empty($c->hoofdfoto_path) && (int)($c->prijs ?? 0) <= 15000;
  }) ?? $nieuw->first(function($c){
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
        <img src="{{ asset('storage/'.$spotlight->hoofdfoto_path) }}" alt="{{ $smm }}">
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
            <span class="px-meta-value">{{ $spotlight->bouwjaar ?? '—' }}</span>
          </li>
          <li>
            <span class="px-meta-label">Tellerstand</span>
            <span class="px-meta-value">{{ number_format($spotlight->tellerstand ?? 0, 0, ',', '.') }} km</span>
          </li>
          <li>
            <span class="px-meta-label">Brandstof</span>
            <span class="px-meta-value">{{ ucfirst($spotlight->brandstof ?? '—') }}</span>
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
    <div class="px-section-head">
      <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Reviews</div>
      <h2 class="px-h2">Wat klanten over ons zeggen.</h2>
      <div class="px-reviews-head">
        <div class="px-stars" aria-label="4.9 sterren">
          @for($i=0;$i<5;$i++)<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
        </div>
        <span class="px-reviews-score"><b>4.9</b> gemiddeld · gebaseerd op Google reviews</span>
      </div>
    </div>

    <div class="px-reviews-track" id="pxReviews">
      @php
        $reviews = [
          ['n' => 'Mark',   'l' => 'Arnhem', 'r' => 5, 't' => 'Snelle service, eerlijke prijs en goede communicatie. Auto reed direct lekker en de afhandeling was strak. Aanrader.'],
          ['n' => 'Linda',  'l' => 'Velp',   'r' => 5, 't' => 'Persoonlijk advies zonder gedoe. Geen verkooppraatjes, gewoon eerlijk. Vond een goede tweedehands binnen mijn budget.'],
          ['n' => 'Jeroen', 'l' => 'Duiven', 'r' => 5, 't' => 'Werkplaats top — APK en kleine reparatie binnen één dag, prijs klopte met de afspraak. Niets meer, niets minder.'],
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
      <p class="px-section-sub">Beantwoord 4 korte vragen — wij matchen je met de meest geschikte occasions uit ons aanbod.</p>
    </div>

    <div class="px-finder">
      <div class="px-finder-progress">
        <div class="px-finder-progress-bar" id="pxFinderBar"></div>
        <div class="px-finder-steps">
          <span class="active" data-s="1">Budget</span>
          <span data-s="2">Brandstof</span>
          <span data-s="3">Type</span>
          <span data-s="4">Bouwjaar</span>
          <span data-s="5">Resultaat</span>
        </div>
      </div>

      <div class="px-finder-step px-active" data-step="1">
        <h3 class="px-step-q">Wat is je budget?</h3>
        <div class="px-budget">
          <div class="px-budget-display">
            <div class="px-budget-pair">
              <span class="px-budget-prefix">Vanaf</span>
              <span class="px-budget-value">€ <span id="pxBudgetMinVal">5.000</span></span>
            </div>
            <span class="px-budget-arrow">→</span>
            <div class="px-budget-pair">
              <span class="px-budget-prefix">Tot</span>
              <span class="px-budget-value">€ <span id="pxBudgetMaxVal">15.000</span></span>
            </div>
          </div>
          <div class="px-range-dual">
            <div class="px-range-bg"></div>
            <div class="px-range-fill" id="pxRangeFill"></div>
            <input type="range" id="pxBudgetMin" min="0" max="30000" step="250" value="5000" aria-label="Minimum budget">
            <input type="range" id="pxBudgetMax" min="0" max="30000" step="250" value="15000" aria-label="Maximum budget">
          </div>
          <div class="px-range-ticks">
            <span>€ 0</span>
            <span>€ 30.000</span>
          </div>
          <div class="px-budget-presets">
            <button type="button" data-min="0"     data-max="5000">Tot € 5k</button>
            <button type="button" data-min="5000"  data-max="10000">€ 5–10k</button>
            <button type="button" data-min="10000" data-max="15000">€ 10–15k</button>
            <button type="button" data-min="15000" data-max="30000">€ 15k+</button>
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

      <div class="px-finder-step" data-step="4">
        <h3 class="px-step-q">Hoe nieuw moet de auto minimaal zijn?</h3>
        <div class="px-options">
          <button type="button" class="px-opt" data-key="minYear" data-val="0">Maakt niet uit</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2010">Vanaf 2010</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2015">Vanaf 2015</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2018">Vanaf 2018</button>
          <button type="button" class="px-opt" data-key="minYear" data-val="2021">Vanaf 2021</button>
        </div>
        <div class="px-finder-actions">
          <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
          <button type="button" class="px-btn px-btn-primary" id="pxFinderGo">Toon resultaat</button>
        </div>
      </div>

      <div class="px-finder-step px-finder-result" data-step="5">
        <div class="px-result-head">
          <div>
            <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Top match</div>
            <h3 class="px-result-title" id="pxResultTitle">We vonden 0 occasions</h3>
          </div>
          <button type="button" class="px-btn px-btn-ghost px-btn-sm" id="pxFinderRestart">Opnieuw zoeken</button>
        </div>
        <div class="px-result-grid" id="pxResultGrid"></div>
        <div class="px-result-empty" id="pxResultEmpty" hidden>
          <p>Geen perfecte match. <a href="#contact">Neem contact op</a> — we helpen je persoonlijk verder.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ============ 7 · WERKPLAATS CTA ============ --}}
<section class="px-werkplaats" id="werkplaats" style="--bg: url('{{ asset('images/afspraak-banner.jpg') }}');">
  <div class="px-werkplaats-overlay"></div>
  <div class="px-container">
    <div class="px-werkplaats-content">
      <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Werkplaats</div>
      <h2 class="px-h2">APK, onderhoud of reparatie?<br>In 4 stappen ingepland.</h2>
      <p>Vul je kenteken in, kies de werkzaamheden en je tijdstip. We bevestigen direct.</p>
      <div class="px-werkplaats-cta">
        <a href="{{ route('workshop.step1') }}" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Plan een afspraak</a>
        <a href="tel:+31638257987" class="px-btn px-btn-ghost px-btn-lg">Of bel direct</a>
      </div>
    </div>
  </div>
</section>

{{-- ============ 8 · OVER ONS (compact strip) ============ --}}
<section class="px-section px-section-alt" id="over">
  <div class="px-container">
    <div class="px-over-strip">
      <div class="px-over-strip-img">
        <img src="{{ asset('images/handshake.jpg') }}" alt="Gerritsen Automotive">
      </div>
      <div class="px-over-strip-body">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Over ons</div>
        <h3 class="px-over-strip-title">Gerritsen Automotive — Arnhem</h3>
        <p>Persoonlijk, helder en zonder gedoe. Auto's, werkplaats en verhuur — onder één dak.</p>
      </div>
      <a href="tel:+31638257987" class="px-btn px-btn-ghost">Neem contact op</a>
    </div>
  </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="px-footer" id="contact">
  <div class="px-container">
    <div class="px-footer-grid">
      <div>
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive" class="px-footer-logo">
        <p class="px-footer-about">Jouw vertrouwde adres in Arnhem voor occasions, werkplaats en verhuur.</p>
      </div>

      <div>
        <h4>Contact</h4>
        <ul class="px-footer-list">
          <li><a href="tel:+31638257987">06 38 25 79 87</a></li>
          <li><a href="mailto:info@gerritsenautomotive.nl">info@gerritsenautomotive.nl</a></li>
          <li>Arnhem, Nederland</li>
        </ul>
      </div>

      <div>
        <h4>Navigeer</h4>
        <ul class="px-footer-list">
          <li><a href="#aanbod">Aanbod</a></li>
          <li><a href="#waarom">Waarom Gerritsen</a></li>
          <li><a href="#finder">Auto-zoeker</a></li>
          <li><a href="#werkplaats">Werkplaats</a></li>
        </ul>
      </div>

      <div>
        <h4>Diensten</h4>
        <ul class="px-footer-list">
          <li><a href="{{ route('booking.show', ['type' => 'aanhanger']) }}">Aanhanger huren</a></li>
          <li><a href="{{ route('booking.show', ['type' => 'stofzuiger']) }}">Stofzuiger</a></li>
          <li><a href="{{ route('booking.show', ['type' => 'koplampen']) }}">Koplampen polish</a></li>
          <li><a href="{{ route('occasions.binnenkort') }}">Binnenkort</a></li>
        </ul>
      </div>
    </div>

    <div class="px-footer-bottom">
      <span>© {{ date('Y') }} Gerritsen Automotive.</span>
      <span class="px-footer-preview-tag">Preview · Concept</span>
    </div>
  </div>
</footer>

<a href="https://wa.me/31638257987" class="px-whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0 0 20.464 3.488"/></svg>
</a>

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
