<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerritsen Automotive</title>


    <link rel="stylesheet" href="{{ asset('css/occasions.css') }}">
    <script src="{{ asset('js/occasions.js') }}" defer></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="navbar" x-data>
  <div class="container">
    <a class="logo" href="/" aria-label="Gerritsen Automotive">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
    </a>

    <div class="icons">


    <!-- Telefoon (alleen mobiel zichtbaar via CSS) -->
    <a href="tel:+31649951874" class="phone-btn" aria-label="Bel ons">
      <img src="{{ asset('images/phone-call.svg') }}" alt="" class="phone-icon">
    </a>

    <!-- Menu toggle: één knop met morphende SVG -->
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
      <a href="/"       class="{{ request()->is('/') ? 'active' : '' }}">HOME</a>
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


    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>


</html>
