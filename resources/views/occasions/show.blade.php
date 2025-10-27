<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ trim(($occasion->merk ?? '').' '.($occasion->model ?? '')) ?: $occasion->titel }} – Gerritsen Automotive</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <!-- Globale styles + occasion styles -->
  <link rel="stylesheet" href="{{ asset('css/occasions.css') }}?v={{ filemtime(public_path('css/occasions.css')) }}">
</head>
<body class="is-oc">
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
      <a href="#info"   class="{{ request()->is('over-ons') ? 'active' : '' }}">OVER ONS</a>
      <a href="#aanbod" class="{{ request()->is('aanbod') ? 'active' : '' }}">AANBOD</a>
      <a href="#footer" class="{{ request()->is('contact') ? 'active' : '' }}">CONTACT</a>

      <!-- Dark mode toggle (DESKTOP – naast CONTACT) -->
      <button id="themeToggle"
              class="theme-toggle theme-toggle--nav"
              type="button"
              aria-label="Dark mode wisselen"
              aria-pressed="false">
        <img src="{{ asset('images/moon.svg') }}" alt="" width="20" height="20">
      </button>
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

      <!-- Dark mode toggle (MOBIEL – in hamburger menu) -->
      <button id="themeToggleMobile"
              class="panel-theme-toggle"
              type="button"
              aria-label="Dark mode wisselen"
              aria-pressed="false">
        <img src="{{ asset('images/moon.svg') }}" alt="" width="22" height="22">
        <span>Dark mode</span>
      </button>

      <a href="tel:+31649951874" class="panel-call">
        <img src="{{ asset('images/phone-call.svg') }}" alt="" class="phone-icon"> Bel ons
      </a>
    </div>
  </div>
</header>

<main class="oc-detail">
  <div class="container">

    <a href="/" class="ocd-back">← Terug naar overzicht</a>

@php
  $cover = $occasion->hoofdfoto_path
            ? asset('storage/'.$occasion->hoofdfoto_path)
            : asset('images/placeholder-car.jpg');

  $galerijRaw = $occasion->galerij ?? [];
  if (is_string($galerijRaw)) {
      $decoded = json_decode($galerijRaw, true);
      if (json_last_error() === JSON_ERROR_NONE) $galerijRaw = $decoded;
      else $galerijRaw = [];
  }

  $galerijUrls = collect((array)$galerijRaw)
      ->filter()
      ->map(function ($path) {
          // ‘occasions/…’ uit storage -> absolute URL
          if (is_string($path) && !str_starts_with($path, 'http')) {
              return asset('storage/'.$path);
          }
          return $path;
      });

  $galleryAll = collect([$cover])
      ->merge($galerijUrls)
      ->filter()
      ->unique()
      ->values();

  $main   = $galleryAll->first() ?? $cover;
  $thumbs = $galleryAll
      ->reject(fn($u) => $u === $cover) // voorkom dubbele hoofdfoto
      ->take(4)
      ->values();
@endphp
    <div class="ocd-shell">
      <!-- Linkerkolom -->
    <div class="ocd-left">
  <div class="ocd-stage" 
       data-urls='@json($galleryAll)'
       data-urls-thumbs='@json($thumbs)'>
    <img id="ocdMain" src="{{ $main }}" alt="{{ $occasion->titel }}">
    @if($galleryAll->count() > 1)
      <button class="ocd-nav ocd-prev" type="button" aria-label="Vorige">‹</button>
      <button class="ocd-nav ocd-next" type="button" aria-label="Volgende">›</button>
    @endif
  </div>

  @if($thumbs->count() > 0)
    <div class="ocd-thumbs" id="ocdThumbs">
      @foreach($thumbs as $i => $url)
        <button class="ocd-thumb{{ $i===0 ? ' is-active' : '' }}" data-idx="{{ $i }}" type="button">
          <img src="{{ $url }}" alt="Foto {{ $i+1 }}">
        </button>
      @endforeach
    </div>
  @endif
</div>


      <!-- Rechterkolom -->
      <aside class="ocd-right">
        <h1 class="ocd-title">
          {{ trim(($occasion->merk ?? '').' '.($occasion->model ?? '')) ?: $occasion->titel }}
        </h1>
        @if(!empty($occasion->type))
          <p class="ocd-sub">{{ $occasion->type }}</p>
        @endif

        <div class="ocd-price-row">
          <div class="ocd-price">€ {{ number_format($occasion->prijs ?? 0, 0, ',', '.') }},-</div>
        </div>

        <!-- Compacte spec-tegelrij zoals eerder gewenst -->
        <div class="ocd-specgrid">
          <div class="ocd-kv"><span class="k">Carrosserie</span><span class="v">{{ $occasion->carrosserie ?? '—' }}</span></div>
          <div class="ocd-kv"><span class="k">BTW/MARGE</span><span class="v">{{ $occasion->btw_marge ?? '—' }}</span></div>
          <div class="ocd-kv"><span class="k">Kleur</span><span class="v">{{ $occasion->kleur ?? $occasion->exterieurkleur ?? '—' }}</span></div>
          <div class="ocd-kv"><span class="k">Brandstof</span><span class="v">{{ $occasion->brandstof ? ucwords($occasion->brandstof) : '—' }}</span></div>
          <div class="ocd-kv"><span class="k">KM-stand</span><span class="v">{{ isset($occasion->tellerstand) ? number_format($occasion->tellerstand, 0, ',', '.') . ' km' : '—' }}</span></div>
          <div class="ocd-kv"><span class="k">Transmissie</span><span class="v">{{ $occasion->transmissie ? ucwords($occasion->transmissie) : '—' }}</span></div>
          <div class="ocd-kv"><span class="k">Energielabel</span>
            <span class="v">
              @if(!empty($occasion->energielabel))
                <span class="badge badge-green">{{ $occasion->energielabel }}</span>
              @else — @endif
            </span>
          </div>
          <div class="ocd-kv"><span class="k">Bouwjaar</span><span class="v">{{ $occasion->bouwjaar ?? '—' }}</span></div>
        </div>

        <div class="ocd-cta-row">
          <a href="#footer" class="ocd-btn ocd-btn-primary">IK BEN GEÏNTERESSEERD</a>
          <a href="#footer" class="ocd-btn ocd-btn-success">AUTO INRUILEN?</a>

        </div>

        <div class="ocd-seller">
          <div class="ocd-seller-info">
            <div class="name">Mick Gerritsen</div>
            <a href="tel:+31649951874">+31 6 49951874</a>
            <a href="mailto:info@gerritsenautomotive.nl">info@gerritsenautomotive.nl</a>
          </div>
        </div>
      </aside>
    </div>

    {{-- ===== Tabs: Kenmerken / Opties / Omschrijving ===== --}}
    @php
      $opties_flat = preg_split('/\r\n|\r|\n/', (string)($occasion->opties ?? ''), -1, PREG_SPLIT_NO_EMPTY);

      $exterieur = $occasion->exterieur_options ?? [];
      $interieur = $occasion->interieur_options ?? [];
      $veiligheid= $occasion->veiligheid_options ?? [];
      $overige   = $occasion->overige_options ?? [];
    @endphp

    <div class="ocd-tabs">
      <button class="ocd-tab is-active" data-tab="specs">Kenmerken</button>
      <button class="ocd-tab" data-tab="options">Opties</button>
      <button class="ocd-tab" data-tab="desc">Omschrijving</button>
    </div>

    <!-- Kenmerken -->
    <section class="ocd-tabpanel is-active" id="tab-specs" role="tabpanel" aria-label="Kenmerken">
      <div class="spec-columns">
        <ul class="spec-list">
          <li><span class="k">Merk</span><span class="v">{{ $occasion->merk ?? '—' }}</span></li>
          <li><span class="k">Model</span><span class="v">{{ $occasion->model ?? '—' }}</span></li>
          <li><span class="k">Type</span><span class="v">{{ $occasion->type ?? '—' }}</span></li>
          <li><span class="k">Transmissie</span><span class="v">{{ $occasion->transmissie ? ucwords($occasion->transmissie) : '—' }}</span></li>
          <li><span class="k">Brandstof</span><span class="v">{{ $occasion->brandstof ? ucwords($occasion->brandstof) : '—' }}</span></li>
          <li><span class="k">Kleur</span><span class="v">{{ $occasion->kleur ?? $occasion->exterieurkleur ?? '—' }}</span></li>
          <li><span class="k">Prijs</span><span class="v">{{ !empty($occasion->prijs) ? '€ '.number_format($occasion->prijs,0,',','.').',-' : '—' }}</span></li>
          <li><span class="k">Aantal cilinders</span><span class="v">{{ $occasion->aantal_cilinders ?? '—' }}</span></li>
          <li><span class="k">Topsnelheid</span><span class="v">{{ !empty($occasion->topsnelheid) ? $occasion->topsnelheid.' km/u' : '—' }}</span></li>
          <li><span class="k">Gewicht</span><span class="v">{{ !empty($occasion->gewicht) ? $occasion->gewicht.' kg' : '—' }}</span></li>
          <li><span class="k">Gemiddeld verbruik</span><span class="v">{{ $occasion->gemiddeld_verbruik ?? '—' }} / 100 KM</span></li>
        </ul>

        <ul class="spec-list">
          <li><span class="k">Aantal deuren</span><span class="v">{{ $occasion->aantal_deuren ?? '—' }}</span></li>
          <li><span class="k">Tellerstand</span><span class="v">{{ !empty($occasion->tellerstand) ? number_format($occasion->tellerstand,0,',','.') . ' KM' : '—' }}</span></li>
          <li><span class="k">Bouwjaar</span><span class="v">{{ $occasion->bouwjaar ?? '—' }}</span></li>
          <li><span class="k">Bekleding</span><span class="v">{{ $occasion->bekleding ?? '—' }}</span></li>
          <li><span class="k">Interieurkleur</span><span class="v">{{ $occasion->interieurkleur ?? '—' }}</span></li>
          <li><span class="k">BTW/Marge</span><span class="v">{{ $occasion->btw_marge ?? '—' }}</span></li>
          <li><span class="k">Cilinderinhoud</span><span class="v">{{ !empty($occasion->cilinderinhoud) ? $occasion->cilinderinhoud.' CC' : '—' }}</span></li>
          <li><span class="k">Carrosserie</span><span class="v">{{ $occasion->carrosserie ?? '—' }}</span></li>
          <li><span class="k">Energielabel</span>
            <span class="v">
              @if(!empty($occasion->energielabel))
                <span class="badge badge-green">{{ $occasion->energielabel }}</span>
              @else — @endif
            </span>
          </li>
          <li><span class="k">Wegenbelasting</span><span class="v"> € {{ $occasion->wegenbelasting_min ?? '—' }}</span></li>
        </ul>
      </div>
    </section>

    <!-- Opties -->
    <section class="ocd-tabpanel" id="tab-options" role="tabpanel" aria-label="Opties">
      @if(!empty($exterieur) || !empty($interieur) || !empty($veiligheid) || !empty($overige) || !empty($opties_flat))
        <div class="options-grid">
          @if(!empty($exterieur))
            <div class="opt-col">
              <h4>Exterieur</h4>
              <ul class="bullets">
                @foreach($exterieur as $o)<li>{{ $o }}</li>@endforeach
              </ul>
            </div>
          @endif

          @if(!empty($overige))
            <div class="opt-col">
              <h4>Infotainment / Overige</h4>
              <ul class="bullets">
                @foreach($overige as $o)<li>{{ $o }}</li>@endforeach
              </ul>
            </div>
          @endif

          @if(!empty($interieur))
            <div class="opt-col">
              <h4>Interieur</h4>
              <ul class="bullets">
                @foreach($interieur as $o)<li>{{ $o }}</li>@endforeach
              </ul>
            </div>
          @endif

          @if(!empty($veiligheid))
            <div class="opt-col">
              <h4>Veiligheid</h4>
              <ul class="bullets">
                @foreach($veiligheid as $o)<li>{{ $o }}</li>@endforeach
              </ul>
            </div>
          @endif

          @if(empty($exterieur) && empty($interieur) && empty($veiligheid) && empty($overige) && !empty($opties_flat))
            <div class="opt-col">
              <ul class="bullets two-col">
                @foreach($opties_flat as $o)<li>{{ trim($o) }}</li>@endforeach
              </ul>
            </div>
          @endif
        </div>
      @else
        <p class="ocd-empty">Geen opties bekend.</p>
      @endif
    </section>

    <!-- Omschrijving -->
    <section class="ocd-tabpanel" id="tab-desc" role="tabpanel" aria-label="Omschrijving">
      @if(!empty($occasion->omschrijving))
        <div class="ocd-text">{!! nl2br(e($occasion->omschrijving)) !!}</div>
      @else
        <p class="ocd-empty">Geen omschrijving aanwezig.</p>
      @endif
    </section>

  </div>
</main>


<section class="afspraak-section">
    <div class="container">
        <div class="afspraak-section-inner">
            <h1>Ook zo'n mooie auto op het oog?</h1>
            <p>Plan snel een afspraak en stap binnenkort in jouw nieuwe auto.</p>
             <div class="btn-aanbod">
                <a href="#footer" class="btn btn-primary">Maak Afspraak</a>
            </div>
        </div>
    </div>
</section>

<section class="openingstijden-section">
    <div class="container">
        <div class="openingstijden-section-inner">
            <div class="openingstijden-content">
                <h1>Openingstijden & afhalen op afspraak</h1>
                <p>Door de week ben je welkom voor advies, verhuur en onderhoud. Op zaterdag kun je snel ophalen/inleveren. Buiten deze tijden plannen we op verzoek een afhaal-/inleverslot (ochtend/avond).</p>
                <p>Openingstijden: Ma–Vr 08:30–17:30, Za 09:00–16:00, Zo: gesloten.</p>
                <div class="btn-aanbod"><a href="#footer" class="btn btn-primary">Maak Afspraak</a></div>
            </div>
            <div class="openingstijden-image">
                <img src="{{ asset('images/car-repair-maintenance-theme-mechanic-uniform-working-auto-service.jpg') }}" alt="Handdruk">
            </div>
        </div>
    </div>
</section>

<section id="footer" class="footer-section">
    <div class="container">
        <div class="footer-section-inner">
            <div class="footer-content-left">
                <h1>Neem contact op</h1>
                
                <div class="footer-content-left-info">
                    <div class="naam">
                        <img src="{{ asset('images/home.svg') }}" alt="home">
                        <p>Gerritsen Automotive</p>
                    </div>

                    <div class="location">
                        <img src="{{ asset('images/location.svg') }}" alt="home">
                        <p>Handelstraat 10, 6851 EH Huissen</p>
                    </div> 

                    <div class="phone">
                        <img src="{{ asset('images/telephone.svg') }}" alt="home">
                        <p>+ 31 6 49951874</p>
                    </div>
                    
                    <div class="email">
                        <img src="{{ asset('images/mail.svg') }}" alt="home">
                        <p>Info@gerritsenautomotive.nl</p>
                    </div>                     
                </div>

                <img src="{{ asset('images/Garage-footer.png') }}" alt="keuringen">

            </div>

            <div class="footer-content-right">
                <form method="POST" action="{{ route('contact.store') }}"  class="contact-form" novalidate>
                   @csrf
                    <div class="row two">
                    <div class="field">
                        <input class="inputform" type="text" name="name" placeholder="Naam">
                    </div>
                    <div class="field">
                        <input class="inputform" type="text" name="phone" placeholder="Telefoonnummer">
                    </div>
                    </div>

                    <div class="field">
                    <input class="inputform" type="email" name="email" placeholder="Email">
                    </div>

                    <div class="field">
                    <textarea class="inputform" name="message" rows="5" placeholder="Bericht:"></textarea>
                    </div>

                    <label class="check">
                    <input type="checkbox" name="privacy">
                    <span class="inputform"> Ik heb het privacybeleid gelezen en begrepen.</span>
                    </label>

                    <button type="submit" class="submit">Verzenden</button>
                </form>
            </div>
        </div>
    </div>

</section>

<div class="contact-map">
  <iframe
    src="https://www.google.com/maps?q=Handelstraat%2010,%206851%20EH%20Huissen&output=embed"
    loading="lazy"
    allowfullscreen
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</div>

<section class="rechten-section">
    <div class="container">
        <div class="rechten-section-inner">
            <p>© Gerritsen Automotive 2025 Alle Rechten Voorbehouden</p>
        </div>
    </div>
</section>

<script>
/* ===== THEME PRE-APPLY (voorkom flits) ===== */
(() => {
  try {
    const saved = localStorage.getItem('ga_theme');
    if (saved === 'dark') document.documentElement.classList.add('dark');
  } catch (_) {}
})();

/* ===== NAV / MENU / SCROLL ===== */
document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('menuToggle');
  const overlay   = document.getElementById('navOverlay');
  const closeBtn  = document.getElementById('menuClose');
  const navbar    = document.querySelector('.navbar');

  // Header offset (voor fixed header)
  const setHeaderOffset = () => {
    if (!navbar) return;
    document.documentElement.style.setProperty('--header-offset', `${navbar.offsetHeight}px`);
  };

  // Mobiel menu open/dicht
  const openMenu = () => {
    document.body.classList.add('menu-open');
    toggleBtn?.classList.add('is-open');
    toggleBtn?.setAttribute('aria-expanded', 'true');
    overlay?.classList.add('is-open');
    overlay?.setAttribute('aria-hidden', 'false');
    overlay?.querySelector('.nav-mobile a')?.focus({ preventScroll: true });
  };
  const closeMenu = () => {
    document.body.classList.remove('menu-open');
    toggleBtn?.classList.remove('is-open');
    toggleBtn?.setAttribute('aria-expanded', 'false');
    toggleBtn?.focus?.({ preventScroll: true });
    overlay?.classList.remove('is-open');
    overlay?.setAttribute('aria-hidden', 'true');
  };

  toggleBtn?.addEventListener('click', () => {
    overlay?.classList.contains('is-open') ? closeMenu() : openMenu();
  });
  closeBtn?.addEventListener('click', closeMenu);
  overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeMenu(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && overlay?.classList.contains('is-open')) closeMenu(); });

  // Sticky header + offset updaten
  const onScroll = () => {
    if (window.scrollY > 8) navbar?.classList.add('scrolled');
    else navbar?.classList.remove('scrolled');
    setHeaderOffset();
  };

  // Smooth scroll voor ankers in het hoofdmenu (#info, #aanbod, #footer)
  const getOffset = () => {
    const cssVar = getComputedStyle(document.documentElement).getPropertyValue('--header-offset').trim();
    const n = parseInt(cssVar, 10);
    return Number.isFinite(n) ? n : (navbar?.offsetHeight || 0);
  };
  const smoothScrollTo = (targetEl) => {
    const top = Math.max(0, targetEl.getBoundingClientRect().top + window.scrollY - getOffset() - 12);
    window.scrollTo({ top, behavior: 'smooth' });
  };
  const isSamePageHash = (a) => a.hash?.startsWith('#') && a.pathname.replace(/\/+$/,'') === location.pathname.replace(/\/+$/,'');

  document.querySelectorAll('.nav-desktop a[href^="#"], .nav-mobile a[href^="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      if (!isSamePageHash(a)) return;
      const target = document.querySelector(a.hash);
      if (!target) return;
      e.preventDefault();
      if (overlay?.classList.contains('is-open')) closeMenu();
      smoothScrollTo(target);
      history.pushState(null, '', a.hash);
    });
  });

  // Init
  onScroll();
  setHeaderOffset();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', setHeaderOffset);
  window.addEventListener('orientationchange', setHeaderOffset);
  if ('ResizeObserver' in window && navbar) new ResizeObserver(setHeaderOffset).observe(navbar);

  /* ===== THEME: Dark/Light (desktop + mobiel) ===== */
  const STORAGE_KEY = 'ga_theme';

  const applyTheme = (theme) => {
    const dark = theme === 'dark';
    document.documentElement.classList.toggle('dark', dark);
    // aria-pressed sync op beide knoppen
    document.querySelectorAll('#themeToggle, #themeToggleMobile')
      .forEach((btn) => btn.setAttribute('aria-pressed', String(dark)));
  };
  const currentTheme = () => {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved === 'dark' || saved === 'light') return saved;
    } catch (_) {}
    return 'light';
  };
  const toggleTheme = () => {
    const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    try { localStorage.setItem(STORAGE_KEY, next); } catch (_) {}
    applyTheme(next);
  };

  // Theme toepassen bij load (als pre-apply niet draaide) + aria sync
  applyTheme(currentTheme());

  // Eén document-listener (event delegation) → werkt ook als markup verandert
  document.addEventListener('click', (e) => {
    const el = e.target;
    const isToggle =
      (el && (el.id === 'themeToggle' || el.id === 'themeToggleMobile')) ||
      el.closest?.('#themeToggle, #themeToggleMobile');
    if (isToggle) {
      e.preventDefault();
      toggleTheme();
    }
  });
});

/* ===== TABS ===== */
(function(){
  const tabs = Array.from(document.querySelectorAll('.ocd-tab'));
  const panels = {
    specs: document.getElementById('tab-specs'),
    options: document.getElementById('tab-options'),
    desc: document.getElementById('tab-desc'),
  };
  tabs.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      tabs.forEach(b=>b.classList.remove('is-active'));
      Object.values(panels).forEach(p=>p?.classList.remove('is-active'));
      btn.classList.add('is-active');
      panels[btn.dataset.tab]?.classList.add('is-active');
    });
  });
})();

/* ===== OCCASION GALLERY (4 thumbs + prev/next) ===== */
(function(){
  const stage   = document.querySelector('.ocd-stage');
  if (!stage) return;

  const mainImg = document.getElementById('ocdMain');
  const prevBtn = stage.querySelector('.ocd-prev');
  const nextBtn = stage.querySelector('.ocd-next');
  const thumbsEl= document.getElementById('ocdThumbs');

  // Volledige set en de 4 getoonde thumbs (uit data attribuut)
  const urlsAll    = JSON.parse(stage.getAttribute('data-urls') || '[]');
  const urlsThumbs = JSON.parse(stage.getAttribute('data-urls-thumbs') || '[]');

  let current = 0; // index in urlsAll

  const setActiveThumb = (url) => {
    if (!thumbsEl) return;
    thumbsEl.querySelectorAll('.ocd-thumb').forEach(btn => {
      const img = btn.querySelector('img');
      btn.classList.toggle('is-active', img && img.src === url);
    });
  };

  const show = (idx) => {
    if (!urlsAll.length) return;
    current = (idx + urlsAll.length) % urlsAll.length; // wrap
    const url = urlsAll[current];
    if (mainImg) {
      mainImg.src = url;
      mainImg.decoding = 'async';
    }
    setActiveThumb(url);
  };

  // Thumbs click (binnen de 4 zichtbare)
  thumbsEl?.querySelectorAll('.ocd-thumb').forEach((btn, i) => {
    btn.addEventListener('click', () => {
      const url = urlsThumbs[i];
      const idxInAll = urlsAll.indexOf(url);
      show(idxInAll >= 0 ? idxInAll : i);
    });
  });

  // Prev/Next
  prevBtn?.addEventListener('click', () => show(current - 1));
  nextBtn?.addEventListener('click', () => show(current + 1));
})();
</script>

</body>
</html>
