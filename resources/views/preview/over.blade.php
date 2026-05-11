<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('over_page.title') }} — Gerritsen Automotive</title>

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
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('over_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('over_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('over_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('over_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ VERHAAL ============ --}}
<section class="px-section">
  <div class="px-container">
    <div class="px-over">
      <div class="px-over-content">
        <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('over.eyebrow') }}</div>
        <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('over.title') }}</h2>

        <p class="px-reveal" style="--rd: .2s">{{ setting('over.body_p1') }}</p>
        <p class="px-reveal" style="--rd: .3s">{{ setting('over.body_p2') }}</p>

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

{{-- ============ FOTO-STROOK ============ --}}
@php
  $galleryImages = collect([1, 2, 3])
      ->map(fn ($i) => setting_image('over_page.gallery_image_'.$i))
      ->filter()
      ->values();
@endphp

@if($galleryImages->isNotEmpty())
<section class="px-section px-section-alt">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('over_page.gallery_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('over_page.gallery_title') }}</h2>
    </div>

    <div class="px-photo-strip">
      @foreach($galleryImages as $i => $img)
        <figure class="px-photo-strip-item" style="--rd: {{ 0.05 + $i * 0.1 }}s">
          <img loading="lazy" src="{{ $img }}" alt="">
        </figure>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ============ TEAM ============ --}}
<section class="px-section px-section-alt">
  <div class="px-container">
    <div class="px-section-head">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('over_page.team_eyebrow') }}</div>
      <h2 class="px-h2 px-reveal" style="--rd: .1s">{{ setting('over_page.team_title') }}</h2>
    </div>

    <div class="px-people px-people-large">
      <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-person px-reveal" style="--rd: .1s">
        <div class="px-person-avatar">{{ Str::upper(Str::substr(setting('over.person1_name'), 0, 1)) }}</div>
        <div class="px-person-body">
          <div class="px-person-role">{{ setting('over.person1_role') }}</div>
          <div class="px-person-name">{{ setting('over.person1_name') }}</div>
          <div class="px-person-phone">{{ setting('contact.phone_sales') }}</div>
        </div>
      </a>
      <a href="tel:{{ setting_tel('contact.phone_workshop') }}" class="px-person px-reveal" style="--rd: .2s">
        <div class="px-person-avatar">{{ Str::upper(Str::substr(setting('over.person2_name'), 0, 1)) }}</div>
        <div class="px-person-body">
          <div class="px-person-role">{{ setting('over.person2_role') }}</div>
          <div class="px-person-name">{{ setting('over.person2_name') }}</div>
          <div class="px-person-phone">{{ setting('contact.phone_workshop') }}</div>
        </div>
      </a>
    </div>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
