<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $occasion->exists ? 'Auto bewerken' : 'Nieuwe auto' }} — Gerritsen Admin</title>
  <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
  <style>
    :root {
      --px-bg:        {{ setting('theme.bg') }};
      --px-bg-2:      {{ setting('theme.bg_alt') }};
      --px-surface:   {{ setting('theme.surface') }};
      --px-fg:        {{ setting('theme.fg') }};
      --px-fg-muted:  {{ setting('theme.fg_muted') }};
      --px-accent:        {{ setting('theme.accent') }};
      --px-accent-soft:   {{ setting('theme.accent_soft') }};
      --px-border:    {{ setting('theme.border') }};
    }
  </style>
</head>
<body class="adm-fullscreen">

{{-- ============ TOPBAR ============ --}}
<header class="adm-occ-topbar">
  <div class="adm-occ-topbar-inner">
    <a href="{{ route('admin.occasions.index') }}" class="adm-occ-back" aria-label="Terug naar overzicht">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
      <span class="adm-occ-back-tag">Admin</span>
    </a>

    <nav class="adm-occ-breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <span class="adm-occ-bc-sep">/</span>
      <a href="{{ route('admin.occasions.index') }}">Occasions</a>
      <span class="adm-occ-bc-sep">/</span>
      <span>{{ $occasion->exists ? trim(($occasion->merk ?? '').' '.($occasion->model ?? '')) ?: 'Bewerken' : 'Nieuwe auto' }}</span>
    </nav>

    <div class="adm-occ-topbar-actions">
      <span class="adm-progress-mini">
        <span class="adm-progress-mini-bar"><span class="adm-progress-mini-fill" id="admProgressMiniFill"></span></span>
        <b id="admProgressMiniPct">0</b>% compleet
      </span>
      <a href="{{ route('admin.occasions.index') }}" class="btn">Annuleren</a>
      <button form="occasionForm" class="btn primary" type="submit" data-magnetic>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg>
        {{ $occasion->exists ? 'Opslaan' : 'Opslaan & publiceren' }}
      </button>
    </div>
  </div>
</header>

<main class="adm-occ-main">

  {{-- Flash/Errors --}}
  @if (session('success'))
    <div class="alert success">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert error">
      <ul>
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="adm-occ-shell">
  <div class="adm-occ-form-col">

  {{-- ===== HOOFDFORMULIER OPEN ===== --}}
  <form id="occasionForm"
        action="{{ $occasion->exists ? route('admin.occasions.update',$occasion) : route('admin.occasions.store') }}"
        method="post" enctype="multipart/form-data">
    @csrf
    @if($occasion->exists) @method('PUT') @endif

    {{-- ============ KENTEKEN HERO ============ --}}
    <div class="adm-plate-card">
      <span class="form-card-eyebrow">Hoofdstuk 01 · Identificatie</span>
      <h2 style="margin:0 0 6px;font-family:'Plus Jakarta Sans','Inter',sans-serif;font-size:22px;font-weight:700;letter-spacing:-.01em">Voertuig identificeren</h2>
      <p class="form-card-sub" style="margin:0">Vul het kenteken in. Wij halen de basisgegevens automatisch op uit de RDW.</p>

      <div class="adm-plate-row">
        <label class="adm-plate" for="kentekenInput">
          <span class="adm-plate-nl">
            <span class="adm-plate-stars">★★★</span>
            NL
          </span>
          <input type="text" id="kentekenInput" name="kenteken" value="{{ old('kenteken',$occasion->kenteken) }}" placeholder="00-XXX-0" maxlength="10" autocapitalize="characters" spellcheck="false">
        </label>
        <button type="button" class="adm-plate-action" id="rdwBtn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          Haal RDW info op
        </button>
      </div>

      <div class="adm-plate-status" id="rdwStatus"></div>
    </div>

    {{-- ============ BASIS ============ --}}
    <div class="form-card">
      <div class="form-card-head">
        <span class="form-card-eyebrow">Hoofdstuk 02 · Basis</span>
        <h3>Voertuiggegevens</h3>
        <p class="form-card-sub">Velden gemarkeerd met <span class="adm-rdw-tag">RDW</span> worden automatisch gevuld.</p>
      </div>
      <div class="form-card-body grid-2">

        <label class="input-row">
          <span>Merk <span class="adm-rdw-tag">RDW</span></span>
          <input id="merkInput" name="merk" value="{{ old('merk',$occasion->merk) }}" required>
          @error('merk')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <label class="input-row">
          <span>Model <span class="adm-rdw-tag">RDW</span></span>
          <input id="modelInput" name="model" value="{{ old('model',$occasion->model) }}" required>
          @error('model')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <label class="input-row">
          <span>Type / uitvoering</span>
          <input id="typeInput" name="type" value="{{ old('type',$occasion->type) }}" placeholder="bv. 1.4 Comfortline">
        </label>

        <label class="input-row">
          <span>Bouwjaar <span class="adm-rdw-tag">RDW</span></span>
          <input id="bouwjaarInput" type="number" name="bouwjaar" value="{{ old('bouwjaar',$occasion->bouwjaar) }}">
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
          <span>Brandstof <span class="adm-rdw-tag">RDW</span></span>
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
          <span>Tellerstand (km)</span>
          <input id="tellerInput" type="number" name="tellerstand" value="{{ old('tellerstand',$occasion->tellerstand) }}">
          <small class="hint" id="tellerPreview"></small>
        </label>

        <label class="input-row">
          <span>Vermogen (PK)</span>
          <input id="vermogenPkInput" type="number" name="vermogen_pk" value="{{ old('vermogen_pk', $occasion->vermogen_pk ?? '') }}" placeholder="bv. 145">
        </label>

        <label class="input-row">
          <span>Exterieur kleur <span class="adm-rdw-tag">RDW</span></span>
          <input id="kleur" type="text" name="kleur" value="{{ old('kleur', $occasion->kleur ?? '') }}" placeholder="bv. Zwart metallic">
        </label>

        <label class="input-row">
          <span>Carrosserie <span class="adm-rdw-tag">RDW</span></span>
          <input id="carrosserieInput" name="carrosserie" value="{{ old('carrosserie',$occasion->carrosserie) }}" placeholder="bv. Hatchback">
        </label>

        <div class="form-subhead">Prijs</div>

        <label class="input-row">
          <span>Vraagprijs (€)</span>
          <div class="with-addon">
            <span class="addon">€</span>
            <input id="prijsInput" type="number" name="prijs" step="1" min="0" value="{{ old('prijs',$occasion->prijs) }}">
          </div>
          <small class="hint" id="prijsPreview"></small>
        </label>

        <label class="input-row">
          <span>Was-prijs <small style="color:var(--muted);font-weight:400">— bij korting</small></span>
          <div class="with-addon">
            <span class="addon">€</span>
            <input type="number" name="oude_prijs" step="1" min="0" value="{{ old('oude_prijs', $occasion->oude_prijs) }}" placeholder="Laat leeg als geen korting">
          </div>
          <small class="hint">Toont de oude prijs met streep erdoor op de site</small>
        </label>

        <label class="input-row" data-internal style="grid-column: 1 / -1;">
          <span>Inkoopprijs (€) <small style="color:var(--muted);font-weight:400">— intern, nooit publiek zichtbaar</small></span>
          <div class="with-addon">
            <span class="addon">€</span>
            <input id="inkoopInput" type="number" name="inkoop_prijs" step="1" min="0" value="{{ old('inkoop_prijs', $occasion->inkoop_prijs) }}" placeholder="Wat heb je ervoor betaald">
          </div>
          <small class="hint" id="margePreview">Marge wordt automatisch berekend zodra je beide prijzen invult.</small>
        </label>

        <div style="grid-column: 1 / -1" class="adm-binnenkort">
          <input type="checkbox" name="binnenkort" id="binnenkortChk" value="1" {{ old('binnenkort', $occasion->binnenkort) ? 'checked' : '' }}>
          <label for="binnenkortChk" class="adm-binnenkort-text" style="margin:0;cursor:pointer">
            <strong>Binnenkort beschikbaar</strong>
            <span>Toon deze auto op de "Binnenkort"-pagina i.p.v. het reguliere aanbod</span>
          </label>
        </div>

        <label class="input-row">
          <span>Verwachte prijs <small style="color:var(--muted);font-weight:400">— alleen bij "binnenkort"</small></span>
          <div class="with-addon">
            <span class="addon">€</span>
            <input type="number" name="verwachte_prijs" step="1" min="0" value="{{ old('verwachte_prijs', $occasion->verwachte_prijs) }}" placeholder="Verwachte verkoopprijs">
          </div>
        </label>
      </div>
    </div>

{{-- ============ SPECIFICATIES ============ --}}
<div class="form-card">
  <div class="form-card-head">
    <span class="form-card-eyebrow">Hoofdstuk 03 · Detail</span>
    <h3>Specificaties</h3>
    <p class="form-card-sub">Technische details van het voertuig.</p>
  </div>
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
      <span>Cilinderinhoud (cc) <span class="adm-rdw-tag">RDW</span></span>
      <input id="cilinderinhoudInput" type="number" name="cilinderinhoud" value="{{ old('cilinderinhoud',$occasion->cilinderinhoud) }}">
    </label>

    <label class="input-row">
      <span>APK tot <span class="adm-rdw-tag">RDW</span></span>
      <input id="apkTotInput" type="date" name="apk_tot" value="{{ old('apk_tot', optional($occasion->apk_tot)->format('Y-m-d')) }}">
    </label>

    <label class="input-row">
      <span>Energielabel <span class="adm-rdw-tag">RDW</span></span>
      <input id="energielabelInput" name="energielabel" value="{{ old('energielabel',$occasion->energielabel) }}">
    </label>

    <label class="input-row">
      <span>Wegenbelasting per kwartaal</span>
      <input id="wegenbelastingMinInput" name="wegenbelasting_min" value="{{ old('wegenbelasting_min',$occasion->wegenbelasting_min) }}">
    </label>

    <label class="input-row">
      <span>Aantal deuren <span class="adm-rdw-tag">RDW</span></span>
      <input id="aantalDeurenInput" type="number" name="aantal_deuren" value="{{ old('aantal_deuren',$occasion->aantal_deuren) }}">
    </label>

    <label class="input-row">
      <span>Bekleding</span>
      <input id="bekledingInput" name="bekleding" value="{{ old('bekleding',$occasion->bekleding) }}" placeholder="bv. Stof / Leder">
    </label>

    <label class="input-row">
      <span>Aantal cilinders <span class="adm-rdw-tag">RDW</span></span>
      <input id="aantalCilindersInput" type="number" name="aantal_cilinders" value="{{ old('aantal_cilinders',$occasion->aantal_cilinders) }}">
    </label>

    <label class="input-row">
      <span>Topsnelheid (km/u)</span>
      <input id="topsnelheidInput" type="number" name="topsnelheid" value="{{ old('topsnelheid',$occasion->topsnelheid) }}">
    </label>

    <label class="input-row">
      <span>Gewicht (kg) <span class="adm-rdw-tag">RDW</span></span>
      <input id="gewichtInput" type="number" name="gewicht" value="{{ old('gewicht',$occasion->gewicht) }}">
    </label>

    <label class="input-row">
      <span>Laadvermogen (kg) <span class="adm-rdw-tag">RDW</span></span>
      <input id="laadvermogenInput" type="number" name="laadvermogen" value="{{ old('laadvermogen',$occasion->laadvermogen) }}">
    </label>

    <label class="input-row">
      <span>Bijtelling (€)</span>
      <div class="with-addon">
        <span class="addon">€</span>
        <input id="bijtellingInput" type="number" name="bijtelling" step="1" min="0" value="{{ old('bijtelling',$occasion->bijtelling) }}" placeholder="bv. 150">
      </div>
      <small class="hint" id="bijtellingPreview"></small>
    </label>

    <label class="input-row">
      <span>Gem. verbruik (l/100) <span class="adm-rdw-tag">RDW</span></span>
      <input id="gemiddeldVerbruikInput" name="gemiddeld_verbruik" value="{{ old('gemiddeld_verbruik',$occasion->gemiddeld_verbruik) }}">
    </label>
  </div>
</div>


    {{-- ============ OPTIES & OMSCHRIJVING ============ --}}
    <div class="form-card">
      <div class="form-card-head">
        <span class="form-card-eyebrow">Hoofdstuk 04 · Verhaal</span>
        <h3>Opties &amp; omschrijving</h3>
        <p class="form-card-sub">Selecteer aanwezige opties en schrijf een verkooptekst.</p>
      </div>
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

    {{-- ============ AFBEELDINGEN ============ --}}
    <div class="form-card">
      <div class="form-card-head">
        <span class="form-card-eyebrow">Hoofdstuk 05 · Beelden</span>
        <h3>Foto's</h3>
        <p class="form-card-sub">Eerste foto wordt automatisch de cover. Sleep om volgorde te wijzigen.</p>
      </div>
      <div class="form-card-body">
        <div class="form-subhead" style="margin-top:0">Hoofdfoto</div>
        <label class="input-row">
          <input type="file" id="hoofdfoto" name="hoofdfoto" accept="image/*">
          @if($occasion->hoofdfoto_path)
            <div class="photo-preview" style="margin-top:8px;">
              <img src="{{ asset('storage/'.$occasion->hoofdfoto_path) }}" alt="">
            </div>
          @endif
        </label>

        <div class="form-subhead">Galerij</div>

        {{-- Dropzone + knop --}}
        <div id="nu-dropzone" class="nu-drop">
          <div class="nu-drop-inner">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--accent);margin-bottom:4px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
            <strong>Sleep foto's hierheen</strong>
            <span>of <button type="button" class="btn sm" id="nu-browse" style="display:inline-flex;padding:4px 12px;margin-left:4px">kies bestanden</button></span>
            <small class="muted">JPG, PNG · sleep om te herordenen · klik op een kaart om als cover in te stellen</small>
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
    <div class="page-actions">
      <a href="{{ route('admin.occasions.index') }}" class="btn">Annuleren</a>
      <div class="spacer"></div>
      <button class="btn primary" type="submit" name="save" value="1">
        {{ $occasion->exists ? 'Opslaan' : '+ Aanmaken' }}
      </button>
    </div>
  </form>
  {{-- ===== HOOFDFORMULIER SLUIT ===== --}}

  @if($occasion->exists)
    {{-- ============ VERKOCHT-STATUS ============ --}}
    @php
      $isSold = $occasion->is_sold;
    @endphp
    <div class="form-card">
      <div class="form-card-head">
        <span class="form-card-eyebrow">Status</span>
        <h3>{{ $isSold ? 'Verkocht' : 'In voorraad' }}</h3>
        <p class="form-card-sub">
          @if($isSold)
            Auto staat gemarkeerd als verkocht en is niet meer zichtbaar in het publieke aanbod.
          @else
            Markeer als verkocht zodra de auto is verkocht — voor accurate omzet- en marge-rapportage.
          @endif
        </p>
      </div>
      <div class="form-card-body">
        @if($isSold)
          <div class="adm-deflist adm-deflist-2col">
            <dt>Verkocht op</dt>
            <dd>{{ $occasion->verkocht_datum?->format('d-m-Y') ?? 'Onbekend' }}</dd>
            <dt>Verkoopprijs</dt>
            <dd>
              @if($occasion->verkoopprijs)
                <strong>€ {{ number_format($occasion->verkoopprijs, 0, ',', '.') }}</strong>
                @if($occasion->prijs && (float) $occasion->verkoopprijs !== (float) $occasion->prijs)
                  <span style="color:var(--muted);font-size:13px"> (vraagprijs was € {{ number_format($occasion->prijs, 0, ',', '.') }})</span>
                @endif
              @else — @endif
            </dd>
            @if($occasion->gerealiseerde_marge !== null)
              <dt>Gerealiseerde marge</dt>
              <dd>
                <span class="adm-marge {{ $occasion->gerealiseerde_marge >= 0 ? 'adm-marge-pos' : 'adm-marge-neg' }}">
                  {{ $occasion->gerealiseerde_marge >= 0 ? '+' : '' }}€ {{ number_format($occasion->gerealiseerde_marge, 0, ',', '.') }}
                </span>
              </dd>
            @endif
            @if($occasion->dagen_in_voorraad !== null)
              <dt>Tijd in voorraad</dt>
              <dd>{{ $occasion->dagen_in_voorraad }} dagen</dd>
            @endif
            @if($occasion->verkocht_aan)
              <dt>Verkocht aan</dt>
              <dd>{{ $occasion->verkocht_aan }}</dd>
            @endif
          </div>
          <form method="POST" action="{{ route('admin.occasions.toggleStatus', $occasion) }}" onsubmit="return confirm('Weet je zeker dat je deze auto terug naar voorraad wilt zetten?')" style="margin-top:18px;display:flex;justify-content:flex-end">
            @csrf
            <button type="submit" class="btn">↺ Terug naar voorraad</button>
          </form>
        @else
          <form method="POST" action="{{ route('admin.occasions.toggleStatus', $occasion) }}" class="adm-sold-form">
            @csrf
            <div class="form-card-body grid-3" style="padding:0;gap:14px">
              <label class="input-row">
                <span>Verkocht op</span>
                <input type="date" name="verkocht_datum" value="{{ now()->toDateString() }}" required>
              </label>
              <label class="input-row">
                <span>Werkelijke verkoopprijs (€)</span>
                <div class="with-addon">
                  <span class="addon">€</span>
                  <input type="number" name="verkoopprijs" min="0" step="1" value="{{ $occasion->prijs }}" placeholder="Vraagprijs is voorgevuld">
                </div>
                <small class="hint">Pas aan als je voor een andere prijs hebt verkocht</small>
              </label>
              <label class="input-row">
                <span>Verkocht aan <small style="color:var(--muted);font-weight:400">— optioneel</small></span>
                <input type="text" name="verkocht_aan" maxlength="160" placeholder="Naam koper">
              </label>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:14px">
              <button type="submit" class="btn primary" data-magnetic>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg>
                Markeer als verkocht
              </button>
            </div>
          </form>
        @endif
      </div>
    </div>

    {{-- ============ NOTITIES (los van hoofdformulier) ============ --}}
    <div class="form-card">
      <div class="form-card-head">
        <h3>Notities <small style="color:var(--muted);font-weight:400">— intern, alleen voor admin zichtbaar</small></h3>
      </div>
      <div class="form-card-body">
        <form method="POST" action="{{ route('admin.occasions.notes.store', $occasion) }}" class="adm-occ-notes-form">
          @csrf
          <textarea name="body" rows="3" required maxlength="5000" placeholder="Notitie toevoegen… (bijv. opmerkingen van klant, kleine reparaties, onderhandelingsruimte)"></textarea>
          <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn primary">Notitie toevoegen</button>
          </div>
        </form>

        @if($occasion->notes->isEmpty())
          <p class="adm-panel-empty" style="padding:16px 0;margin:0;text-align:left">Nog geen notities. Voeg er eentje toe hierboven.</p>
        @else
          <ul class="adm-notes-list">
            @foreach($occasion->notes as $note)
              <li class="adm-note-item">
                <div class="adm-note-head">
                  <div class="adm-note-body">{{ $note->body }}</div>
                  <form method="POST" action="{{ route('admin.occasions.notes.destroy', [$occasion, $note]) }}" onsubmit="return confirm('Notitie verwijderen?')" class="adm-note-del-form">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn sm danger" title="Verwijderen">×</button>
                  </form>
                </div>
                <div class="adm-note-meta">
                  {{ $note->user?->name ?? 'Admin' }} · {{ $note->created_at->diffForHumans() }}
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    {{-- ============ TAKEN PER AUTO ============ --}}
    <div class="form-card">
      <div class="form-card-head">
        <h3>Taken voor deze auto</h3>
      </div>
      <div class="form-card-body">
        <form method="POST" action="{{ route('admin.tasks.store') }}" class="adm-occ-tasks-form">
          @csrf
          <input type="hidden" name="occasion_id" value="{{ $occasion->id }}">
          <input type="text" name="title" required maxlength="200" placeholder="bijv. Achterruitenwisser vervangen">
          <input type="datetime-local" name="due_at">
          <select name="priority">
            <option value="normal">Normaal</option>
            <option value="high">Hoog</option>
            <option value="low">Laag</option>
          </select>
          <button type="submit" class="btn primary">Toevoegen</button>
        </form>

        @if($occasion->tasks->isEmpty())
          <p class="adm-panel-empty" style="padding:16px 0;margin:0;text-align:left">Nog geen taken voor deze auto.</p>
        @else
          <ul class="adm-list">
            @foreach($occasion->tasks as $task)
              <li class="adm-list-item @if($task->is_overdue && ! $task->is_completed) is-error @endif @if($task->is_completed) is-done @endif">
                <form method="POST" action="{{ route('admin.tasks.toggle', $task) }}" class="adm-occ-task-form">
                  @csrf
                  <button type="submit" class="adm-task-check @if($task->is_completed) is-checked @endif" title="{{ $task->is_completed ? 'Markeer als open' : 'Markeer als gedaan' }}">
                    @if($task->is_completed)<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg>@endif
                  </button>
                </form>
                <div class="adm-list-body">
                  <div class="adm-task-title @if($task->is_completed) is-done @endif">{{ $task->title }}</div>
                  <div class="adm-list-meta @if($task->is_overdue && ! $task->is_completed) adm-list-meta-warn @endif">
                    @if($task->due_at)
                      {{ $task->is_overdue && ! $task->is_completed ? '⚠ ' : '' }}{{ $task->due_at->format('d-m-Y H:i') }} · {{ $task->due_at->diffForHumans() }} ·
                    @endif
                    Prioriteit: {{ ['low'=>'laag','normal'=>'normaal','high'=>'hoog'][$task->priority] ?? $task->priority }}
                  </div>
                </div>
                <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Taak verwijderen?')" class="adm-occ-task-form">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn sm danger" title="Verwijderen">×</button>
                </form>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  @endif

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
          <p class="muted">Nog geen galerijfoto's.</p>
        @endif
      </div>
    </div>
  @endif

  </div> {{-- /adm-occ-form-col --}}

  {{-- ============ LIVE PREVIEW (rechts, sticky) ============ --}}
  <aside class="adm-occ-preview">
    <div class="adm-occ-preview-sticky">
      <div class="adm-occ-preview-head">
        <span class="adm-occ-preview-eyebrow"><span class="adm-dot"></span>Live preview</span>
        <span class="adm-occ-preview-tag">Hoe klanten dit zien</span>
      </div>

      <div class="adm-occ-preview-card">
        <div class="adm-occ-preview-photo">
          <img id="admPreviewPhoto" src="{{ $occasion->hoofdfoto_path ? asset('storage/'.$occasion->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="">
          <div class="adm-occ-preview-photo-empty" id="admPreviewPhotoEmpty" @if($occasion->hoofdfoto_path) hidden @endif>
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            <span>Voeg een hoofdfoto toe</span>
          </div>
        </div>
        <div class="adm-occ-preview-body">
          <h3 class="adm-occ-preview-title" id="admPreviewTitle">{{ trim(($occasion->merk ?? '').' '.($occasion->model ?? '')) ?: 'Auto titel' }}</h3>
          <div class="adm-occ-preview-type" id="admPreviewType">{{ $occasion->type ?? 'Type / uitvoering' }}</div>
          <ul class="adm-occ-preview-meta" id="admPreviewMeta">
            <li>{{ $occasion->bouwjaar ?? '·' }}</li>
            <li>{{ $occasion->tellerstand ? number_format($occasion->tellerstand, 0, ',', '.').' km' : '·' }}</li>
            <li>{{ $occasion->brandstof ? ucfirst($occasion->brandstof) : '·' }}</li>
          </ul>
          <div class="adm-occ-preview-foot">
            <span class="adm-occ-preview-price" id="admPreviewPrice">
              {{ $occasion->prijs ? '€ '.number_format($occasion->prijs, 0, ',', '.') : '€ 0' }}
            </span>
            <span class="adm-occ-preview-arrow">→</span>
          </div>
        </div>
      </div>

      <div class="adm-occ-preview-progress">
        <div class="adm-occ-preview-progress-bar"><div class="adm-occ-preview-progress-fill" id="admPreviewProgressFill"></div></div>
        <div class="adm-occ-preview-progress-meta">
          <span><b id="admPreviewProgressPct">0</b>% compleet</span>
          <span id="admPreviewProgressNext">Begin met kenteken</span>
        </div>
      </div>

      @if($occasion->exists && isset($occasion->marge) && $occasion->marge !== null)
        <div class="adm-occ-preview-marge {{ $occasion->marge >= 0 ? 'is-pos' : 'is-neg' }}">
          <span class="adm-occ-preview-marge-label">Marge (intern)</span>
          <span class="adm-occ-preview-marge-value">
            {{ $occasion->marge >= 0 ? '+' : '' }}€ {{ number_format($occasion->marge, 0, ',', '.') }}
            @if($occasion->marge_percent !== null) <small>({{ $occasion->marge >= 0 ? '+' : '' }}{{ $occasion->marge_percent }}%)</small> @endif
          </span>
        </div>
      @endif
    </div>
  </aside>
  </div> {{-- /adm-occ-shell --}}


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
      rdwStatus.className = 'adm-plate-status is-error';
      rdwStatus.textContent = 'Vul eerst een kenteken in.';
      return;
    }

    rdwStatus.className = 'adm-plate-status';
    rdwStatus.textContent = 'RDW data ophalen…';

    try {
      const url = `{{ route('admin.occasions.rdw', 'KENTEKEN') }}`.replace('KENTEKEN', kenteken);
      const res = await fetch(url);
      const data = await res.json();

      if (!res.ok) {
        rdwStatus.className = 'adm-plate-status is-error';
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


      rdwStatus.className = 'adm-plate-status is-success';
      rdwStatus.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12l5 5L20 7"/></svg> RDW gegevens ingevuld.';
    } catch (e) {
      rdwStatus.className = 'adm-plate-status is-error';
      rdwStatus.textContent = 'RDW ophalen mislukt.';
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

    /* Live marge-berekening (verkoop - inkoop). Alleen intern. */
    const inkoopInput = document.getElementById('inkoopInput');
    const margePreview = document.getElementById('margePreview');
    function updateMargePreview(){
      if (!margePreview) return;
      const verkoop = parseInt(prijsInput?.value || '0', 10);
      const inkoop  = parseInt(inkoopInput?.value || '0', 10);
      if (!inkoop || !verkoop) {
        margePreview.textContent = 'Marge wordt automatisch berekend zodra je beide prijzen invult.';
        margePreview.style.color = '';
        return;
      }
      const marge = verkoop - inkoop;
      const pct = inkoop > 0 ? Math.round((marge / inkoop) * 1000) / 10 : 0;
      const sign = marge >= 0 ? '+' : '';
      margePreview.textContent = `Marge: ${sign}€ ${formatEuro(marge)} (${sign}${pct}%)`;
      margePreview.style.color = marge < 0 ? '#c0392b' : '#1f8f3a';
      margePreview.style.fontWeight = '600';
    }
    if (inkoopInput){ updateMargePreview(); inkoopInput.addEventListener('input', updateMargePreview); prijsInput?.addEventListener('input', updateMargePreview); }

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

<script>
  /* ============================================================
     LIVE PREVIEW + PROGRESS — luistert naar form input
     ============================================================ */
  (function () {
    const titleEl = document.getElementById('admPreviewTitle');
    const typeEl  = document.getElementById('admPreviewType');
    const metaEl  = document.getElementById('admPreviewMeta');
    const priceEl = document.getElementById('admPreviewPrice');
    const photoEl = document.getElementById('admPreviewPhoto');
    const photoEmpty = document.getElementById('admPreviewPhotoEmpty');
    const progressFill = document.getElementById('admPreviewProgressFill');
    const progressPct  = document.getElementById('admPreviewProgressPct');
    const progressNext = document.getElementById('admPreviewProgressNext');
    const miniFill     = document.getElementById('admProgressMiniFill');
    const miniPct      = document.getElementById('admProgressMiniPct');

    const merkInp = document.getElementById('merkInput');
    const modelInp = document.getElementById('modelInput');
    const typeInp = document.getElementById('typeInput');
    const bouwjaarInp = document.getElementById('bouwjaarInput');
    const tellerInp = document.getElementById('tellerInput');
    const brandstofInp = document.getElementById('brandstofSelect');
    const transmissieInp = document.getElementById('transmissieSelect');
    const prijsInp = document.getElementById('prijsInput');
    const hoofdfotoInp = document.getElementById('hoofdfoto');

    function fmt(n){ return new Intl.NumberFormat('nl-NL').format(parseInt(n||0,10)); }

    function update() {
      const merk = merkInp?.value.trim() || '';
      const model = modelInp?.value.trim() || '';
      const type = typeInp?.value.trim() || '';
      const titel = `${merk} ${model}`.trim();

      if (titleEl) titleEl.textContent = titel || 'Auto titel';
      if (typeEl) typeEl.textContent = type || 'Type / uitvoering';

      if (metaEl) {
        const items = [
          bouwjaarInp?.value.trim() || '·',
          tellerInp?.value ? fmt(tellerInp.value) + ' km' : '·',
          brandstofInp?.value || '·',
        ];
        metaEl.innerHTML = items.map(i => `<li>${i}</li>`).join('');
      }

      if (priceEl) {
        priceEl.textContent = prijsInp?.value ? '€ ' + fmt(prijsInp.value) : '€ 0';
      }

      // Progress: tel hoeveel van de "key" velden ingevuld zijn
      const keyFields = [merkInp, modelInp, bouwjaarInp, tellerInp, brandstofInp, transmissieInp, prijsInp];
      const filled = keyFields.filter(f => f?.value && String(f.value).trim() !== '').length;
      const photoOk = !!(photoEl && photoEl.src && !photoEl.src.includes('placeholder')) || (hoofdfotoInp?.files?.length > 0);
      const total = keyFields.length + 1; // +1 voor foto
      const score = filled + (photoOk ? 1 : 0);
      const pct = Math.round((score / total) * 100);

      if (progressFill) progressFill.style.width = pct + '%';
      if (progressPct) progressPct.textContent = pct;
      if (miniFill) miniFill.style.width = pct + '%';
      if (miniPct) miniPct.textContent = pct;

      if (progressNext) {
        if (!merkInp?.value) progressNext.textContent = 'Begin met merk';
        else if (!modelInp?.value) progressNext.textContent = 'Voeg model toe';
        else if (!prijsInp?.value) progressNext.textContent = 'Stel een prijs in';
        else if (!photoOk) progressNext.textContent = 'Voeg een foto toe';
        else if (pct === 100) progressNext.textContent = '✓ Alles compleet';
        else progressNext.textContent = 'Vul de rest in';
      }
    }

    // Listen op alle relevante velden
    [merkInp, modelInp, typeInp, bouwjaarInp, tellerInp, brandstofInp, transmissieInp, prijsInp]
      .forEach(el => el?.addEventListener('input', update));
    [brandstofInp, transmissieInp].forEach(el => el?.addEventListener('change', update));

    // Hoofdfoto preview
    hoofdfotoInp?.addEventListener('change', () => {
      const file = hoofdfotoInp.files?.[0];
      if (file && photoEl) {
        photoEl.src = URL.createObjectURL(file);
        if (photoEmpty) photoEmpty.hidden = true;
      }
      update();
    });

    update();
  })();

  /* ============================================================
     MAGNETIC BUTTONS — subtle scale/translate op hover
     ============================================================ */
  (function () {
    const isFinePointer = window.matchMedia('(pointer: fine)').matches;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!isFinePointer || reduceMotion) return;

    document.querySelectorAll('[data-magnetic]').forEach(btn => {
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        const x = (e.clientX - r.left - r.width / 2) * 0.15;
        const y = (e.clientY - r.top - r.height / 2) * 0.15;
        btn.style.transform = `translate(${x}px, ${y}px)`;
      });
      btn.addEventListener('mouseleave', () => {
        btn.style.transform = '';
      });
    });
  })();
</script>

</main>
</body>
</html>
