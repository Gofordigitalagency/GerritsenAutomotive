{{-- Smart workshop booking widget — herbruikbaar op homepage en /werkplaats. --}}
<div class="px-smart-card px-reveal" id="pxSmartCard">
  <div class="px-smart-progress">
    <span class="px-smart-step active" data-s="1">01 · Kenteken</span>
    <span class="px-smart-divider"></span>
    <span class="px-smart-step" data-s="2">02 · Werkzaamheden</span>
    <span class="px-smart-divider"></span>
    <span class="px-smart-step" data-s="3">03 · Plannen</span>
  </div>

  {{-- STEP 1 — kenteken --}}
  <div class="px-smart-step-body px-active" data-step="1">
    <h3 class="px-smart-q">Welk kenteken?</h3>
    <p class="px-smart-help">We koppelen direct met de RDW. Geen account nodig.</p>

    <form class="px-smart-plate-form" id="pxSmartForm" autocomplete="off">
      <label class="px-plate-big" for="pxSmartPlate">
        <span class="px-plate-nl-big">
          <span class="px-plate-stars">★★★</span>
          NL
        </span>
        <input type="text" id="pxSmartPlate" name="kenteken" placeholder="00-XXX-0" maxlength="10" autocapitalize="characters" spellcheck="false">
      </label>
      <button type="submit" class="px-btn px-btn-primary px-btn-lg" id="pxSmartLookup" data-magnetic>
        <span class="px-smart-btn-label">Zoek mijn auto →</span>
        <span class="px-spinner" aria-hidden="true"></span>
      </button>
    </form>

    <div class="px-smart-error" id="pxSmartError" hidden></div>
  </div>

  {{-- STEP 2 — service tegels (verschijnt na RDW match) --}}
  <div class="px-smart-step-body" data-step="2">
    <div class="px-smart-found" id="pxSmartFound">
      <div class="px-smart-found-head">
        <div>
          <div class="px-smart-found-label">Gevonden</div>
          <div class="px-smart-found-name" id="pxSmartName"></div>
          <div class="px-smart-found-meta" id="pxSmartMeta"></div>
        </div>
        <button type="button" class="px-smart-edit" id="pxSmartEdit">Wijzig</button>
      </div>
      <div class="px-smart-apk" id="pxSmartApk" hidden>
        <span class="px-smart-apk-label">APK-vervaldatum</span>
        <span class="px-smart-apk-value" id="pxSmartApkValue"></span>
        <span class="px-smart-apk-badge" id="pxSmartApkBadge"></span>
      </div>
    </div>

    <h3 class="px-smart-q">Wat moet er gebeuren?</h3>
    <div class="px-smart-services" id="pxSmartServices">
      <button type="button" class="px-service" data-svc="APK keuring" data-key="apk">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
        </span>
        <span class="px-service-name">APK keuring</span>
        <span class="px-service-hint">Verplichte jaarlijkse keuring</span>
      </button>
      <button type="button" class="px-service" data-svc="Kleine beurt" data-key="kleine">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </span>
        <span class="px-service-name">Kleine beurt</span>
        <span class="px-service-hint">Olie + filters</span>
      </button>
      <button type="button" class="px-service" data-svc="Grote beurt" data-key="grote">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </span>
        <span class="px-service-name">Grote beurt</span>
        <span class="px-service-hint">Volledige onderhoudsbeurt</span>
      </button>
      <button type="button" class="px-service" data-svc="Onderhoud volgens service indicatie" data-key="indicatie">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </span>
        <span class="px-service-name">Service indicatie</span>
        <span class="px-service-hint">Volgens dashboard</span>
      </button>
      <button type="button" class="px-service" data-svc="Aankoopkeuring" data-key="aankoop">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-3.5-3.5"/><circle cx="11" cy="11" r="7"/></svg>
        </span>
        <span class="px-service-name">Aankoopkeuring</span>
        <span class="px-service-hint">Voor je een auto koopt</span>
      </button>
      <button type="button" class="px-service" data-svc="Airco service" data-key="airco">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="2" y1="12" x2="22" y2="12"/><line x1="12" y1="2" x2="12" y2="22"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4 4 4"/></svg>
        </span>
        <span class="px-service-name">Airco service</span>
        <span class="px-service-hint">Vullen, controle of onderhoud</span>
      </button>
      <button type="button" class="px-service" data-svc="Anders" data-key="anders">
        <span class="px-service-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        </span>
        <span class="px-service-name">Iets anders</span>
        <span class="px-service-hint">Beschrijven of bellen</span>
      </button>
    </div>

    <div class="px-smart-actions">
      <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
      <button type="button" class="px-btn px-btn-primary" id="pxSmartNext" disabled>Door naar plannen →</button>
    </div>
  </div>

  {{-- STEP 3 — plannen --}}
  <div class="px-smart-step-body" data-step="3">
    <h3 class="px-smart-q">Wanneer komt het uit?</h3>
    <div class="px-smart-summary" id="pxSmartSummary"></div>

    <div class="px-smart-pick-label">
      <span>Kies een dag</span>
      <span class="px-smart-pick-hint">Werkplaats geopend di–za</span>
    </div>
    <div class="px-day-strip" id="pxDayStrip"></div>

    <div class="px-smart-pick-label">
      <span>Kies een tijd</span>
      <span class="px-smart-pick-hint" id="pxSelectedDate"></span>
    </div>
    <div class="px-time-strip" id="pxTimeStrip"></div>

    <form class="px-smart-contact" id="pxSmartContactForm" method="POST" action="{{ route('contact.store') }}">
      @csrf
      <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
      <input type="hidden" name="message" id="pxSmartMessage">
      <input type="hidden" name="privacy" value="1">

      <div class="px-input-wrap">
        <label for="pxContactName">Voornaam</label>
        <input type="text" id="pxContactName" name="name" required maxlength="120">
      </div>
      <div class="px-input-wrap">
        <label for="pxContactPhone">Telefoon</label>
        <input type="tel" id="pxContactPhone" name="phone" maxlength="40">
      </div>
      <div class="px-input-wrap">
        <label for="pxContactEmail">E-mail</label>
        <input type="email" id="pxContactEmail" name="email" required maxlength="190">
      </div>

      <div class="px-smart-actions">
        <button type="button" class="px-btn px-btn-ghost" data-prev>← Terug</button>
        <button type="submit" class="px-btn px-btn-primary px-btn-lg" id="pxSmartConfirm" data-magnetic>
          <span class="px-smart-btn-label">Bevestig afspraak →</span>
          <span class="px-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>

    <div class="px-smart-form-error" id="pxSmartFormError" hidden></div>

    <p class="px-smart-fallback">
      Liever even bellen? <a href="tel:{{ setting_tel('contact.phone_workshop') }}">{{ setting('contact.phone_workshop') }}</a>
    </p>
  </div>

  {{-- SUCCESS state — na succesvolle submit --}}
  <div class="px-smart-step-body px-smart-success" data-step="success">
    <div class="px-smart-success-icon">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
    </div>
    <h3 class="px-smart-q">Aanvraag verzonden.</h3>
    <p class="px-smart-success-text">We nemen zo snel mogelijk contact met je op om de afspraak te bevestigen.</p>
    <div class="px-smart-success-summary" id="pxSmartSuccessSummary"></div>
    <button type="button" class="px-btn px-btn-ghost" id="pxSmartReset">Nog een afspraak plannen</button>
  </div>
</div>
