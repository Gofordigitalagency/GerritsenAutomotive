@php
  // Header werkt al volgens de eind-structuur: alle items wijzen naar hun eigen route,
  // ook al zijn die pagina's er nog niet (redirecten voor nu naar de homepage anchor).
  // Wanneer de echte pagina's gebouwd zijn, blijft de header werken zonder wijziging.
  $linkAanbod     = route('preview.aanbod');
  $linkWerkplaats = route('preview.werkplaats');
  $linkDiensten   = route('preview.diensten');
  $linkOver       = route('preview.over');
  $linkContact    = route('preview.contact');
@endphp

<header class="px-nav" id="pxNav">
  <div class="px-nav-inner">
    <a href="{{ route('preview.home') }}" class="px-logo" aria-label="Gerritsen Automotive">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
    </a>

    <nav class="px-nav-links" aria-label="Hoofdmenu">
      <a href="{{ $linkAanbod }}"     class="px-link {{ request()->routeIs('preview.aanbod') ? 'active' : '' }}">Aanbod</a>
      <a href="{{ $linkWerkplaats }}" class="px-link {{ request()->routeIs('preview.werkplaats') ? 'active' : '' }}">Werkplaats</a>
      <a href="{{ $linkDiensten }}"   class="px-link {{ request()->routeIs('preview.diensten') ? 'active' : '' }}">Diensten</a>
      <a href="{{ $linkOver }}"       class="px-link {{ request()->routeIs('preview.over') ? 'active' : '' }}">Over ons</a>
      <a href="{{ $linkContact }}"    class="px-link {{ request()->routeIs('preview.contact') ? 'active' : '' }}">Contact</a>
    </nav>

    <div class="px-nav-actions">
      <a href="tel:+31638257987" class="px-btn px-btn-ghost px-phone-pill">06 38 25 79 87</a>
      <a href="{{ $linkAanbod }}" class="px-btn px-btn-primary" data-magnetic>Bekijk aanbod</a>

      <button class="px-menu-btn" id="pxMenuBtn" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <div class="px-mobile-menu" id="pxMobileMenu" aria-hidden="true">
    <a href="{{ $linkAanbod }}">Aanbod</a>
    <a href="{{ $linkWerkplaats }}">Werkplaats</a>
    <a href="{{ $linkDiensten }}">Diensten</a>
    <a href="{{ $linkOver }}">Over ons</a>
    <a href="{{ $linkContact }}">Contact</a>
    <a href="tel:+31638257987" class="px-mobile-call">Bel ons · 06 38 25 79 87</a>
  </div>
</header>
