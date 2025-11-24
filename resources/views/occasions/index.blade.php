<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Alle occasions – Gerritsen Automotive</title>

  <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/occasions.css') }}?v={{ filemtime(public_path('css/occasions.css')) }}">
</head>
<body class="is-oc">

<header class="navbar" x-data>
  <div class="container">
    <a class="logo" href="/" aria-label="Gerritsen Automotive">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
    </a>

    <div class="icons">
      <!-- Telefoon (alleen mobiel via CSS) -->
      <a href="tel:+31649951874" class="phone-btn" aria-label="Bel ons">
        <img src="{{ asset('images/phone-call.svg') }}" alt="" class="phone-icon">
      </a>

      <!-- Menu toggle -->
      <button
        id="menuToggle"
        class="menu-toggle"
        aria-label="Menu openen"
        aria-controls="mainNav"
        aria-expanded="false"
        type="button"
      >
        <svg class="hamburger-svg" viewBox="0 0 24 24" width="40" height="40" aria-hidden="true">
          <line class="line top"    x1="4" y1="7"  x2="20" y2="7"  />
          <line class="line middle" x1="4" y1="12" x2="20" y2="12" />
          <line class="line bottom" x1="4" y1="17" x2="20" y2="17" />
        </svg>
        <span class="sr-only">Menu</span>
      </button>
    </div>

    <!-- Desktop menu -->
    <nav id="mainNav" class="nav-desktop" aria-label="Hoofdmenu">
      <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">HOME</a>
      <a href="#info" class="{{ request()->is('over-ons') ? 'active' : '' }}">OVER ONS</a>
      <a href="#aanbod" class="{{ request()->is('aanbod') ? 'active' : '' }}">AANBOD</a>
      <a href="#footer" class="{{ request()->is('contact') ? 'active' : '' }}">CONTACT</a>
    </nav>
  </div>

  <!-- Mobiele overlay + panel -->
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
        <a href="#info">OVER ONS</a>
        <a href="#aanbod">AANBOD</a>
        <a href="#footer">CONTACT</a>
      </nav>

      <a href="tel:+31649951874" class="panel-call">
        <img src="{{ asset('images/phone-call.svg') }}" alt="" class="phone-icon"> Bel ons
      </a>
    </div>
  </div>
</header>


<main style="padding-top:200px; padding-bottom:120px;">
  <section class="nieuw-binnen">
    <div class="container">
      <h1 class="sectie-titel">Alle occasions</h1>

      <div class="cards-grid">
        @foreach($occasions as $car)
          <a class="car-card" href="{{ route('occasions.show', $car->slug) }}">
            <div class="car-photo">
              <img src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="{{ $car->titel }}">
            </div>
            <div class="car-info">
              <h3 class="car-title">
                {{ trim(($car->merk ?? '').' '.($car->model ?? '')) ?: $car->titel }}
              </h3>
              @if(!empty($car->type))
                <div class="car-type" style="font-size:14px; opacity:.85; margin-top:-6px;">{{ $car->type }}</div>
              @endif
              <div class="car-meta">
                <span>{{ ucfirst($car->transmissie) }}</span>
                <span>{{ $car->bouwjaar ?? '—' }}</span>
                <span>{{ ucfirst($car->brandstof) }}</span>
                <span>{{ number_format($car->tellerstand ?? 0, 0, ',', '.') }} km</span>
              </div>
              <div class="car-price">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }},-</div>
            </div>
          </a>
        @endforeach
      </div>

    
    </div>
  </section>
</main>

<script>
  (function(){
    const body = document.body;
    const nav  = document.getElementById('siteNav');
    const ham  = document.getElementById('hamburger');
    const close= document.getElementById('menuClose');

    const toggle = (open) => {
      nav.classList.toggle('active', open);
      body.classList.toggle('menu-open', open);
      body.style.overflow = open ? 'hidden' : '';
    };
    ham?.addEventListener('click', ()=> toggle(true));
    close?.addEventListener('click', ()=> toggle(false));

    const header = document.querySelector('.navbar');
    const setScrolled = () => {
      const y = window.scrollY || document.documentElement.scrollTop || 0;
      header?.classList.toggle('scrolled', y > 10);
    };
    setScrolled();
    window.addEventListener('scroll', setScrolled, { passive:true });
  })();
</script>
</body>
</html>
