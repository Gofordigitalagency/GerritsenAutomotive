<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('binnenkort_page.title') }} — Gerritsen Automotive</title>

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
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('binnenkort_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('binnenkort_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('binnenkort_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('binnenkort_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ GRID ============ --}}
<section class="px-section">
  <div class="px-container">

    @if($nieuw->isEmpty())
      <div class="px-grid-empty px-grid-empty-large">
        <div class="px-empty-icon">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        </div>
        <h3>{{ setting('binnenkort_page.empty_title') }}</h3>
        <p>{{ setting('binnenkort_page.empty_sub') }}</p>
        <a href="{{ route('aanbod') }}" class="px-btn px-btn-ghost">Bekijk huidig aanbod →</a>
      </div>
    @else
      <div class="px-grid">
        @foreach($nieuw as $car)
          @php
            $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));
            if ($merkModel === '' && !empty($car->titel)) $merkModel = $car->titel;
          @endphp
          <div class="px-card">
            <div class="px-card-photo">
              <img loading="lazy"
                   src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}"
                   alt="{{ $merkModel }}">
              <span class="px-card-badge px-card-badge-soon">Binnenkort</span>
            </div>

            <div class="px-card-body">
              <h3 class="px-card-title">{{ $merkModel }}</h3>
              @if(!empty($car->type))
                <div class="px-card-type">{{ $car->type }}</div>
              @endif

              <ul class="px-card-meta">
                @if(!empty($car->bouwjaar))<li>{{ $car->bouwjaar }}</li>@endif
                @if(!empty($car->tellerstand))<li>{{ number_format($car->tellerstand, 0, ',', '.') }} km</li>@endif
                @if(!empty($car->brandstof))<li>{{ ucfirst($car->brandstof) }}</li>@endif
                @if(!empty($car->transmissie))<li>{{ ucfirst($car->transmissie) }}</li>@endif
              </ul>

              <div class="px-card-foot">
                @if(!empty($car->prijs))
                  <div class="px-card-price">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }}</div>
                @else
                  <div class="px-card-price-soon">Prijs op aanvraag</div>
                @endif
                <a href="{{ route('contact') }}" class="px-card-arrow" aria-label="Eerste optie aanvragen">→</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
