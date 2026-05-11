<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('werkplaats_page.title') }} — Gerritsen Automotive</title>

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

{{-- ============ PAGE HERO met foto-achtergrond ============ --}}
<section class="px-page-hero px-page-hero-photo">
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('werkplaats_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('werkplaats_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('werkplaats_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('werkplaats_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ SMART BOOKING ============ --}}
<section class="px-section" id="afspraak">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('werkplaats_page.booking_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('werkplaats_page.booking_title') }}</h2>
      <p class="px-section-sub px-reveal" style="--rd: .2s">{{ setting('werkplaats_page.booking_sub') }}</p>
    </div>

    @include('preview.partials.workshop-booking')
  </div>
</section>

{{-- ============ DIENSTEN OVERZICHT ============ --}}
<section class="px-section px-section-alt">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('werkplaats_page.services_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('werkplaats_page.services_title') }}</h2>
      <p class="px-section-sub px-reveal" style="--rd: .2s">{{ setting('werkplaats_page.services_sub') }}</p>
    </div>

    <div class="px-wp-grid">
      @php
        $wpServices = [
          ['key' => 'svc1', 'icon' => '<path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>'],
          ['key' => 'svc2', 'icon' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>'],
          ['key' => 'svc3', 'icon' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'],
          ['key' => 'svc4', 'icon' => '<path d="M21 21l-3.5-3.5"/><circle cx="11" cy="11" r="7"/>'],
        ];
      @endphp
      @foreach($wpServices as $i => $s)
        @php
          $img = setting('werkplaats_page.'.$s['key'].'_image');
          $imgUrl = !empty($img) ? setting_image('werkplaats_page.'.$s['key'].'_image') : null;
        @endphp
        <article class="px-wp-card {{ $imgUrl ? 'px-wp-card-photo' : '' }}" style="--rd: {{ 0.05 + $i * 0.07 }}s">
          @if($imgUrl)
            <div class="px-wp-card-img">
              <img loading="lazy" src="{{ $imgUrl }}" alt="{{ setting('werkplaats_page.'.$s['key'].'_title') }}">
            </div>
          @else
            <div class="px-wp-card-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $s['icon'] !!}</svg>
            </div>
          @endif
          <div class="px-wp-card-content">
            <h3 class="px-wp-card-title">{{ setting('werkplaats_page.'.$s['key'].'_title') }}</h3>
            <p class="px-wp-card-body">{{ setting('werkplaats_page.'.$s['key'].'_body') }}</p>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

{{-- ============ USPs ============ --}}
<section class="px-section">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('werkplaats_page.usps_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('werkplaats_page.usps_title') }}</h2>
    </div>

    <ul class="px-wp-usps">
      @foreach (['usp1','usp2','usp3','usp4','usp5','usp6'] as $i => $key)
        @php $val = setting('werkplaats_page.'.$key); @endphp
        @if (!empty($val))
          <li class="px-wp-usp px-reveal" style="--rd: {{ 0.05 + $i * 0.07 }}s">
            <span class="px-wp-usp-check">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg>
            </span>
            <span>{{ $val }}</span>
          </li>
        @endif
      @endforeach
    </ul>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
