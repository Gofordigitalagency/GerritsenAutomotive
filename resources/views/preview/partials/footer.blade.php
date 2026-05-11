{{-- Gedeelde footer + WhatsApp FAB voor alle preview-pagina's. --}}
<footer class="px-footer px-reveal" id="contact">
  <div class="px-container">
    <div class="px-footer-grid">
      <div>
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive" class="px-footer-logo">
        <p class="px-footer-about">Jouw vertrouwde adres in Arnhem voor occasions, werkplaats en verhuur.</p>
      </div>

      <div>
        <h4>Contact</h4>
        <ul class="px-footer-list">
          <li><a href="tel:{{ setting_tel('contact.phone_sales') }}">{{ setting('contact.phone_sales') }}</a></li>
          <li><a href="mailto:{{ setting('contact.email') }}">{{ setting('contact.email') }}</a></li>
          <li>{{ setting('contact.address') }}</li>
        </ul>
      </div>

      <div>
        <h4>Navigeer</h4>
        <ul class="px-footer-list">
          <li><a href="/aanbod">Aanbod</a></li>
          <li><a href="/werkplaats">Werkplaats</a></li>
          <li><a href="/diensten">Diensten</a></li>
          <li><a href="/over">Over ons</a></li>
        </ul>
      </div>

      <div>
        <h4>Diensten</h4>
        <ul class="px-footer-list">
          <li><a href="{{ route('booking.show', ['type' => 'aanhanger']) }}">Aanhanger huren</a></li>
          <li><a href="{{ route('booking.show', ['type' => 'stofzuiger']) }}">Stofzuiger</a></li>
          <li><a href="{{ route('booking.show', ['type' => 'koplampen']) }}">Koplampen polish</a></li>
          <li><a href="{{ route('booking.show', ['type' => 'leenauto']) }}">Leenauto</a></li>
          <li><a href="{{ route('occasions.binnenkort') }}">Binnenkort</a></li>
          <li><a href="{{ route('sellcar.show') }}">Auto verkopen</a></li>
        </ul>
      </div>
    </div>

    <div class="px-footer-bottom">
      <span>© {{ date('Y') }} Gerritsen Automotive.</span>
    </div>
  </div>
</footer>

<a href="https://wa.me/{{ ltrim(setting_tel('contact.phone_sales'), '+') }}" class="px-whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0 0 20.464 3.488"/></svg>
</a>
