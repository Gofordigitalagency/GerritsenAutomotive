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
          <input name="merk" value="{{ old('merk',$occasion->merk) }}" required>
          @error('merk')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <label class="input-row">
          <span>Model</span>
          <input name="model" value="{{ old('model',$occasion->model) }}" required>
          @error('model')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <label class="input-row">
          <span>Type</span>
          <input name="type" value="{{ old('type',$occasion->type) }}">
        </label>

        <label class="input-row">
          <span>Transmissie</span>
          <input name="transmissie" value="{{ old('transmissie',$occasion->transmissie) }}" placeholder="Handgeschakeld / Automaat" required>
        </label>

        <label class="input-row">
          <span>Brandstof</span>
          <input name="brandstof" value="{{ old('brandstof',$occasion->brandstof) }}" required>
        </label>

        <label class="input-row">
          <span>Bouwjaar</span>
          <input type="number" name="bouwjaar" value="{{ old('bouwjaar',$occasion->bouwjaar) }}">
        </label>

        <label class="input-row">
          <span>Exterieur kleur</span>
          <input id="kleur" type="text" name="kleur" value="{{ old('kleur', $occasion->kleur ?? '') }}"placeholder="Bijv. Zwart metallic">
        </label>
        <label class="input-row">
          <span>Tellerstand (km)</span>
          <input type="number" name="tellerstand" value="{{ old('tellerstand',$occasion->tellerstand) }}" id="tellerInput">
          <small class="hint" id="tellerPreview"></small>
        </label>

        <label class="input-row">
          <span>Prijs (€)</span>
          <div class="with-addon">
            <span class="addon">€</span>
            <input type="number" name="prijs" step="1" min="0" value="{{ old('prijs',$occasion->prijs) }}" id="prijsInput">
          </div>
          <small class="hint" id="prijsPreview"></small>
        </label>

        <label class="input-row">
          <span>Kenteken</span>
          <input name="kenteken" value="{{ old('kenteken',$occasion->kenteken) }}" id="kentekenInput" placeholder="XX-999-X">
          <small class="hint">Wordt automatisch in hoofdletters gezet.</small>
        </label>
      </div>
    </div>

    {{-- SPECIFICATIES --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Specificaties</h3></div>
      <div class="form-card-body grid-3">
        <label class="input-row">
          <span>Interieurkleur</span>
          <input name="interieurkleur" value="{{ old('interieurkleur',$occasion->interieurkleur) }}">
        </label>

        <label class="input-row">
          <span>BTW/MARGE</span>
          <input name="btw_marge" value="{{ old('btw_marge',$occasion->btw_marge) }}">
        </label>

        <label class="input-row">
          <span>Cilinderinhoud (cc)</span>
          <input type="number" name="cilinderinhoud" value="{{ old('cilinderinhoud',$occasion->cilinderinhoud) }}">
        </label>

        <label class="input-row">
          <span>Carrosserie</span>
          <input name="carrosserie" value="{{ old('carrosserie',$occasion->carrosserie) }}">
        </label>

        <label class="input-row">
          <span>Max. trekgewicht (kg)</span>
          <input type="number" name="max_trekgewicht" value="{{ old('max_trekgewicht',$occasion->max_trekgewicht) }}">
        </label>

        <label class="input-row">
          <span>APK tot</span>
          <input type="date" name="apk_tot" value="{{ old('apk_tot', optional($occasion->apk_tot)->format('Y-m-d')) }}">
        </label>

        <label class="input-row">
          <span>Energielabel</span>
          <input name="energielabel" value="{{ old('energielabel',$occasion->energielabel) }}">
        </label>

        <label class="input-row">
          <span>Wegenbelasting min</span>
          <input name="wegenbelasting_min" value="{{ old('wegenbelasting_min',$occasion->wegenbelasting_min) }}">
        </label>

        <label class="input-row">
          <span>Aantal deuren</span>
          <input type="number" name="aantal_deuren" value="{{ old('aantal_deuren',$occasion->aantal_deuren) }}">
        </label>

        <label class="input-row">
          <span>Bekleding</span>
          <input name="bekleding" value="{{ old('bekleding',$occasion->bekleding) }}">
        </label>

        <label class="input-row">
          <span>Aantal cilinders</span>
          <input type="number" name="aantal_cilinders" value="{{ old('aantal_cilinders',$occasion->aantal_cilinders) }}">
        </label>

        <label class="input-row">
          <span>Topsnelheid (km/u)</span>
          <input type="number" name="topsnelheid" value="{{ old('topsnelheid',$occasion->topsnelheid) }}">
        </label>

        <label class="input-row">
          <span>Gewicht (kg)</span>
          <input type="number" name="gewicht" value="{{ old('gewicht',$occasion->gewicht) }}">
        </label>

        <label class="input-row">
          <span>Laadvermogen (kg)</span>
          <input type="number" name="laadvermogen" value="{{ old('laadvermogen',$occasion->laadvermogen) }}">
        </label>

        <label class="input-row">
          <span>Bijtelling (€)</span>
          <div class="with-addon">
            <span class="addon">€</span>
            <input type="number" name="bijtelling" id="bijtellingInput"
                  step="1" min="0"
                  value="{{ old('bijtelling',$occasion->bijtelling) }}"
                  placeholder="bijv. 150">
          </div>
          <small class="hint" id="bijtellingPreview"></small>
        </label>


        <label class="input-row">
          <span>Gemiddeld verbruik</span>
          <input name="gemiddeld_verbruik" value="{{ old('gemiddeld_verbruik',$occasion->gemiddeld_verbruik) }}">
        </label>
      </div>
    </div>

    {{-- OPTIES & OMSCHRIJVING --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Opties & Omschrijving</h3></div>
      <div class="form-card-body grid-2">
        <label class="input-row">
          <span>Exterieur (1 per regel)</span>
          <textarea name="exterieur_options_text" rows="6" id="exterieurTA"
            placeholder="Metallic lak&#10;Lichtmetalen velgen&#10;LED dagrijverlichting">@php
echo old('exterieur_options_text', collect($occasion->exterieur_options ?? [])->implode("\n"));
@endphp</textarea>
          <small class="hint">Tip: één optie per regel. Hieronder zie je een preview.</small>
          <div class="chips" id="exterieurChips"></div>
        </label>

        <label class="input-row">
          <span>Interieur (1 per regel)</span>
          <textarea name="interieur_options_text" rows="6" id="interieurTA"
            placeholder="Airco&#10;Cruise control&#10;Stoelverwarming">@php
echo old('interieur_options_text', collect($occasion->interieur_options ?? [])->implode("\n"));
@endphp</textarea>
          <div class="chips" id="interieurChips"></div>
        </label>

        <label class="input-row">
          <span>Veiligheid (1 per regel)</span>
          <textarea name="veiligheid_options_text" rows="6" id="veiligheidTA"
            placeholder="ABS&#10;ESP&#10;Achteruitrijcamera">@php
echo old('veiligheid_options_text', collect($occasion->veiligheid_options ?? [])->implode("\n"));
@endphp</textarea>
          <div class="chips" id="veiligheidChips"></div>
        </label>

        <label class="input-row">
          <span>Overige (1 per regel)</span>
          <textarea name="overige_options_text" rows="6" id="overigeTA"
            placeholder="Trekhaak&#10;Bluetooth&#10;Boekjes aanwezig">@php
echo old('overige_options_text', collect($occasion->overige_options ?? [])->implode("\n"));
@endphp</textarea>
          <div class="chips" id="overigeChips"></div>
        </label>

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
        <label class="input-row">
          <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple>
          <small class="hint">Bestanden worden toegevoegd bij <b>Opslaan</b>. (Alleen bestandsnamen zichtbaar)</small>
        </label>

        {{-- Lijst met bestandsnamen (geen grote previews) --}}
        <div id="galleryNewPreview" style="display:none;"></div>
      </div>
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

  {{-- Bestaande galerij beheren (los van hoofdformulier, voorkomt nested forms) --}}
  @php $gallery = $occasion->galerij ?? []; @endphp
  @if($occasion->exists)
    <div class="form-card">
      <div class="form-card-head"><h3>Bestaande galerij</h3></div>
      <div class="form-card-body">
        @if(count($gallery))
          <div class="gallery-grid">
            @foreach($gallery as $i => $path)
              <div class="g-item">
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
        @else
          <p class="muted">Nog geen galerijfoto’s.</p>
        @endif
      </div>
    </div>
  @endif

  <script>
    // ---------- Helpers ----------
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

    // ---------- Chips preview ----------
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

    // ---------- Kenteken auto-uppercase ----------
    const kenteken = document.getElementById('kentekenInput');
    if (kenteken) kenteken.addEventListener('input', e => e.target.value = e.target.value.toUpperCase());

    // ---------- Prijs preview ----------
    const prijsInput = document.getElementById('prijsInput');
    const prijsPreview = document.getElementById('prijsPreview');
    function updatePrijsPreview(){ prijsPreview.textContent = prijsInput?.value ? 'Voorbeeld: € ' + formatEuro(prijsInput.value) : ''; }
    if (prijsInput){ updatePrijsPreview(); prijsInput.addEventListener('input', updatePrijsPreview); }

    // ---------- Tellerstand preview "123.456 km" ----------
    const tellerInput = document.getElementById('tellerInput');
    const tellerPreview = document.getElementById('tellerPreview');
    function updateTellerPreview(){
      if (!tellerInput) return;
      const f = formatIntNL(tellerInput.value);
      tellerPreview.textContent = f ? `Voorbeeld: ${f} km` : '';
    }
    if (tellerInput){ updateTellerPreview(); tellerInput.addEventListener('input', updateTellerPreview); }

    // ---------- Bijtelling preview "10%" ----------
    // Bijtelling preview "€ 150,-"
    const bijInput = document.getElementById('bijtellingInput');
    const bijPreview = document.getElementById('bijtellingPreview');
    function updateBijPreview(){
      if (!bijInput) return;
      bijPreview.textContent = bijInput.value ? 'Voorbeeld: € ' + formatEuro(bijInput.value) : '';
    }
    if (bijInput){ updateBijPreview(); bijInput.addEventListener('input', updateBijPreview); }


    // ---------- Nieuwe galerij-bestanden: bestandsnamenlijst ----------
    const gi = document.getElementById('galleryInput');
    const gp = document.getElementById('galleryNewPreview');
    if (gi) {
      gi.addEventListener('change', () => {
        gp.innerHTML = '';
        if (!gi.files || gi.files.length === 0) { gp.style.display='none'; return; }
        gp.style.display = 'flex';
        [...gi.files].forEach(file => {
          const row = document.createElement('div');
          row.className = 'file-row';
          row.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 4v16m8-8H4"/></svg>
            <span>${file.name}</span>
          `;
          gp.appendChild(row);
        });
      });
    }
  </script>
@endsection
