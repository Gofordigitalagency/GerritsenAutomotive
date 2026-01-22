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

    <div class="occasions-topbar" style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
      <h2 class="sectie-titel" style="margin:0 0 1rem 0;">Alle Occasions</h2>

      <div class="oc-sort" style="display:flex; align-items:center; gap:10px;">
        <label for="sort" style="font-weight:600; font-size:14px;">Sorteren:</label>

        <select id="sort" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(0,0,0,.15);">
          <option value="best">Beste resultaten</option>
          <option value="price_asc">Prijs oplopend</option>
          <option value="price_desc">Prijs aflopend</option>
          <option value="newest">Nieuwste aanbod eerst</option>
          <option value="km_asc">Kilometerstand oplopend</option>
          <option value="km_desc">Kilometerstand aflopend</option>
          <option value="year_asc">Bouwjaar oplopend</option>
          <option value="year_desc">Bouwjaar aflopend</option>
        </select>
      </div>
    </div>

    {{-- GRID (partial) --}}
    <div id="nieuwGrid" class="cards-grid">
      @include('occasions.partials.home_cards', ['nieuw' => $nieuw])
    </div>

    {{-- CTA altijd laten staan, JS regelt tonen/verbergen --}}
    <div class="cta-center" id="aanbodCta" style="display:none;">
      <button id="btnBekijkAanbod" class="btn btn-primary" type="button">
        Bekijk Het Aanbod
      </button>
    </div>

  </div>
</section>


<div class="wa-wrap" id="wa">
  <h1 class="wa-title">Plan gemakkelijk en snel een werkplaatsafspraak</h1>
  <p class="wa-sub">
    Staat uw gewenste reparatie er niet tussen? Neem dan contact op.
  </p>

  {{-- STEPPER (zonder vestiging) --}}
  <div class="wa-stepper" role="navigation" aria-label="Stappen">
    <div class="wa-step wa-active" data-step="1">
      <div class="wa-step-label">AUTOGEGEVENS</div>
      <div class="wa-dot wa-on"></div>
    </div>
    <div class="wa-line wa-on"></div>
    <div class="wa-step" data-step="2">
      <div class="wa-step-label">WERKZAAMHEDEN</div>
      <div class="wa-dot"></div>
    </div>
    <div class="wa-line"></div>
    <div class="wa-step" data-step="3">
      <div class="wa-step-label">TIJDSTIP</div>
      <div class="wa-dot"></div>
    </div>
    <div class="wa-line"></div>
    <div class="wa-step" data-step="4">
      <div class="wa-step-label">CONTACTGEGEVENS</div>
      <div class="wa-dot"></div>
    </div>
  </div>

  <div class="wa-grid">
    {{-- LEFT CONTENT --}}
    <div class="wa-main">
      <form id="wa-form" method="POST" action="{{ route('workshop.finish') }}">
        @csrf

        {{-- ================= STEP 1 ================= --}}
        <section class="wa-panel wa-show" data-panel="1">
          <h2 class="wa-h2">UW AUTOGEGEVENS</h2>
          <p class="wa-help">Om u van dienst te zijn hebben wij uw kenteken en kilometerstand nodig.</p>

          <div class="wa-row">
            <div class="wa-plate">
              <div class="wa-plate-nl">NL</div>
              <input class="wa-plate-input" name="license_plate" id="wa-license" type="text" placeholder="00-XXX-0" autocomplete="off">
            </div>

            <div class="wa-km">
              <input class="wa-km-input" name="mileage" id="wa-mileage" type="number" min="0" step="1" placeholder="Km-stand bij benadering">
              <div class="wa-km-suf">KM</div>
            </div>
          </div>

          <div class="wa-actions wa-actions-right">
            <button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">DOOR NAAR STAP 2 <span class="wa-arr">›</span></button>
          </div>
        </section>

        {{-- ================= STEP 2 ================= --}}
        <section class="wa-panel" data-panel="2">
          <h2 class="wa-h2">SELECTEER UW WERKZAAMHEDEN</h2>
          <p class="wa-help">Kies werkzaamheden die aan uw auto moeten gebeuren.</p>

          <div class="wa-block-title">ONDERHOUD</div>

          <div class="wa-list">
            @php $maintenance = config('workshop_services.maintenance'); @endphp
            @foreach($maintenance as $opt)
              <label class="wa-item">
                <span class="wa-radio">
                  <input type="radio" name="maintenance_option" value="{{ $opt }}" onchange="WA.sync()">
                  <span class="wa-radio-ui"></span>
                </span>

                <span class="wa-item-text">{{ $opt }}</span>

                <span class="wa-info" title="Info">i</span>
              </label>
            @endforeach
          </div>

          <div class="wa-block-title wa-mt">AANVULLENDE WERKZAAMHEDEN</div>

          <div class="wa-list">
            @php $extras = config('workshop_services.extras'); @endphp
            @foreach($extras as $ex)
              <label class="wa-item wa-item-check">
                <span class="wa-check">
                  <input type="checkbox" name="extra_services[]" value="{{ $ex }}" onchange="WA.sync()">
                  <span class="wa-check-ui"></span>
                </span>

                <span class="wa-item-text">{{ $ex }}</span>
              </label>
            @endforeach
          </div>

          <div class="wa-actions">
            <button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">‹ VORIGE</button>
            <button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">VOLGENDE ›</button>
          </div>
        </section>

        {{-- ================= STEP 3 ================= --}}
        <section class="wa-panel" data-panel="3">
          <h2 class="wa-h2">PLAN UW TIJDSTIP</h2>
          <p class="wa-help">Selecteer een beschikbare dag.</p>

          {{-- Hidden inputs die naar controller gaan --}}
          <input type="hidden" name="appointment_date" id="wa-date">
          <input type="hidden" name="appointment_time" id="wa-time">

          <div class="wa-cal">
            <div class="wa-cal-head">
              <button class="wa-cal-nav" type="button" onclick="WA.calPrev()">‹</button>
              <div class="wa-cal-month" id="wa-cal-month">—</div>
              <button class="wa-cal-nav" type="button" onclick="WA.calNext()">›</button>
            </div>

            <div class="wa-cal-week">
              <div>Maandag</div><div>Dinsdag</div><div>Woensdag</div><div>Donderdag</div><div>Vrijdag</div><div>Zaterdag</div><div>Zondag</div>
            </div>

            <div class="wa-cal-grid" id="wa-cal-grid"></div>
          </div>

          <div class="wa-split">
            <div class="wa-card">
              <div class="wa-card-title">WILT U WACHTEN TIJDENS HET ONDERHOUD OF REPARATIE?</div>
              <label class="wa-radio-row">
                <input type="radio" name="wait_while_service" value="1" onchange="WA.sync()"> Ja
              </label>
              <label class="wa-radio-row">
                <input type="radio" name="wait_while_service" value="0" onchange="WA.sync()"> Nee
              </label>
            </div>

            <div class="wa-card">
              <div class="wa-card-title">HOE LAAT WILT U UW AUTO HET LIEFST BRENGEN?</div>
              <div class="wa-times">
                <button type="button" class="wa-time" data-time="08:00" onclick="WA.pickTime(this)">08:00</button>
                <button type="button" class="wa-time" data-time="09:00" onclick="WA.pickTime(this)">09:00</button>
                <button type="button" class="wa-time" data-time="09:30" onclick="WA.pickTime(this)">09:30</button>
              </div>
            </div>
          </div>

          <!-- <div class="wa-card wa-mt2">
            <div class="wa-card-title">WILT U VERVANGEND VERVOER?</div>
            <label class="wa-radio-row"><input type="radio" name="replacement_transport" value="Ja, graag vervangend vervoer (tegen servicehuur tarief)" onchange="WA.sync()"> Ja, graag vervangend vervoer (tegen servicehuur tarief)</label>
            <label class="wa-radio-row"><input type="radio" name="replacement_transport" value="Ja, graag vervangend vervoer, deze wordt vergoed door mijn leasemaatschappij" onchange="WA.sync()"> Ja, graag vervangend vervoer, deze wordt vergoed door mijn leasemaatschappij</label>
            <label class="wa-radio-row"><input type="radio" name="replacement_transport" value="Ja, graag een leenfiets" onchange="WA.sync()"> Ja, graag een leenfiets.</label>
            <label class="wa-radio-row"><input type="radio" name="replacement_transport" value="Nee, vervangend vervoer is niet nodig" onchange="WA.sync()"> Nee, vervangend vervoer is niet nodig.</label>
          </div> -->

          <div class="wa-actions">
            <button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">‹ VORIGE</button>
            <button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">VOLGENDE ›</button>
          </div>
        </section>

        {{-- ================= STEP 4 ================= --}}
        <section class="wa-panel" data-panel="4">
          <h2 class="wa-h2">UW CONTACTGEGEVENS</h2>
          <p class="wa-help">Wij hebben de volgende gegevens van u nodig om de afspraak te bevestigen.</p>

          <label class="wa-field">
            <span>Bedrijfsnaam (Optioneel)</span>
            <input type="text" name="company_name">
          </label>

          <div class="wa-2col wa-mt2">
            <div class="wa-field">
              <span>Aanhef</span>
              <div class="wa-inline">
                <label class="wa-radio-row"><input type="radio" name="salutation" value="dhr"> Dhr.</label>
                <label class="wa-radio-row"><input type="radio" name="salutation" value="mevr"> Mevr.</label>
              </div>
            </div>

            <div></div>
          </div>

          <label class="wa-field"><span>Voornaam</span><input type="text" name="first_name" required></label>
          <label class="wa-field"><span>Tussenvoegsel</span><input type="text" name="middle_name"></label>
          <label class="wa-field"><span>Achternaam</span><input type="text" name="last_name" required></label>

          <div class="wa-3col wa-mt2">
            <label class="wa-field"><span>Straat</span><input type="text" name="street"></label>
            <label class="wa-field"><span>Huisnummer</span><input type="text" name="house_number"></label>
            <label class="wa-field"><span>Toevoeging</span><input type="text" name="addition"></label>
          </div>

          <div class="wa-2col wa-mt2">
            <label class="wa-field"><span>Postcode</span><input type="text" name="postal_code"></label>
            <label class="wa-field"><span>Woonplaats</span><input type="text" name="city"></label>
          </div>

          <label class="wa-field wa-mt2"><span>Telefoonnummer</span><input type="text" name="phone"></label>
          <label class="wa-field"><span>E-mail</span><input type="email" name="email" required></label>

          <label class="wa-field">
            <span>Opmerkingen</span>
            <textarea name="remarks" rows="5" placeholder="Zijn er nog andere werkzaamheden? Heeft u verder vragen of wilt u vooraf een kostenindicatie?"></textarea>
          </label>

          <label class="wa-checkline">
            <input type="checkbox" name="terms_accepted" required>
            <span>Ik ga akkoord met de voorwaarden</span>
          </label>

          <div class="wa-termsbox">
            <div class="wa-terms-title">VOORWAARDEN</div>
            <div>Ik ga ermee akkoord dat mijn gegevens worden gebruikt voor het afhandelen van mijn serviceafspraak.</div>
          </div>

          <label class="wa-checkline">
            <input type="checkbox" name="marketing_opt_in" value="1">
            <span>Houd mij op de hoogte van nieuws en aanbiedingen</span>
          </label>

          <div class="wa-actions">
            <button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">‹ VORIGE</button>
            <button class="wa-btn wa-btn-primary" type="submit">AFSPRAAK INPLANNEN</button>
          </div>
        </section>
      </form>
    </div>

    {{-- RIGHT OVERVIEW --}}
    <aside class="wa-side">
      <div class="wa-side-title">OVERZICHT</div>

      <div class="wa-acc">
        <button class="wa-acc-head" type="button" onclick="WA.acc(this)">
          <span class="wa-ic">🚗</span> AUTOGEGEVENS <span class="wa-caret">⌃</span>
        </button>
        <div class="wa-acc-body wa-open">
          <div class="wa-acc-row"><b>Kenteken:</b> <span id="ov-plate">-</span></div>
          <div class="wa-acc-row"><b>KM:</b> <span id="ov-km">-</span></div>
        </div>
      </div>

      <div class="wa-acc">
        <button class="wa-acc-head" type="button" onclick="WA.acc(this)">
          <span class="wa-ic">🛠️</span> WERKZAAMHEDEN <span class="wa-caret">⌃</span>
        </button>
        <div class="wa-acc-body">
          <div class="wa-acc-row"><b>Onderhoud:</b> <span id="ov-main">-</span></div>
          <div class="wa-acc-row"><b>Aanvullend:</b> <span id="ov-extra">-</span></div>
        </div>
      </div>

      <div class="wa-acc">
        <button class="wa-acc-head" type="button" onclick="WA.acc(this)">
          <span class="wa-ic">📅</span> TIJDSTIP <span class="wa-caret">⌃</span>
        </button>
        <div class="wa-acc-body">
          <div class="wa-acc-row"><b>Datum:</b> <span id="ov-date">-</span></div>
          <div class="wa-acc-row"><b>Tijd:</b> <span id="ov-time">-</span></div>
          <div class="wa-acc-row"><b>Wachten:</b> <span id="ov-wait">-</span></div>
        </div>
      </div>

      <div class="wa-acc">
        <button class="wa-acc-head" type="button" onclick="WA.acc(this)">
          <span class="wa-ic">👤</span> CONTACTGEGEVENS <span class="wa-caret">⌃</span>
        </button>
        <div class="wa-acc-body">
          <div class="wa-acc-row"><b>Naam:</b> <span id="ov-name">-</span></div>
          <div class="wa-acc-row"><b>Email:</b> <span id="ov-email">-</span></div>
        </div>
      </div>
    </aside>
  </div>
</div>





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
                    €25 per dag,
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
  <img src="{{ asset('images/telephone.svg') }}" alt="phone">
  <a href="tel:+31638257987">+31 6 38257987 (Verkoop, Shania)</a>
</div>

<div class="phone">
  <img src="{{ asset('images/telephone.svg') }}" alt="phone">
  <a href="tel:+31649951874">+31 6 49951874 (Werkplaats, Mick)</a>
</div>

<div class="email">
  <img src="{{ asset('images/mail.svg') }}" alt="mail">
  <a href="mailto:Handelsonderneming@mgerritsen.nl">Handelsonderneming@mgerritsen.nl</a>
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

(function () {
  // run meteen (script staat onderaan page)
  const select = document.getElementById('sort');
  const grid   = document.getElementById('nieuwGrid');
  const cta    = document.getElementById('aanbodCta');
  const btn    = document.getElementById('btnBekijkAanbod');

  console.log('FOUND:', { select: !!select, grid: !!grid, cta: !!cta, btn: !!btn });

  if (!select || !grid) return;

  let expanded = false;

  function applyUI() {
    const cards = Array.from(grid.querySelectorAll('.car-card'));
    const hasMore = cards.length > 3;

    if (cta) cta.style.display = hasMore ? '' : 'none';

    // als geen knop bestaat, alleen hide logic doen
    if (!btn) {
      cards.forEach((c, i) => c.classList.toggle('is-hidden', i > 2));
      return;
    }

    if (!expanded) {
      cards.forEach((c, i) => c.classList.toggle('is-hidden', i > 2));
      btn.textContent = 'Bekijk Het Aanbod';
    } else {
      cards.forEach(c => c.classList.remove('is-hidden'));
      btn.textContent = 'Toon minder';
    }
  }

  // init
  applyUI();

  // toggle
  if (btn) {
    btn.addEventListener('click', () => {
      expanded = !expanded;
      applyUI();
    });
  }

  // sort
  select.addEventListener('change', async () => {
    const sort = select.value;
    expanded = false;

    const url = `{{ route('occasions.cards') }}?sort=${encodeURIComponent(sort)}`;
    console.log('FETCH:', url);

    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      console.log('RES:', res.status);

      if (!res.ok) {
        console.error('Fetch failed', res.status, res.statusText);
        return;
      }

      const html = await res.text();
      grid.innerHTML = html;

      applyUI();

      history.replaceState(null, '', `/?sort=${encodeURIComponent(sort)}#aanbod`);
    } catch (e) {
      console.error('Fetch error:', e);
    }
  });
})();


/**
 * Workshop Wizard - werkt 1-op-1 met jouw HTML
 * Vereist: jouw bestaande CSS classes (wa-show, wa-active, wa-on, wa-open, etc.)
 */
window.WA = (() => {
  let step = 1;

  // kalender state
  let calYear = new Date().getFullYear();
  let calMonth = new Date().getMonth();
  let selectedDate = "";
  let selectedTime = "";

  const monthsNL = [
    "JANUARI","FEBRUARI","MAART","APRIL","MEI","JUNI",
    "JULI","AUGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DECEMBER"
  ];

  const qs  = (s, r=document) => r.querySelector(s);
  const qsa = (s, r=document) => Array.from(r.querySelectorAll(s));

  const pad = (n) => String(n).padStart(2,"0");
  const ymd = (y,m,d) => `${y}-${pad(m+1)}-${pad(d)}`;

  function fmtDateNL(ymdStr){
    if(!ymdStr) return "-";
    const [y,m,d] = ymdStr.split("-");
    return `${d}-${m}-${y}`;
  }
  function normPlate(v){ return (v||"").toString().trim().toUpperCase(); }
  function fmtKm(v){
    if(v === "" || v === null || typeof v === "undefined") return "-";
    const n = Number(v);
    if(Number.isNaN(n)) return "-";
    return n.toLocaleString("nl-NL");
  }

  // ===== step UI =====
  function showStep(n){
    step = n;

    // panels
    qsa(".wa-panel").forEach(p => p.classList.remove("wa-show"));
    const panel = qs(`.wa-panel[data-panel="${n}"]`);
    if(panel) panel.classList.add("wa-show");

    // stepper
    const steps = qsa(".wa-stepper .wa-step");
    const lines = qsa(".wa-stepper .wa-line");

    steps.forEach(s => {
      const sNum = Number(s.getAttribute("data-step"));
      s.classList.toggle("wa-active", sNum === n);
      const dot = s.querySelector(".wa-dot");
      if(dot) dot.classList.toggle("wa-on", sNum <= n);
    });

    lines.forEach((l, idx) => l.classList.toggle("wa-on", n >= (idx + 2)));

    sync();
    window.scrollTo({ top: qs("#wa")?.offsetTop - 20 || 0, behavior: "smooth" });
  }

  function next(){
    if(!validate(step)) return;
    if(step < 4) showStep(step + 1);
  }
  function prev(){
    if(step > 1) showStep(step - 1);
  }

  // ===== accordion =====
  function acc(btn){
    const body = btn?.parentElement?.querySelector(".wa-acc-body");
    if(!body) return;
    body.classList.toggle("wa-open");
  }

  // ===== calendar =====
  function renderCalendar(){
    const monthEl = qs("#wa-cal-month");
    const gridEl  = qs("#wa-cal-grid");
    if(!monthEl || !gridEl) return;

    monthEl.textContent = `${monthsNL[calMonth]} ${calYear}`;
    gridEl.innerHTML = "";

    const first = new Date(calYear, calMonth, 1);
    let startDay = first.getDay(); // 0=zo
    startDay = (startDay === 0) ? 7 : startDay;
    const blanks = startDay - 1;

    const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();

    // lege vakken
    for(let i=0;i<blanks;i++){
      const d = document.createElement("div");
      d.className = "wa-day wa-off";
      gridEl.appendChild(d);
    }

    for(let day=1; day<=daysInMonth; day++){
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "wa-day";
      const value = ymd(calYear, calMonth, day);
      btn.textContent = day;

      if(selectedDate === value) btn.classList.add("wa-picked");

      btn.addEventListener("click", (e) => pickDate(value, e.currentTarget));
      gridEl.appendChild(btn);
    }
  }

  function calPrev(){
    calMonth--;
    if(calMonth < 0){ calMonth = 11; calYear--; }
    renderCalendar();
  }
  function calNext(){
    calMonth++;
    if(calMonth > 11){ calMonth = 0; calYear++; }
    renderCalendar();
  }

  function pickDate(value, btnEl){
    selectedDate = value;
    const hidden = qs("#wa-date");
    if(hidden) hidden.value = value;

    qsa("#wa-cal-grid .wa-day").forEach(b => b.classList.remove("wa-picked"));
    if(btnEl) btnEl.classList.add("wa-picked");

    sync();
  }

  // ===== time =====
  function pickTime(btn){
    const t = btn.getAttribute("data-time");
    selectedTime = t;

    const hidden = qs("#wa-time");
    if(hidden) hidden.value = t;

    qsa(".wa-time").forEach(b => b.classList.remove("wa-picked"));
    btn.classList.add("wa-picked");

    sync();
  }

  // ===== overview sync =====
  function sync(){
    const plate = normPlate(qs("#wa-license")?.value);
    const km    = qs("#wa-mileage")?.value;

    if(qs("#ov-plate")) qs("#ov-plate").textContent = plate || "-";
    if(qs("#ov-km"))    qs("#ov-km").textContent    = km ? `${fmtKm(km)} km` : "-";

    const maint = qs('input[name="maintenance_option"]:checked')?.value || "";
    const extras = qsa('input[name="extra_services[]"]:checked').map(x => x.value);

    if(qs("#ov-main"))  qs("#ov-main").textContent  = maint || "-";
    if(qs("#ov-extra")) qs("#ov-extra").textContent = extras.length ? extras.join(", ") : "-";

    const d = qs("#wa-date")?.value || selectedDate || "";
    const t = qs("#wa-time")?.value || selectedTime || "";
    const wait = qs('input[name="wait_while_service"]:checked')?.value;
    // const rt = qs('input[name="replacement_transport"]:checked')?.value || "";

    if(qs("#ov-date")) qs("#ov-date").textContent = d ? fmtDateNL(d) : "-";
    if(qs("#ov-time")) qs("#ov-time").textContent = t ? `${t} uur` : "-";
    if(qs("#ov-wait")) qs("#ov-wait").textContent = (wait === "1") ? "Ja" : (wait === "0" ? "Nee" : "-");
    if(qs("#ov-rt"))   qs("#ov-rt").textContent   = rt || "-";

    const fn = (qs('input[name="first_name"]')?.value || "").trim();
    const mn = (qs('input[name="middle_name"]')?.value || "").trim();
    const ln = (qs('input[name="last_name"]')?.value || "").trim();
    const email = (qs('input[name="email"]')?.value || "").trim();

    const fullName = [fn, mn, ln].filter(Boolean).join(" ");
    if(qs("#ov-name"))  qs("#ov-name").textContent  = fullName || "-";
    if(qs("#ov-email")) qs("#ov-email").textContent = email || "-";
  }

  // ===== validation =====
  function error(msg){ alert(msg); }

  function validate(n){
    if(n === 1){
      const plate = normPlate(qs("#wa-license")?.value);
      if(!plate){ error("Vul uw kenteken in."); return false; }
      return true;
    }

    if(n === 2){
      const maint = qs('input[name="maintenance_option"]:checked')?.value || "";
      const extras = qsa('input[name="extra_services[]"]:checked');
      if(!maint && extras.length === 0){
        error("Selecteer minimaal één werkzaamheid (onderhoud of aanvullend).");
        return false;
      }
      return true;
    }

    if(n === 3){
      const d = qs("#wa-date")?.value || selectedDate;
      const t = qs("#wa-time")?.value || selectedTime;
      if(!d){ error("Selecteer een datum."); return false; }
      if(!t){ error("Selecteer een tijdstip."); return false; }

      const wait = qs('input[name="wait_while_service"]:checked')?.value;
      if(wait !== "1" && wait !== "0"){
        error("Geef aan of u wilt wachten tijdens het onderhoud/reparatie.");
        return false;
      }

      // const rt = qs('input[name="replacement_transport"]:checked')?.value;
      // if(!rt){
      //   error("Selecteer een vervangend vervoer optie.");
      //   return false;
      // }
      return true;
    }

    if(n === 4){
      const fn = (qs('input[name="first_name"]')?.value || "").trim();
      const ln = (qs('input[name="last_name"]')?.value || "").trim();
      const em = (qs('input[name="email"]')?.value || "").trim();
      const terms = qs('input[name="terms_accepted"]');

      if(!fn){ error("Voornaam is verplicht."); return false; }
      if(!ln){ error("Achternaam is verplicht."); return false; }
      if(!em){ error("E-mail is verplicht."); return false; }
      if(terms && !terms.checked){ error("U moet akkoord gaan met de voorwaarden."); return false; }

      return true;
    }

    return true;
  }

  function bind(){
    const liveSelectors = [
      "#wa-license","#wa-mileage",
      'input[name="maintenance_option"]',
      'input[name="extra_services[]"]',
      'input[name="wait_while_service"]',
      'input[name="replacement_transport"]',
      'input[name="first_name"]',
      'input[name="middle_name"]',
      'input[name="last_name"]',
      'input[name="email"]',
    ];

    liveSelectors.forEach(sel => {
      qsa(sel).forEach(elm => {
        elm.addEventListener("input", sync);
        elm.addEventListener("change", sync);
      });
    });

    renderCalendar();
    sync();
    showStep(1);
  }

  return { next, prev, acc, calPrev, calNext, pickTime, sync, init: bind };
})();

window.WA.init();



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
