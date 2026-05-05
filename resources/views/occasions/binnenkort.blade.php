<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Binnenkort beschikbaar – Gerritsen Automotive</title>

  <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/occasions.css') }}?v={{ filemtime(public_path('css/occasions.css')) }}">

  <style>
    .binnenkort-hero{
      background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)), url('/images/backgroundhome.jpg') no-repeat center/cover;
      padding:140px 0 60px; text-align:center; color:#fff;
    }
    .binnenkort-hero h1{ font-size:clamp(32px,5vw,56px); margin:0 0 12px; font-weight:700; }
    .binnenkort-hero p{ font-size:17px; opacity:.85; max-width:600px; margin:0 auto; }
    .binnenkort-section{ padding:60px 0 100px; background:#fff; }
    .binnenkort-section .cards-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:32px; }
    .binnenkort-card{
      display:flex; flex-direction:column; background:#e9e9e9; overflow:hidden; position:relative;
      transition:transform .2s ease;
    }
    .binnenkort-card:hover{ transform:translateY(-4px); }
    .binnenkort-card .photo{ position:relative; aspect-ratio:16/10; overflow:hidden; }
    .binnenkort-card .photo img{ width:100%; height:100%; object-fit:cover; }
    .binnenkort-badge{
      position:absolute; top:12px; left:12px;
      background:#c1121f; color:#fff;
      padding:6px 14px; font-size:12px; font-weight:700; letter-spacing:.5px;
      text-transform:uppercase;
    }
    .binnenkort-card .info{ padding:24px; display:flex; flex-direction:column; gap:8px; }
    .binnenkort-card .title{ font-size:22px; font-weight:700; color:#111; margin:0; }
    .binnenkort-card .type{ font-size:16px; font-weight:600; color:#111; opacity:.8; }
    .binnenkort-card .meta{ display:flex; flex-wrap:wrap; gap:14px; font-size:14px; color:#444; margin-top:4px; }
    .binnenkort-card .verwacht{
      margin-top:10px; display:flex; flex-direction:column; gap:2px;
    }
    .binnenkort-card .verwacht-label{ font-size:11px; color:#666; letter-spacing:.5px; text-transform:uppercase; font-weight:600; }
    .binnenkort-card .verwacht-prijs{
      background:#747474; color:#fff; padding:8px 14px;
      font-weight:700; font-size:16px; align-self:flex-start;
    }
    .binnenkort-empty{
      text-align:center; padding:80px 20px; color:#666;
    }
    .binnenkort-empty h2{ color:#111; margin-bottom:12px; }
    @media (max-width:1024px){
      .binnenkort-section .cards-grid{ grid-template-columns:repeat(2,1fr); }
    }
    @media (max-width:640px){
      .binnenkort-section .cards-grid{ grid-template-columns:1fr; }
    }
  </style>
</head>
<body class="is-oc">

<header class="navbar" x-data>
  <div class="container">
    <a class="logo" href="/" aria-label="Gerritsen Automotive">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
    </a>

    <div class="icons">
      <a href="tel:+31649951874" class="phone-btn" aria-label="Bel ons">
        <img src="{{ asset('images/phone-call.svg') }}" alt="" class="phone-icon">
      </a>
      <button id="menuToggle" class="menu-toggle" aria-label="Menu openen" aria-controls="mainNav" aria-expanded="false" type="button">
        <svg class="hamburger-svg" viewBox="0 0 24 24" width="40" height="40" aria-hidden="true">
          <line class="line top"    x1="4" y1="7"  x2="20" y2="7"  />
          <line class="line middle" x1="4" y1="12" x2="20" y2="12" />
          <line class="line bottom" x1="4" y1="17" x2="20" y2="17" />
        </svg>
        <span class="sr-only">Menu</span>
      </button>
    </div>

    <nav id="mainNav" class="nav-desktop" aria-label="Hoofdmenu">
      <a href="/">HOME</a>
      <a href="/#info">OVER ONS</a>
      <a href="{{ route('occasions.index') }}">AANBOD</a>
      <a href="{{ route('occasions.binnenkort') }}" class="active">BINNENKORT</a>
      <a href="/#footer">CONTACT</a>
    </nav>
  </div>

  <div id="navOverlay" class="nav-overlay" aria-hidden="true">
    <div class="nav-panel" role="dialog" aria-modal="true" aria-labelledby="mobileNavTitle">
      <div class="panel-top">
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive" class="panel-logo">
        <button id="menuClose" class="panel-close" aria-label="Menu sluiten" type="button">
          <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true">
            <line x1="6" y1="6" x2="18" y2="18" />
            <line x1="18" y1="6" x2="6" y2="18" />
          </svg>
        </button>
      </div>

      <nav class="nav-mobile" aria-labelledby="mobileNavTitle">
        <h2 id="mobileNavTitle" class="sr-only">Hoofdmenu</h2>
        <a href="/">HOME</a>
        <a href="/#info">OVER ONS</a>
        <a href="{{ route('occasions.index') }}">AANBOD</a>
        <a href="{{ route('occasions.binnenkort') }}">BINNENKORT</a>
        <a href="/#footer">CONTACT</a>
      </nav>

      <a href="tel:+31649951874" class="panel-call">
        <img src="{{ asset('images/phone-call.svg') }}" alt="" class="phone-icon"> Bel ons
      </a>
    </div>
  </div>
</header>

<section class="binnenkort-hero">
  <div class="container">
    <h1>Binnenkort beschikbaar</h1>
    <p>Een kijkje in onze pijplijn — auto's die we momenteel klaarmaken voor verkoop. Bekijk alvast de verwachte prijzen en houd uw favoriet in de gaten.</p>
  </div>
</section>

<section class="binnenkort-section">
  <div class="container">
    @if($nieuw->count() === 0)
      <div class="binnenkort-empty">
        <h2>Geen auto's binnenkort</h2>
        <p>Op dit moment hebben we geen auto's in voorbereiding. <a href="{{ route('occasions.index') }}" style="color:#c1121f">Bekijk ons huidige aanbod</a>.</p>
      </div>
    @else
      <div class="cards-grid">
        @foreach($nieuw as $car)
          @php
            $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));
            $type = $car->type ?? '';
          @endphp
          <div class="binnenkort-card">
            <div class="photo">
              <img src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="{{ $merkModel }}">
              <span class="binnenkort-badge">Binnenkort</span>
            </div>
            <div class="info">
              <h3 class="title">{{ $merkModel }}</h3>
              @if(!empty($type))<div class="type">{{ $type }}</div>@endif
              <div class="meta">
                @if($car->bouwjaar)<span>{{ $car->bouwjaar }}</span>@endif
                @if($car->brandstof)<span>{{ ucfirst($car->brandstof) }}</span>@endif
                @if($car->transmissie)<span>{{ ucfirst($car->transmissie) }}</span>@endif
                @if($car->tellerstand)<span>{{ number_format($car->tellerstand, 0, ',', '.') }} km</span>@endif
              </div>
              @if($car->verwachte_prijs)
                <div class="verwacht">
                  <span class="verwacht-label">Verwachte prijs</span>
                  <span class="verwacht-prijs">€ {{ number_format($car->verwachte_prijs, 0, ',', '.') }},-</span>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
</body>
</html>
