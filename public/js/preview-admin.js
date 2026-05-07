/* =========================================================
   GERRITSEN ADMIN — auto toevoegen, smart flow
   ========================================================= */
(() => {
  'use strict';
  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const csrf = () => document.querySelector('meta[name="csrf-token"]').content;

  /* =========================================================
     KENTEKEN LOOKUP — vult ~16 velden in een halve seconde
     ========================================================= */
  const plate     = $('#admPlate');
  const lookupBtn = $('#admLookup');
  const errBox    = $('#admPlateError');
  const sucBox    = $('#admPlateSuccess');
  const filledN   = $('#admFilledCount');
  const foundName = $('#admFoundName');

  if (plate) {
    plate.addEventListener('input', () => {
      plate.value = plate.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
    });
  }

  function showError(msg) {
    errBox.textContent = msg;
    errBox.hidden = false;
    sucBox.hidden = true;
  }
  function hideError() { errBox.hidden = true; }

  async function doLookup() {
    hideError();
    sucBox.hidden = true;
    const raw = (plate.value || '').replace(/[^A-Z0-9]/gi, '').toUpperCase();
    if (raw.length < 4) return showError('Vul een geldig kenteken in.');

    lookupBtn.classList.add('loading');
    lookupBtn.disabled = true;

    try {
      const res = await fetch(`/api/rdw-full/${encodeURIComponent(raw)}`, {
        headers: { 'Accept': 'application/json' }
      });
      if (!res.ok) {
        if (res.status === 404)      showError('Kenteken niet gevonden bij RDW.');
        else if (res.status === 422) showError('Ongeldig kenteken-formaat.');
        else                         showError('RDW even niet bereikbaar.');
        return;
      }
      const data = await res.json();
      const filledCount = countFilled(data) - 1; // -1 want kenteken is geen "ingevuld" veld
      await fillFieldsAnimated(data);

      // Naam mooi formatten
      const naam = [data.merk, data.model].filter(Boolean).join(' ').toLowerCase()
        .replace(/\b\w/g, c => c.toUpperCase());
      const jaar = data.bouwjaar ? ` · ${data.bouwjaar}` : '';
      foundName.textContent = (naam || raw) + jaar;
      filledN.textContent = filledCount;
      sucBox.hidden = false;

      updatePreview();
      maybeSuggestPrice();
    } catch (err) {
      showError('Verbinding mislukt. Probeer opnieuw.');
    } finally {
      lookupBtn.classList.remove('loading');
      lookupBtn.disabled = false;
    }
  }

  if (lookupBtn) lookupBtn.addEventListener('click', doLookup);
  if (plate) plate.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); doLookup(); }
  });

  function countFilled(data) {
    return Object.values(data).filter(v => v !== null && v !== undefined && v !== '').length;
  }

  /* Vul velden met staggered fill-flash animatie */
  async function fillFieldsAnimated(data) {
    const fields = $$('[data-fill]');
    let i = 0;
    for (const f of fields) {
      const key = f.dataset.fill;
      const val = data[key];
      if (val === null || val === undefined || val === '') continue;

      const wrapper = f.closest('.adm-field');
      // Set value
      if (f.tagName === 'SELECT') {
        // Match option case-insensitive
        const opt = Array.from(f.options).find(o => o.value.toLowerCase() === String(val).toLowerCase());
        f.value = opt ? opt.value : '';
      } else {
        f.value = val;
      }
      // Flash animation
      if (wrapper) {
        wrapper.classList.add('adm-just-filled');
        setTimeout(() => wrapper.classList.remove('adm-just-filled'), 1300);
      }
      // Stagger
      await new Promise(r => setTimeout(r, 35));
      i++;
    }
    // Trigger preview updates after all fills
    updatePreview();
  }

  /* =========================================================
     LIVE PREVIEW — alle wijzigingen reflecteren
     ========================================================= */
  const pPhoto    = $('#admPreviewPhoto');
  const pPhotoEmp = $('#admPreviewPhotoEmpty');
  const pTitle    = $('#admPreviewTitle');
  const pType     = $('#admPreviewType');
  const pMeta     = $('#admPreviewMeta');
  const pPrice    = $('#admPreviewPrice');
  const pProgBar  = $('#admPreviewProgress');
  const pProgPct  = $('#admPreviewProgressPct');
  const pProgNxt  = $('#admPreviewProgressNext');

  const form = $('#admForm');
  if (form) {
    form.addEventListener('input', () => {
      updatePreview();
      // Debounced price suggest on bouwjaar/merk/model change
      clearTimeout(window.__pxPriceTimer);
      window.__pxPriceTimer = setTimeout(maybeSuggestPrice, 350);
    });
  }

  function getVal(name) {
    const el = form?.querySelector(`[name="${name}"]`);
    return el ? el.value.trim() : '';
  }

  function updatePreview() {
    const merk      = getVal('merk');
    const model     = getVal('model');
    const type      = getVal('type');
    const bouwjaar  = getVal('bouwjaar');
    const tellerstand = getVal('tellerstand');
    const brandstof = getVal('brandstof');
    const trans     = getVal('transmissie');
    const prijs     = getVal('prijs');

    const titel = (merk + ' ' + model).trim() || 'Auto titel';
    pTitle.textContent = titel;
    pType.textContent = type || 'Type / uitvoering';

    const meta = [];
    if (bouwjaar)    meta.push(bouwjaar);
    if (tellerstand) meta.push(Number(tellerstand).toLocaleString('nl-NL') + ' km');
    if (brandstof)   meta.push(brandstof);
    if (trans)       meta.push(trans);
    pMeta.innerHTML = meta.length
      ? meta.map(m => `<li>${m}</li>`).join('')
      : '<li>—</li><li>—</li><li>—</li>';

    pPrice.textContent = prijs
      ? '€ ' + Number(prijs).toLocaleString('nl-NL')
      : '€ 0';

    // Completeness
    const totalFields = ['merk','model','bouwjaar','tellerstand','brandstof','transmissie','kleur','prijs','beschrijving'];
    const filled = totalFields.filter(n => getVal(n) !== '').length;
    const photosOk = thumbs.length > 0;
    const totalChecks = totalFields.length + 1;
    const passed = filled + (photosOk ? 1 : 0);
    const pct = Math.round((passed / totalChecks) * 100);
    pProgBar.style.width = pct + '%';
    pProgPct.textContent = pct;

    // Volgende stap suggestie
    let nextStep = '';
    if (!getVal('merk')) nextStep = 'Begin met kenteken';
    else if (!photosOk) nextStep = 'Voeg foto\'s toe';
    else if (!getVal('beschrijving')) nextStep = 'Schrijf beschrijving';
    else if (!getVal('prijs')) nextStep = 'Stel een prijs in';
    else nextStep = 'Klaar om te publiceren ✓';
    pProgNxt.textContent = nextStep;

    // Update first photo
    if (thumbs.length > 0 && pPhoto) {
      pPhoto.src = thumbs[0].url;
      pPhoto.classList.add('has-img');
    } else if (pPhoto) {
      pPhoto.classList.remove('has-img');
    }
  }

  /* =========================================================
     DRAG & DROP FOTO'S
     ========================================================= */
  const drop      = $('#admDropzone');
  const fileInput = $('#admFile');
  const browseBtn = $('#admBrowse');
  const thumbsBox = $('#admThumbs');
  const thumbsHint= $('#admThumbsHint');
  const thumbCnt  = $('#admThumbCount');
  const clearBtn  = $('#admClearThumbs');

  let thumbs = []; // [{url, name, file, isCover}]

  if (browseBtn) browseBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.click();
  });
  if (drop) {
    drop.addEventListener('click', () => fileInput.click());
    ['dragenter','dragover'].forEach(evt => drop.addEventListener(evt, (e) => {
      e.preventDefault(); drop.classList.add('over');
    }));
    ['dragleave','drop'].forEach(evt => drop.addEventListener(evt, (e) => {
      e.preventDefault(); drop.classList.remove('over');
    }));
    drop.addEventListener('drop', (e) => {
      const files = Array.from(e.dataTransfer.files || []).filter(f => f.type.startsWith('image/'));
      addFiles(files);
    });
  }
  if (fileInput) fileInput.addEventListener('change', (e) => {
    const files = Array.from(e.target.files || []).filter(f => f.type.startsWith('image/'));
    addFiles(files);
    fileInput.value = '';
  });

  function addFiles(files) {
    files.forEach((f, i) => {
      const url = URL.createObjectURL(f);
      thumbs.push({ url, name: f.name, file: f, isCover: thumbs.length === 0 && i === 0 });
    });
    renderThumbs();
    updatePreview();
  }

  if (clearBtn) clearBtn.addEventListener('click', () => {
    thumbs.forEach(t => URL.revokeObjectURL(t.url));
    thumbs = [];
    renderThumbs();
    updatePreview();
  });

  function renderThumbs() {
    if (!thumbsBox) return;
    if (thumbs.length === 0) {
      thumbsBox.innerHTML = '';
      thumbsHint.hidden = true;
      return;
    }
    // Eerste = cover als geen ander gemarkeerd
    if (!thumbs.some(t => t.isCover)) thumbs[0].isCover = true;

    thumbsBox.innerHTML = thumbs.map((t, i) => `
      <div class="adm-thumb adm-new ${t.isCover ? 'is-cover' : ''}" draggable="true" data-i="${i}">
        <img src="${t.url}" alt="${t.name}">
        <span class="adm-thumb-cover">Cover</span>
        <span class="adm-thumb-num">${String(i+1).padStart(2,'0')}</span>
        <div class="adm-thumb-actions">
          <button type="button" class="adm-thumb-action" data-action="cover" title="Als cover">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          </button>
          <button type="button" class="adm-thumb-action" data-action="delete" title="Verwijder">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          </button>
        </div>
      </div>
    `).join('');

    thumbsHint.hidden = false;
    thumbCnt.textContent = thumbs.length;

    // Wire up actions + drag-and-drop reorder
    $$('.adm-thumb', thumbsBox).forEach(el => {
      const idx = parseInt(el.dataset.i, 10);

      $$('button[data-action]', el).forEach(btn => btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const action = btn.dataset.action;
        if (action === 'delete') {
          URL.revokeObjectURL(thumbs[idx].url);
          thumbs.splice(idx, 1);
          renderThumbs(); updatePreview();
        } else if (action === 'cover') {
          thumbs.forEach(t => t.isCover = false);
          thumbs[idx].isCover = true;
          renderThumbs(); updatePreview();
        }
      }));

      // Click op de thumb (niet op een knop) = set cover
      el.addEventListener('click', () => {
        thumbs.forEach(t => t.isCover = false);
        thumbs[idx].isCover = true;
        renderThumbs(); updatePreview();
      });

      // Drag-and-drop reorder
      el.addEventListener('dragstart', (e) => {
        el.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', idx);
      });
      el.addEventListener('dragend', () => el.classList.remove('dragging'));
      el.addEventListener('dragover', (e) => { e.preventDefault(); });
      el.addEventListener('drop', (e) => {
        e.preventDefault();
        const from = parseInt(e.dataTransfer.getData('text/plain'), 10);
        const to = idx;
        if (from === to) return;
        const [moved] = thumbs.splice(from, 1);
        thumbs.splice(to, 0, moved);
        renderThumbs(); updatePreview();
      });
    });
  }

  /* =========================================================
     AI BESCHRIJVING — typewriter result
     ========================================================= */
  const aiBtn   = $('#admAiGenerate');
  const tones   = $$('.adm-tone');
  const textarea = $('#admBeschrijving');

  let activeTone = 'verkooppunt';
  tones.forEach(t => t.addEventListener('click', () => {
    tones.forEach(x => x.classList.remove('selected'));
    t.classList.add('selected');
    activeTone = t.dataset.tone;
  }));

  if (aiBtn) aiBtn.addEventListener('click', async () => {
    aiBtn.classList.add('loading');
    aiBtn.disabled = true;

    try {
      const opties = $$('input[name="opties[]"]:checked').map(c => c.value);
      const payload = {
        merk: getVal('merk'),
        model: getVal('model'),
        bouwjaar: parseInt(getVal('bouwjaar'), 10) || null,
        tellerstand: parseInt(getVal('tellerstand'), 10) || null,
        brandstof: getVal('brandstof'),
        transmissie: getVal('transmissie'),
        kleur: getVal('kleur'),
        opties: opties,
        tone: activeTone,
      };

      const res = await fetch('/api/preview/ai-describe', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf(),
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });
      if (!res.ok) throw new Error('AI failed');
      const data = await res.json();
      typewriteInto(textarea, data.text || '');
      updatePreview();
    } catch (err) {
      showToast('AI kon nu niet genereren', 'error');
    } finally {
      aiBtn.classList.remove('loading');
      aiBtn.disabled = false;
    }
  });

  function typewriteInto(target, text) {
    target.value = '';
    let i = 0;
    const total = text.length;
    const dur = Math.min(2200, Math.max(800, total * 12));
    const start = performance.now();
    const step = (t) => {
      const progress = Math.min(1, (t - start) / dur);
      const eased = progress;
      const n = Math.floor(eased * total);
      if (n > i) {
        target.value = text.slice(0, n);
        i = n;
      }
      if (progress < 1) requestAnimationFrame(step);
      else target.value = text;
    };
    requestAnimationFrame(step);
  }

  /* =========================================================
     SMART PRICE SUGGEST
     ========================================================= */
  const priceBox = $('#admPriceSuggest');
  const priceTit = $('#admPriceSuggestTitle');
  const priceDet = $('#admPriceSuggestDetail');
  const priceBtn = $('#admPriceApply');
  const priceInp = $('#admPrice');

  let lastSuggest = null;

  async function maybeSuggestPrice() {
    const merk = getVal('merk');
    const model = getVal('model');
    const bouwjaar = parseInt(getVal('bouwjaar'), 10) || 0;
    if (!merk) return;

    const params = new URLSearchParams({ merk });
    if (model) params.set('model', model);
    if (bouwjaar) params.set('bouwjaar', bouwjaar);

    try {
      const res = await fetch(`/api/preview/price-suggest?${params.toString()}`);
      if (!res.ok) return;
      const data = await res.json();

      if (!data.count || data.count === 0) {
        priceTit.textContent = 'Nog geen vergelijkbare data';
        priceDet.textContent = data.message || 'Stel zelf een prijs in.';
        priceBtn.style.display = 'none';
        priceBox.hidden = false;
        return;
      }

      lastSuggest = data;
      priceTit.textContent = `Vergelijkbaar: € ${data.min.toLocaleString('nl-NL')} – € ${data.max.toLocaleString('nl-NL')}`;
      priceDet.textContent = `Op basis van ${data.count} vergelijkbare ${data.count === 1 ? 'auto' : 'auto\'s'} in jullie aanbod · gemiddelde € ${data.avg.toLocaleString('nl-NL')}`;
      priceBtn.style.display = 'inline-block';
      priceBox.hidden = false;
    } catch (err) {
      // silent
    }
  }

  if (priceBtn) priceBtn.addEventListener('click', () => {
    if (!lastSuggest || !priceInp) return;
    priceInp.value = lastSuggest.avg;
    priceInp.dispatchEvent(new Event('input', { bubbles: true }));
    showToast('Gemiddelde prijs ingevuld', 'success');
  });

  /* =========================================================
     TOAST
     ========================================================= */
  const toast = $('#admToast');
  const toastText = $('#admToastText');
  let toastTimer = null;

  function showToast(text, type = 'success') {
    if (!toast) return;
    toastText.textContent = text;
    toast.hidden = false;
    requestAnimationFrame(() => toast.classList.add('show'));
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.hidden = true, 300);
    }, 2400);
  }

  /* =========================================================
     SAVE button (mock)
     ========================================================= */
  $$('.adm-btn-primary').forEach(btn => {
    if (btn.id === 'admLookup') return; // skip de RDW knop
    btn.addEventListener('click', (e) => {
      // Skip als het een AI of price button is
      if (btn.id === 'admAiGenerate') return;
      if (btn.classList.contains('adm-btn-ai')) return;
      e.preventDefault();
      showToast('Demo: zou nu opslaan & publiceren');
    });
  });

  /* =========================================================
     MAGNETIC BUTTONS (zoals op homepage)
     ========================================================= */
  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    $$('[data-magnetic]').forEach(btn => {
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        const x = (e.clientX - r.left - r.width / 2) * 0.2;
        const y = (e.clientY - r.top - r.height / 2) * 0.2;
        btn.style.transform = `translate(${x}px, ${y}px)`;
      });
      btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
    });
  }

  /* Initial preview */
  updatePreview();
})();
