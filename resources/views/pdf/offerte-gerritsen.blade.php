<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Offerte Go For Digital</title>
<style>
  @page { margin: 22mm 20mm; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    color: #1a1a1a;
    font-size: 10.5pt;
    line-height: 1.6;
  }

  /* ============ HEADER ============ */
  .header { margin-bottom: 22px; }
  .header-row { display: table; width: 100%; }
  .header-left, .header-right { display: table-cell; vertical-align: middle; }
  .header-right { text-align: right; }
  .logo { width: 90px; height: 90px; object-fit: contain; }
  .header-meta { font-size: 9pt; color: #6b7280; line-height: 1.7; }
  .header-meta .key { color: #16BFB9; font-weight: bold; font-size: 9pt; letter-spacing: .5pt; text-transform: uppercase; }
  .header-meta .val { color: #0b0c10; font-weight: bold; }

  /* ============ AANHEF ============ */
  .greeting { margin: 6px 0 22px; }
  .greeting-hi {
    font-size: 18pt;
    font-weight: bold;
    color: #0b0c10;
    margin: 0 0 8px;
    letter-spacing: -0.3pt;
  }
  .greeting-hi .accent { color: #16BFB9; }
  .greeting p { margin: 0; font-size: 11pt; color: #1f2937; line-height: 1.6; }

  /* ============ SECTIONS ============ */
  .section-title {
    font-size: 11pt;
    font-weight: bold;
    color: #0b0c10;
    text-transform: uppercase;
    letter-spacing: 1.2pt;
    margin: 0 0 14px;
    padding-bottom: 6px;
    border-bottom: 2px solid #16BFB9;
  }

  .item { margin-bottom: 16px; page-break-inside: avoid; }
  .item h3 {
    font-size: 11.5pt;
    color: #0b0c10;
    margin: 0 0 6px;
    font-weight: bold;
  }
  .item h3 .num {
    display: inline-block;
    color: #16BFB9;
    font-weight: bold;
    margin-right: 6px;
  }
  .item ul {
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .item ul li {
    position: relative;
    padding: 3px 0 3px 18px;
    font-size: 10pt;
    color: #1f2937;
    line-height: 1.5;
  }
  .item ul li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 9px;
    width: 6px;
    height: 6px;
    background: #16BFB9;
    border-radius: 1px;
  }
  .item ul li b { color: #0b0c10; font-weight: bold; }

  /* ============ PRICE ============ */
  .price-box {
    margin: 22px 0 18px;
    background: #0b0c10;
    color: #fff;
    padding: 22px 28px;
    border-radius: 8px;
    page-break-inside: avoid;
  }
  .price-box-stripe { width: 50px; height: 3px; background: #16BFB9; margin-bottom: 10px; }
  .price-box-row { display: table; width: 100%; }
  .price-box-left { display: table-cell; vertical-align: middle; }
  .price-box-right { display: table-cell; vertical-align: middle; text-align: right; }
  .price-box-label {
    font-size: 9pt;
    text-transform: uppercase;
    letter-spacing: 1.2pt;
    color: #999;
    margin-bottom: 4px;
    font-weight: bold;
  }
  .price-box-value {
    font-size: 26pt;
    font-weight: bold;
    letter-spacing: -0.5pt;
    line-height: 1;
    color: #fff;
  }
  .price-box-meta {
    font-size: 9.5pt;
    color: #c8c8c8;
    line-height: 1.4;
  }

  /* ============ FOOTER INFO ============ */
  .info-row { display: table; width: 100%; margin-bottom: 22px; border-collapse: separate; border-spacing: 12px 0; }
  .info-cell {
    display: table-cell;
    width: 50%;
    vertical-align: top;
  }
  .info-cell .info-label {
    font-size: 8.5pt;
    text-transform: uppercase;
    letter-spacing: 1pt;
    color: #16BFB9;
    font-weight: bold;
    margin-bottom: 4px;
  }
  .info-cell .info-value { font-size: 10pt; color: #1f2937; line-height: 1.5; }

  /* ============ AKKOORD ============ */
  .signature {
    margin-top: 22px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
  }
  .signature-row { display: table; width: 100%; margin-top: 18px; border-collapse: separate; border-spacing: 24px 0; }
  .signature-cell { display: table-cell; width: 50%; vertical-align: top; }
  .signature-line { border-top: 1px solid #0b0c10; height: 45px; margin-bottom: 5px; }
  .signature-label {
    font-size: 8.5pt;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.8pt;
    font-weight: bold;
  }

  /* ============ FOOTER ============ */
  .footer {
    margin-top: 26px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
    font-size: 8.5pt;
    color: #9ca3af;
    text-align: center;
  }
  .footer .footer-brand { color: #16BFB9; font-weight: bold; letter-spacing: 0.5pt; }
</style>
</head>
<body>

{{-- ============ HEADER ============ --}}
<div class="header">
  <div class="header-row">
    <div class="header-left">
      <img src="{{ public_path('images/570508001_17847616710588479_7589556742439848286_n.jpg') }}" alt="Go For Digital" class="logo">
    </div>
    <div class="header-right header-meta">
      <span class="key">Datum</span> <span class="val">11 mei 2026</span><br>
      <span class="key">Voor</span> <span class="val">Mick &amp; Shania</span><br>
      <span class="key">Referentie</span> <span class="val">GA.2026.001</span>
    </div>
  </div>
</div>

{{-- ============ AANHEF ============ --}}
<div class="greeting">
  <div class="greeting-hi">Hoi <span class="accent">Mick &amp; Shania</span>,</div>
  <p>
    Hierbij onze aangepaste offerte voor het nieuwe platform van Gerritsen Automotive.
    Hieronder staat wat we voor jullie bouwen, wat het oplevert en wat het kost.
  </p>
</div>

{{-- ============ WAT WE BOUWEN ============ --}}
<h2 class="section-title">Wat we bouwen</h2>

<div class="item">
  <h3><span class="num">01.</span> Nieuwe website</h3>
  <ul>
    <li>Premium design op maat, geen sjabloon</li>
    <li>Volledig responsive (mobiel, tablet, desktop)</li>
    <li>Snelle laadtijden en SEO-klaar</li>
    <li>Slimme zoek- en filterfunctie op het aanbod</li>
    <li>Online afspraken voor werkplaats en verhuur</li>
  </ul>
</div>

<div class="item">
  <h3><span class="num">02.</span> SEO landingspages voor Google</h3>
  <ul>
    <li>Aparte pagina per zoekopdracht, geoptimaliseerd voor Google</li>
    <li>Voorbeelden: Occasions Arnhem, Auto kopen Arnhem, APK Arnhem</li>
    <li>Brengt structureel extra bezoekers binnen</li>
  </ul>
</div>

<div class="item">
  <h3><span class="num">03.</span> Live AI chatbot</h3>
  <ul>
    <li>24/7 bereikbaar over aanbod, werkplaats en openingstijden</li>
    <li>Kent het actuele aanbod en helpt klanten naar de juiste auto</li>
    <li>Leidt door naar contact, proefrit of WhatsApp</li>
    <li>Minder telefoontjes over standaardvragen</li>
  </ul>
</div>

<div class="item">
  <h3><span class="num">04.</span> Eigen adminpaneel</h3>
  <ul>
    <li>Dashboard met omzet, marge, taken en voorraad in één oogopslag</li>
    <li>Occasions toevoegen, bewerken en als verkocht markeren</li>
    <li>Foto's slepen voor de juiste volgorde, automatisch verkleind</li>
    <li>Notities en taken per auto</li>
    <li>Volledig beheer over teksten, foto's en kleuren op de site</li>
  </ul>
</div>

<div class="item">
  <h3><span class="num">05.</span> RDW koppeling</h3>
  <ul>
    <li>15+ velden automatisch gevuld uit de RDW database</li>
    <li>Bespaart 5 tot 10 minuten per auto</li>
    <li>Werkt vergelijkbaar met systemen zoals VWE</li>
  </ul>
</div>

<div class="item">
  <h3><span class="num">06.</span> AI beschrijving per auto</h3>
  <ul>
    <li>Compleet verhaal per auto, gegenereerd vanuit merk, model, opties en bouwjaar</li>
    <li>Drie tonen om uit te kiezen (verkooppunt, feitelijk, premium)</li>
    <li>Altijd handmatig aan te passen voor de finishing touch</li>
    <li>Bespaart 5 tot 10 minuten schrijfwerk per auto</li>
  </ul>
</div>

<div class="item">
  <h3><span class="num">07.</span> AutoTelex koppeling</h3>
  <ul>
    <li>Kentekencheck met uitgebreide voertuiginformatie</li>
    <li>Actuele handels- en verkoopwaardes</li>
    <li>Inzicht in uitvoering, opties en voertuigdata</li>
    <li>Sneller occasions invoeren met automatische gegevens</li>
    <li>Professionele voertuigrapporten voor intern gebruik</li>
    <li>Vereist een eigen AutoTelex abonnement</li>
  </ul>
</div>

{{-- ============ PRIJS ============ --}}
<div class="price-box">
  <div class="price-box-stripe"></div>
  <div class="price-box-row">
    <div class="price-box-left">
      <div class="price-box-label">Totaal</div>
      <div class="price-box-value">&euro; 1.350,&ndash;</div>
    </div>
    <div class="price-box-right price-box-meta">
      excl. 21% btw<br>
      eenmalig, alles inbegrepen
    </div>
  </div>
</div>

{{-- ============ BETALING & OPLEVERING ============ --}}
<div class="info-row">
  <div class="info-cell">
    <div class="info-label">Betaling</div>
    <div class="info-value">50% bij start, 50% bij oplevering.</div>
  </div>
  <div class="info-cell">
    <div class="info-label">Oplevering</div>
    <div class="info-value">In overleg, zodra jullie akkoord geven.</div>
  </div>
</div>

{{-- ============ AKKOORD ============ --}}
<div class="signature">
  <div class="signature-row">
    <div class="signature-cell">
      <div class="signature-line"></div>
      <div class="signature-label">Gerritsen Automotive . Datum &amp; handtekening</div>
    </div>
    <div class="signature-cell">
      <div class="signature-line"></div>
      <div class="signature-label">Go For Digital . Datum &amp; handtekening</div>
    </div>
  </div>
</div>

<div class="footer">
  <span class="footer-brand">GO FOR DIGITAL</span>
</div>

</body>
</html>
