/* app.js – Gerritsen Automotive
   - Mobiel menu (open/close + focus + ESC)
   - Sticky header + smooth scroll met offset
   - Dark mode toggle (desktop + mobiel) met localStorage
*/
'use strict';

/* ========== Theme pre-apply (voorkom flits) ========== */
(() => {
  try {
    const saved = localStorage.getItem('ga_theme');
    if (saved === 'dark') document.documentElement.classList.add('dark');
  } catch (_) {}
})();

document.addEventListener('DOMContentLoaded', () => {
  /* ===========================
     NAV / MENU / SCROLL
  ============================ */
  const toggleBtn = document.getElementById('menuToggle');
  const overlay   = document.getElementById('navOverlay');
  const closeBtn  = document.getElementById('menuClose');
  const navbar    = document.querySelector('.navbar');

  // Header offset variabele bijhouden
  const setHeaderOffset = () => {
    if (!navbar) return;
    const h = navbar.offsetHeight;
    document.documentElement.style.setProperty('--header-offset', `${h}px`);
  };

  // Menu openen/sluiten
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

  // Sticky header + offset
  const onScroll = () => {
    if (!navbar) return;
    if (window.scrollY > 8) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
    setHeaderOffset();
  };

  // Smooth scroll met offset
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
    const cur = location.pathname.replace(/\/+$/, '');
    const linkPath = a.pathname.replace(/\/+$/, '');
    return cur === linkPath;
  };

  const handleAnchorClick = (e) => {
    const a = e.currentTarget;
    if (!isSamePageHash(a)) return;

    const target = document.querySelector(a.hash);
    if (!target) return;

    e.preventDefault();
    if (overlay?.classList.contains('is-open')) closeMenu();
    smoothScrollTo(target);
    history.pushState(null, '', a.hash);
  };

  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener('click', handleAnchorClick);
  });

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

  // Init
  onScroll();
  setHeaderOffset();

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', setHeaderOffset);
  window.addEventListener('orientationchange', setHeaderOffset);

  if ('ResizeObserver' in window && navbar) {
    const ro = new ResizeObserver(setHeaderOffset);
    ro.observe(navbar);
  }

  /* ===========================
     THEME: Dark/Light (robust)
  ============================ */
  const STORAGE_KEY = 'ga_theme';

  // 1) Toepassen huidige theme (als pre-apply niet liep)
  (function applyInitial() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved === 'dark' || saved === 'light') {
        document.documentElement.classList.toggle('dark', saved === 'dark');
      }
    } catch (_) {}
  })();

  // 2) Delegated clicks: werkt altijd, ook als knoppen later wisselen
  function isToggleButton(el) {
    return !!el && (el.id === 'themeToggle' || el.id === 'themeToggleMobile' || el.closest?.('#themeToggle, #themeToggleMobile'));
  }

  function syncAria() {
    const dark = document.documentElement.classList.contains('dark');
    document.querySelectorAll('#themeToggle, #themeToggleMobile').forEach(btn => {
      btn.setAttribute('aria-pressed', String(dark));
    });
  }

  function toggleTheme() {
    const nextDark = !document.documentElement.classList.contains('dark');
    document.documentElement.classList.toggle('dark', nextDark);
    try { localStorage.setItem(STORAGE_KEY, nextDark ? 'dark' : 'light'); } catch(_) {}
    syncAria();
  }

  // Koppel één listener op document (delegation)
  document.addEventListener('click', (e) => {
    const target = e.target;
    if (isToggleButton(target)) {
      e.preventDefault();
      toggleTheme();
    }
  });


const WorkshopWizard = (() => {
  let current = 1;

  function qs(sel, root=document){ return root.querySelector(sel); }
  function qsa(sel, root=document){ return Array.from(root.querySelectorAll(sel)); }

  function open(){
    const ov = qs('#ws-overlay');
    ov.classList.add('is-open');
    ov.setAttribute('aria-hidden','false');
    current = 1;
    show(current);
    sync();
    document.body.style.overflow = 'hidden';
  }

  function close(e){
    if (e && e.target && !e.target.classList.contains('ws-overlay')) {
      // close called from button without overlay click
    }
    const ov = qs('#ws-overlay');
    ov.classList.remove('is-open');
    ov.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  function show(step){
    current = step;

    // panels
    qsa('.ws-panel').forEach(p => p.classList.remove('is-active'));
    const panel = qs(`.ws-panel[data-panel="${step}"]`);
    if(panel) panel.classList.add('is-active');

    // stepper
    qsa('.ws-step').forEach(s => s.classList.remove('is-active'));
    const st = qs(`.ws-step[data-step="${step}"]`);
    if(st) st.classList.add('is-active');

    // scroll modal top a bit for UX
    const modal = qs('.ws-modal');
    if(modal) modal.scrollTop = 0;
  }

  function next(){
    if(current < 4) show(current + 1);
    sync();
  }

  function prev(){
    if(current > 1) show(current - 1);
    sync();
  }

  function sync(){
    const f = qs('#ws-form');
    if(!f) return;

    const plate = (f.license_plate?.value || '').trim() || '-';
    const mileage = (f.mileage?.value || '').trim();
    const km = mileage ? `${Number(mileage).toLocaleString('nl-NL')} km` : '-';

    const maintenance = (f.querySelector('input[name="maintenance_option"]:checked')?.value) || '-';

    const extras = qsa('input[name="extra_services[]"]:checked', f).map(x => x.value);
    const extrasTxt = extras.length ? extras.join(', ') : '-';

    const date = (f.appointment_date?.value || '').trim();
    const time = (f.appointment_time?.value || '').trim();
    const dt = (date && time) ? `${date} ${time}` : '-';

    const waitVal = (f.querySelector('input[name="wait_while_service"]:checked')?.value);
    const waitTxt = waitVal === "1" ? "Ja" : (waitVal === "0" ? "Nee" : "-");

    qs('#sum-license').textContent = plate.toUpperCase();
    qs('#sum-mileage').textContent = km;
    qs('#sum-maintenance').textContent = maintenance;
    qs('#sum-extras').textContent = extrasTxt;
    qs('#sum-datetime').textContent = dt;
    qs('#sum-wait').textContent = waitTxt;
  }

  return { open, close, next, prev, sync };
})();



  // Sync aria state bij load
  syncAria();
});
