document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('menuToggle');
  const overlay   = document.getElementById('navOverlay');
  const closeBtn  = document.getElementById('menuClose');
  const navbar    = document.querySelector('.navbar');

  // --- Header offset (voor fixed header) ---
  const setHeaderOffset = () => {
    if (!navbar) return;
    const h = navbar.offsetHeight;
    document.documentElement.style.setProperty('--header-offset', `${h}px`);
  };

  // --- Mobiel menu open/dicht ---
  const openMenu = () => {
    document.body.classList.add('menu-open');
    if (toggleBtn) {
      toggleBtn.classList.add('is-open');
      toggleBtn.setAttribute('aria-expanded', 'true');
    }
    if (overlay) {
      overlay.classList.add('is-open');
      overlay.setAttribute('aria-hidden', 'false');
      const firstLink = overlay.querySelector('.nav-mobile a');
      if (firstLink) firstLink.focus({ preventScroll: true });
    }
  };

  const closeMenu = () => {
    document.body.classList.remove('menu-open');
    if (toggleBtn) {
      toggleBtn.classList.remove('is-open');
      toggleBtn.setAttribute('aria-expanded', 'false');
      toggleBtn.focus?.({ preventScroll: true });
    }
    if (overlay) {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
    }
  };

  toggleBtn?.addEventListener('click', () => {
    const isOpen = overlay?.classList.contains('is-open');
    isOpen ? closeMenu() : openMenu();
  });
  closeBtn?.addEventListener('click', closeMenu);
  overlay?.addEventListener('click', (e) => {
    if (e.target === overlay) closeMenu();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay?.classList.contains('is-open')) closeMenu();
  });

  // --- Sticky header behavior + offset updaten ---
  const onScroll = () => {
    if (!navbar) return;
    if (window.scrollY > 8) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
    setHeaderOffset();
  };

  // --- Smooth scroll met offset ---
  const getOffset = () => {
    const cssVar = getComputedStyle(document.documentElement).getPropertyValue('--header-offset').trim();
    const n = parseInt(cssVar, 10);
    return Number.isFinite(n) ? n : (navbar?.offsetHeight || 0);
  };

  const smoothScrollTo = (targetEl) => {
    const off = getOffset();
    const top = Math.max(0, targetEl.getBoundingClientRect().top + window.scrollY - off - 12);
    window.scrollTo({ top, behavior: 'smooth' });
  };

  const isSamePageHash = (a) => {
    if (!a.hash || !a.hash.startsWith('#')) return false;
    // zelfde path (negeer trailing slash)
    const cur = location.pathname.replace(/\/+$/, '');
    const linkPath = a.pathname.replace(/\/+$/, '');
    return cur === linkPath;
  };

  const handleAnchorClick = (e) => {
    const a = e.currentTarget;
    // Alleen eigen pagina hashes (#info, #aanbod, #footer)
    if (!isSamePageHash(a)) return;

    const target = document.querySelector(a.hash);
    if (!target) return;

    e.preventDefault();

    // Sluit mobiel menu indien open
    if (overlay?.classList.contains('is-open')) closeMenu();

    // Scroll + update URL hash
    smoothScrollTo(target);
    history.pushState(null, '', a.hash);
  };

  // Koppel handlers aan alle anchor-links die met # beginnen (site-breed)
  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener('click', handleAnchorClick);
  });

  // Optioneel: aparte knop die je had in het grid
  const btnBekijkAanbod = document.getElementById('btnBekijkAanbod');
  if (btnBekijkAanbod) {
    btnBekijkAanbod.addEventListener('click', () => {
      const t = document.querySelector('#aanbod');
      if (t) {
        smoothScrollTo(t);
        history.pushState(null, '', '#aanbod');
      }
    });
  }

  // --- Init ---
  onScroll();
  setHeaderOffset();

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', setHeaderOffset);
  window.addEventListener('orientationchange', setHeaderOffset);

  // Recalc offset als de navbar-hoogte dynamisch verandert
  if ('ResizeObserver' in window && navbar) {
    const ro = new ResizeObserver(setHeaderOffset);
    ro.observe(navbar);
  }
});
