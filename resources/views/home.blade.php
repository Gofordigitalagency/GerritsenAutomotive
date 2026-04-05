@extends('layout')

@section('content')

{{-- ═══════ HERO — fullscreen, clean ═══════ --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <h1 class="hero-h1" data-a>Gerritsen<br><span>Automotive</span></h1>
        <p class="hero-p" data-a>Occasions · Werkplaats · Verhuur</p>
        <div class="hero-ctas" data-a>
            <a href="#aanbod" class="btn btn-red">Bekijk auto's</a>
            <a href="#footer" class="btn btn-outline">Contact</a>
        </div>
        <div class="hero-scroll" data-a>
            <div class="hero-scroll-line"></div>
        </div>
    </div>
</section>

{{-- ═══════ AUTO'S — meteen, geen intro ═══════ --}}
<section id="aanbod" class="sec-cars">
  <div class="container">
    <div class="sec-cars-top" data-a>
      <h2>Ons aanbod</h2>
      <div class="oc-sort">
        <select id="sort">
          <option value="best">Sorteren</option>
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
    <div id="nieuwGrid" class="cars" data-s>
      @include('occasions.partials.home_cards', ['nieuw' => $nieuw])
    </div>
    <div class="cta-center" id="aanbodCta" style="display:none">
      <button id="btnBekijkAanbod" class="btn btn-red" type="button">Toon alles</button>
    </div>
  </div>
</section>

{{-- ═══════ BENTO — diensten als grid met grote+kleine blokken ═══════ --}}
<section id="diensten" class="sec-bento">
  <div class="container">
    <h2 class="bento-title" data-a>Meer dan alleen auto's</h2>
    <div class="bento" data-s>

      <a href="#wa" class="bento-card bento-big" data-a>
        <img src="{{ asset('images/car-repair-maintenance-theme-mechanic-uniform-working-auto-service.jpg') }}" alt="" class="bento-bg">
        <div class="bento-content">
          <i class="fas fa-wrench"></i>
          <h3>Werkplaats</h3>
          <p>Onderhoud, reparatie & APK</p>
        </div>
      </a>

      <a href="#verhuur" class="bento-card" data-a>
        <div class="bento-content bento-solid" style="background:var(--red)">
          <i class="fas fa-key"></i>
          <h3>Auto huren</h3>
          <p>Vanaf &euro;35/dag</p>
        </div>
      </a>

      <a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="bento-card" data-a>
        <div class="bento-content bento-solid">
          <i class="fas fa-trailer"></i>
          <h3>Aanhanger</h3>
          <p>Vanaf &euro;15</p>
        </div>
      </a>

      <div class="bento-card" data-a onclick="SellCar.open()" style="cursor:pointer">
        <div class="bento-content bento-solid">
          <i class="fas fa-hand-holding-dollar"></i>
          <h3>Auto verkopen</h3>
          <p>Snel & eerlijk bod</p>
        </div>
      </div>

      <a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="bento-card bento-wide" data-a>
        <img src="{{ asset('images/head-lights-car.jpg') }}" alt="" class="bento-bg">
        <div class="bento-content">
          <i class="fas fa-lightbulb"></i>
          <h3>Koplampen polijsten</h3>
          <p>Helder zicht, frisse look</p>
        </div>
      </a>

      <a href="{{ route('booking.show', ['type' => 'aanhanger']) }}" class="bento-card" data-a>
        <div class="bento-content bento-solid">
          <i class="fas fa-spray-can-sparkles"></i>
          <h3>Tapijtreiniger</h3>
          <p>&euro;25/dag</p>
        </div>
      </a>

    </div>
  </div>
</section>

{{-- ═══════ OVER ONS — compact, horizontaal ═══════ --}}
<section id="info" class="sec-about">
  <div class="container">
    <div class="about-grid" data-a>
      <div class="about-img"><img src="{{ asset('images/handshake.jpg') }}" alt=""></div>
      <div class="about-text">
        <h2>10+ jaar vertrouwd in Arnhem</h2>
        <p>Gerritsen Automotive staat voor transparante prijzen, eerlijk advies en snel schakelen. Geen poespas, wel resultaat.</p>
        <div class="about-stats">
          <div><strong>200+</strong><span>Auto's verkocht</span></div>
          <div><strong>&#9733; 5.0</strong><span>Google reviews</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════ VERHUUR — horizontale slider ═══════ --}}
<section id="verhuur" class="sec-rental">
  <div class="container">
    <div class="rental-row" data-a>
      <div class="rental-info">
        <span class="rental-price">Vanaf &euro;35/dag</span>
        <h2>Toyota Aygo Premium</h2>
        <ul>
          <li>Apple CarPlay</li><li>Lederen interieur</li><li>Airco</li><li>Onbeperkte KM</li>
        </ul>
        <div class="rental-btns">
          <a href="#footer" class="btn btn-red">Reserveer</a>
          <a href="tel:+31649951874" class="btn btn-outline">Bel direct</a>
        </div>
        <button class="rental-terms" type="button" onclick="openVoorwaarden()">Voorwaarden</button>
      </div>
      <div class="rental-photos" id="rentalScroll">
        @foreach(['WhatsApp Image 2026-02-25 at 08.05.40.jpeg','WhatsApp Image 2026-02-25 at 08.05.41 (1).jpeg','WhatsApp Image 2026-02-25 at 08.05.41 (2).jpeg','WhatsApp Image 2026-02-25 at 08.05.41 (3).jpeg','WhatsApp Image 2026-02-25 at 08.05.41 (4).jpeg','WhatsApp Image 2026-02-25 at 08.05.41 (5).jpeg','WhatsApp Image 2026-02-25 at 08.05.41.jpeg'] as $img)
          <img src="{{ asset('images/'.$img) }}" alt="Aygo">
        @endforeach
      </div>
    </div>
  </div>
</section>
<div class="ga-modal-overlay" id="gaVoorwaardenModal"><div class="ga-modal"><span class="ga-modal-close" onclick="closeVoorwaarden()">&times;</span><h3>Huurvoorwaarden</h3><ul><li>Borg &euro;250</li><li>Eigen risico &euro;500</li><li>Volgetankt retour</li><li>Niet roken</li></ul></div></div>

{{-- ═══════ WERKPLAATS — als overlay-trigger + inline form ═══════ --}}
<section id="wa" class="sec-workshop">
  <div class="container">
    <div class="ws-header" data-a>
      <h2>Werkplaatsafspraak</h2>
      <p>Selecteer, plan en bevestig in 4 stappen.</p>
    </div>
    <div class="ws-pills" data-a>
      <button type="button" class="wa-pill is-active" data-step="1"><span class="wa-pill-num">1</span> Auto</button>
      <button type="button" class="wa-pill" data-step="2"><span class="wa-pill-num">2</span> Werk</button>
      <button type="button" class="wa-pill" data-step="3"><span class="wa-pill-num">3</span> Tijd</button>
      <button type="button" class="wa-pill" data-step="4"><span class="wa-pill-num">4</span> Contact</button>
    </div>
    <div class="ws-form-wrap" data-a>
      <form id="wa-form" method="POST" action="{{ route('workshop.finish') }}">
        @csrf
        {{-- STEP 1 --}}
        <section class="wa-panel wa-show" data-panel="1">
          <div class="ws-step-title">Kenteken & kilometerstand</div>
          <div class="wa-row">
            <div class="wa-plate"><div class="wa-plate-nl">NL</div><input class="wa-plate-input" name="license_plate" id="wa-license" type="text" placeholder="AB-123-C" autocomplete="off" maxlength="9"></div>
            <div class="wa-km"><input class="wa-km-input" name="mileage" id="wa-mileage" type="number" min="0" placeholder="Kilometerstand"><div class="wa-km-suf">KM</div></div>
          </div>
          <div class="wa-actions wa-actions-right"><button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">Volgende &rsaquo;</button></div>
        </section>
        {{-- STEP 2 --}}
        <section class="wa-panel" data-panel="2">
          <div class="ws-step-title">Selecteer werkzaamheden</div>
          <div class="wa-block-title">ONDERHOUD</div>
          <div class="wa-list">@php $maintenance=config('workshop_services.maintenance');@endphp @foreach($maintenance as $opt)<label class="wa-item"><span class="wa-radio"><input type="radio" name="maintenance_option" value="{{ $opt }}" onchange="WA.sync()"><span class="wa-radio-ui"></span></span><span class="wa-item-text">{{ $opt }}</span></label>@endforeach</div>
          <div class="wa-block-title wa-mt">AANVULLEND</div>
          <div class="wa-list">@php $extras=config('workshop_services.extras');@endphp @foreach($extras as $ex)<label class="wa-item"><span class="wa-check"><input type="checkbox" name="extra_services[]" value="{{ $ex }}" onchange="WA.sync()"><span class="wa-check-ui"></span></span><span class="wa-item-text">{{ $ex }}</span></label>@endforeach</div>
          <div class="wa-actions"><button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">&lsaquo; Terug</button><button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">Volgende &rsaquo;</button></div>
        </section>
        {{-- STEP 3 --}}
        <section class="wa-panel" data-panel="3">
          <div class="ws-step-title">Kies datum & tijd</div>
          <input type="hidden" name="appointment_date" id="wa-date"><input type="hidden" name="appointment_time" id="wa-time">
          <div class="wa-cal"><div class="wa-cal-head"><button class="wa-cal-nav" type="button" onclick="WA.calPrev()">&lsaquo;</button><div class="wa-cal-month" id="wa-cal-month"></div><button class="wa-cal-nav" type="button" onclick="WA.calNext()">&rsaquo;</button></div><div class="wa-cal-week"><div>Ma</div><div>Di</div><div>Wo</div><div>Do</div><div>Vr</div><div>Za</div><div>Zo</div></div><div class="wa-cal-grid" id="wa-cal-grid"></div></div>
          <div class="wa-split"><div class="wa-card"><div class="wa-card-title">Wachten?</div><label class="wa-radio-row"><input type="radio" name="wait_while_service" value="1" onchange="WA.sync()"> Ja</label><label class="wa-radio-row"><input type="radio" name="wait_while_service" value="0" onchange="WA.sync()"> Nee</label></div><div class="wa-card"><div class="wa-card-title">Tijd</div><div class="wa-times"><button type="button" class="wa-time" data-time="08:00" onclick="WA.pickTime(this)">08:00</button><button type="button" class="wa-time" data-time="09:00" onclick="WA.pickTime(this)">09:00</button><button type="button" class="wa-time" data-time="09:30" onclick="WA.pickTime(this)">09:30</button></div></div></div>
          <div class="wa-actions"><button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">&lsaquo; Terug</button><button class="wa-btn wa-btn-primary" type="button" onclick="WA.next()">Volgende &rsaquo;</button></div>
        </section>
        {{-- STEP 4 --}}
        <section class="wa-panel" data-panel="4">
          <div class="ws-step-title">Uw gegevens</div>
          <div class="wa-2col"><label class="wa-field"><span>Voornaam *</span><input type="text" name="first_name" required></label><label class="wa-field"><span>Achternaam *</span><input type="text" name="last_name" required></label></div>
          <div class="wa-2col"><label class="wa-field"><span>E-mail *</span><input type="email" name="email" required></label><label class="wa-field"><span>Telefoon</span><input type="text" name="phone"></label></div>
          <label class="wa-field"><span>Opmerkingen</span><textarea name="remarks" rows="3" placeholder="Vragen of opmerkingen"></textarea></label>
          <input type="hidden" name="company_name" value=""><input type="hidden" name="middle_name" value=""><input type="hidden" name="salutation" value=""><input type="hidden" name="street" value=""><input type="hidden" name="house_number" value=""><input type="hidden" name="addition" value=""><input type="hidden" name="postal_code" value=""><input type="hidden" name="city" value="">
          <label class="wa-checkline"><input type="checkbox" name="terms_accepted" required><span>Ik ga akkoord met de <a href="/privacy" target="_blank">voorwaarden</a></span></label>
          <div class="wa-actions"><button class="wa-btn wa-btn-ghost" type="button" onclick="WA.prev()">&lsaquo; Terug</button><button class="wa-btn wa-btn-primary" type="submit">Versturen</button></div>
        </section>
      </form>
    </div>
    <span id="ov-plate" hidden></span><span id="ov-km" hidden></span><span id="ov-main" hidden></span><span id="ov-extra" hidden></span><span id="ov-date" hidden></span><span id="ov-time" hidden></span><span id="ov-wait" hidden></span><span id="ov-name" hidden></span><span id="ov-email" hidden></span>
  </div>
</section>

{{-- ═══════ OPENINGSTIJDEN — minimalistische strip ═══════ --}}
<section class="sec-hours">
  <div class="container">
    <div class="hours-row" data-a>
      <h3>Openingstijden</h3>
      <div class="hours-times">
        <div><strong>Ma–Vr</strong> 08:30–17:30</div>
        <div><strong>Za</strong> 09:00–16:00</div>
        <div><strong>Zo</strong> Gesloten</div>
      </div>
      <a href="#footer" class="btn btn-red btn-sm">Afspraak maken</a>
    </div>
  </div>
</section>

{{-- ═══════ AUTO VERKOPEN — sell-car modal (trigger + form) ═══════ --}}
<div id="sellcar-overlay" class="sc-overlay" aria-hidden="true" onclick="SellCar.close()">
  <div class="sc-modal" onclick="event.stopPropagation()">
    <button class="sc-close" onclick="SellCar.close()">&times;</button>
    <h2 class="sc-title">Auto verkopen</h2>
    <form id="sellcar-form" class="sc-form" action="{{ route('sellcar.store') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      <h3 class="sc-subtitle">Autogegevens</h3>
      <div class="sc-grid">
        <label class="sc-input"><span>Merk</span><input name="brand" type="text"></label>
        <label class="sc-input"><span>Model</span><input name="model" type="text"></label>
        <label class="sc-input"><span>Kenteken *</span><input name="license_plate" type="text" required></label>
        <label class="sc-input"><span>KM-stand *</span><input name="mileage" type="number" min="0" required></label>
      </div>
      <div class="sc-field"><span class="sc-label">Opties</span><div class="sc-options">@php $opts=['Airco','Climate control','Cruise control','Elektrische ramen voor','Elektrische ramen achter','Schuifdak','Panoramadak','Lichtmetalen velgen','Navigatie','Multifunctioneel stuur','Xenon verlichting','Lederen bekleding','Stoelverwarming','Parkeersensoren','Elektrische stoelverstelling','Metallic lak','Elektrische spiegels'];@endphp @foreach($opts as $o)<label class="sc-check"><input type="checkbox" name="options[]" value="{{ $o }}"><span>{{ $o }}</span></label>@endforeach</div></div>
      <div class="sc-field"><span class="sc-label">Foto's</span><div id="sc-drop" class="sc-drop"><strong>Sleep bestanden</strong><span>of</span><label class="sc-browse">Blader<input id="photos" name="photos[]" type="file" accept="image/*" multiple hidden></label><div class="sc-count"><span id="sc-count">0</span>/20</div></div><div id="sc-preview" class="sc-preview"></div></div>
      <label class="sc-input"><span>Bijzonderheden</span><textarea name="remarks" rows="3"></textarea></label>
      <h3 class="sc-subtitle">Uw gegevens</h3>
      <label class="sc-input"><span>Naam *</span><input name="name" type="text" required></label>
      <label class="sc-input"><span>Telefoon *</span><input name="phone" type="tel" required></label>
      <label class="sc-input"><span>E-mail *</span><input name="email" type="email" required></label>
      <label class="sc-privacy"><input name="privacy" type="checkbox" required><span>Akkoord met <a href="/privacy" target="_blank">privacybeleid</a></span></label>
      <button class="sc-submit" type="submit">Verzenden</button>
    </form>
  </div>
</div>
@if(session('success'))<div style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#22c55e;color:#fff;padding:14px 24px;border-radius:12px;font-weight:600;font-size:14px;box-shadow:0 8px 30px rgba(0,0,0,.3)" id="toast">{{ session('success') }}</div><script>setTimeout(()=>document.getElementById('toast')?.remove(),4000)</script>@endif
@if($errors->any())<div style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#ef4444;color:#fff;padding:14px 24px;border-radius:12px;font-weight:600;font-size:14px;box-shadow:0 8px 30px rgba(0,0,0,.3)" id="toast"><ul style="margin:0;padding-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div><script>setTimeout(()=>document.getElementById('toast')?.remove(),6000)</script>@endif

{{-- ═══════ FOOTER ═══════ --}}
<section id="footer" class="sec-footer">
  <div class="container">
    <div class="footer-top" data-a>
      <div class="footer-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen" class="footer-logo">
        <p>Gelderse Rooslaan 14 A<br>6841 BE Arnhem</p>
        <div class="footer-links">
          <a href="tel:+31638257987"><i class="fas fa-phone"></i> Verkoop</a>
          <a href="tel:+31649951874"><i class="fas fa-phone"></i> Werkplaats</a>
          <a href="mailto:Handelsonderneming@mgerritsen.nl"><i class="fas fa-envelope"></i> E-mail</a>
        </div>
        <img src="{{ asset('images/Garage-footer.png') }}" alt="NAP" style="width:200px;margin-top:16px">
      </div>
      <div class="footer-form">
        <h3>Stuur een bericht</h3>
        <form method="POST" action="{{ route('contact.store') }}" novalidate>
          @csrf<input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
          <div class="ff-row"><input type="text" name="name" placeholder="Naam *" value="{{ old('name') }}" required><input type="text" name="phone" placeholder="Telefoon" value="{{ old('phone') }}"></div>
          <input type="email" name="email" placeholder="Email *" value="{{ old('email') }}" required>
          <textarea name="message" rows="3" placeholder="Bericht *" required>{{ old('message') }}</textarea>
          <label class="ff-check"><input type="checkbox" name="privacy" required><span>Privacybeleid gelezen</span></label>
          <button type="submit" class="btn btn-red" style="width:100%">Verzenden</button>
        </form>
        @if(session('success'))<div id="contactModal" class="gd-modal"><div class="gd-modal-box"><h3>Bedankt!</h3><p>We nemen snel contact op.</p><div class="gd-actions"><button type="button" data-close-modal>OK</button></div></div></div>@endif
      </div>
    </div>
  </div>
</section>

<div class="contact-map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2458.0517541550735!2d5.901556076625399!3d51.96948127192204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c7a70883823355%3A0xa6dd7685a8359450!2sGerritsen%20Automotive!5e0!3m2!1sen!2snl!4v1761597008139!5m2!1sen!2snl" width="600" height="450" style="border:0" allowfullscreen loading="lazy"></iframe></div>
<div class="rechten-section"><div class="container"><p>&copy; Gerritsen Automotive 2025</p></div></div>

{{-- ═══════ JS ═══════ --}}
<script>
document.addEventListener('DOMContentLoaded',function(){
  // contact modal
  var m=document.getElementById('contactModal');if(m){function cl(){m.remove()}m.querySelectorAll('[data-close-modal]').forEach(b=>b.addEventListener('click',cl));m.addEventListener('click',e=>{if(e.target===m)cl()});document.addEventListener('keydown',e=>{if(e.key==='Escape')cl()})}
  window.openVoorwaarden=()=>document.getElementById('gaVoorwaardenModal').classList.add('active');
  window.closeVoorwaarden=()=>document.getElementById('gaVoorwaardenModal').classList.remove('active');

  // SellCar
  window.SellCar=(function(){const ov=()=>document.getElementById('sellcar-overlay'),dr=()=>document.getElementById('sc-drop'),fi=()=>document.getElementById('photos'),pv=()=>document.getElementById('sc-preview'),ct=()=>document.getElementById('sc-count');let files=[];function open(){if(ov()){ov().classList.add('is-open');ov().setAttribute('aria-hidden','false')}}function close(){if(ov()){ov().classList.remove('is-open');ov().setAttribute('aria-hidden','true')}}function upd(){if(ct())ct().textContent=files.length}function ren(){if(!pv())return;pv().innerHTML='';files.forEach((f,i)=>{const u=URL.createObjectURL(f),d=document.createElement('div');d.className='sc-thumb';d.innerHTML=`<img src="${u}"><button type="button" data-i="${i}">&times;</button>`;pv().appendChild(d)})}function syn(){if(!fi())return;const dt=new DataTransfer();files.forEach(f=>dt.items.add(f));fi().files=dt.files}function add(l){for(const f of l){if(files.length>=20)break;if(!f.type?.startsWith('image/'))continue;files.push(f)}upd();ren();syn()}if(fi())fi().addEventListener('change',e=>add(e.target.files));if(dr()){['dragenter','dragover'].forEach(ev=>dr().addEventListener(ev,e=>{e.preventDefault();dr().classList.add('dragover')}));['dragleave','drop'].forEach(ev=>dr().addEventListener(ev,e=>{e.preventDefault();dr().classList.remove('dragover')}));dr().addEventListener('drop',e=>add(e.dataTransfer.files))}if(pv())pv().addEventListener('click',e=>{const b=e.target.closest('button[data-i]');if(!b)return;files.splice(+b.dataset.i,1);upd();ren();syn()});const fm=document.getElementById('sellcar-form');if(fm)fm.addEventListener('submit',e=>{if(!fm.checkValidity()){e.preventDefault();fm.reportValidity()}});return{open,close}})();

  // Occasions
  (function(){const sel=document.getElementById('sort'),grid=document.getElementById('nieuwGrid'),cta=document.getElementById('aanbodCta'),btn=document.getElementById('btnBekijkAanbod');if(!sel||!grid)return;let exp=false;function ui(){const c=[...grid.querySelectorAll('.car-card')],more=c.length>3;if(cta)cta.style.display=more?'':'none';if(!btn){c.forEach((x,i)=>x.classList.toggle('is-hidden',i>2));return}if(!exp){c.forEach((x,i)=>x.classList.toggle('is-hidden',i>2));btn.textContent='Toon alles'}else{c.forEach(x=>x.classList.remove('is-hidden'));btn.textContent='Toon minder'}}ui();if(btn)btn.addEventListener('click',()=>{exp=!exp;ui()});sel.addEventListener('change',async()=>{exp=false;try{const r=await fetch(`{{ route('occasions.cards') }}?sort=${encodeURIComponent(sel.value)}`,{headers:{'X-Requested-With':'XMLHttpRequest'}});if(!r.ok)return;grid.innerHTML=await r.text();ui();history.replaceState(null,'',`/?sort=${encodeURIComponent(sel.value)}#aanbod`)}catch(e){console.error(e)}})})();

  // Workshop
  window.WA=(()=>{let step=1,cY=new Date().getFullYear(),cM=new Date().getMonth(),sD='',sT='';const M=["JANUARI","FEBRUARI","MAART","APRIL","MEI","JUNI","JULI","AUGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DECEMBER"];const q=(s,r=document)=>r.querySelector(s),qa=(s,r=document)=>[...r.querySelectorAll(s)];const pad=n=>String(n).padStart(2,'0'),ymd=(y,m,d)=>`${y}-${pad(m+1)}-${pad(d)}`;function fN(s){if(!s)return'-';const[y,m,d]=s.split('-');return`${d}-${m}-${y}`}function nP(v){return(v||'').toString().trim().toUpperCase()}function fK(v){if(v===''||v==null)return'-';const n=Number(v);return isNaN(n)?'-':n.toLocaleString('nl-NL')}
  function show(n,o){o=o||{};step=n;qa('.wa-panel').forEach(p=>p.classList.remove('wa-show'));const p=q(`.wa-panel[data-panel="${n}"]`);if(p)p.classList.add('wa-show');qa('.wa-pill').forEach(pill=>{const sn=+pill.dataset.step;pill.classList.remove('is-active','is-done');if(sn===n)pill.classList.add('is-active');else if(sn<n)pill.classList.add('is-done')});sync();if(o.scroll!==false)window.scrollTo({top:q('#wa')?.offsetTop-20||0,behavior:'smooth'})}
  function next(){if(!val(step))return;if(step<4)show(step+1)}function prev(){if(step>1)show(step-1)}
  function renderCal(){const mE=q('#wa-cal-month'),gE=q('#wa-cal-grid');if(!mE||!gE)return;mE.textContent=`${M[cM]} ${cY}`;gE.innerHTML='';let sd=new Date(cY,cM,1).getDay();sd=sd===0?7:sd;for(let i=0;i<sd-1;i++){const d=document.createElement('div');d.className='wa-day wa-off';gE.appendChild(d)}const dm=new Date(cY,cM+1,0).getDate();for(let day=1;day<=dm;day++){const b=document.createElement('button');b.type='button';b.className='wa-day';const v=ymd(cY,cM,day);b.textContent=day;if(sD===v)b.classList.add('wa-picked');b.addEventListener('click',e=>pickD(v,e.currentTarget));gE.appendChild(b)}}
  function calPrev(){cM--;if(cM<0){cM=11;cY--}renderCal()}function calNext(){cM++;if(cM>11){cM=0;cY++}renderCal()}
  function pickD(v,el){sD=v;const h=q('#wa-date');if(h)h.value=v;qa('#wa-cal-grid .wa-day').forEach(b=>b.classList.remove('wa-picked'));if(el)el.classList.add('wa-picked');sync()}
  function pickTime(b){const t=b.dataset.time;sT=t;const h=q('#wa-time');if(h)h.value=t;qa('.wa-time').forEach(b=>b.classList.remove('wa-picked'));b.classList.add('wa-picked');sync()}
  function sync(){const pl=nP(q('#wa-license')?.value),km=q('#wa-mileage')?.value;if(q('#ov-plate'))q('#ov-plate').textContent=pl||'-';if(q('#ov-km'))q('#ov-km').textContent=km?`${fK(km)} km`:'-';const mt=q('input[name="maintenance_option"]:checked')?.value||'';const ex=qa('input[name="extra_services[]"]:checked').map(x=>x.value);if(q('#ov-main'))q('#ov-main').textContent=mt||'-';if(q('#ov-extra'))q('#ov-extra').textContent=ex.length?ex.join(', '):'-';const d=q('#wa-date')?.value||sD||'',t=q('#wa-time')?.value||sT||'';const w=q('input[name="wait_while_service"]:checked')?.value;if(q('#ov-date'))q('#ov-date').textContent=d?fN(d):'-';if(q('#ov-time'))q('#ov-time').textContent=t?t+' uur':'-';if(q('#ov-wait'))q('#ov-wait').textContent=w==='1'?'Ja':w==='0'?'Nee':'-';const fn=(q('input[name="first_name"]')?.value||'').trim(),ln=(q('input[name="last_name"]')?.value||'').trim(),em=(q('input[name="email"]')?.value||'').trim();if(q('#ov-name'))q('#ov-name').textContent=[fn,ln].filter(Boolean).join(' ')||'-';if(q('#ov-email'))q('#ov-email').textContent=em||'-'}
  function err(m){alert(m)}
  function val(n){if(n===1)return nP(q('#wa-license')?.value)?(true):(err('Vul uw kenteken in.'),false);if(n===2){if(!q('input[name="maintenance_option"]:checked')?.value&&!qa('input[name="extra_services[]"]:checked').length)return err('Selecteer minimaal 1 werkzaamheid.'),false;return true}if(n===3){if(!(q('#wa-date')?.value||sD))return err('Selecteer een datum.'),false;if(!(q('#wa-time')?.value||sT))return err('Selecteer een tijdstip.'),false;const w=q('input[name="wait_while_service"]:checked')?.value;if(w!=='1'&&w!=='0')return err('Geef aan of u wilt wachten.'),false;return true}if(n===4){if(!(q('input[name="first_name"]')?.value||'').trim())return err('Voornaam verplicht.'),false;if(!(q('input[name="last_name"]')?.value||'').trim())return err('Achternaam verplicht.'),false;if(!(q('input[name="email"]')?.value||'').trim())return err('E-mail verplicht.'),false;const t=q('input[name="terms_accepted"]');if(t&&!t.checked)return err('Accepteer de voorwaarden.'),false;return true}return true}
  ['#wa-license','#wa-mileage','input[name="maintenance_option"]','input[name="extra_services[]"]','input[name="wait_while_service"]','input[name="first_name"]','input[name="last_name"]','input[name="email"]'].forEach(s=>qa(s).forEach(el=>{el.addEventListener('input',sync);el.addEventListener('change',sync)}));renderCal();sync();show(1,{scroll:false});
  return{next,prev,calPrev,calNext,pickTime,sync}})();

  function dl(){if(location.hash==='#verkopen'&&window.SellCar)SellCar.open()}dl();window.addEventListener('hashchange',dl);
});
</script>

@endsection
