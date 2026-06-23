<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="robots" content="index,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page->meta_title ?: ($page->hero_title . ' — Gerritsen Automotive') }}</title>
    @if($page->meta_description)
      <meta name="description" content="{{ $page->meta_description }}">
    @endif
    <link rel="canonical" href="{{ $page->url() }}">

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

      /* SEO-tekstblok */
      .lp-prose{max-width:820px;margin:0 auto;font-size:17px;line-height:1.75;color:var(--px-fg)}
      .lp-prose h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:28px;line-height:1.25;margin:1.6em 0 .5em;font-weight:700}
      .lp-prose h3{font-size:21px;margin:1.4em 0 .4em;font-weight:700}
      .lp-prose p{margin:0 0 1.1em}
      .lp-prose ul,.lp-prose ol{margin:0 0 1.1em;padding-left:1.3em}
      .lp-prose li{margin:.3em 0}
      .lp-prose a{color:var(--px-accent-soft);text-decoration:underline}
      .lp-prose strong{color:var(--px-fg);font-weight:700}

      /* FAQ */
      .lp-faq{max-width:820px;margin:0 auto;display:flex;flex-direction:column;gap:12px}
      .lp-faq-item{border:1px solid var(--px-border);border-radius:14px;background:var(--px-surface);overflow:hidden}
      .lp-faq-item > summary{list-style:none;cursor:pointer;padding:18px 22px;font-weight:600;font-size:16.5px;
        display:flex;justify-content:space-between;align-items:center;gap:16px;color:var(--px-fg)}
      .lp-faq-item > summary::-webkit-details-marker{display:none}
      .lp-faq-item > summary::after{content:'+';font-size:24px;color:var(--px-accent-soft);transition:transform .2s;line-height:1}
      .lp-faq-item[open] > summary::after{transform:rotate(45deg)}
      .lp-faq-answer{padding:0 22px 20px;color:var(--px-fg-muted);line-height:1.7;font-size:15.5px}
    </style>
</head>
<body class="px-body">

@include('preview.partials.header')

{{-- ============ HERO ============ --}}
<section class="px-hero" id="pxHero">
  <div class="px-hero-bg" style="background-image: url('{{ $page->hero_image ? asset('storage/'.$page->hero_image) : setting_image('hero.bg_image') }}');"></div>
  <div class="px-hero-overlay"></div>
  <div class="px-hero-grain"></div>
  <div class="px-hero-cursor-light" id="pxHeroCursor" aria-hidden="true"></div>

  <div class="px-hero-inner">
    <div class="px-hero-content">
      @if($page->hero_eyebrow)
        <div class="px-eyebrow px-reveal"><span class="px-eyebrow-dot"></span>{{ $page->hero_eyebrow }}</div>
      @endif
      <h1 class="px-hero-title px-reveal" style="--rd: .1s">{{ $page->hero_title }}</h1>
      @if($page->hero_subtitle)
        <p class="px-hero-sub px-reveal" style="--rd: .2s">{!! nl2br(e($page->hero_subtitle)) !!}</p>
      @endif
      <div class="px-hero-cta px-reveal" style="--rd: .3s">
        <a href="{{ $page->cta_url ?: '/aanbod' }}" class="px-btn px-btn-primary px-btn-lg" data-magnetic>
          {{ $page->cta_label ?: 'Bekijk aanbod' }}
        </a>
        <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-btn px-btn-ghost px-btn-lg">
          Bel {{ setting_phone('contact.phone_sales') }}
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ============ SEO-TEKSTBLOK ============ --}}
@if($page->body)
  <section class="px-section">
    <div class="px-container">
      <div class="lp-prose">
        {!! $page->bodyHtml() !!}
      </div>
    </div>
  </section>
@endif

{{-- ============ OPTIONEEL: ACTUEEL AANBOD ============ --}}
@if($page->show_occasions && $nieuw->count())
  @php
    $availableCount = $nieuw->reject(fn ($c) => stripos($c->model ?? '', '(VERKOCHT)') !== false)->count();
  @endphp
  <section class="px-section px-section-alt" id="aanbod">
    <div class="px-container">
      <div class="px-section-head">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Ons aanbod</div>
        <h2 class="px-h2">Actuele occasions</h2>
      </div>

      <div class="px-grid">
        @foreach($nieuw->take(6) as $car)
          @php
            $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));
            if ($merkModel === '' && !empty($car->titel)) $merkModel = $car->titel;
            $hasDiscount = !empty($car->oude_prijs) && $car->oude_prijs > $car->prijs;
            $sold = stripos($car->model ?? '', '(VERKOCHT)') !== false;
          @endphp
          <a class="px-card {{ $sold ? 'px-card-sold' : '' }}" href="{{ route('occasions.show', $car->slug) }}">
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

      @if($availableCount > 6)
        <div class="px-grid-more">
          <a href="{{ route('occasions.index') }}" class="px-btn px-btn-ghost px-btn-lg">
            Bekijk alle {{ $availableCount }} occasions →
          </a>
        </div>
      @endif
    </div>
  </section>
@endif

{{-- ============ FAQ ============ --}}
@php $faqItems = $page->faqItems(); @endphp
@if(count($faqItems))
  <section class="px-section" id="faq">
    <div class="px-container">
      <div class="px-section-head">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>FAQ</div>
        <h2 class="px-h2">Veelgestelde vragen</h2>
      </div>

      <div class="lp-faq">
        @foreach($faqItems as $item)
          <details class="lp-faq-item">
            <summary>{{ $item['question'] }}</summary>
            <div class="lp-faq-answer">{!! nl2br(e($item['answer'])) !!}</div>
          </details>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Schema.org FAQPage voor rich results in Google --}}
  <script type="application/ld+json">
  {!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => collect($faqItems)->map(fn ($item) => [
      '@type' => 'Question',
      'name'  => $item['question'],
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text'  => $item['answer'],
      ],
    ])->all(),
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>
@endif

{{-- ============ FOOTER (gedeelde partial) ============ --}}
@include('preview.partials.footer')

<script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
</body>
</html>
