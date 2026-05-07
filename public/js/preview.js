/* =========================================================
   GERRITSEN PREVIEW — JS
   ========================================================= */
(() => {
  'use strict';

  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const isFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  /* ---------- NAV scroll state ---------- */
  const nav = $('#pxNav');
  const onScroll = () => {
    if (window.scrollY > 24) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------- Mobile menu ---------- */
  const menuBtn = $('#pxMenuBtn');
  const mobile  = $('#pxMobileMenu');
  if (menuBtn && mobile) {
    menuBtn.addEventListener('click', () => {
      const open = mobile.classList.toggle('open');
      menuBtn.classList.toggle('open', open);
      menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    });
    $$('a', mobile).forEach(a => a.addEventListener('click', () => {
      mobile.classList.remove('open');
      menuBtn.classList.remove('open');
      menuBtn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }));
  }

  /* ---------- Smooth anchor scroll ---------- */
  $$('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id.length < 2) return;
      const t = document.querySelector(id);
      if (!t) return;
      e.preventDefault();
      const y = t.getBoundingClientRect().top + window.scrollY - 80;
      window.scrollTo({ top: y, behavior: 'smooth' });
    });
  });

  /* =========================================================
     SCROLL REVEAL (sections, hero text, cards)
     ========================================================= */
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(en => {
      if (en.isIntersecting) {
        en.target.classList.add('shown');
        revealObserver.unobserve(en.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  $$('.px-reveal').forEach(el => revealObserver.observe(el));

  // staggered card reveal
  const cardObserver = new IntersectionObserver((entries) => {
    entries.forEach((en, i) => {
      if (en.isIntersecting) {
        const idx = Array.from(en.target.parentElement?.children || []).indexOf(en.target);
        en.target.style.transitionDelay = Math.min(idx * 60, 600) + 'ms';
        en.target.classList.add('in');
        cardObserver.unobserve(en.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  $$('.px-card').forEach(el => cardObserver.observe(el));

  /* =========================================================
     TILT ON HOVER (cards, spotlight)
     ========================================================= */
  if (isFinePointer && !reduceMotion) {
    const applyTilt = (el, max = 4) => {
      el.style.transformStyle = 'preserve-3d';
      el.addEventListener('mousemove', (e) => {
        const r = el.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        el.style.transform = `perspective(1000px) rotateY(${x * max}deg) rotateX(${-y * max}deg) translateY(-4px)`;
      });
      el.addEventListener('mouseleave', () => {
        el.style.transform = '';
      });
    };

    $$('.px-card').forEach(c => applyTilt(c, 3));
    const sp = $('.px-spotlight');
    if (sp) applyTilt(sp, 2.5);
  }

  /* =========================================================
     MAGNETIC BUTTONS
     ========================================================= */
  if (isFinePointer && !reduceMotion) {
    $$('[data-magnetic]').forEach(btn => {
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        const x = (e.clientX - r.left - r.width / 2) * 0.25;
        const y = (e.clientY - r.top - r.height / 2) * 0.25;
        btn.style.transform = `translate(${x}px, ${y}px)`;
      });
      btn.addEventListener('mouseleave', () => {
        btn.style.transform = '';
      });
    });
  }

  /* =========================================================
     CUSTOM CURSOR DOT (subtle, hides native cursor)
     ========================================================= */
  if (isFinePointer && !reduceMotion) {
    const cursor = document.createElement('div');
    cursor.className = 'px-cursor';
    document.body.appendChild(cursor);
    document.body.classList.add('px-cursor-on');   // hide native cursor (CSS)

    let cx = window.innerWidth / 2, cy = window.innerHeight / 2;
    let tx = cx, ty = cy;

    document.addEventListener('mousemove', (e) => {
      tx = e.clientX; ty = e.clientY;
    });

    const tick = () => {
      cx += (tx - cx) * 0.22;
      cy += (ty - cy) * 0.22;
      cursor.style.transform = `translate(${cx}px, ${cy}px) translate(-50%, -50%)`;
      requestAnimationFrame(tick);
    };
    tick();

    const hoverables = 'a, button, .px-chip, .px-opt, .px-card, .px-spotlight, input, label';
    document.addEventListener('mouseover', (e) => {
      if (e.target.closest(hoverables)) cursor.classList.add('is-hover');
    });
    document.addEventListener('mouseout', (e) => {
      if (e.target.closest(hoverables)) cursor.classList.remove('is-hover');
    });

    // Restore native cursor when leaving the document (e.g. devtools)
    document.addEventListener('mouseleave', () => cursor.style.opacity = '0');
    document.addEventListener('mouseenter', () => cursor.style.opacity = '1');
  }

  /* =========================================================
     SCROLL PROGRESS BAR
     ========================================================= */
  const scrollProgress = document.createElement('div');
  scrollProgress.className = 'px-scroll-progress';
  document.body.appendChild(scrollProgress);

  const updateScrollProgress = () => {
    const h = document.documentElement;
    const total = h.scrollHeight - h.clientHeight;
    if (total <= 0) return;
    const pct = (h.scrollTop / total) * 100;
    scrollProgress.style.transform = `scaleX(${pct / 100})`;
  };
  window.addEventListener('scroll', updateScrollProgress, { passive: true });
  updateScrollProgress();

  /* =========================================================
     HERO SCROLL PARALLAX (content drifts up + fades)
     ========================================================= */
  if (!reduceMotion) {
    const heroContent = $('.px-hero-content');
    const heroBg = $('.px-hero-bg');
    if (heroContent || heroBg) {
      window.addEventListener('scroll', () => {
        const y = window.scrollY;
        const vh = window.innerHeight;
        if (y > vh) return;
        if (heroContent) {
          heroContent.style.transform = `translateY(${y * 0.25}px)`;
          heroContent.style.opacity   = String(Math.max(0, 1 - y / (vh * 0.7)));
        }
      }, { passive: true });
    }
  }

  /* =========================================================
     FINDER STATE (declared early — slider reads from it)
     ========================================================= */
  const finderState = {
    minBudget: 1500,
    maxBudget: 3000,
    brandstof: '',
    type: '',
    minYear: 0,
  };

  /* =========================================================
     BUDGET SLIDER (dual-range, vanaf → tot)
     ========================================================= */
  const minR = $('#pxBudgetMin');
  const maxR = $('#pxBudgetMax');
  const minV = $('#pxBudgetMinVal');
  const maxV = $('#pxBudgetMaxVal');
  const fill = $('#pxRangeFill');
  const presetBtns = $$('.px-budget-presets button');

  const fmt = (n) => Number(n).toLocaleString('nl-NL');

  const updateRange = (driver) => {
    if (!minR || !maxR) return;

    let lo = parseInt(minR.value, 10);
    let hi = parseInt(maxR.value, 10);

    // Voorkom overlap: min mag max niet voorbij, en omgekeerd
    if (lo > hi) {
      if (driver === 'min') hi = lo;
      else                  lo = hi;
      minR.value = lo;
      maxR.value = hi;
    }

    const min = parseInt(minR.min, 10);
    const max = parseInt(minR.max, 10);
    const lp = ((lo - min) / (max - min)) * 100;
    const hp = ((hi - min) / (max - min)) * 100;

    if (fill) {
      // 12px inset aan beide kanten (matched met CSS .px-range-bg)
      fill.style.left  = `calc(12px + (100% - 24px) * ${lp / 100})`;
      fill.style.width = `calc((100% - 24px) * ${(hp - lp) / 100})`;
    }

    if (minV) minV.textContent = fmt(lo);
    if (maxV) maxV.textContent = fmt(hi);

    finderState.minBudget = lo;
    finderState.maxBudget = hi;

    // Z-index trick: zorg dat de actief gesleepte thumb erbovenop staat
    if (driver === 'min') { minR.style.zIndex = 4; maxR.style.zIndex = 3; }
    if (driver === 'max') { minR.style.zIndex = 3; maxR.style.zIndex = 4; }

    // Presets-state
    presetBtns.forEach(b => {
      const bm = parseInt(b.dataset.min, 10);
      const bx = parseInt(b.dataset.max, 10);
      b.classList.toggle('active', bm === lo && bx === hi);
    });
  };

  if (minR && maxR) {
    minR.addEventListener('input', () => updateRange('min'));
    maxR.addEventListener('input', () => updateRange('max'));
    updateRange();
  }

  presetBtns.forEach(btn => btn.addEventListener('click', () => {
    if (!minR || !maxR) return;
    minR.value = btn.dataset.min;
    maxR.value = btn.dataset.max;
    updateRange();
  }));

  /* =========================================================
     FINDER WIZARD
     ========================================================= */
  const stepEls = $$('.px-finder-step');
  const stepLabels = $$('.px-finder-steps span');
  const progressBar = $('#pxFinderBar');

  let currentStep = 1;
  const totalSteps = 5;

  function goToStep(n) {
    n = Math.max(1, Math.min(totalSteps, n));
    currentStep = n;
    stepEls.forEach(el => {
      el.classList.toggle('px-active', parseInt(el.dataset.step, 10) === n);
    });
    stepLabels.forEach(s => {
      const k = parseInt(s.dataset.s, 10);
      s.classList.toggle('active', k <= n);
    });
    if (progressBar) progressBar.style.setProperty('--progress', ((n / totalSteps) * 100) + '%');
    if (n === totalSteps) renderResults();
  }

  $$('[data-next]').forEach(b => b.addEventListener('click', () => goToStep(currentStep + 1)));
  $$('[data-prev]').forEach(b => b.addEventListener('click', () => goToStep(currentStep - 1)));

  function selectOption(opt) {
    const key = opt.dataset.key;
    const val = opt.dataset.val;
    $$(`.px-opt[data-key="${key}"]`).forEach(o => o.classList.remove('selected'));
    opt.classList.add('selected');
    if (key === 'minYear') finderState.minYear = parseInt(val, 10) || 0;
    else finderState[key] = val;
  }

  $$('.px-opt').forEach(opt => {
    opt.addEventListener('click', () => {
      selectOption(opt);
      const stepEl = opt.closest('.px-finder-step');
      const stepN = parseInt(stepEl.dataset.step, 10);
      if (stepN < 4) {
        setTimeout(() => goToStep(stepN + 1), 220);
      } else if (stepN === 4) {
        setTimeout(() => goToStep(5), 220);
      }
    });
  });

  $('#pxFinderGo')?.addEventListener('click', () => goToStep(5));
  $('#pxFinderRestart')?.addEventListener('click', () => {
    finderState.brandstof = '';
    finderState.type = '';
    finderState.minYear = 0;
    finderState.minBudget = 1500;
    finderState.maxBudget = 3000;
    $$('.px-opt').forEach(o => o.classList.remove('selected'));
    if (minR && maxR) {
      minR.value = 1500; maxR.value = 3000; updateRange();
    }
    goToStep(1);
  });

  /* ---------- Result rendering ---------- */
  let allOccasions = [];
  try {
    allOccasions = JSON.parse(document.getElementById('pxOccasionData').textContent || '[]');
  } catch (e) { allOccasions = []; }

  function scoreOccasion(o) {
    let score = 100;

    // Percentage-based budget penalty (werkt op elke schaal: €1k of €100k segment)
    if (o.prijs < finderState.minBudget && finderState.minBudget > 0) {
      const underPct = ((finderState.minBudget - o.prijs) / finderState.minBudget) * 100;
      score -= Math.min(50, Math.round(underPct * 0.5));
    }
    if (o.prijs > finderState.maxBudget && finderState.maxBudget > 0) {
      const overPct = ((o.prijs - finderState.maxBudget) / finderState.maxBudget) * 100;
      score -= Math.min(70, Math.round(overPct * 0.8));
    }

    if (finderState.brandstof && o.brandstof) {
      if (o.brandstof.toLowerCase() !== finderState.brandstof.toLowerCase()) score -= 25;
    }

    if (finderState.type && o.type) {
      const t = (o.type || '').toLowerCase();
      const wanted = finderState.type.toLowerCase();
      const match =
        (wanted === 'suv' && /(suv|cross)/.test(t)) ||
        (wanted === 'hatchback' && /(hatch|compact|3-?d|5-?d)/.test(t)) ||
        (wanted === 'stationwagon' && /(stat|estate|combi)/.test(t)) ||
        (wanted === 'sedan' && /(sedan|salo)/.test(t)) ||
        (wanted === 'mpv' && /(mpv|monovolume|familie)/.test(t)) ||
        t.includes(wanted);
      if (!match) score -= 15;
    }

    if (finderState.minYear && o.bouwjaar && o.bouwjaar < finderState.minYear) {
      const diff = finderState.minYear - o.bouwjaar;
      score -= Math.min(40, diff * 8);
    }

    if (o.sold) score -= 50;

    return Math.max(0, Math.min(100, score));
  }

  function renderResults() {
    const grid = $('#pxResultGrid');
    const empty = $('#pxResultEmpty');
    const titleEl = $('#pxResultTitle');
    if (!grid) return;

    const scored = allOccasions
      .map(o => ({ ...o, score: scoreOccasion(o) }))
      .sort((a, b) => b.score - a.score);

    const top = scored.filter(o => o.score >= 60).slice(0, 6);
    const usingFallback = top.length === 0;
    const display = usingFallback ? scored.slice(0, 6) : top;

    titleEl.textContent = usingFallback
      ? `Geen perfecte match — ${scored.length} alternatieven`
      : `${top.length} top match${top.length === 1 ? '' : 'es'} voor jou`;

    grid.innerHTML = '';
    empty.hidden = display.length > 0;

    display.forEach(o => {
      const sale = o.oude_prijs && o.oude_prijs > o.prijs;
      const a = document.createElement('a');
      a.className = 'px-card in';
      if (o.sold) a.classList.add('px-card-sold');
      a.href = o.url;
      a.innerHTML = `
        <div class="px-card-photo">
          <img loading="lazy" src="${o.foto}" alt="${o.title}">
          ${o.sold ? '<span class="px-card-badge px-card-badge-sold">Verkocht</span>' : ''}
          ${!o.sold && sale ? '<span class="px-card-badge px-card-badge-sale">Aanbieding</span>' : ''}
          <span class="px-match-badge">${o.score}% match</span>
        </div>
        <div class="px-card-body">
          <h3 class="px-card-title">${o.title}</h3>
          <ul class="px-card-meta">
            <li>${o.bouwjaar || '—'}</li>
            <li>${o.km.toLocaleString('nl-NL')} km</li>
            <li>${o.brandstof || '—'}</li>
            ${o.transmissie ? `<li>${o.transmissie}</li>` : ''}
          </ul>
          <div class="px-card-foot">
            ${sale
              ? `<div class="px-card-prices"><span class="px-card-old">€ ${o.oude_prijs.toLocaleString('nl-NL')}</span><span class="px-card-price px-card-price-sale">€ ${o.prijs.toLocaleString('nl-NL')}</span></div>`
              : `<div class="px-card-price">€ ${o.prijs.toLocaleString('nl-NL')}</div>`
            }
            <span class="px-card-arrow">→</span>
          </div>
        </div>
      `;
      grid.appendChild(a);
    });
  }

  /* =========================================================
     CHIP FILTERS + SEARCH (showcase grid)
     ========================================================= */
  const chips = $$('.px-chip');
  const search = $('#pxSearch');
  const grid = $('#pxGrid');
  const gridEmpty = $('#pxGridEmpty');
  const cards = grid ? Array.from(grid.children) : [];
  let activeFilter = 'all';

  function applyGridFilter() {
    const q = (search?.value || '').trim().toLowerCase();
    let visible = 0;

    cards.forEach(c => {
      let pass = true;

      if (activeFilter !== 'all') {
        if (activeFilter === 'sale') {
          pass = c.dataset.sale === '1';
        } else if (activeFilter.startsWith('brandstof:')) {
          const want = activeFilter.split(':')[1].toLowerCase();
          pass = (c.dataset.brandstof || '').toLowerCase() === want;
        } else if (activeFilter.startsWith('trans:')) {
          const want = activeFilter.split(':')[1].toLowerCase(); // 'auto' of 'hand'
          const t = (c.dataset.trans || '').toLowerCase();
          pass = want === 'auto'
            ? t.includes('auto')
            : (t.includes('hand') || t.includes('schakel'));
        } else if (activeFilter.startsWith('price:')) {
          const [lo, hi] = activeFilter.split(':')[1].split('-').map(Number);
          const p = parseInt(c.dataset.prijs || '0', 10);
          pass = p >= lo && p <= hi;
        }
      }

      if (pass && q) {
        const haystack = `${c.dataset.merk} ${c.dataset.model} ${c.dataset.type}`;
        pass = haystack.includes(q);
      }

      c.classList.toggle('hidden', !pass);
      if (pass) visible++;
    });

    if (gridEmpty) gridEmpty.hidden = visible > 0;
  }

  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      chips.forEach(c => c.classList.remove('px-chip-active', 'active'));
      chip.classList.add('active');
      activeFilter = chip.dataset.filter;
      applyGridFilter();
    });
  });

  if (search) {
    let t;
    search.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(applyGridFilter, 80);
    });
  }

  $('#pxClearFilters')?.addEventListener('click', () => {
    chips.forEach(c => c.classList.remove('active'));
    chips[0].classList.add('px-chip-active');
    activeFilter = 'all';
    if (search) search.value = '';
    applyGridFilter();
  });

})();
