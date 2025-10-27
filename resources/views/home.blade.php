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
                <p>Gerritsen Automotive is jouw vertrouwde adres in Huissen voor advies, verkoop van geselecteerde occasions en praktische verhuur.</p>
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
                <div class="btn-aanbod"><a href="#footer" class="btn btn-primary">Maak een afspraak</a></div>
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
  var modal = document.getElementById('contactModal');
  if (!modal) return;
  function closeModal(){ modal.remove(); }
  modal.querySelectorAll('[data-close-modal]').forEach(function(btn){
    btn.addEventListener('click', closeModal);
  });
  // klik buiten de box sluit ook
  modal.addEventListener('click', function(e){
    if(e.target === modal){ closeModal(); }
  });
  // ESC sluit
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){ closeModal(); }
  });
});
</script>

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

@endsection
