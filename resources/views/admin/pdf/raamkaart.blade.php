
<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title>Raamkaart</title>

  <style>
*{
  font-family: DejaVu Sans, Arial, Helvetica, sans-serif !important;
}

@page { margin: 0; }

body{
  margin:0;
  padding:0;
  font-family: DejaVu Sans, sans-serif;
  font-size: 13px;
}

h1, h2, h3, h4, h5,
.title-under-logo,
.price,
.footer .cta,
.footer .phone{
  font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
}

:root{
  --blue:#2b7ea0;
  --line:#cfd6dc;
  --muted:#606a73;
}

.page{
  padding: 34px 44px;
}

.sp1{ height: 14px; }
.sp2{ height: 22px; }
.sp3{ height: 34px; }

.line{ border-top:1px solid var(--line); }

/* ===== HEADER ===== */
.header{
  text-align:right;
}
.logo img{
  width: 180px;
  height:auto;
  opacity:.28;
}

.title-under-logo{
  margin-top: 40px;
  font-weight: 700;
  text-align: left;
  color: var(--blue);
  text-transform: uppercase;
  letter-spacing: .2px;
  line-height: 1.15;
  font-size: 20px;
}

/* ===== FOTO + INFO ===== */
.top{
  width:100%;
  border-collapse:collapse;
  table-layout: fixed;
  
}
.leftCol{
  width: 340px;
  vertical-align: top;
}
.rightCol{
  vertical-align: top;
  padding-left: 28px;
}

.photoFrame{
  width: 340px;
  height: 210px;
  border: 1px solid var(--line);
  background:#fff;
  overflow:hidden;
}
.photoFrame img{
  width: 340px;
  height:auto;
  display:block;
}

.specs{
  width:100%;
  border-collapse:collapse;
  margin-top: 2px;
  font-size: 13px;
}
.specs td{
  padding: 7px 0;
  vertical-align: top;
}
.specs td.label{
  width: 160px;
  color:#222;
}
.specs td.value{
  color:#222;
}

/* ===== PRIJS ===== */
.price{
  font-size: 40px;
  color: var(--blue);
  letter-spacing: .2px;
  font-weight: 700;
}
.price .label{
  margin-right: 10px;
}

/* ===== OPTIES ===== */
.optsTitle{
  margin-bottom: 8px;
  color:#222;
}
.opts{
  width:100%;
  border-collapse:collapse;
  table-layout: fixed;
}
.opts td{
  vertical-align: top;
  width:50%;
}
.opts td.colL{ padding-right: 22px; }
.opts td.colR{ padding-left: 22px; }

.bullets{
  margin:0;
  padding-left: 18px;
  line-height: 1.9;
  font-size: 13px;
}
.bullets li{ margin:0; }

/* ===== FOOTER ===== */
.footer{
  font-weight: 700;
  text-align:center;
  margin-top: 18px;
}
.footer .cta{
    font-weight: 700;
  color:#222;
  margin: 4px 0;
}
.footer .ctaBlue{
     font-weight: 700;
  color: var(--blue);
  letter-spacing: .2px;
}
.footer .phone{
     font-weight: 700;
  font-size: 28px;
  color: var(--blue);
  margin: 6px 0 4px;
}
.footer .web{
     font-weight: 700;
  font-size: 18px;
  color:#2f3b45;
  margin: 6px 0 6px;
}
.footer .slogan{
     font-weight: 700;
  color:#222;
  margin-top: 14px;
}

.bottomPad{ height: 10px; }
  </style>
</head>


<body>
@php
  // Bouw titel in 2 regels zoals jij wil:
  $line1 = strtoupper(trim(($occasion->merk ?? '').' '.($occasion->model ?? '')));
  $line2 = strtoupper(trim(($occasion->type ?? '')));

  // specifieke wens: "MERCEDES-BENZ C-KLASSE" en "CABRIO 63 S AMG" (dus type op 2e regel)
  // (merk+model staat al op regel 1, type op regel 2)

  $km = !empty($occasion->tellerstand) ? number_format($occasion->tellerstand,0,',','.').' km' : '-';
  $pk = $occasion->vermogen_pk ?? $occasion->pk ?? null;
  $pk = !empty($pk) ? $pk.' pk' : '-';
  $apk = $occasion->apk_tot ? \Carbon\Carbon::parse($occasion->apk_tot)->format('d-m-Y') : '-';

  // Combineer alle opties (4 velden) tot 1 lijst
  $allOptions = [];
  foreach (['exterieur_options','interieur_options','veiligheid_options','overige_options'] as $f) {
    $arr = $occasion->{$f} ?? [];
    if (is_string($arr)) $arr = json_decode($arr, true) ?: [];
    if (is_array($arr)) $allOptions = array_merge($allOptions, $arr);
  }
  $allOptions = array_values(array_filter(array_map('trim', $allOptions)));

  // Split opties netjes in 2 kolommen
  $half = (int) ceil(count($allOptions) / 2);
  $optL = array_slice($allOptions, 0, $half);
  $optR = array_slice($allOptions, $half);
@endphp

<div class="page">


  {{-- LOGO (rechtsboven) --}}
  <div class="header">
    @if(!empty($logo) && file_exists($logo))
      <div class="logo">
        <img src="{{ $logo }}" alt="Gerritsen Automotive">
      </div>
    @endif

    {{-- Blauwe titel ONDER het logo --}}
    <div class="title-under-logo">
      {{ $line1 ?: 'OCCASION' }}<br>
      {{ $line2 ?: '' }}
    </div>
  </div>

  <div class="sp2"></div>

  {{-- FOTO LINKS + INFO RECHTS --}}
  <table class="top">
    <tr>
      <td class="leftCol">
        <div class="photoFrame">
@if($photo)
    <img src="{{ $photo }}" style="width:100%; height:auto;">
@endif
        </div>
      </td>

      <td class="rightCol">
        <table class="specs">
          <tr><td class="label">Kenteken:</td><td class="value">{{ $occasion->kenteken ?? '-' }}</td></tr>
          <tr><td class="label">Bouwjaar:</td><td class="value">{{ $occasion->bouwjaar ?? '-' }}</td></tr>
          <tr><td class="label">Gewicht:</td><td class="value">{{ !empty($occasion->gewicht) ? $occasion->gewicht.' KG' : '-' }}</td></tr>
          <tr><td class="label">Vermogen:</td><td class="value">{{ $pk }}</td></tr>
          <tr><td class="label">Brandstof:</td><td class="value">{{ $occasion->brandstof ?? '-' }}</td></tr>
          <tr><td class="label">Kleur:</td><td class="value">{{ $occasion->kleur ?? '-' }}</td></tr>
          <tr><td class="label">Kilometerstand:</td><td class="value">{{ $km }}</td></tr>
          <tr><td class="label">Transmissie:</td><td class="value">{{ $occasion->transmissie ?? '-' }}</td></tr>
          <tr><td class="label">APK:</td><td class="value">{{ $apk }}</td></tr>
        </table>
      </td>
    </tr>
  </table>

  <div class="sp3"></div>

  {{-- VRAAGPRIJS --}}
  <div class="price">
    <span class="label">Vraagprijs:</span>
    € {{ number_format($occasion->prijs ?? 0, 0, ',', '.') }},-
  </div>

  <div class="sp1"></div>
  <div class="line"></div>

  <div class="sp2"></div>

  {{-- OPTIES --}}
  <div class="optsTitle">Opties:</div>

  <table class="opts">
    <tr>
      <td class="colL">
        <ul class="bullets">
          @if(count($optL))
            @foreach($optL as $o)
              <li>{{ $o }}</li>
            @endforeach
          @else
            <li>-</li>
          @endif
        </ul>
      </td>
      <td class="colR">
        <ul class="bullets">
          @foreach($optR as $o)
            <li>{{ $o }}</li>
          @endforeach
        </ul>
      </td>
    </tr>
  </table>

  <div class="sp3"></div>

  {{-- STREEP ONDER OPTIES --}}
  <div class="line"></div>

  <div class="sp2"></div>

  {{-- FOOTER GECENTREERD --}}
  <div class="footer">
    <div class="cta">INTERESSE IN DEZE TOP AUTO? NEEM GERUST CONTACT OP MET ONS!</div>
    <div class="cta">WIJ ZIJN BEREIKBAAR OP</div>
    <div class="phone">+31 6 49951874</div>
    <div class="web">www.gerritsenautomotive.nl</div>

    <div class="sp2"></div>

    <div class="slogan">Gerritsen Automotive - Dat is fijn zaken doen!</div>
  </div>

  <div class="bottomPad"></div>
</div>

</body>
</html>
