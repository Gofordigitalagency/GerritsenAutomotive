@extends('layout')

@section('content')

{{-- ====== HERO ====== --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-particles" id="heroParticles"></div>
    <div class="container">
        <div class="hero-inner">
            <div class="hero-eyebrow"><span class="dot"></span> Arnhem</div>
            <h1>Rijd weg in<br>uw <em>volgende auto</em></h1>
            <p class="hero-desc">Geselecteerde occasions, transparante prijzen en persoonlijk advies. Al meer dan 10 jaar uw adres in Arnhem.</p>
            <div class="hero-ctas">
                <a href="#aanbod" class="btn btn-red">Bekijk aanbod</a>
                <a href="#diensten" class="btn btn-outline">Onze diensten</a>
            </div>
        </div>
    </div>
</section>

{{-- ====== MARQUEE ====== --}}
<div class="marquee-strip">
    <div class="marquee-track">
        <span class="marquee-item">Occasions <span class="sep"></span></span>
        <span class="marquee-item">Werkplaats <span class="sep"></span></span>
        <span class="marquee-item">Auto verhuur <span class="sep"></span></span>
        <span class="marquee-item">Aanhanger verhuur <span class="sep"></span></span>
        <span class="marquee-item">Auto verkopen <span class="sep"></span></span>
        <span class="marquee-item">Koplampen polijsten <span class="sep"></span></span>
        <span class="marquee-item">Tapijtreiniger huur <span class="sep"></span></span>
        <span class="marquee-item">NAP controle <span class="sep"></span></span>
        <span class="marquee-item">Occasions <span class="sep"></span></span>
        <span class="marquee-item">Werkplaats <span class="sep"></span></span>
        <span class="marquee-item">Auto verhuur <span class="sep"></span></span>
        <span class="marquee-item">Aanhanger verhuur <span class="sep"></span></span>
        <span class="marquee-item">Auto verkopen <span class="sep"></span></span>
        <span class="marquee-item">Koplampen polijsten <span class="sep"></span></span>
        <span class="marquee-item">Tapijtreiniger huur <span class="sep"></span></span>
        <span class="marquee-item">NAP controle <span class="sep"></span></span>
    </div>
</div>

{{-- ====== DIENSTEN ====== --}}
<section id="diensten" class="services" style="background:var(--dark)">
    <div class="container">
        <div class="services-header" data-a>
            <div class="tag">Wat wij doen</div>
            <h2 class="heading-lg">Alles onder &eacute;&eacute;n dak</h2>
            <p class="sub">Van aankoop tot onderhoud &mdash; wij regelen het</p>
        </div>
        <div class="services-grid" data-s>
            <div class="svc-card" data-a>
                <div class="svc-icon"><i class="fas fa-car"></i></div>
                <h3>Occasions</h3>
                <p>Zorgvuldig geselecteerde auto's met NAP-controle, garantie en scherpe prijzen.</p>
                <a href="#aanbod" class="svc-link">Bekijk aanbod <span>&rarr;</span></a>
            </div>
            <div class="svc-card" data-a>
                <div class="svc-icon"><i class="fas fa-wrench"></i></div>
                <h3>Werkplaats</h3>
                <p>Onderhoud, reparatie en APK. Plan eenvoudig online een afspraak.</p>
                <a href="#wa" class="svc-link">Plan afspraak <span>&rarr;</span></a>
            </div>
            <div class="svc-card" data-a>
                <div class="svc-icon"><i class="fas fa-key"></i></div>
                <h3>Auto verhuur</h3>
                <p>Toyota Aygo Premium Edition. Vanaf &euro;35/dag inclusief onbeperkte kilometers.</p>
                <a href="#verhuur" class="svc-link">Meer info <span>&rarr;</span></a>
            </div>
            <div class="svc-card" data-a>
                <div class="svc-icon"><i class="fas fa-trailer"></i></div>
                <h3>Aanhanger huren</h3>
                <p>Direct beschikbaar. Vanaf &euro;15 voor 4 uur. Inclusief advies over belading.</p>
                <a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="svc-link">Reserveer <span>&rarr;</span></a>
            </div>
            <div class="svc-card" data-a>
                <div class="svc-icon"><i class="fas fa-hand-holding-dollar"></i></div>
                <h3>Auto verkopen</h3>
                <p>Snel en eerlijk bod. Geen gedoe met platforms of advertenties.</p>
                <a href="#verkoop" class="svc-link">Verkoop uw auto <span>&rarr;</span></a>
            </div>
            <div class="svc-card" data-a>
                <div class="svc-icon"><i class="fas fa-lightbulb"></i></div>
                <h3>Koplampen polijsten</h3>
                <p>Doffe lampen? Wij herstellen helderheid en glans in &eacute;&eacute;n behandeling.</p>
                <a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="svc-link">Boek nu <span>&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

{{-- ====== OVER ONS ====== --}}
<section id="info" style="padding:100px 0;background:var(--dark-2)">
    <div class="container">
        <div class="split">
            <div class="split-img" data-a="left"><img src="{{ asset('images/handshake.jpg') }}" alt="Persoonlijke service"></div>
            <div class="split-text" data-a="right">
                <div class="tag">Over ons</div>
                <h2>Persoonlijk. Helder. Zonder gedoe.</h2>
                <p>Gerritsen Automotive is al meer dan 10 jaar het vertrouwde adres in Arnhem voor occasions, verhuur en onderhoud. Wij geloven in transparante prijzen, eerlijk advies en snel schakelen.</p>
                <p>Of je nu vandaag wil proefrijden, iets wilt huren of je auto wilt laten nakijken &mdash; wij denken met je mee en regelen het vlot.</p>
                <div style="display:flex;gap:24px;margin-top:8px">
                    <div><strong style="font-size:28px;color:var(--red)">10+</strong><br><span style="font-size:12px;color:var(--gray)">Jaar ervaring</span></div>
                    <div><strong style="font-size:28px;color:var(--red)">200+</strong><br><span style="font-size:12px;color:var(--gray)">Auto's verkocht</span></div>
                    <div><strong style="font-size:28px;color:var(--red)">&#9733; 5</strong><br><span style="font-size:12px;color:var(--gray)">Google reviews</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ====== OCCASIONS ====== --}}
<section id="aanbod" class="occasions">
  <div class="container">
    <div class="occasions-top" data-a>
      <div>
        <div class="tag">Ons aanbod</div>
        <h2 class="heading-lg">Occasions</h2>
      </div>
      <div class="oc-sort">
        <label for="sort">Sorteer:</label>
        <select id="sort">
          <option value="best">Beste resultaten</option>
          <option value="price_asc">Prijs &uarr;</option>
          <option value="price_desc">Prijs &darr;</option>
          <option value="newest">Nieuwste</option>
          <option value="km_asc">KM &uarr;</option>
          <option value="km_desc">KM &darr;</option>
          <option value="year_asc">Bouwjaar &uarr;</option>
          <option value="year_desc">Bouwjaar &darr;</option>
        </select>
      </div>
    </div>
    <h2 class="sectie-titel-hidden">Alle Occasions</h2>
    <div id="nieuwGrid" class="cards-grid" data-s>
      @include('occasions.partials.home_cards', ['nieuw' => $nieuw])
    </div>
    <div class="cta-center" id="aanbodCta" style="display:none">
      <button id="btnBekijkAanbod" class="btn btn-red" type="button">Bekijk alles</button>
    </div>
  </div>
</section>

{{-- ====== CTA ====== --}}
<section class="cta-full">
    <div class="cta-full-bg"></div>
    <div class="cta-full-overlay"></div>
    <div class="container" data-a>
        <div class="tag">Proefrit?</div>
        <h2>Uw volgende auto staat hier</h2>
        <p>Plan een afspraak en stap binnenkort in uw nieuwe auto.</p>
        <a href="#footer" class="btn btn-red">Plan een afspraak</a>
    </div>
</section>

{{-- ====== WERKPLAATS ====== --}}
<section class="wa-section" id="wa">
  <div class="wa-wrap">
    <div data-a>
      <div class="tag">Werkplaats</div>
      <h1 class="wa-title">Plan een afspraak</h1>
      <p class="wa-sub">Reparatie, onderhoud of APK? Plan het hier. Staat uw reparatie er niet bij? <a href="#footer">Neem contact op</a>.</p>
    </div>
    <div class="wa-stepper" data-a>
      <div class="wa-step wa-active" data-step="1"><div class="wa-step-label">AUTOGEGEVENS</div><div class="wa-dot wa-on"></div></div>
      <div class="wa-line wa-on"></div>
      <div class="wa-step" data-step="2"><div class="wa-step-label">WERKZAAMHEDEN</div><div class="wa-dot"></div></div>
      <div class="wa-line"></div>
      <div class="wa-step" data-step="3"><div class="wa-step-label">TIJDSTIP</div><div class="wa-dot"></div></div>
      <div class="wa-line"></div>
      <div class="wa-step" data-step="4"><div class="wa-step-label">CONTACT</div><div class="wa-dot"></div></div>
    </div>
    <div class="wa-grid" data-a>
      <div class="wa-main">
        <form id="wa-form" method="POST" action="{{ route('workshop.finish') }}">
          @csrf
          <section class="wa-panel wa-show" data-panel="1">
            <h2 class="wa-h2">Autogegevens</h2><p class="wa-help">Vul uw kenteken en km-stand in.</p>
            <div class="wa-row">
              <div class="wa-plate"><div class="wa-plate-nl">NL</div><input class="wa-plate-input" name="license_plate" id="wa-license" type="text" placeholder="00-XXX-0" autocomplete="off"></div>
              <div class="wa-km"><input class="wa-km-input" name="mileage" id="wa-mileage" type="number" min="0" step="1" placeholder="Km-stand"><div class="wa-km-suf">KM</div></div>
            </div>
            <div class="wa-actions wa-actions-right"><button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">Volgende &rsaquo;</button></div>
          </section>
          <section class="wa-panel" data-panel="2">
            <h2 class="wa-h2">Werkzaamheden</h2><p class="wa-help">Wat moet er gebeuren?</p>
            <div class="wa-block-title">ONDERHOUD</div>
            <div class="wa-list">@php $maintenance=config('workshop_services.maintenance');@endphp @foreach($maintenance as $opt)<label class="wa-item"><span class="wa-radio"><input type="radio" name="maintenance_option" value="{{ $opt }}" onchange="WA.sync()"><span class="wa-radio-ui"></span></span><span class="wa-item-text">{{ $opt }}</span><span class="wa-info">i</span></label>@endforeach</div>
            <div class="wa-block-title wa-mt">AANVULLEND</div>
            <div class="wa-list">@php $extras=config('workshop_services.extras');@endphp @foreach($extras as $ex)<label class="wa-item wa-item-check"><span class="wa-check"><input type="checkbox" name="extra_services[]" value="{{ $ex }}" onchange="WA.sync()"><span class="wa-check-ui"></span></span><span class="wa-item-text">{{ $ex }}</span></label>@endforeach</div>
            <div class="wa-actions"><button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">&lsaquo; Vorige</button><button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">Volgende &rsaquo;</button></div>
          </section>
          <section class="wa-panel" data-panel="3">
            <h2 class="wa-h2">Tijdstip kiezen</h2><p class="wa-help">Selecteer een dag en tijd.</p>
            <input type="hidden" name="appointment_date" id="wa-date"><input type="hidden" name="appointment_time" id="wa-time">
            <div class="wa-cal"><div class="wa-cal-head"><button class="wa-cal-nav" type="button" onclick="WA.calPrev()">&lsaquo;</button><div class="wa-cal-month" id="wa-cal-month">&mdash;</div><button class="wa-cal-nav" type="button" onclick="WA.calNext()">&rsaquo;</button></div><div class="wa-cal-week"><div>Ma</div><div>Di</div><div>Wo</div><div>Do</div><div>Vr</div><div>Za</div><div>Zo</div></div><div class="wa-cal-grid" id="wa-cal-grid"></div></div>
            <div class="wa-split"><div class="wa-card"><div class="wa-card-title">Wachten tijdens onderhoud?</div><label class="wa-radio-row"><input type="radio" name="wait_while_service" value="1" onchange="WA.sync()"> Ja</label><label class="wa-radio-row"><input type="radio" name="wait_while_service" value="0" onchange="WA.sync()"> Nee</label></div><div class="wa-card"><div class="wa-card-title">Hoe laat brengen?</div><div class="wa-times"><button type="button" class="wa-time" data-time="08:00" onclick="WA.pickTime(this)">08:00</button><button type="button" class="wa-time" data-time="09:00" onclick="WA.pickTime(this)">09:00</button><button type="button" class="wa-time" data-time="09:30" onclick="WA.pickTime(this)">09:30</button></div></div></div>
            <div class="wa-actions"><button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">&lsaquo; Vorige</button><button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">Volgende &rsaquo;</button></div>
          </section>
          <section class="wa-panel" data-panel="4">
            <h2 class="wa-h2">Contactgegevens</h2><p class="wa-help">Voor bevestiging van uw afspraak.</p>
            <label class="wa-field"><span>Bedrijfsnaam (optioneel)</span><input type="text" name="company_name"></label>
            <div class="wa-2col wa-mt2"><div class="wa-field"><span>Aanhef</span><div class="wa-inline"><label class="wa-radio-row"><input type="radio" name="salutation" value="dhr"> Dhr.</label><label class="wa-radio-row"><input type="radio" name="salutation" value="mevr"> Mevr.</label></div></div><div></div></div>
            <label class="wa-field"><span>Voornaam</span><input type="text" name="first_name" required></label>
            <label class="wa-field"><span>Tussenvoegsel</span><input type="text" name="middle_name"></label>
            <label class="wa-field"><span>Achternaam</span><input type="text" name="last_name" required></label>
            <div class="wa-3col wa-mt2"><label class="wa-field"><span>Straat</span><input type="text" name="street"></label><label class="wa-field"><span>Nr.</span><input type="text" name="house_number"></label><label class="wa-field"><span>Toev.</span><input type="text" name="addition"></label></div>
            <div class="wa-2col wa-mt2"><label class="wa-field"><span>Postcode</span><input type="text" name="postal_code"></label><label class="wa-field"><span>Woonplaats</span><input type="text" name="city"></label></div>
            <label class="wa-field wa-mt2"><span>Telefoon</span><input type="text" name="phone"></label>
            <label class="wa-field"><span>E-mail</span><input type="email" name="email" required></label>
            <label class="wa-field"><span>Opmerkingen</span><textarea name="remarks" rows="3" placeholder="Vragen of opmerkingen"></textarea></label>
            <label class="wa-checkline"><input type="checkbox" name="terms_accepted" required><span>Ik ga akkoord met de voorwaarden</span></label>
            <div class="wa-termsbox"><div class="wa-terms-title">Voorwaarden</div><div>Ik ga ermee akkoord dat mijn gegevens worden gebruikt voor mijn serviceafspraak.</div></div>
            <label class="wa-checkline"><input type="checkbox" name="marketing_opt_in" value="1"><span>Houd mij op de hoogte van nieuws</span></label>
            <div class="wa-actions"><button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">&lsaquo; Vorige</button><button class="wa-btn wa-btn-primary" type="submit">Afspraak inplannen</button></div>
          </section>
        </form>
      </div>
      <aside class="wa-side">
        <div class="wa-side-title">OVERZICHT</div>
        <div class="wa-acc"><button class="wa-acc-head" type="button" onclick="WA.acc(this)"><span class="wa-ic"><i class="fas fa-car"></i></span> AUTO <span class="wa-caret">&and;</span></button><div class="wa-acc-body wa-open"><div class="wa-acc-row"><b>Kenteken:</b> <span id="ov-plate">-</span></div><div class="wa-acc-row"><b>KM:</b> <span id="ov-km">-</span></div></div></div>
        <div class="wa-acc"><button class="wa-acc-head" type="button" onclick="WA.acc(this)"><span class="wa-ic"><i class="fas fa-wrench"></i></span> WERK <span class="wa-caret">&and;</span></button><div class="wa-acc-body"><div class="wa-acc-row"><b>Onderhoud:</b> <span id="ov-main">-</span></div><div class="wa-acc-row"><b>Extra:</b> <span id="ov-extra">-</span></div></div></div>
        <div class="wa-acc"><button class="wa-acc-head" type="button" onclick="WA.acc(this)"><span class="wa-ic"><i class="fas fa-calendar"></i></span> TIJD <span class="wa-caret">&and;</span></button><div class="wa-acc-body"><div class="wa-acc-row"><b>Datum:</b> <span id="ov-date">-</span></div><div class="wa-acc-row"><b>Tijd:</b> <span id="ov-time">-</span></div><div class="wa-acc-row"><b>Wachten:</b> <span id="ov-wait">-</span></div></div></div>
        <div class="wa-acc"><button class="wa-acc-head" type="button" onclick="WA.acc(this)"><span class="wa-ic"><i class="fas fa-user"></i></span> CONTACT <span class="wa-caret">&and;</span></button><div class="wa-acc-body"><div class="wa-acc-row"><b>Naam:</b> <span id="ov-name">-</span></div><div class="wa-acc-row"><b>Email:</b> <span id="ov-email">-</span></div></div></div>
      </aside>
    </div>
  </div>
</section>

{{-- ====== VERHUUR ====== --}}
<section id="verhuur" class="rental-section">
    <div class="container">
        <div class="rental">
          <div class="rental-text" data-a="left">
            <span class="rental-badge">Vanaf &euro;35/dag</span>
            <h2>Toyota Aygo Premium</h2>
            <p>Compact, zuinig en volledig uitgerust. Direct beschikbaar in Arnhem.</p>
            <ul class="rental-benefits">
              <li>&#10003; Apple CarPlay</li><li>&#10003; Lederen interieur</li>
              <li>&#10003; Airco</li><li>&#10003; 5-deurs</li>
              <li>&#10003; Elektrische ramen</li><li>&#10003; Zuinig verbruik</li>
              <li>&#10003; Handgeschakeld</li><li>&#10003; Onbeperkte KM</li>
            </ul>
            <p class="rental-terms" onclick="openVoorwaarden()">Huurvoorwaarden bekijken</p>
            <div class="rental-btns">
              <a href="#footer" class="btn btn-red">Reserveer</a>
              <a href="tel:+31649951874" class="btn btn-outline">Bel direct</a>
            </div>
          </div>
          <div class="rental-slider" data-a="right">
            <div class="rental-slides" id="gaSlides">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.40.jpeg') }}" alt="Aygo">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.41 (1).jpeg') }}" alt="Aygo">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.41 (2).jpeg') }}" alt="Aygo">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.41 (3).jpeg') }}" alt="Aygo">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.41 (4).jpeg') }}" alt="Aygo">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.41 (5).jpeg') }}" alt="Aygo">
              <img src="{{ asset('images/WhatsApp Image 2026-02-25 at 08.05.41.jpeg') }}" alt="Aygo">
            </div>
          </div>
        </div>
    </div>
</section>
<div class="ga-modal-overlay" id="gaVoorwaardenModal"><div class="ga-modal"><span class="ga-modal-close" onclick="closeVoorwaarden()">&times;</span><h3>Huurvoorwaarden</h3><ul><li>Borg &euro;250</li><li>Eigen risico &euro;500</li><li>Volgetankt retour</li><li>Niet roken</li><li>Uitsluitend legaal gebruik</li></ul></div></div>

{{-- ====== OPENINGSTIJDEN + VERKOPEN ====== --}}
<section style="padding:100px 0;background:var(--dark-2)">
    <div class="container">
        <div class="split">
            <div class="split-text" data-a="left">
                <div class="tag">Bezoek ons</div>
                <h2>Openingstijden</h2>
                <p>Welkom voor een proefrit, advies of verhuur. Wij staan klaar.</p>
                <div style="display:grid;grid-template-columns:auto 1fr;gap:6px 16px;font-size:14px;color:var(--gray-light);margin:4px 0">
                    <strong style="color:#fff">Ma &ndash; Vr</strong><span>08:30 &ndash; 17:30</span>
                    <strong style="color:#fff">Zaterdag</strong><span>09:00 &ndash; 16:00</span>
                    <strong style="color:#fff">Zondag</strong><span>Gesloten</span>
                </div>
                <p>Buiten deze tijden? Neem contact op voor een afspraak.</p>
                <div><a href="#footer" class="btn btn-red" style="margin-top:8px">Contact opnemen</a></div>
            </div>
            <div class="split-img" data-a="right"><img src="{{ asset('images/car-repair-maintenance-theme-mechanic-uniform-working-auto-service.jpg') }}" alt="Werkplaats"></div>
        </div>
    </div>
</section>

{{-- ====== AUTO VERKOPEN ====== --}}
<section id="verkoop" style="padding:100px 0;background:var(--dark)">
    <div class="container">
        <div class="split">
            <div class="split-img" data-a="left"><img src="{{ asset('images/car-sale.jpg') }}" alt="Auto verkopen"></div>
            <div class="split-text" data-a="right">
                <div class="tag">Verkopen</div>
                <h2>Uw auto verkopen?</h2>
                <p>Geen gedoe met advertenties, onderhandelingen of oplichters. Vul uw gegevens in en ontvang snel een eerlijk, vrijblijvend bod.</p>
                @if(session('success'))<div style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.15);padding:10px 14px;border-radius:8px;color:#22c55e;font-size:13px">{{ session('success') }}</div>@endif
                @if($errors->any())<div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15);padding:10px 14px;border-radius:8px;color:#ef4444;font-size:13px"><ul style="margin:0;padding-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                <button class="btn-sell" type="button" onclick="SellCar.open()">Auto verkopen &rarr;</button>
                {{-- SELL CAR MODAL --}}
                <div id="sellcar-overlay" class="sc-overlay" aria-hidden="true" onclick="SellCar.close()">
                  <div class="sc-modal" onclick="event.stopPropagation()">
                    <button class="sc-close" onclick="SellCar.close()">&times;</button>
                    <h2 class="sc-title">Auto verkopen</h2>
                    <form id="sellcar-form" class="sc-form" action="{{ route('sellcar.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                      @csrf
                      <h3 class="sc-subtitle">Autogegevens</h3>
                      <div class="sc-grid">
                        <label class="sc-input"><span>Merk</span><input name="brand" type="text" placeholder="Merk"></label>
                        <label class="sc-input"><span>Model</span><input name="model" type="text" placeholder="Model"></label>
                        <label class="sc-input"><span>Kenteken *</span><input name="license_plate" type="text" placeholder="XX-999-X" required></label>
                        <label class="sc-input"><span>KM-stand *</span><input name="mileage" type="number" min="0" placeholder="123456" required></label>
                      </div>
                      <div class="sc-field"><span class="sc-label">Opties</span><div class="sc-options">@php $opts=['Airco','Climate control','Cruise control','Elektrische ramen voor','Elektrische ramen achter','Schuifdak','Panoramadak','Lichtmetalen velgen','Navigatie','Multifunctioneel stuur','Xenon verlichting','Lederen bekleding','Stoelverwarming','Parkeersensoren','Elektrische stoelverstelling','Metallic lak','Elektrische spiegels'];@endphp @foreach($opts as $o)<label class="sc-check"><input type="checkbox" name="options[]" value="{{ $o }}"><span>{{ $o }}</span></label>@endforeach</div></div>
                      <div class="sc-field"><span class="sc-label">Foto's</span><div id="sc-drop" class="sc-drop"><strong>Sleep bestanden hierheen</strong><span>of</span><label class="sc-browse">Blader<input id="photos" name="photos[]" type="file" accept="image/*" multiple hidden></label><div class="sc-count"><span id="sc-count">0</span>/20</div></div><div id="sc-preview" class="sc-preview"></div></div>
                      <label class="sc-input"><span>Bijzonderheden</span><textarea name="remarks" rows="3" placeholder="Schade, onderhoud, opties..."></textarea></label>
                      <h3 class="sc-subtitle">Uw gegevens</h3>
                      <label class="sc-input"><span>Naam *</span><input name="name" type="text" required></label>
                      <label class="sc-input"><span>Telefoon *</span><input name="phone" type="tel" required></label>
                      <label class="sc-input"><span>E-mail *</span><input name="email" type="email" required></label>
                      <label class="sc-input"><span>Opmerking</span><textarea name="message" rows="2" placeholder="(optioneel)"></textarea></label>
                      <label class="sc-privacy"><input name="privacy" type="checkbox" required><span>Akkoord met het <a href="/privacy" target="_blank">privacybeleid</a></span></label>
                      <button class="sc-submit" type="submit">Verzenden</button>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ====== FOOTER ====== --}}
<section id="footer" class="footer-section">
    <div class="footer-bg"></div><div class="footer-overlay"></div>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-left" data-a="left">
                <h2>Neem contact op</h2>
                <div class="footer-info">
                    <div><img src="{{ asset('images/home.svg') }}" alt=""><p>Gerritsen Automotive</p></div>
                    <div><img src="{{ asset('images/location.svg') }}" alt=""><p>Gelderse Rooslaan 14 A, 6841 BE Arnhem</p></div>
                    <div><img src="{{ asset('images/telephone.svg') }}" alt=""><a href="tel:+31638257987">+31 6 38257987 (Verkoop)</a></div>
                    <div><img src="{{ asset('images/telephone.svg') }}" alt=""><a href="tel:+31649951874">+31 6 49951874 (Werkplaats)</a></div>
                    <div><img src="{{ asset('images/mail.svg') }}" alt=""><a href="mailto:Handelsonderneming@mgerritsen.nl">Handelsonderneming@mgerritsen.nl</a></div>
                </div>
                <img src="{{ asset('images/Garage-footer.png') }}" alt="NAP">
            </div>
            <div class="footer-right" data-a="right">
              <form method="POST" action="{{ route('contact.store') }}" class="contact-form" novalidate>
                @csrf<input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
                <div class="row two"><div class="field"><input type="text" name="name" placeholder="Naam *" value="{{ old('name') }}" required></div><div class="field"><input type="text" name="phone" placeholder="Telefoon" value="{{ old('phone') }}"></div></div>
                <div class="field"><input type="email" name="email" placeholder="Email *" value="{{ old('email') }}" required></div>
                <div class="field"><textarea name="message" rows="4" placeholder="Bericht *" required>{{ old('message') }}</textarea></div>
                <label class="check"><input type="checkbox" name="privacy" required><span>Ik heb het privacybeleid gelezen.</span></label>
                <button type="submit" class="submit">Verzenden</button>
                @if(session('success'))<div id="contactModal" class="gd-modal"><div class="gd-modal-box"><h3>Bedankt!</h3><p>We nemen snel contact op.</p><div class="gd-actions"><button type="button" data-close-modal>Sluiten</button></div></div></div>@endif
              </form>
            </div>
        </div>
    </div>
</section>

<div class="contact-map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2458.0517541550735!2d5.901556076625399!3d51.96948127192204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c7a70883823355%3A0xa6dd7685a8359450!2sGerritsen%20Automotive!5e0!3m2!1sen!2snl!4v1761597008139!5m2!1sen!2snl" width="600" height="450" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
<section class="rechten-section"><div class="container"><div class="rechten-section-inner"><p>&copy; Gerritsen Automotive 2025</p></div></div></section>

{{-- ====== JS ====== --}}
<script>
document.addEventListener('DOMContentLoaded',function(){
  // Contact modal
  var m=document.getElementById('contactModal');
  if(m){function cl(){m.remove()}m.querySelectorAll('[data-close-modal]').forEach(b=>b.addEventListener('click',cl));m.addEventListener('click',e=>{if(e.target===m)cl()});document.addEventListener('keydown',e=>{if(e.key==='Escape')cl()})}

  window.openVoorwaarden=()=>document.getElementById('gaVoorwaardenModal').classList.add('active');
  window.closeVoorwaarden=()=>document.getElementById('gaVoorwaardenModal').classList.remove('active');

  // Slider
  const sl=document.getElementById('gaSlides');
  if(sl){let i=0;const n=sl.children.length;setInterval(()=>{i=(i+1)%n;sl.style.transform=`translateX(-${i*100}%)`},4000)}

  // SellCar
  window.SellCar=(function(){
    const ov=()=>document.getElementById('sellcar-overlay'),dr=()=>document.getElementById('sc-drop'),fi=()=>document.getElementById('photos'),pv=()=>document.getElementById('sc-preview'),ct=()=>document.getElementById('sc-count');
    let files=[];
    function open(){if(ov()){ov().classList.add('is-open');ov().setAttribute('aria-hidden','false')}}
    function close(){if(ov()){ov().classList.remove('is-open');ov().setAttribute('aria-hidden','true')}}
    function upd(){if(ct())ct().textContent=files.length}
    function ren(){if(!pv())return;pv().innerHTML='';files.forEach((f,i)=>{const u=URL.createObjectURL(f),d=document.createElement('div');d.className='sc-thumb';d.innerHTML=`<img src="${u}"><button type="button" data-i="${i}">&times;</button>`;pv().appendChild(d)})}
    function syn(){if(!fi())return;const dt=new DataTransfer();files.forEach(f=>dt.items.add(f));fi().files=dt.files}
    function add(l){for(const f of l){if(files.length>=20)break;if(!f.type?.startsWith('image/'))continue;files.push(f)}upd();ren();syn()}
    if(fi())fi().addEventListener('change',e=>add(e.target.files));
    if(dr()){['dragenter','dragover'].forEach(ev=>dr().addEventListener(ev,e=>{e.preventDefault();dr().classList.add('dragover')}));['dragleave','drop'].forEach(ev=>dr().addEventListener(ev,e=>{e.preventDefault();dr().classList.remove('dragover')}));dr().addEventListener('drop',e=>add(e.dataTransfer.files))}
    if(pv())pv().addEventListener('click',e=>{const b=e.target.closest('button[data-i]');if(!b)return;files.splice(+b.dataset.i,1);upd();ren();syn()});
    const fm=document.getElementById('sellcar-form');if(fm)fm.addEventListener('submit',e=>{if(!fm.checkValidity()){e.preventDefault();fm.reportValidity()}});
    return{open,close}
  })();

  // Occasions
  (function(){
    const sel=document.getElementById('sort'),grid=document.getElementById('nieuwGrid'),cta=document.getElementById('aanbodCta'),btn=document.getElementById('btnBekijkAanbod');
    if(!sel||!grid)return;let exp=false;
    function ui(){const c=[...grid.querySelectorAll('.car-card')],more=c.length>3;if(cta)cta.style.display=more?'':'none';if(!btn){c.forEach((x,i)=>x.classList.toggle('is-hidden',i>2));return}if(!exp){c.forEach((x,i)=>x.classList.toggle('is-hidden',i>2));btn.textContent='Bekijk alles'}else{c.forEach(x=>x.classList.remove('is-hidden'));btn.textContent='Toon minder'}}
    ui();if(btn)btn.addEventListener('click',()=>{exp=!exp;ui()});
    sel.addEventListener('change',async()=>{exp=false;try{const r=await fetch(`{{ route('occasions.cards') }}?sort=${encodeURIComponent(sel.value)}`,{headers:{'X-Requested-With':'XMLHttpRequest'}});if(!r.ok)return;grid.innerHTML=await r.text();ui();history.replaceState(null,'',`/?sort=${encodeURIComponent(sel.value)}#aanbod`)}catch(e){console.error(e)}});
  })();

  // Workshop Wizard
  window.WA=(()=>{
    let step=1,cY=new Date().getFullYear(),cM=new Date().getMonth(),sD='',sT='';
    const M=["JANUARI","FEBRUARI","MAART","APRIL","MEI","JUNI","JULI","AUGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DECEMBER"];
    const q=(s,r=document)=>r.querySelector(s),qa=(s,r=document)=>[...r.querySelectorAll(s)];
    const pad=n=>String(n).padStart(2,'0'),ymd=(y,m,d)=>`${y}-${pad(m+1)}-${pad(d)}`;
    function fN(s){if(!s)return'-';const[y,m,d]=s.split('-');return`${d}-${m}-${y}`}
    function nP(v){return(v||'').toString().trim().toUpperCase()}
    function fK(v){if(v===''||v==null)return'-';const n=Number(v);return isNaN(n)?'-':n.toLocaleString('nl-NL')}
    function show(n,o){o=o||{};step=n;qa('.wa-panel').forEach(p=>p.classList.remove('wa-show'));const p=q(`.wa-panel[data-panel="${n}"]`);if(p)p.classList.add('wa-show');qa('.wa-stepper .wa-step').forEach(s=>{const sn=+s.dataset.step;s.classList.toggle('wa-active',sn===n);const d=s.querySelector('.wa-dot');if(d)d.classList.toggle('wa-on',sn<=n)});qa('.wa-stepper .wa-line').forEach((l,i)=>l.classList.toggle('wa-on',n>=(i+2)));sync();if(o.scroll!==false)window.scrollTo({top:q('#wa')?.offsetTop-20||0,behavior:'smooth'})}
    function next(){if(!val(step))return;if(step<4)show(step+1)}
    function prev(){if(step>1)show(step-1)}
    function acc(b){const bd=b?.parentElement?.querySelector('.wa-acc-body');if(bd)bd.classList.toggle('wa-open')}
    function renderCal(){const mE=q('#wa-cal-month'),gE=q('#wa-cal-grid');if(!mE||!gE)return;mE.textContent=`${M[cM]} ${cY}`;gE.innerHTML='';let sd=new Date(cY,cM,1).getDay();sd=sd===0?7:sd;for(let i=0;i<sd-1;i++){const d=document.createElement('div');d.className='wa-day wa-off';gE.appendChild(d)}const dm=new Date(cY,cM+1,0).getDate();for(let day=1;day<=dm;day++){const b=document.createElement('button');b.type='button';b.className='wa-day';const v=ymd(cY,cM,day);b.textContent=day;if(sD===v)b.classList.add('wa-picked');b.addEventListener('click',e=>pickD(v,e.currentTarget));gE.appendChild(b)}}
    function calPrev(){cM--;if(cM<0){cM=11;cY--}renderCal()}
    function calNext(){cM++;if(cM>11){cM=0;cY++}renderCal()}
    function pickD(v,el){sD=v;const h=q('#wa-date');if(h)h.value=v;qa('#wa-cal-grid .wa-day').forEach(b=>b.classList.remove('wa-picked'));if(el)el.classList.add('wa-picked');sync()}
    function pickTime(b){const t=b.dataset.time;sT=t;const h=q('#wa-time');if(h)h.value=t;qa('.wa-time').forEach(b=>b.classList.remove('wa-picked'));b.classList.add('wa-picked');sync()}
    function sync(){const pl=nP(q('#wa-license')?.value),km=q('#wa-mileage')?.value;if(q('#ov-plate'))q('#ov-plate').textContent=pl||'-';if(q('#ov-km'))q('#ov-km').textContent=km?`${fK(km)} km`:'-';const mt=q('input[name="maintenance_option"]:checked')?.value||'';const ex=qa('input[name="extra_services[]"]:checked').map(x=>x.value);if(q('#ov-main'))q('#ov-main').textContent=mt||'-';if(q('#ov-extra'))q('#ov-extra').textContent=ex.length?ex.join(', '):'-';const d=q('#wa-date')?.value||sD||'',t=q('#wa-time')?.value||sT||'';const w=q('input[name="wait_while_service"]:checked')?.value;if(q('#ov-date'))q('#ov-date').textContent=d?fN(d):'-';if(q('#ov-time'))q('#ov-time').textContent=t?t+' uur':'-';if(q('#ov-wait'))q('#ov-wait').textContent=w==='1'?'Ja':w==='0'?'Nee':'-';const fn=(q('input[name="first_name"]')?.value||'').trim(),mn=(q('input[name="middle_name"]')?.value||'').trim(),ln=(q('input[name="last_name"]')?.value||'').trim(),em=(q('input[name="email"]')?.value||'').trim();if(q('#ov-name'))q('#ov-name').textContent=[fn,mn,ln].filter(Boolean).join(' ')||'-';if(q('#ov-email'))q('#ov-email').textContent=em||'-'}
    function err(m){alert(m)}
    function val(n){if(n===1)return nP(q('#wa-license')?.value)?(true):(err('Vul uw kenteken in.'),false);if(n===2){if(!q('input[name="maintenance_option"]:checked')?.value&&!qa('input[name="extra_services[]"]:checked').length)return err('Selecteer minimaal 1 werkzaamheid.'),false;return true}if(n===3){if(!(q('#wa-date')?.value||sD))return err('Selecteer een datum.'),false;if(!(q('#wa-time')?.value||sT))return err('Selecteer een tijdstip.'),false;const w=q('input[name="wait_while_service"]:checked')?.value;if(w!=='1'&&w!=='0')return err('Geef aan of u wilt wachten.'),false;return true}if(n===4){if(!(q('input[name="first_name"]')?.value||'').trim())return err('Voornaam is verplicht.'),false;if(!(q('input[name="last_name"]')?.value||'').trim())return err('Achternaam is verplicht.'),false;if(!(q('input[name="email"]')?.value||'').trim())return err('E-mail is verplicht.'),false;const t=q('input[name="terms_accepted"]');if(t&&!t.checked)return err('Accepteer de voorwaarden.'),false;return true}return true}
    function bind(){['#wa-license','#wa-mileage','input[name="maintenance_option"]','input[name="extra_services[]"]','input[name="wait_while_service"]','input[name="first_name"]','input[name="middle_name"]','input[name="last_name"]','input[name="email"]'].forEach(s=>qa(s).forEach(el=>{el.addEventListener('input',sync);el.addEventListener('change',sync)}));renderCal();sync();show(1,{scroll:false})}
    return{next,prev,acc,calPrev,calNext,pickTime,sync,init:bind}
  })();
  window.WA.init();
  function dl(){if(location.hash==='#verkopen'&&window.SellCar)SellCar.open()}dl();window.addEventListener('hashchange',dl);
});
</script>

@endsection
