@extends('admin.layout')
@section('title', $occasion->exists ? 'Occasion bewerken' : 'Nieuwe occasion')
@section('page_title', $occasion->exists ? 'Occasion bewerken' : 'Nieuwe occasion')

@section('content')
  {{-- Top actions --}}
  <div class="page-actions">
    <a href="{{ route('admin.occasions.index') }}" class="btn">← Terug naar overzicht</a>
    <div class="spacer"></div>
    <button form="occasionForm" class="btn primary" type="submit">
      {{ $occasion->exists ? 'Opslaan' : 'Aanmaken' }}
    </button>
  </div>

  {{-- Flash/Errors --}}
  @if ($errors->any())
    <div class="alert error" style="margin-bottom:14px;">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  {{-- ===== HOOFDFORMULIER OPEN ===== --}}
  <form id="occasionForm"
        action="{{ $occasion->exists ? route('admin.occasions.update',$occasion) : route('admin.occasions.store') }}"
        method="post" enctype="multipart/form-data">
    @csrf
    @if($occasion->exists) @method('PUT') @endif

    {{-- BASIS --}}
<div class="form-card">
  <div class="form-card-head"><h3>Basis</h3></div>
  <div class="form-card-body grid-2">
    <label class="input-row">
      <span>Merk</span>
      <input id="merkInput" name="merk" value="{{ old('merk',$occasion->merk) }}" required>
      @error('merk')<small class="field-error">{{ $message }}</small>@enderror
    </label>

    <label class="input-row">
      <span>Model</span>
      <input id="modelInput" name="model" value="{{ old('model',$occasion->model) }}" required>
      @error('model')<small class="field-error">{{ $message }}</small>@enderror
    </label>

    <label class="input-row">
      <span>Type</span>
      <input id="typeInput" name="type" value="{{ old('type',$occasion->type) }}">
    </label>

    <label class="input-row">
      <span>Transmissie</span>
      <select id="transmissieSelect" name="transmissie" required>
        <option value="">-- Kies transmissie --</option>
        <option value="Handgeschakeld" {{ old('transmissie', $occasion->transmissie) == 'Handgeschakeld' ? 'selected' : '' }}>Handgeschakeld</option>
        <option value="Automaat" {{ old('transmissie', $occasion->transmissie) == 'Automaat' ? 'selected' : '' }}>Automaat</option>
        <option value="Semi-automaat" {{ old('transmissie', $occasion->transmissie) == 'Semi-automaat' ? 'selected' : '' }}>Semi-automaat</option>
      </select>
    </label>

    <label class="input-row">
      <span>Brandstof</span>
      <select id="brandstofSelect" name="brandstof" required>
        <option value="">-- Kies brandstof --</option>
        <option value="Benzine" {{ old('brandstof', $occasion->brandstof) == 'Benzine' ? 'selected' : '' }}>Benzine</option>
        <option value="Diesel" {{ old('brandstof', $occasion->brandstof) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
        <option value="Elektrisch" {{ old('brandstof', $occasion->brandstof) == 'Elektrisch' ? 'selected' : '' }}>Elektrisch</option>
        <option value="Hybride" {{ old('brandstof', $occasion->brandstof) == 'Hybride' ? 'selected' : '' }}>Hybride</option>
        <option value="LPG" {{ old('brandstof', $occasion->brandstof) == 'LPG' ? 'selected' : '' }}>LPG</option>
      </select>
    </label>

    <label class="input-row">
      <span>Bouwjaar</span>
      <input id="bouwjaarInput" type="number" name="bouwjaar" value="{{ old('bouwjaar',$occasion->bouwjaar) }}">
    </label>

    <label class="input-row">
      <span>Exterieur kleur</span>
      <input id="kleur" type="text" name="kleur" value="{{ old('kleur', $occasion->kleur ?? '') }}" placeholder="Bijv. Zwart metallic">
    </label>

    <label class="input-row">
      <span>Tellerstand (km)</span>
      <input id="tellerInput" type="number" name="tellerstand" value="{{ old('tellerstand',$occasion->tellerstand) }}">
      <small class="hint" id="tellerPreview"></small>
    </label>

    <label class="input-row">
  <span>Vermogen (PK)</span>
  <input id="vermogenPkInput" type="number" name="vermogen_pk"
         value="{{ old('vermogen_pk', $occasion->vermogen_pk ?? '') }}"
         placeholder="bijv. 510">
</label>

    <label class="input-row">
      <span>Prijs (€)</span>
      <div class="with-addon">
        <span class="addon">€</span>
        <input id="prijsInput" type="number" name="prijs" step="1" min="0" value="{{ old('prijs',$occasion->prijs) }}">
      </div>
      <small class="hint" id="prijsPreview"></small>
    </label>

      <label class="input-row" style="grid-column: 1 / -1;">
        <span>Kenteken</span>

        <div style="display:flex; gap:10px; align-items:center;">
          <input name="kenteken"
                value="{{ old('kenteken',$occasion->kenteken) }}"
                id="kentekenInput"
                placeholder="XX-999-X"
                style="flex:1;">
          <button type="button" class="btn sm" id="rdwBtn">Haal RDW info op</button>
        </div>

        <small class="hint">Wordt automatisch in hoofdletters gezet.</small>
        <small class="hint" id="rdwStatus"></small>
      </label>
  </div>
</div>

{{-- SPECIFICATIES --}}
<div class="form-card">
  <div class="form-card-head"><h3>Specificaties</h3></div>
  <div class="form-card-body grid-3">
    <label class="input-row">
      <span>Interieurkleur</span>
      <input id="interieurkleurInput" name="interieurkleur" value="{{ old('interieurkleur',$occasion->interieurkleur) }}">
    </label>

    <label class="input-row">
      <span>BTW/MARGE</span>
      <select id="btwMargeSelect" name="btw_marge">
        <option value="BTW" {{ old('btw_marge', $occasion->btw_marge) == 'BTW' ? 'selected' : '' }}>BTW</option>
        <option value="Marge" {{ old('btw_marge', $occasion->btw_marge) == 'Marge' ? 'selected' : '' }}>Marge</option>
      </select>
    </label>

    <label class="input-row">
      <span>Cilinderinhoud (cc)</span>
      <input id="cilinderinhoudInput" type="number" name="cilinderinhoud" value="{{ old('cilinderinhoud',$occasion->cilinderinhoud) }}">
    </label>

    <label class="input-row">
      <span>Carrosserie</span>
      <input id="carrosserieInput" name="carrosserie" value="{{ old('carrosserie',$occasion->carrosserie) }}">
    </label>


    <label class="input-row">
      <span>APK tot</span>
      <input id="apkTotInput" type="date" name="apk_tot" value="{{ old('apk_tot', optional($occasion->apk_tot)->format('Y-m-d')) }}">
    </label>

    <label class="input-row">
      <span>Energielabel</span>
      <input id="energielabelInput" name="energielabel" value="{{ old('energielabel',$occasion->energielabel) }}">
    </label>

    <label class="input-row">
      <span>Wegenbelasting per kwartaal</span>
      <input id="wegenbelastingMinInput" name="wegenbelasting_min" value="{{ old('wegenbelasting_min',$occasion->wegenbelasting_min) }}">
    </label>

    <label class="input-row">
      <span>Aantal deuren</span>
      <input id="aantalDeurenInput" type="number" name="aantal_deuren" value="{{ old('aantal_deuren',$occasion->aantal_deuren) }}">
    </label>

    <label class="input-row">
      <span>Bekleding</span>
      <input id="bekledingInput" name="bekleding" value="{{ old('bekleding',$occasion->bekleding) }}">
    </label>

    <label class="input-row">
      <span>Aantal cilinders</span>
      <input id="aantalCilindersInput" type="number" name="aantal_cilinders" value="{{ old('aantal_cilinders',$occasion->aantal_cilinders) }}">
    </label>

    <label class="input-row">
      <span>Topsnelheid (km/u)</span>
      <input id="topsnelheidInput" type="number" name="topsnelheid" value="{{ old('topsnelheid',$occasion->topsnelheid) }}">
    </label>

    <label class="input-row">
      <span>Gewicht (kg)</span>
      <input id="gewichtInput" type="number" name="gewicht" value="{{ old('gewicht',$occasion->gewicht) }}">
    </label>

    <label class="input-row">
      <span>Laadvermogen (kg)</span>
      <input id="laadvermogenInput" type="number" name="laadvermogen" value="{{ old('laadvermogen',$occasion->laadvermogen) }}">
    </label>

    <label class="input-row">
      <span>Bijtelling (€)</span>
      <div class="with-addon">
        <span class="addon">€</span>
        <input id="bijtellingInput" type="number" name="bijtelling"
               step="1" min="0"
               value="{{ old('bijtelling',$occasion->bijtelling) }}"
               placeholder="bijv. 150">
      </div>
      <small class="hint" id="bijtellingPreview"></small>
    </label>

    <label class="input-row">
      <span>Gemiddeld verbruik</span>
      <input id="gemiddeldVerbruikInput" name="gemiddeld_verbruik" value="{{ old('gemiddeld_verbruik',$occasion->gemiddeld_verbruik) }}">
    </label>
  </div>
</div>


    {{-- OPTIES & OMSCHRIJVING --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Opties & Omschrijving</h3></div>
      <div class="form-card-body grid-2">
@php
  $selectedExterieur  = old('exterieur_options',  $occasion->exterieur_options  ?? []);
  $selectedInterieur  = old('interieur_options',  $occasion->interieur_options  ?? []);
  $selectedVeiligheid = old('veiligheid_options', $occasion->veiligheid_options ?? []);
  $selectedOverige    = old('overige_options',    $occasion->overige_options    ?? []);

  $labels = [
    'exterieur' => ['title' => 'Exterieur',  'selected' => $selectedExterieur],
    'interieur' => ['title' => 'Interieur',  'selected' => $selectedInterieur],
    'veiligheid'=> ['title' => 'Veiligheid', 'selected' => $selectedVeiligheid],
    'overige'   => ['title' => 'Overige',    'selected' => $selectedOverige],
  ];
@endphp

@foreach($labels as $key => $meta)
  <div class="opt-section">
    <div class="opt-head">
      <h4 class="opt-title">{{ $meta['title'] }}</h4>
      <div class="opt-actions">
        <button type="button" class="opt-btn" data-check="{{ $key }}">Alles</button>
        <button type="button" class="opt-btn" data-uncheck="{{ $key }}">Geen</button>
      </div>
    </div>

    <div class="opt-grid" data-section="{{ $key }}">
      @foreach(($optionLists[$key] ?? []) as $opt)
        <label class="opt-card">
          <input
            class="opt-check"
            type="checkbox"
            name="{{ $key }}_options[]"
            value="{{ $opt }}"
            @checked(in_array($opt, $meta['selected']))
          >
          <span class="opt-text">{{ $opt }}</span>
        </label>
      @endforeach
    </div>
  </div>
@endforeach

        <label class="input-row" style="grid-column:1/-1">
          <span>Omschrijving</span>
          <textarea name="omschrijving" rows="8" placeholder="Schrijf hier de volledige verkooptekst…">{{ old('omschrijving', $occasion->omschrijving) }}</textarea>
          <small class="hint">Je kunt hier een lange tekst kwijt; alinea’s zijn toegestaan.</small>
        </label>
      </div>
    </div>

    {{-- AFBEELDINGEN --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Afbeeldingen</h3></div>
      <div class="form-card-body">
        <h4 class="subhead">Hoofdfoto</h4>
        <label class="input-row">
          <input type="file" id="hoofdfoto" name="hoofdfoto" accept="image/*">
          @if($occasion->hoofdfoto_path)
            <div class="photo-preview" style="margin-top:8px;">
              <img src="{{ asset('storage/'.$occasion->hoofdfoto_path) }}" alt="" style="max-width:360px; width:100%; border-radius:12px; border:1px solid #e5e7eb;">
            </div>
          @endif
        </label>

<h4 class="subhead">Meerdere foto’s</h4>

{{-- Dropzone + knop --}}
<div id="nu-dropzone" class="nu-drop">
  <div class="nu-drop-inner">
    <strong>Sleep bestanden hierheen</strong>
    <span>of</span>
    <button type="button" class="btn sm" id="nu-browse">Kies bestanden</button>
    <small class="muted" style="display:block;margin-top:6px;">
      Sleep om de volgorde te wijzigen. Verwijderen kan per kaart.
    </small>
  </div>
  <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple hidden>
</div>

{{-- Volgorde JSON (oude indices in nieuwe volgorde) --}}
<input type="hidden" name="gallery_new_order" id="galleryNewOrder" value="[]">

{{-- Kaart-grid met thumbnails (nieuw te uploaden) --}}
<div id="nu-grid-wrap" style="display:none;">
  <div id="nu-grid" class="nu-grid"></div>
</div>

    {{-- Bottom actions (in-form) --}}
    <div class="page-actions" style="margin-top:14px;">
      <a href="{{ route('admin.occasions.index') }}" class="btn">Annuleren</a>
      <div class="spacer"></div>
      <button class="btn primary" type="submit" name="save" value="1">
        <span>{{ $occasion->exists ? 'Opslaan' : 'Aanmaken' }}</span>
      </button>
    </div>
  </form>
  {{-- ===== HOOFDFORMULIER SLUIT ===== --}}

  {{-- Bestaande galerij beheren (los van hoofdformulier) --}}
  @php $gallery = $occasion->galerij ?? []; @endphp
  @if($occasion->exists)
    <div class="form-card">
      <div class="form-card-head"><h3>Bestaande galerij</h3></div>
      <div class="form-card-body">
        @if(count($gallery))
          {{-- Sleepbare grid (géén formulier hier, om nested forms te voorkomen) --}}
          <div id="existingGallery" class="gallery-grid sortable-existing">
            @foreach($gallery as $i => $path)
              <div class="g-item sortable-item" data-oldindex="{{ $i }}" draggable="true">
                <span class="drag-handle" aria-hidden="true" title="Sleep om te sorteren">⋮⋮</span>
                <img src="{{ asset('storage/'.$path) }}" alt="galerij">
                <div class="g-actions">
                  @if($occasion->hoofdfoto_path === $path)
                    <span class="badge">Hoofdfoto</span>
                  @else
                    <form action="{{ route('admin.occasions.gallery.cover', [$occasion,$i]) }}" method="post">
                      @csrf
                      <button type="submit" class="btn sm">Maak hoofdfoto</button>
                    </form>
                  @endif
                  <form action="{{ route('admin.occasions.gallery.remove', [$occasion,$i]) }}" method="post" onsubmit="return confirm('Verwijderen?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn sm danger">Verwijderen</button>
                  </form>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Reorder formulier (alleen hidden fields + knop) --}}
          <form action="{{ route('admin.occasions.gallery.reorder', $occasion) }}" method="post" id="galleryReorderForm" style="margin-top:12px;">
            @csrf
            <div id="existingOrderFields"></div>
            <div class="page-actions" style="margin-top:8px;">
              <div class="spacer"></div>
              <button type="submit" class="btn primary">Volgorde opslaan</button>
            </div>
          </form>
        @else
          <p class="muted">Nog geen galerijfoto’s.</p>
        @endif
      </div>
    </div>
  @endif

  
  <script>

    document.addEventListener('click', (e) => {
  const btnCheck = e.target.closest('[data-check]');
  const btnUncheck = e.target.closest('[data-uncheck]');

  if (btnCheck) {
    const key = btnCheck.getAttribute('data-check');
    const section = document.querySelector(`.opt-grid[data-section="${key}"]`);
    section?.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
  }

  if (btnUncheck) {
    const key = btnUncheck.getAttribute('data-uncheck');
    const section = document.querySelector(`.opt-grid[data-section="${key}"]`);
    section?.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
  }
});

     const rdwBtn = document.getElementById('rdwBtn');
  const rdwStatus = document.getElementById('rdwStatus');

  rdwBtn?.addEventListener('click', async () => {
    const raw = document.getElementById('kentekenInput')?.value || '';
    const kenteken = raw.toUpperCase().replace(/[^A-Z0-9]/g, '');
    

    if (!kenteken) {
      rdwStatus.textContent = 'Vul eerst een kenteken in.';
      return;
    }

    rdwStatus.textContent = 'RDW data ophalen...';

    try {
      const url = `{{ route('admin.occasions.rdw', 'KENTEKEN') }}`.replace('KENTEKEN', kenteken);
      const res = await fetch(url);
      const data = await res.json();

        console.log('RDW response:', data);


      if (!res.ok) {
        rdwStatus.textContent = data.message || 'Er ging iets mis.';
        return;
      }

      if (data.merk) document.getElementById('merkInput').value = data.merk;
      if (data.model) document.getElementById('modelInput').value = data.model;
      if (data.type) document.getElementById('typeInput').value = data.type;
      if (data.bouwjaar) document.getElementById('bouwjaarInput').value = data.bouwjaar;
      if (data.kleur) document.getElementById('kleur').value = data.kleur;
      if (data.cilinderinhoud) document.getElementById('cilinderinhoudInput').value = data.cilinderinhoud;
if (data.aantal_cilinders) document.getElementById('aantalCilindersInput').value = data.aantal_cilinders;
if (data.aantal_deuren) document.getElementById('aantalDeurenInput').value = data.aantal_deuren;

if (data.apk_tot) document.getElementById('apkTotInput').value = data.apk_tot;
if (data.energielabel) document.getElementById('energielabelInput').value = data.energielabel;

if (data.gewicht) document.getElementById('gewichtInput').value = data.gewicht;
if (data.laadvermogen) document.getElementById('laadvermogenInput').value = data.laadvermogen;
if (data.max_trekgewicht) document.getElementById('maxTrekgewichtInput').value = data.max_trekgewicht;

if (data.gemiddeld_verbruik) document.getElementById('gemiddeldVerbruikInput').value = data.gemiddeld_verbruik;

// Selects
if (data.brandstof) document.getElementById('brandstofSelect').value = data.brandstof;

// transmissie is meestal niet beschikbaar → alleen invullen als je later een mapping hebt:
if (data.transmissie) document.getElementById('transmissieSelect').value = data.transmissie;

if (data.carrosserie) document.getElementById('carrosserieInput').value = data.carrosserie;
if (data.max_trekgewicht) document.getElementById('maxTrekgewichtInput').value = data.max_trekgewicht;
if (data.topsnelheid) document.getElementById('topsnelheidInput').value = data.topsnelheid;
if (data.energielabel) document.getElementById('energielabelInput').value = data.energielabel;
if (data.carrosserie) document.getElementById('carrosserieInput').value = data.carrosserie;


      rdwStatus.textContent = '✅ RDW gegevens ingevuld.';
    } catch (e) {
      rdwStatus.textContent = '❌ RDW ophalen mislukt.';
    }
  });
    /* ---------------------------
       Helpers voor previews/format
    --------------------------- */
    function formatIntNL(n){
      const x = Number(String(n ?? '').replace(/[^\d]/g,''));
      if (Number.isNaN(x) || x === 0) return '';
      return new Intl.NumberFormat('nl-NL').format(x);
    }
    function formatEuro(n){
      if(n===''||n===null) return '';
      n = Number(n);
      if (isNaN(n)) return '';
      return new Intl.NumberFormat('nl-NL').format(n) + ',-';
    }

    function wireChips(textareaId, chipsId){
      const ta = document.getElementById(textareaId);
      const wrap = document.getElementById(chipsId);
      if(!ta || !wrap) return;
      const render = () => {
        const lines = ta.value.split(/\r?\n/).map(s => s.trim()).filter(Boolean);
        wrap.innerHTML = '';
        lines.forEach(txt => {
          const chip = document.createElement('span');
          chip.className = 'chip';
          chip.textContent = txt;
          wrap.appendChild(chip);
        });
      };
      ta.addEventListener('input', render); render();
    }
    wireChips('exterieurTA','exterieurChips');
    wireChips('interieurTA','interieurChips');
    wireChips('veiligheidTA','veiligheidChips');
    wireChips('overigeTA','overigeChips');

    const kenteken = document.getElementById('kentekenInput');
    if (kenteken) kenteken.addEventListener('input', e => e.target.value = e.target.value.toUpperCase());

    const prijsInput = document.getElementById('prijsInput');
    const prijsPreview = document.getElementById('prijsPreview');
    function updatePrijsPreview(){ prijsPreview.textContent = prijsInput?.value ? 'Voorbeeld: € ' + formatEuro(prijsInput.value) : ''; }
    if (prijsInput){ updatePrijsPreview(); prijsInput.addEventListener('input', updatePrijsPreview); }

    const tellerInput = document.getElementById('tellerInput');
    const tellerPreview = document.getElementById('tellerPreview');
    function updateTellerPreview(){
      if (!tellerInput) return;
      const f = formatIntNL(tellerInput.value);
      tellerPreview.textContent = f ? `Voorbeeld: ${f} km` : '';
    }
    if (tellerInput){ updateTellerPreview(); tellerInput.addEventListener('input', updateTellerPreview); }

    const bijInput = document.getElementById('bijtellingInput');
    const bijPreview = document.getElementById('bijtellingPreview');
    function updateBijPreview(){
      if (!bijInput) return;
      bijPreview.textContent = bijInput.value ? 'Voorbeeld: € ' + formatEuro(bijInput.value) : '';
    }
    if (bijInput){ updateBijPreview(); bijInput.addEventListener('input', updateBijPreview); }

    /* -----------------------------------------
       Drag & Drop - Bestaande galerij (edit)
    ----------------------------------------- */
    function makeSortable(container, onUpdate) {
      container.addEventListener('dragstart', (e) => {
        const item = e.target.closest('.sortable-item');
        if (!item) return;
        item.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', 'drag');
      });

      container.addEventListener('dragend', (e) => {
        const item = e.target.closest('.sortable-item');
        if (!item) return;
        item.classList.remove('dragging');
        onUpdate && onUpdate();
      });

      container.addEventListener('dragover', (e) => {
        e.preventDefault();
        const after = getAfterElement(container, e.clientY);
        const cur = container.querySelector('.dragging');
        if (!cur) return;
        if (!after) container.appendChild(cur);
        else container.insertBefore(cur, after);
      });

      function getAfterElement(container, y) {
        const items = [...container.querySelectorAll('.sortable-item:not(.dragging)')];
        return items.reduce((closest, child) => {
          const box = child.getBoundingClientRect();
          const offset = y - box.top - box.height / 2;
          if (offset < 0 && offset > closest.offset) {
            return { offset, element: child };
          } else {
            return closest;
          }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
      }
    }

    function writeExistingOrder() {
      const grid = document.getElementById('existingGallery');
      const wrap = document.getElementById('existingOrderFields');
      if (!grid || !wrap) return;
      wrap.innerHTML = '';
      [...grid.querySelectorAll('.sortable-item')].forEach((el) => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'order[]';
        inp.value = el.dataset.oldindex; // oude index
        wrap.appendChild(inp);
      });
    }

    const existingGrid = document.getElementById('existingGallery');
    if (existingGrid) {
      makeSortable(existingGrid, writeExistingOrder);
      writeExistingOrder();
    }

  </script>


{{-- SortableJS (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
(function(){

  /* ===========================================================
     Image compressie — verklein foto's vóór upload (iPhone fix)
     =========================================================== */
  const MAX_DIM  = 2000;   // max breedte/hoogte in pixels
  const QUALITY  = 0.80;   // JPEG kwaliteit (0-1)
  const MAX_BYTES = 1024 * 1024; // 1MB — altijd comprimeren als groter

  function compressImage(file) {
    return new Promise((resolve) => {
      // Check of het een afbeelding is (breed matchen — type kan leeg zijn op mobiel)
      const isImage = file.type.startsWith('image/')
        || /\.(jpe?g|png|webp|heic|heif|gif|bmp)$/i.test(file.name);
      if (!isImage) return resolve(file);

      // Kleine bestanden hoeven niet gecomprimeerd
      if (file.size < MAX_BYTES) return resolve(file);

      const reader = new FileReader();
      reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
          let w = img.naturalWidth;
          let h = img.naturalHeight;

          // Schaal berekenen
          if (w > MAX_DIM || h > MAX_DIM) {
            const ratio = Math.min(MAX_DIM / w, MAX_DIM / h);
            w = Math.round(w * ratio);
            h = Math.round(h * ratio);
          }

          const canvas = document.createElement('canvas');
          canvas.width  = w;
          canvas.height = h;
          canvas.getContext('2d').drawImage(img, 0, 0, w, h);

          canvas.toBlob(function(blob) {
            if (!blob || blob.size >= file.size) return resolve(file);
            const ext = file.name.replace(/\.[^.]+$/, '');
            const compressed = new File([blob], ext + '.jpg', {
              type: 'image/jpeg',
              lastModified: Date.now()
            });
            resolve(compressed);
          }, 'image/jpeg', QUALITY);
        };
        img.onerror = function() { resolve(file); };
        img.src = e.target.result;
      };
      reader.onerror = function() { resolve(file); };
      reader.readAsDataURL(file);
    });
  }

  async function compressFiles(fileList) {
    return Promise.all([...fileList].map(f => compressImage(f)));
  }

  /* ===========================================================
     Hoofdfoto — comprimeer bij selectie
     =========================================================== */
  const hoofdfotoInput = document.getElementById('hoofdfoto');
  let hoofdfotoReady = true; // vlag: is compressie klaar?

  if (hoofdfotoInput) {
    hoofdfotoInput.addEventListener('change', async function() {
      if (!this.files || !this.files[0]) return;
      hoofdfotoReady = false;
      const original = this.files[0];
      const compressed = await compressImage(original);
      const dt = new DataTransfer();
      dt.items.add(compressed);
      this.files = dt.files;
      hoofdfotoReady = true;
    });
  }

  /* ===========================================================
     Form submit — wacht tot alle compressie klaar is
     =========================================================== */
  const form = document.getElementById('occasionForm');
  let galleryBusy = false; // vlag: worden er gallery-files gecomprimeerd?

  if (form) {
    form.addEventListener('submit', async function(e) {
      // Als er nog compressie bezig is, blokkeer submit en wacht
      if (!hoofdfotoReady || galleryBusy) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const origText = btn ? btn.textContent : '';
        if (btn) { btn.disabled = true; btn.textContent = 'Foto\'s verkleinen...'; }

        // Wacht max 30 seconden
        let waited = 0;
        while ((!hoofdfotoReady || galleryBusy) && waited < 30000) {
          await new Promise(r => setTimeout(r, 200));
          waited += 200;
        }

        if (btn) { btn.disabled = false; btn.textContent = origText; }
        form.submit();
      }
    });
  }

  /* ===========================================================
     Galerij upload met compressie + sortable + drag-drop
     =========================================================== */
  const input   = document.getElementById('galleryInput');
  const browse  = document.getElementById('nu-browse');
  const dz      = document.getElementById('nu-dropzone');
  const gridWrap= document.getElementById('nu-grid-wrap');
  const grid    = document.getElementById('nu-grid');
  const orderEl = document.getElementById('galleryNewOrder');

  /** @type {File[]} */
  let files = [];
  let urls  = [];

  function showGrid(){
    gridWrap.style.display = files.length ? 'block' : 'none';
  }

  function uniqKey(f){ return [f.name, f.size, f.lastModified].join('::'); }

  async function addFiles(list){
    galleryBusy = true;
    if (dz) dz.classList.add('nu-compressing');

    const compressed = await compressFiles(list);
    const existing = new Set(files.map(uniqKey));
    compressed.forEach(f=>{
      const key = uniqKey(f);
      if (!existing.has(key)) { files.push(f); existing.add(key); }
    });

    if (dz) dz.classList.remove('nu-compressing');
    galleryBusy = false;
    render();
  }

  function removeAt(idx){
    files.splice(idx,1);
    render();
  }

  function clearURLs(){
    urls.forEach(u => URL.revokeObjectURL(u));
    urls = [];
  }

  function render(){
    clearURLs();
    grid.innerHTML = '';
    files.forEach((f, idx)=>{
      const url = URL.createObjectURL(f);
      urls.push(url);
      const card = document.createElement('div');
      card.className = 'nu-item';
      card.dataset.idx = String(idx);
      const sizeKB = Math.round(f.size / 1024);
      const sizeLabel = sizeKB > 1024 ? (sizeKB/1024).toFixed(1)+' MB' : sizeKB+' KB';
      card.innerHTML = `
        <div class="nu-toolbar">
          <button type="button" class="nu-del" title="Verwijderen">&times;</button>
          <span class="nu-handle" title="Sleep om te sorteren">⋮⋮</span>
        </div>
        <div class="nu-thumb"><img src="${url}" alt="${f.name}"></div>
        <div class="nu-meta" title="${f.name}">${f.name} (${sizeLabel})</div>
      `;
      grid.appendChild(card);
    });

    orderEl.value = JSON.stringify(files.map((_,i)=>i));
    showGrid();
    rebuildInputFileList();
  }

  function rebuildInputFileList(){
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    input.files = dt.files;
  }

  // Sortable op grid
  new Sortable(grid, {
    animation: 150,
    handle: '.nu-handle',
    ghostClass: 'nu-ghost',
    onEnd: function(evt){
      if (evt.oldIndex === evt.newIndex) return;
      const moved = files.splice(evt.oldIndex,1)[0];
      files.splice(evt.newIndex,0,moved);
      orderEl.value = JSON.stringify(files.map((_,i)=>i));
      rebuildInputFileList();
      [...grid.children].forEach((el,i)=> el.dataset.idx = String(i));
    }
  });

  // Klik op verwijderen
  grid.addEventListener('click', (e)=>{
    const btn = e.target.closest('.nu-del');
    if (!btn) return;
    const card = btn.closest('.nu-item');
    const idx = parseInt(card.dataset.idx,10);
    removeAt(idx);
    [...grid.children].forEach((el,i)=> el.dataset.idx = String(i));
  });

  // Browse knop
  browse.addEventListener('click', ()=> input.click());

  // Input change
  input.addEventListener('change', ()=> addFiles(input.files));

  // Drag & drop
  ;['dragenter','dragover'].forEach(ev=>{
    dz.addEventListener(ev, (e)=>{ e.preventDefault(); dz.classList.add('nu-dragging'); });
  });
  ;['dragleave','drop'].forEach(ev=>{
    dz.addEventListener(ev, (e)=>{ e.preventDefault(); dz.classList.remove('nu-dragging'); });
  });
  dz.addEventListener('drop', (e)=>{
    if (e.dataTransfer && e.dataTransfer.files) addFiles(e.dataTransfer.files);
  });

})();
</script>

<style>
  .nu-drop {
    border: 2px dashed #d1d5db; border-radius: 12px; padding: 16px; background:#fafafa;
    margin-bottom: 12px; transition: .15s border-color ease;
  }
  .nu-drop.nu-dragging { border-color:#60a5fa; background:#f3f8ff; }
  .nu-drop-inner { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
  .nu-grid {
    display:grid; gap:16px;
    grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
  }
  .nu-item {
    position:relative; background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px;
    padding:10px; display:flex; flex-direction:column; gap:8px;
  }
  .nu-toolbar { display:flex; justify-content:space-between; align-items:center; }
  .nu-handle { cursor:grab; user-select:none; font-weight:700; color:#64748b; }
  .nu-del { background:transparent; border:0; font-size:20px; line-height:20px; cursor:pointer; color:#ef4444; }
  .nu-thumb { display:flex; align-items:center; justify-content:center; background:#fff; border:1px solid #e5e7eb; border-radius:10px; min-height:140px; padding:8px; }
  .nu-thumb img { max-width:100%; max-height:180px; border-radius:8px; display:block; }
  .nu-meta { font-size:.9rem; color:#475569; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; padding:0 2px; }
  .nu-compressing .nu-drop-inner::after { content:'Foto\27s verkleinen...'; display:block; width:100%; color:#6366f1; font-weight:600; margin-top:4px; }
  @media (max-width:640px){ .nu-grid{ grid-template-columns:1fr 1fr; } }
</style>
@endsection
