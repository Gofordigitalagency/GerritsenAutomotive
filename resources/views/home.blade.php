@extends('layout')

@section('content')

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Uw Partner in</h1>
            <h1>BETROUWBARE OCCASIONS</h1>
            <p>Bij Gerritsen Automotive vindt u zorgvuldig geselecteerde auto’s, persoonlijke service en eerlijk advies.</p>
            <div class="hero-buttons">
                <a href="#aanbod" class="btn btn-primary">Bekijk Occasions</a>
                <a href="#footer" class="btn btn-secondary">Contact</a>
            </div>
        </div>
    </div>
</section>

<section id="info" class="info-section">
    <div class="container">
        <div class="info-section-inner">

            <div class="info-image">
                <img src="{{ asset('images/handshake.jpg') }}" alt="Handdruk">
            </div>

            <div class="info-content">
                <h1>Gerritsen Automotive</h1>
                <p>Gerritsen Automotive is jouw vertrouwde adres in Arnhem voor advies, verkoop van geselecteerde occasions en praktische verhuur.</p>
                <p>We houden het persoonlijk en helder: duidelijke prijzen, transparante informatie en snel schakelen. Of je nu vandaag wil proefrijden of iets wilt huren, we regelen het vlot en zonder gedoe. Klaar om te rijden? We denken met je mee.</p>
               <div class="btn-aanbod">
                <a href="#aanbod" class="btn btn-primary">Bekijk Onze Aanbod</a>
               </div>
            </div>

        </div>
    </div>
</section>

<section id="aanbod" class="nieuw-binnen">
  <div class="container">
    <h2 class="sectie-titel">Nieuw bij ons binnen</h2>

    <div id="nieuwGrid" class="cards-grid">
      @foreach($nieuw as $i => $car)
        <a class="car-card {{ $i > 2 ? 'is-hidden' : '' }}" href="{{ route('occasions.show', $car->slug) }}">
          <div class="car-photo">
            <img src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="{{ $car->titel }}">
          </div>

          <div class="car-info">
            @php
              // Bouw "Merk + Model"
              $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));

              // Type (optioneel)
              $type = $car->type ?? '';

              // Fallback: als merk+model leeg is, probeer het uit de titel te halen
              if ($merkModel === '' && !empty($car->titel)) {
                  $titel = trim($car->titel);
                  // Als we een type hebben, strip het aan het eind van de titel
                  if ($type) {
                      $merkModel = trim(preg_replace('/\s*' . preg_quote($type, '/') . '\s*$/i', '', $titel));
                  } else {
                      $merkModel = $titel;
                  }
              }
              if ($merkModel === '') { $merkModel = $car->titel; } // ultieme fallback
            @endphp

            {{-- Alleen merk + model als titel --}}
            <h3 class="car-title">{{ $merkModel }}</h3>

            {{-- Type eronder, kleiner --}}
            @if(!empty($type))
              <div class="car-type">{{ $type }}</div>
            @endif

            <div class="car-meta">
              <span>{{ ucfirst($car->transmissie) }}</span>
              <span>{{ $car->bouwjaar ?? '—' }}</span>
              <span>{{ ucfirst($car->brandstof) }}</span>
              <span>{{ number_format($car->tellerstand ?? 0, 0, ',', '.') }} km</span>
            </div>

            <div class="car-price">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }},-</div>
          </div>
        </a>
      @endforeach
    </div>

    @if(($nieuw ?? collect())->count() > 3)
      <div class="cta-center">
        <button id="btnBekijkAanbod" class="btn btn-primary" type="button">Bekijk Het Aanbod</button>
      </div>
    @endif
  </div>
</section>


<!-- <section class="openingstijden-section">
    <div class="container">
        <div class="openingstijden-section-inner">
            <div class="openingstijden-image">
                <img src="{{ asset('images/onderhoud.png') }}" alt="Handdruk">
            </div>
            <div class="openingstijden-content">
                <h1>Onderhoud voor elke auto</h1>
                <p>Voor elk onderhoud ben je bij ons aan het juiste adres. Wij zorgen dat jouw auto veilig, betrouwbaar en in topconditie blijft.</p>

                <p>*Kleine beurt</p>

                <p>*Grote beurt</p>

                <p>*Olie & filters</p>

                <p>*Remmenservice</p>
                                
                <p>*Diagnose & storingen</p>


                <div class="btn-aanbod"><a href="#footer" class="btn btn-primary">Maak een afspraak</a></div>
            </div>
        </div>
    </div>
</section> -->

<section class="openingstijden-section">
    <div class="container">
        <div class="openingstijden-section-inner">
            <div class="openingstijden-content">
                <h1>Huur vandaag nog een aanhanger</h1>
                <p> Afmetingen laadbak: ca. 130x 250 cm <br>
                    €35 per dag,
                    €15 voor 4 uur,
                    €50 voor een weekend. 
                    </p>
                <p>Onze aanhangers zijn veilig, schoon en direct beschikbaar in meerdere maten. Huur per dag, weekend of langer met heldere tarieven en zonder verrassingen.</p>
                <p>Wij zetten ’m op tijd voor je klaar en geven gratis advies over belading, sjorpunten en kogeldruk. Spanbanden, netten en een disselslot zijn optioneel verkrijgbaar, zodat jij zorgeloos en volgens de regels de weg op kunt.</p>
                <div class="btn-aanbod"><a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="btn btn-primary">Boek nu!</a></div>
            </div>
            <div class="openingstijden-image">
                <img src="{{ asset('images/cargo-trailers-passenger-car-parked-spacious-lot.jpg') }}" alt="Handdruk">
            </div>
        </div>
    </div>
</section>

<section class="openingstijden-section">
    <div class="container">
        <div class="openingstijden-section-inner">
            <div class="openingstijden-image">
                <img src="{{ asset('images/1200x810.jpg') }}" alt="Handdruk">
            </div>
            <div class="openingstijden-content">
                <h1>Numatic George Tapijtreiniger <br> Slechts €25 per dag!</h1>
                <p>Wil je je tapijten, meubels of vloerkleden grondig reinigen? Huur dan de krachtige en betrouwbare Numatic George tapijtreiniger!</p>
                <p>Huur per dag of weekend, zonder gedoe. We adviseren je graag over het juiste mondstuk, filtergebruik en veilig legen van de ketel.</p>
                <div class="btn-aanbod"><a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="btn btn-primary">Boek nu!</a></div>
            </div>
        </div>
    </div>
</section>


<section class="openingstijden-section">
    <div class="container">
        <div class="openingstijden-section-inner">
            <div class="openingstijden-content">
                <h1>Koplampen polijsten</h1>
                <p>Door verwering, zonlicht en vuil kunnen koplampen na verloop van tijd dof en geel worden, wat de lichtopbrengst en uitstraling van je auto vermindert. Met een professionele polijstbehandeling herstellen wij de helderheid en glans van je koplampen. Dit zorgt voor beter zicht in het donker, een veiligere rijervaring en een frisse, verzorgde uitstraling van je auto vaak met zichtbaar resultaat binnen één behandeling.<br></p>
                <div class="btn-aanbod"><a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="btn btn-primary">Boek nu!</a></div>
            </div>
            <div class="openingstijden-image">
                <img src="{{ asset('images/head-lights-car.jpg') }}" alt="Handdruk">
            </div>
        </div>
    </div>
</section>

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
                <h1>Openingstijden</h1>
                <p>Je bent van harte welkom tijdens onze vaste openingstijden. Of je nu komt voor verhuur, een reservering wilt ophalen of iets wilt inleveren: wij staan klaar om je snel en prettig te helpen. We denken graag met je mee, zorgen dat alles vlot geregeld is en laten je niet onnodig wachten. Zo weet je precies waar je aan toe bent en kun je snel weer op weg.</p>
                <p><strong>Ma–Vr:</strong> 08:30–17:30 · <strong>Za:</strong> 09:00–16:00 · <strong>Zo:</strong> gesloten</p>
                <p>U bent welkom tijdens onze reguliere openingstijden. Wilt u langskomen buiten deze tijden? <br> Dat kan! Neem gerust contact met ons op om een afspraak te maken.</p>
                <div class="btn-aanbod"><a href="#footer" class="btn btn-primary">Maak Afspraak</a></div>
            </div>
            <div class="openingstijden-image">
                <img src="{{ asset('images/car-repair-maintenance-theme-mechanic-uniform-working-auto-service.jpg') }}" alt="Handdruk">
            </div>
        </div>
    </div>
</section>



<section class="openingstijden-section">
    <div class="container">
        <div class="openingstijden-section-inner">
            <div class="openingstijden-image">
                <img src="{{ asset('images/car-sale.jpg') }}" alt="Handdruk">
            </div>
            <div class="openingstijden-content">
                <h1>Uw auto snel en betrouwbaar verkopen</h1>
                <p>Wilt u uw auto verkopen zonder gedoe met online platforms of handelaren?</p>
                <p>Vul hieronder uw gegevens in en ontvang binnen korte tijd een vrijblijvend bod van Gerritsen Automotive.</p>


                @if(session('success'))
  <div class="alert success" style="margin:10px 0;background:#e8fff0;border:1px solid #b6f0c5;padding:10px;border-radius:8px;">
    {{ session('success') }}
  </div>
@endif
@if ($errors->any())
  <div class="alert error" style="margin:10px 0;background:#fff3f3;border:1px solid #f5b5b5;padding:10px;border-radius:8px;">
    <ul style="margin:0;padding-left:18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif
                <button href="#verkopen" class="btn-sell-open" type="button" onclick="SellCar.open()">Auto verkopen</button>
                <div id="sellcar-overlay" class="sc-overlay" aria-hidden="true" onclick="SellCar.close(event)">
  <div class="sc-modal" role="dialog" aria-modal="true" aria-labelledby="sellcar-title" onclick="event.stopPropagation()">
    <button class="sc-close" aria-label="Sluiten" onclick="SellCar.close()">×</button>
    <h2 id="sellcar-title" class="sc-title">Auto verkopen</h2>

    <form id="sellcar-form" class="sc-form" action="{{ route('sellcar.store') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf

      <h3 class="sc-subtitle">Gegevens van uw auto</h3>
      <div class="sc-grid">
        <label class="sc-input">
          <span>Merk</span>
          <input name="brand" type="text" placeholder="Merk">
        </label>
        <label class="sc-input">
          <span>Model</span>
          <input name="model" type="text" placeholder="Model">
        </label>
        <label class="sc-input">
          <span>Kenteken <b>*</b></span>
          <input name="license_plate" type="text" placeholder="XX-999-X" required>
        </label>
        <label class="sc-input">
          <span>Kilometerstand <b>*</b></span>
          <input name="mileage" type="number" min="0" step="1" placeholder="bijv. 123456" required>
        </label>
      </div>

      <div class="sc-field">
        <span class="sc-label">Opties</span>
        <div class="sc-options">
          @php
            $opts = [
              'Airco','Climate control','Cruise control','Elektrische ramen voor','Elektrische ramen achter','Schuifdak',
              'Panoramadak','Lichtmetalen velgen','Navigatie','Multifunctioneel stuur','Xenon verlichting','Lederen bekleding',
              'Stoelverwarming','Parkeersensoren','Elektrische stoelverstelling','Metallic lak','Elektrische spiegels'
            ];
          @endphp
          @foreach($opts as $o)
          <label class="sc-check"><input type="checkbox" name="options[]" value="{{ $o }}"> <span>{{ $o }}</span></label>
          @endforeach
        </div>
      </div>

      <div class="sc-field">
        <span class="sc-label">Foto's</span>
        <div id="sc-drop" class="sc-drop" aria-label="Foto upload">
          <strong>Drag &amp; Drop Files Here</strong>
          <span>ofwel</span>
          <label class="sc-browse">Blader door de bestanden
            <input id="photos" name="photos[]" type="file" accept="image/*" multiple hidden>
          </label>
          <div class="sc-count"><span id="sc-count">0</span> van 20</div>
        </div>
        <div id="sc-preview" class="sc-preview"></div>
      </div>

      <label class="sc-input">
        <span>Overige bijzonderheden, zoals eventuele schade, staat van onderhoud en speciale opties</span>
        <textarea name="remarks" rows="4" placeholder="Vertel iets over de staat van de auto..."></textarea>
      </label>

      <h3 class="sc-subtitle">Uw gegevens</h3>
      <label class="sc-input">
        <span>Uw naam <b>*</b></span>
        <input name="name" type="text" required>
      </label>
      <label class="sc-input">
        <span>Telefoonnummer <b>*</b></span>
        <input name="phone" type="tel" required>
      </label>
      <label class="sc-input">
        <span>E-mailadres <b>*</b></span>
        <input name="email" type="email" required>
      </label>
      <label class="sc-input">
        <span>Vraag en/of opmerking</span>
        <textarea name="message" rows="3" placeholder="(optioneel)"></textarea>
      </label>

      <label class="sc-privacy">
        <input id="privacy" name="privacy" type="checkbox" required>
        <span>Door dit formulier te gebruiken gaat u akkoord met onze <a href="/privacy" target="_blank" rel="noopener">privacyverklaring</a></span>
      </label>

      <button class="sc-submit" type="submit">Verzenden</button>
    </form>
  </div>
</div>
            </div>
        </div>
    </div>
</section>




<!-- Trigger knop (zet waar je wilt) -->


<!-- Modal -->


<style>
/* --- basic button trigger --- */
.btn-sell-open{padding: 10px 20px;;border:none;background:#747474;color:#fff;  font-family: 'Play', sans-serif; font-size:16px; font-weight: bold;}

/* --- modal --- */
.sc-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;padding:16px;z-index:9999}
.sc-overlay.is-open{display:flex}
.sc-modal{width:min(860px,100%);background:#fff;border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.25);position:relative;max-height:90vh;overflow:auto}
.sc-title{margin:0;padding:18px 22px;background:#747474;color:#fff;border-radius:16px 16px 0 0;font-size:24px;letter-spacing:.2px}
.sc-close{position:absolute;right:12px;top:10px;width:38px;height:38px;border:none;border-radius:999px;background:#747474;color:#fff;font-size:24px;cursor:pointer}
.sc-form{padding:18px 22px 24px 22px}
.sc-subtitle{margin:8px 0 14px 0;font-size:20px}
.sc-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media (max-width:640px){.sc-grid{grid-template-columns:1fr}}

.sc-input{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
.sc-input > span{font-weight:600}
.sc-input input,.sc-input textarea{
  width:100%;padding:12px 14px;border:1px solid #747474;border-radius:10px;font-size:16px;outline:none;
}
.sc-input input:focus,.sc-input textarea:focus{border-color:#d0011c;box-shadow:0 0 0 3px rgba(208,1,28,.1)}

.sc-field{margin:12px 0}
.sc-label{display:block;font-weight:700;margin-bottom:8px}
.sc-options{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
@media (max-width:640px){.sc-options{grid-template-columns:1fr}}
.sc-check{display:flex;align-items:center;gap:8px}
.sc-check input{width:18px;height:18px}

.sc-drop{
  border:2px dashed #747474;border-radius:12px;padding:22px;text-align:center;display:flex;flex-direction:column;gap:6px;position:relative
}
.sc-drop.dragover{background:#f9fafb;border-color:#747474}
.sc-browse{color:#d0011c;cursor:pointer;font-weight:700}
.sc-count{position:absolute;right:12px;bottom:8px;font-size:12px;color:#6b7280}

.sc-preview{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:10px}
@media (max-width:640px){.sc-preview{grid-template-columns:repeat(3,1fr)}}
.sc-thumb{position:relative;border:1px solid #eee;border-radius:8px;overflow:hidden}
.sc-thumb img{width:100%;height:100%;object-fit:cover;display:block;aspect-ratio:1/1}
.sc-thumb button{position:absolute;top:4px;right:4px;border:none;background:#0009;color:#fff;width:24px;height:24px;border-radius:6px;cursor:pointer}

.sc-privacy{display:flex;gap:10px;align-items:flex-start;margin:12px 0}
.sc-privacy a{color:#d0011c}

.sc-submit{
  display:inline-block;width:100%;margin-top:4px;border:none;border-radius:10px;background:#747474;color:#fff;
  font-weight:800;padding:14px 16px;font-size:16px;cursor:pointer
}
</style>



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
                        <p>Gelderse Rooslaan 14 A, 6841 BE Arnhem</p>
                    </div> 

                    <div class="phone">
                        <img src="{{ asset('images/telephone.svg') }}" alt="home">
                        <p>+ 31 6 38257987 (Verkoop, Shania)</p>

                    </div>

                    <div class="phone">
                        <img src="{{ asset('images/telephone.svg') }}" alt="home">
                        <p>+ 31 6 49951874 (Werkplaats, Mick)</p>
                    </div>
                    
                    <div class="email">
                        <img src="{{ asset('images/mail.svg') }}" alt="home">
                        <p>Handelsonderneming@mgerritsen.nl</p>
                    </div>                     
                </div>

                <img src="{{ asset('images/Garage-footer.png') }}" alt="keuringen">

            </div>

            <div class="footer-content-right">
               <form method="POST" action="{{ route('contact.store') }}" class="contact-form" novalidate>
    @csrf

    {{-- Honeypot (spamprotectie) --}}
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

    <div class="row two">
        <div class="field">
            <input class="inputform" 
                   type="text" 
                   name="name" 
                   placeholder="Naam *"
                   value="{{ old('name') }}"
                   required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <input class="inputform" 
                   type="text" 
                   name="phone" 
                   placeholder="Telefoonnummer"
                   value="{{ old('phone') }}">
            @error('phone')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="field">
        <input class="inputform" 
               type="email" 
               name="email" 
               placeholder="Email *"
               value="{{ old('email') }}"
               required>
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <textarea class="inputform" 
                  name="message" 
                  rows="5" 
                  placeholder="Bericht *" 
                  required>{{ old('message') }}</textarea>
        @error('message')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <label class="check">
        <input type="checkbox" name="privacy" required>
        <span class="inputform">Ik heb het privacybeleid gelezen en begrepen.</span>
        @error('privacy')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>

    <button type="submit" class="submit">Verzenden</button>

    {{-- Succes melding --}}
    @if(session('success'))
<div id="contactModal" class="gd-modal" role="dialog" aria-modal="true" aria-labelledby="contactModalTitle">
  <div class="gd-modal-box">
    <h3 id="contactModalTitle">Bedankt!</h3>
    <p>Je bericht is verstuurd. We nemen zo snel mogelijk contact met je op.</p>
    <div class="gd-actions">
      <button type="button" data-close-modal>Sluiten</button>
    </div>
  </div>
</div>
@endif

<style>
.gd-modal{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.6);z-index:9999;padding:16px;}
.gd-modal-box{background:#fff;color:#111;max-width:420px;width:100%;border-radius:12px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.25);text-align:center}
.gd-actions{margin-top:16px}
.gd-actions button{padding:10px 16px;border:0;border-radius:8px;cursor:pointer;background:#111;color:#fff}
.gd-actions button:hover{opacity:.9}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // (optioneel) bestaand contactModal gedrag — veilig gemaakt
  var modal = document.getElementById('contactModal');
  if (modal) {
    function closeModal(){ modal.remove(); }
    modal.querySelectorAll('[data-close-modal]').forEach(function(btn){
      btn.addEventListener('click', closeModal);
    });
    modal.addEventListener('click', function(e){ if(e.target === modal){ closeModal(); } });
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ closeModal(); } });
  }

  // ✅ SellCar module
  window.SellCar = (function () {
    const overlay   = () => document.getElementById('sellcar-overlay');
    const drop      = () => document.getElementById('sc-drop');
    const fileInput = () => document.getElementById('photos');
    const preview   = () => document.getElementById('sc-preview');
    const counter   = () => document.getElementById('sc-count');
    const MAX_FILES = 20;

    let files = [];

    function open(){
      if (overlay()) {
        overlay().classList.add('is-open');
        overlay().setAttribute('aria-hidden','false');
      }
    }
    function close(){
      if (overlay()) {
        overlay().classList.remove('is-open');
        overlay().setAttribute('aria-hidden','true');
      }
    }

    function updateCounter(){ if(counter()) counter().textContent = files.length; }
    function renderPreviews(){
      if (!preview()) return;
      preview().innerHTML = '';
      files.forEach((f, idx) => {
        const url = URL.createObjectURL(f);
        const card = document.createElement('div');
        card.className = 'sc-thumb';
        card.innerHTML = `<img src="${url}" alt="">
                          <button type="button" aria-label="Verwijderen" data-i="${idx}">×</button>`;
        preview().appendChild(card);
      });
    }
    function syncInput(){
      if (!fileInput()) return;
      const dt = new DataTransfer();
      files.forEach(f => dt.items.add(f));
      fileInput().files = dt.files;
    }
    function addFiles(list){
      for (const f of list){
        if (files.length >= MAX_FILES) break;
        if (!f.type || !f.type.startsWith('image/')) continue;
        files.push(f);
      }
      updateCounter(); renderPreviews(); syncInput();
    }

    function bind(){
      // browse
      if (fileInput()) fileInput().addEventListener('change', e => addFiles(e.target.files));

      // drag & drop
      if (drop()){
        ['dragenter','dragover'].forEach(ev => {
          drop().addEventListener(ev, e => { e.preventDefault(); drop().classList.add('dragover'); });
        });
        ['dragleave','drop'].forEach(ev => {
          drop().addEventListener(ev, e => { e.preventDefault(); drop().classList.remove('dragover'); });
        });
        drop().addEventListener('drop', e => addFiles(e.dataTransfer.files));
      }

      // verwijderen
      if (preview()) preview().addEventListener('click', e => {
        const btn = e.target.closest('button[data-i]');
        if (!btn) return;
        files.splice(+btn.dataset.i, 1);
        updateCounter(); renderPreviews(); syncInput();
      });

      // submit-validatie
      const form = document.getElementById('sellcar-form');
      if (form) form.addEventListener('submit', e => {
        if (!form.checkValidity()){
          e.preventDefault();
          form.reportValidity();
        }
      });

      // Buttons die de popup moeten openen (graceful: mogen ook een href hebben)
      document.querySelectorAll('[data-sellcar-open]').forEach(el => {
        el.addEventListener('click', function(ev){
          // Als het een <a href="#verkopen"> is, laat de hash werken, maar open ook popup
          open();
        });
      });
    }

    bind();
    return { open, close };
  })();

  // ✳️ Kleine helper: highlight effect voor fallback sectie
  (function ensureHighlightStyle(){
    if (document.getElementById('sc-highlight-style')) return;
    const css = `
      .sc-highlight { outline: 3px solid rgba(246, 199, 118, .9); box-shadow: 0 0 0 6px rgba(246,199,118,.25); transition: outline-color .6s ease, box-shadow .6s ease; }
    `;
    const style = document.createElement('style');
    style.id = 'sc-highlight-style';
    style.textContent = css;
    document.head.appendChild(style);
  })();

  // 🔗 Deep-link handler: opent popup bij #verkopen, of scrolt/markeert sectie als fallback
  function handleDeepLink(){
    if (location.hash === '#verkopen') {
      // Probeer popup te openen
      if (window.SellCar && typeof window.SellCar.open === 'function') {
        window.SellCar.open();
      }
      // Fallback: scroll + highlight naar sectie (alleen als geen overlay bestaat)
      const overlayEl = document.getElementById('sellcar-overlay');
      if (!overlayEl) {
        const section = document.getElementById('sellcar-section') || document.querySelector('[data-sellcar-section]');
        if (section) {
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
          section.classList.add('sc-highlight');
          setTimeout(() => section.classList.remove('sc-highlight'), 2000);
        }
      }
    }
  }

  // Run nu en op hash-wijziging
  handleDeepLink();
  window.addEventListener('hashchange', handleDeepLink);
});
</script>



</form>

            </div>
        </div>
    </div>

</section>

<div class="contact-map">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2458.0517541550735!2d5.901556076625399!3d51.96948127192204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c7a70883823355%3A0xa6dd7685a8359450!2sGerritsen%20Automotive!5e0!3m2!1sen!2snl!4v1761597008139!5m2!1sen!2snl" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

<section class="rechten-section">
    <div class="container">
        <div class="rechten-section-inner">
            <p>© Gerritsen Automotive 2025 Alle Rechten Voorbehouden</p>
        </div>
    </div>
</section>

@endsection
