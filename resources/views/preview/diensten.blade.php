<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('diensten_page.title') }} — Gerritsen Automotive</title>

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
  $leenautoUsps = collect(explode("\n", setting('leenauto.usps') ?? ''))
      ->map(fn ($l) => trim($l))->filter()->values();
  $leenautoThumbs = collect(['leenauto.image_2','leenauto.image_3','leenauto.image_4','leenauto.image_5'])
      ->map(fn ($k) => setting_image($k))->filter()->values();
@endphp

{{-- ============ PAGE HERO met foto-achtergrond ============ --}}
<section class="px-page-hero px-page-hero-photo">
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('diensten_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('diensten_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('diensten_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('diensten_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ LEENAUTO FEATURED ============ --}}
<section class="px-section">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('diensten_page.leenauto_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('diensten_page.leenauto_title') }}</h2>
      <p class="px-section-sub px-reveal" style="--rd: .2s">{{ setting('diensten_page.leenauto_sub') }}</p>
    </div>

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
  </div>
</section>

{{-- ============ VERHUUR + SERVICE ============ --}}
<section class="px-section px-section-alt">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('diensten_page.verhuur_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('diensten_page.verhuur_title') }}</h2>
      <p class="px-section-sub px-reveal" style="--rd: .2s">{{ setting('diensten_page.verhuur_sub') }}</p>
    </div>

    <div class="px-services-grid">
      @php
        $services = [
          ['route' => route('booking.show', ['type' => 'aanhanger']),  'key' => 'svc1'],
          ['route' => route('booking.show', ['type' => 'stofzuiger']), 'key' => 'svc2'],
          ['route' => route('booking.show', ['type' => 'koplampen']),  'key' => 'svc3'],
        ];
      @endphp

      @foreach($services as $i => $svc)
        @php
          $img    = setting_image('diensten_page.'.$svc['key'].'_image');
          $tag    = setting('diensten_page.'.$svc['key'].'_tag');
          $title  = setting('diensten_page.'.$svc['key'].'_title');
          $desc   = setting('diensten_page.'.$svc['key'].'_desc');
          $pLbl   = setting('diensten_page.'.$svc['key'].'_price_lbl');
          $pAmt   = setting('diensten_page.'.$svc['key'].'_price_amt');
          $pMeta  = setting('diensten_page.'.$svc['key'].'_price_meta');
        @endphp
        <a href="{{ $svc['route'] }}" class="px-service-card px-reveal" style="--rd: {{ 0.05 + $i * 0.08 }}s">
          <div class="px-service-photo">
            <img loading="lazy" src="{{ $img }}" alt="{{ $title }}">
            @if(!empty($tag))<span class="px-service-tag">{{ $tag }}</span>@endif
          </div>
          <div class="px-service-body">
            <h3 class="px-service-name">{{ $title }}</h3>
            @if(!empty($desc))<p class="px-service-desc">{{ $desc }}</p>@endif
            <div class="px-service-foot">
              <div class="px-service-prijs">
                @if(!empty($pLbl))<span class="px-service-prijs-label">{{ $pLbl }}</span>@endif
                @if(!empty($pAmt))<span class="px-service-prijs-amt">{{ $pAmt }}</span>@endif
                @if(!empty($pMeta))<span class="px-service-prijs-meta">{{ $pMeta }}</span>@endif
              </div>
              <span class="px-service-arrow">→</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
