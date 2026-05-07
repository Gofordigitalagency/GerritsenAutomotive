<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin · Auto toevoegen · Preview</title>

    <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/preview-admin.css') }}?v={{ filemtime(public_path('css/preview-admin.css')) }}">
</head>
<body class="adm-body">

{{-- ============ TOPBAR ============ --}}
<header class="adm-topbar">
  <div class="adm-topbar-inner">
    <a href="/preview" class="adm-logo">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
      <span class="adm-logo-tag">Admin</span>
    </a>

    <div class="adm-breadcrumb">
      <a href="#">Occasions</a>
      <span class="adm-bc-sep">/</span>
      <span>Nieuw toevoegen</span>
    </div>

    <div class="adm-topbar-actions">
      <span class="adm-badge-preview">Preview · Concept</span>
      <button type="button" class="adm-btn adm-btn-ghost">Annuleren</button>
      <button type="button" class="adm-btn adm-btn-primary" id="admSave" data-magnetic>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg>
        Opslaan & publiceren
      </button>
    </div>
  </div>
</header>

<main class="adm-main">
  <div class="adm-grid">

    {{-- ============ LEFT: FORM ============ --}}
    <form class="adm-form" id="admForm" autocomplete="off">

      {{-- KENTEKEN HERO --}}
      <section class="adm-section adm-hero">
        <div class="adm-hero-bg" aria-hidden="true"></div>
        <div class="adm-section-head">
          <div class="adm-eyebrow"><span class="adm-dot"></span>Hoofdstuk 01 · Identificatie</div>
          <h2 class="adm-h2">Voertuig <span class="adm-italic-accent">identificeren</span></h2>
          <p class="adm-section-sub">Vul het kenteken in. Wij halen de rest automatisch op uit de RDW-database.</p>
        </div>

        <div class="adm-plate-row">
          <label class="adm-plate" for="admPlate">
            <span class="adm-plate-nl">
              <span class="adm-plate-stars">★★★</span>
              NL
            </span>
            <input type="text" id="admPlate" placeholder="00-XXX-0" maxlength="10" autocapitalize="characters">
          </label>
          <button type="button" class="adm-btn adm-btn-primary adm-btn-lg" id="admLookup" data-magnetic>
            <span class="adm-btn-label">Voertuig ophalen</span>
            <span class="adm-spinner"></span>
            <svg class="adm-btn-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </button>
        </div>
        <div class="adm-plate-error" id="admPlateError" hidden></div>
        <div class="adm-plate-success" id="admPlateSuccess" hidden>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg>
          <strong id="admFoundName"></strong>
          <span class="adm-plate-success-sep">·</span>
          <span><b id="admFilledCount">0</b> velden ingevuld</span>
        </div>
      </section>

      {{-- VOERTUIGGEGEVENS --}}
      <section class="adm-section">
        <div class="adm-section-head">
          <div class="adm-eyebrow"><span class="adm-dot"></span>Hoofdstuk 02 · Specificaties</div>
          <h2 class="adm-h2">Voertuiggegevens</h2>
          <p class="adm-section-sub">Velden gemarkeerd met <span class="adm-rdw-tag">RDW</span> worden automatisch gevuld.</p>
        </div>

        <div class="adm-grid-fields">
          <div class="adm-field"><label>Merk <span class="adm-rdw-tag">RDW</span></label><input type="text" name="merk" data-fill="merk"></div>
          <div class="adm-field"><label>Model <span class="adm-rdw-tag">RDW</span></label><input type="text" name="model" data-fill="model"></div>
          <div class="adm-field"><label>Type / Uitvoering</label><input type="text" name="type" placeholder="bv. 1.4 Comfortline"></div>
          <div class="adm-field"><label>Bouwjaar <span class="adm-rdw-tag">RDW</span></label><input type="number" name="bouwjaar" data-fill="bouwjaar"></div>
          <div class="adm-field"><label>Carrosserie <span class="adm-rdw-tag">RDW</span></label><input type="text" name="carrosserie" data-fill="carrosserie"></div>
          <div class="adm-field"><label>Kleur exterieur <span class="adm-rdw-tag">RDW</span></label><input type="text" name="kleur" data-fill="kleur"></div>
          <div class="adm-field"><label>Aantal deuren <span class="adm-rdw-tag">RDW</span></label><input type="number" name="aantal_deuren" data-fill="aantal_deuren"></div>
          <div class="adm-field"><label>Brandstof <span class="adm-rdw-tag">RDW</span></label>
            <select name="brandstof" data-fill="brandstof">
              <option value="">Kies…</option>
              <option>Benzine</option><option>Diesel</option><option>Hybride</option><option>Elektrisch</option><option>LPG</option>
            </select>
          </div>
          <div class="adm-field"><label>Cilinderinhoud (cc) <span class="adm-rdw-tag">RDW</span></label><input type="number" name="cilinderinhoud" data-fill="cilinderinhoud"></div>
          <div class="adm-field"><label>Aantal cilinders <span class="adm-rdw-tag">RDW</span></label><input type="number" name="aantal_cilinders" data-fill="aantal_cilinders"></div>
          <div class="adm-field"><label>Gewicht ledig (kg) <span class="adm-rdw-tag">RDW</span></label><input type="number" name="gewicht" data-fill="gewicht"></div>
          <div class="adm-field"><label>Vermogen (kW) <span class="adm-rdw-tag">RDW</span></label><input type="number" name="vermogen" data-fill="vermogen"></div>
          <div class="adm-field"><label>CO2-uitstoot (g/km) <span class="adm-rdw-tag">RDW</span></label><input type="number" name="co2_uitstoot" data-fill="co2_uitstoot"></div>
          <div class="adm-field"><label>Verbruik gem. (l/100) <span class="adm-rdw-tag">RDW</span></label><input type="text" name="gemiddeld_verbruik" data-fill="gemiddeld_verbruik"></div>
          <div class="adm-field"><label>Aantal zitplaatsen <span class="adm-rdw-tag">RDW</span></label><input type="number" name="aantal_zitplaatsen" data-fill="aantal_zitplaatsen"></div>
          <div class="adm-field"><label>Max trekgewicht (kg) <span class="adm-rdw-tag">RDW</span></label><input type="number" name="max_trekgewicht" data-fill="max_trekgewicht"></div>
          <div class="adm-field"><label>Energielabel <span class="adm-rdw-tag">RDW</span></label><input type="text" name="energielabel" data-fill="energielabel"></div>
          <div class="adm-field"><label>APK tot <span class="adm-rdw-tag">RDW</span></label><input type="date" name="apk_tot" data-fill="apk_tot"></div>
          <div class="adm-field"><label>Tellerstand (km)</label><input type="number" name="tellerstand" placeholder="bv. 145000"></div>
          <div class="adm-field"><label>Transmissie</label>
            <select name="transmissie">
              <option value="">Kies…</option>
              <option>Handgeschakeld</option><option>Automaat</option>
            </select>
          </div>
          <div class="adm-field"><label>Topsnelheid (km/h)</label><input type="number" name="topsnelheid" placeholder="bv. 180"></div>
          <div class="adm-field"><label>Interieurkleur</label><input type="text" name="interieurkleur"></div>
          <div class="adm-field"><label>Bekleding</label>
            <select name="bekleding">
              <option value="">Kies…</option>
              <option>Stof</option><option>Leder</option><option>Half-leder</option><option>Alcantara</option>
            </select>
          </div>
          <div class="adm-field"><label>BTW-marge</label>
            <select name="btw_marge">
              <option value="">Kies…</option>
              <option>BTW</option><option>Marge</option>
            </select>
          </div>
          <div class="adm-field"><label>Wegenbelasting (min/kw)</label><input type="text" name="wegenbelasting_min"></div>
        </div>
      </section>

      {{-- FOTO'S --}}
      <section class="adm-section">
        <div class="adm-section-head">
          <div class="adm-eyebrow"><span class="adm-dot"></span>Hoofdstuk 03 · Beelden</div>
          <h2 class="adm-h2">Foto's</h2>
          <p class="adm-section-sub">Sleep meerdere foto's tegelijk. Sleep om de volgorde te wijzigen, de eerste foto wordt automatisch cover.</p>
        </div>

        <div class="adm-dropzone" id="admDropzone">
          <input type="file" id="admFile" multiple accept="image/*" hidden>
          <div class="adm-dropzone-content">
            <div class="adm-dropzone-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
            </div>
            <p class="adm-dropzone-title">Sleep foto's hierheen</p>
            <p class="adm-dropzone-sub">of <button type="button" class="adm-link" id="admBrowse">kies bestanden</button> · JPG, PNG, max 10MB per foto</p>
          </div>
        </div>

        <div class="adm-thumbs" id="admThumbs"></div>
        <div class="adm-thumbs-hint" id="admThumbsHint" hidden>
          <span><b id="admThumbCount">0</b> foto's · sleep om te herordenen · klik op een foto voor cover</span>
          <button type="button" class="adm-link" id="admClearThumbs">Alles wissen</button>
        </div>
      </section>

      {{-- BESCHRIJVING --}}
      <section class="adm-section">
        <div class="adm-section-head">
          <div class="adm-eyebrow"><span class="adm-dot"></span>Hoofdstuk 04 · Verhaal</div>
          <h2 class="adm-h2">Verkoopbeschrijving</h2>
          <p class="adm-section-sub">Schrijf zelf, of laat AI een eerste versie genereren op basis van de auto-data.</p>
        </div>

        <div class="adm-ai-tools">
          <button type="button" class="adm-btn adm-btn-primary adm-btn-ai" id="admAiGenerate" data-magnetic>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1l2.4 7.6L22 11l-7.6 2.4L12 21l-2.4-7.6L2 11l7.6-2.4L12 1z"/></svg>
            <span class="adm-btn-label">Genereer met AI</span>
            <span class="adm-spinner"></span>
          </button>
          <div class="adm-tone-picker">
            <span class="adm-tone-label">Tone:</span>
            <button type="button" class="adm-tone selected" data-tone="verkooppunt">Verkooppunt</button>
            <button type="button" class="adm-tone" data-tone="feitelijk">Feitelijk</button>
            <button type="button" class="adm-tone" data-tone="premium">Premium</button>
          </div>
        </div>

        <textarea name="beschrijving" id="admBeschrijving" rows="8" placeholder="Schrijf hier een verkooptekst, of klik op 'Genereer met AI' om automatisch te beginnen…"></textarea>

        <div class="adm-options">
          <span class="adm-options-label">Aanwezige opties (worden meegegeven aan AI):</span>
          <div class="adm-options-chips" id="admOptionChips">
            @php
              $opties = ['Airconditioning','Climate control','Cruise control','Navigatie','Achteruitrijcamera','Parkeersensoren','Stoelverwarming','Lederen bekleding','Lichtmetalen velgen','Trekhaak','Bluetooth','Apple CarPlay','Android Auto','Schuifdak','Xenon','LED-koplampen'];
            @endphp
            @foreach($opties as $opt)
              <label class="adm-option-chip">
                <input type="checkbox" name="opties[]" value="{{ $opt }}">
                <span>{{ $opt }}</span>
              </label>
            @endforeach
          </div>
        </div>
      </section>

      {{-- PRIJS --}}
      <section class="adm-section">
        <div class="adm-section-head">
          <div class="adm-eyebrow"><span class="adm-dot"></span>Hoofdstuk 05 · Prijsbepaling</div>
          <h2 class="adm-h2">Prijs</h2>
          <p class="adm-section-sub">We helpen je de prijs te bepalen op basis van je eigen verkoophistorie.</p>
        </div>

        <div class="adm-price-row">
          <div class="adm-field adm-price-field">
            <label>Vraagprijs</label>
            <div class="adm-price-input">
              <span class="adm-price-symbol">€</span>
              <input type="number" name="prijs" id="admPrice" placeholder="0">
            </div>
          </div>
          <div class="adm-field adm-price-field">
            <label>Was-prijs (optioneel)</label>
            <div class="adm-price-input">
              <span class="adm-price-symbol">€</span>
              <input type="number" name="oude_prijs" placeholder="0">
            </div>
          </div>
        </div>

        <div class="adm-price-suggest" id="admPriceSuggest" hidden>
          <div class="adm-price-suggest-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18M7 14l4-4 4 4 5-5"/></svg>
          </div>
          <div class="adm-price-suggest-body">
            <strong id="admPriceSuggestTitle"></strong>
            <span id="admPriceSuggestDetail"></span>
          </div>
          <button type="button" class="adm-link" id="admPriceApply">Pak gemiddelde</button>
        </div>
      </section>

      <div class="adm-form-foot">
        <button type="button" class="adm-btn adm-btn-ghost">Concept opslaan</button>
        <button type="button" class="adm-btn adm-btn-primary adm-btn-lg" data-magnetic>
          Opslaan & publiceren →
        </button>
      </div>
    </form>

    {{-- ============ RIGHT: LIVE PREVIEW ============ --}}
    <aside class="adm-preview" id="admPreview">
      <div class="adm-preview-sticky">
        <div class="adm-preview-head">
          <span class="adm-preview-eyebrow"><span class="adm-dot"></span>Live preview</span>
          <span class="adm-preview-tag">Hoe klanten dit zien</span>
        </div>

        <div class="adm-preview-card">
          <div class="adm-preview-photo">
            <img id="admPreviewPhoto" src="{{ asset('images/placeholder-car.jpg') }}" alt="">
            <div class="adm-preview-photo-empty" id="admPreviewPhotoEmpty">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
              <span>Voeg foto's toe</span>
            </div>
          </div>
          <div class="adm-preview-body">
            <h3 class="adm-preview-title" id="admPreviewTitle">Auto titel</h3>
            <div class="adm-preview-type" id="admPreviewType">Type / uitvoering</div>
            <ul class="adm-preview-meta" id="admPreviewMeta">
              <li>·</li><li>·</li><li>·</li>
            </ul>
            <div class="adm-preview-foot">
              <span class="adm-preview-price" id="admPreviewPrice">€ 0</span>
              <span class="adm-preview-arrow">→</span>
            </div>
          </div>
        </div>

        <div class="adm-preview-completeness">
          <div class="adm-preview-progress">
            <div class="adm-preview-progress-bar" id="admPreviewProgress"></div>
          </div>
          <div class="adm-preview-progress-meta">
            <span><b id="admPreviewProgressPct">0</b>% compleet</span>
            <span id="admPreviewProgressNext">Begin met kenteken</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</main>

{{-- TOAST --}}
<div class="adm-toast" id="admToast" hidden>
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg>
  <span id="admToastText">Saved</span>
</div>

<script src="{{ asset('js/preview-admin.js') }}?v={{ filemtime(public_path('js/preview-admin.js')) }}" defer></script>
</body>
</html>
