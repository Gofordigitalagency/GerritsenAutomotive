<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('contact_page.title') }} — Gerritsen Automotive</title>

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
  <div class="px-page-hero-bg-img" style="background-image: url('{{ setting_image('contact_page.bg_image') }}');"></div>
  <div class="px-page-hero-overlay"></div>
  <div class="px-hero-grain"></div>

  <div class="px-container">
    <div class="px-page-hero-inner">
      <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ setting('contact_page.eyebrow') }}</div>
      <h1 class="px-page-title px-reveal" style="--rd: .1s">{{ setting('contact_page.title') }}</h1>
      <p class="px-page-sub px-reveal" style="--rd: .2s">{{ setting('contact_page.subtitle') }}</p>
    </div>
  </div>
</section>

{{-- ============ CONTACT CARDS ============ --}}
<section class="px-section">
  <div class="px-container">
    <div class="px-contact-grid">
      <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-contact-card px-reveal" style="--rd: .05s">
        <div class="px-contact-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <div class="px-contact-card-body">
          <span class="px-contact-card-label">Verkoop</span>
          <span class="px-contact-card-value">{{ setting_phone('contact.phone_sales') }}</span>
          <span class="px-contact-card-meta">{{ setting('over.person1_name') }}</span>
        </div>
      </a>

      <a href="tel:{{ setting_tel('contact.phone_workshop') }}" class="px-contact-card px-reveal" style="--rd: .12s">
        <div class="px-contact-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <div class="px-contact-card-body">
          <span class="px-contact-card-label">Werkplaats</span>
          <span class="px-contact-card-value">{{ setting_phone('contact.phone_workshop') }}</span>
          <span class="px-contact-card-meta">{{ setting('over.person2_name') }}</span>
        </div>
      </a>

      <a href="mailto:{{ setting('contact.email') }}" class="px-contact-card px-reveal" style="--rd: .19s">
        <div class="px-contact-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <div class="px-contact-card-body">
          <span class="px-contact-card-label">E-mail</span>
          <span class="px-contact-card-value">{{ setting('contact.email') }}</span>
          <span class="px-contact-card-meta">Reactie zo snel mogelijk</span>
        </div>
      </a>

      <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(setting('contact.address')) }}" target="_blank" rel="noopener" class="px-contact-card px-reveal" style="--rd: .26s">
        <div class="px-contact-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="px-contact-card-body">
          <span class="px-contact-card-label">Adres</span>
          <span class="px-contact-card-value">{{ setting('contact.address') }}</span>
          <span class="px-contact-card-meta">Open in Google Maps →</span>
        </div>
      </a>
    </div>

    <div class="px-contact-hours px-reveal" style="--rd: .3s">
      <span class="px-contact-hours-label">Openingstijden</span>
      <ul class="px-contact-hours-list">
        <li><span>Ma t/m vr</span><span>{{ setting('contact.hours_weekday') }}</span></li>
        <li><span>Zaterdag</span><span>{{ setting('contact.hours_saturday') }}</span></li>
        <li><span>Zondag</span><span>{{ setting('contact.hours_sunday') }}</span></li>
      </ul>
    </div>
  </div>
</section>

{{-- ============ MAP ============ --}}
@if(setting('contact_page.map_embed_url'))
  <section class="px-section px-section-flush">
    <div class="px-contact-map">
      <iframe
        src="{{ setting('contact_page.map_embed_url') }}"
        width="100%"
        height="420"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </section>
@endif

{{-- ============ FORM + SFEERFOTO ============ --}}
<section class="px-section px-section-alt" id="formulier">
  <div class="px-container">
    <div class="px-contact-split">
      <aside class="px-contact-aside px-reveal">
        <img class="px-contact-aside-img px-parallax-img" loading="lazy"
             src="{{ setting_image('contact_page.sfeer_image') }}"
             alt="{{ setting('contact_page.title') }}">
        <div class="px-contact-aside-overlay">
          <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>{{ setting('contact_page.form_eyebrow') }}</div>
          <h2 class="px-h2">{{ setting('contact_page.form_title') }}</h2>
          <p>{{ setting('contact_page.form_sub') }}</p>
        </div>
      </aside>

      <form class="px-contact-form px-reveal" method="POST" action="{{ route('contact.store') }}" style="--rd: .15s">
        @csrf
        <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
        <input type="hidden" name="privacy" value="1">

        @if(session('success'))
          <div class="px-form-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="px-form-error">
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        <div class="px-form-row">
          <div class="px-input-wrap">
            <label for="cName">Naam</label>
            <input type="text" id="cName" name="name" required maxlength="120" value="{{ old('name') }}">
          </div>
          <div class="px-input-wrap">
            <label for="cEmail">E-mail</label>
            <input type="email" id="cEmail" name="email" required maxlength="190" value="{{ old('email') }}">
          </div>
        </div>

        <div class="px-input-wrap">
          <label for="cPhone">Telefoon (optioneel)</label>
          <input type="tel" id="cPhone" name="phone" maxlength="40" value="{{ old('phone') }}">
        </div>

        <div class="px-input-wrap">
          <label for="cMessage">Bericht</label>
          <textarea id="cMessage" name="message" required maxlength="5000" rows="6">{{ old('message') }}</textarea>
        </div>

        <div class="px-form-foot">
          <button type="submit" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Verstuur bericht</button>
          <span class="px-form-foot-meta">
            Liever bellen? <a href="tel:{{ setting_tel('contact.phone_sales') }}">{{ setting_phone('contact.phone_sales') }}</a>
          </span>
        </div>
      </form>
    </div>
  </div>
</section>

@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
