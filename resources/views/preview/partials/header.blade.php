@php
  /* Hardcoded paden: zo werkt de header ook als de routes nog niet
     allemaal geregistreerd zijn (bv. op productie waar alleen /preview
     gepusht is). Lokaal werken deze paden uiteraard ook. */
  $linkAanbod     = '/aanbod';
  $linkWerkplaats = '/werkplaats';
  $linkDiensten   = '/diensten';
  $linkVerkopen   = '/auto-verkopen';
  $linkOver       = '/over';
  $linkContact    = '/contact';
@endphp

<header class="px-nav" id="pxNav">
  <div class="px-nav-inner">
    <a href="/" class="px-logo" aria-label="Gerritsen Automotive">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
    </a>

    <nav class="px-nav-links" aria-label="Hoofdmenu">
      <a href="{{ $linkAanbod }}"     class="px-link {{ request()->routeIs('aanbod') ? 'active' : '' }}">Aanbod</a>
      <a href="{{ $linkWerkplaats }}" class="px-link {{ request()->routeIs('werkplaats') ? 'active' : '' }}">Werkplaats</a>
      <a href="{{ $linkDiensten }}"   class="px-link {{ request()->routeIs('diensten') ? 'active' : '' }}">Diensten</a>
      <a href="{{ $linkVerkopen }}"   class="px-link {{ request()->routeIs('sellcar.show') ? 'active' : '' }}">Auto verkopen</a>
      <a href="{{ $linkOver }}"       class="px-link {{ request()->routeIs('over') ? 'active' : '' }}">Over ons</a>
      <a href="{{ $linkContact }}"    class="px-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </nav>

    <div class="px-nav-actions">
      <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-btn px-btn-ghost px-phone-pill">{{ setting_phone('contact.phone_sales') }}</a>
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
    <a href="{{ $linkVerkopen }}">Auto verkopen</a>
    <a href="{{ $linkOver }}">Over ons</a>
    <a href="{{ $linkContact }}">Contact</a>
    <a href="tel:{{ setting_tel('contact.phone_sales') }}" class="px-mobile-call">Bel ons · {{ setting_phone('contact.phone_sales') }}</a>
  </div>
</header>
