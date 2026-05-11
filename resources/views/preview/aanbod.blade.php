<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('aanbod_page.title') }} — Gerritsen Automotive</title>

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

@php
  // Filter-counts voor de chips (server-side berekend op basis van het volledige aanbod)
  $countSale     = $occasions->filter(fn ($c) => !empty($c->oude_prijs) && $c->oude_prijs > $c->prijs)->count();
  $countAuto     = $occasions->filter(fn ($c) => stripos($c->transmissie ?? '', 'auto') !== false)->count();
  $countBenzine  = $occasions->where('brandstof', 'Benzine')->count();
  $countDiesel   = $occasions->where('brandstof', 'Diesel')->count();
  $countTier1    = $occasions->where('prijs', '<=', 1500)->count();
  $countTier2    = $occasions->whereBetween('prijs', [1500, 2500])->count();
  $countTier3    = $occasions->whereBetween('prijs', [2500, 4000])->count();
  $countTier4    = $occasions->where('prijs', '>=', 4000)->count();
@endphp

{{-- ============ PAGE HERO met foto-achtergrond ============ --}}
<section class="px-page-hero px-page-hero-photo">
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('aanbod_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('aanbod_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('aanbod_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('aanbod_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ STICKY TOOLBAR + GRID ============ --}}
<section class="px-section">
  <div class="px-container">

    <div class="px-toolbar-sticky" id="pxToolbar">
      <div class="px-toolbar px-toolbar-spacious">
        <div class="px-search-wrap">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
          <input type="search" id="pxSearch" placeholder="Zoek op merk of model…">
        </div>

        <div class="px-toolbar-right">
          <span class="px-result-count" id="pxResultCount">
            <b id="pxVisibleCount">{{ count($occasions) }}</b> van {{ count($occasions) }}
          </span>
          <form class="px-sort" method="GET" action="{{ route('aanbod') }}">
            <label for="pxSort">Sorteer:</label>
            <select id="pxSort" name="sort" onchange="this.form.submit()">
              <option value="best"      @selected($sort==='best')>Beste resultaten</option>
              <option value="price_asc" @selected($sort==='price_asc')>Prijs oplopend</option>
              <option value="price_desc"@selected($sort==='price_desc')>Prijs aflopend</option>
              <option value="newest"    @selected($sort==='newest')>Nieuwste aanbod</option>
              <option value="km_asc"    @selected($sort==='km_asc')>Km oplopend</option>
              <option value="km_desc"   @selected($sort==='km_desc')>Km aflopend</option>
              <option value="year_asc"  @selected($sort==='year_asc')>Bouwjaar oplopend</option>
              <option value="year_desc" @selected($sort==='year_desc')>Bouwjaar aflopend</option>
            </select>
          </form>
        </div>
      </div>

      <div class="px-chips" id="pxChips">
        @if(($binnenkortCount ?? 0) > 0)
          <a href="{{ route('occasions.binnenkort') }}" class="px-chip px-chip-soon" title="Auto's die binnenkort beschikbaar zijn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            Binnenkort verwacht <span>{{ $binnenkortCount }}</span>
          </a>
        @endif
        <button type="button" class="px-chip px-chip-active" data-filter="all">Alle <span>{{ count($occasions) }}</span></button>
        <button type="button" class="px-chip" data-filter="price:0-1500">Tot € 1.500 <span>{{ $countTier1 }}</span></button>
        <button type="button" class="px-chip" data-filter="price:1500-2500">€ 1.500 – 2.500 <span>{{ $countTier2 }}</span></button>
        <button type="button" class="px-chip" data-filter="price:2500-4000">€ 2.500 – 4.000 <span>{{ $countTier3 }}</span></button>
        <button type="button" class="px-chip" data-filter="price:4000-99999999">€ 4.000+ <span>{{ $countTier4 }}</span></button>
        <button type="button" class="px-chip" data-filter="brandstof:Benzine">Benzine <span>{{ $countBenzine }}</span></button>
        @if($countDiesel > 0)
          <button type="button" class="px-chip" data-filter="brandstof:Diesel">Diesel <span>{{ $countDiesel }}</span></button>
        @endif
        <button type="button" class="px-chip" data-filter="trans:auto">Automaat <span>{{ $countAuto }}</span></button>
        @if($countSale > 0)
          <button type="button" class="px-chip" data-filter="sale">Aanbieding <span>{{ $countSale }}</span></button>
        @endif
      </div>
    </div>

    <div class="px-grid" id="pxGrid">
      @foreach($occasions as $car)
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

    <div class="px-grid-empty px-grid-empty-large" id="pxGridEmpty" hidden>
      <div class="px-empty-icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/><path d="M8 11h6"/></svg>
      </div>
      <h3>{{ setting('aanbod_page.empty_title') }}</h3>
      <p>{{ setting('aanbod_page.empty_sub') }}</p>
      <button type="button" class="px-btn px-btn-ghost" id="pxClearFilters">Filters wissen</button>
    </div>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>

{{-- Live result counter — update zodra chips/search filteren --}}
<script>
  (function () {
    const grid = document.getElementById('pxGrid');
    const counter = document.getElementById('pxVisibleCount');
    if (!grid || !counter) return;
    const update = () => {
      const visible = Array.from(grid.children).filter(c => !c.classList.contains('hidden')).length;
      counter.textContent = visible;
    };
    // Wacht tot de filter-script de classlist daadwerkelijk wijzigt — chips/search
    // dispatchen reeds events, en de IntersectionObserver-toggles ('in') zijn niet
    // relevant voor de teller. Listen op chip/search input ipv MutationObserver
    // zodat scroll-driven class-changes geen reflow-loop triggeren.
    document.querySelectorAll('#pxChips .px-chip, #pxSearch').forEach(el => {
      el.addEventListener('click', () => requestAnimationFrame(update));
      el.addEventListener('input', () => requestAnimationFrame(update));
    });
    update();
  })();
</script>
</body>
</html>
