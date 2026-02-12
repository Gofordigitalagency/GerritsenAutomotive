<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Raamkaart</title>

<style>
* { font-family: DejaVu Sans, Arial, Helvetica, sans-serif !important; }

@page { margin: 0; }
body { margin:0; padding:0; font-size: 13px; color:#111; }

.sheet{
  width: 794px;
  height: 1123px;
  background:#fff;
  position: relative;
}

/* ===== HEADER ===== */
.header{
  background:#0b0b0b;
  color:#fff;
  padding: 18px 26px;
}
.headerTable{
  width:100%;
  border-collapse:collapse;
}
.headerLeft{ width:50%; vertical-align:middle; }
.headerRight{ width:50%; vertical-align:middle; text-align:right; font-size:16px; }



.logo img{
  height:60px !important;
  width:auto !important;
  max-height:60px !important;
}
/* ===== CONTENT ===== */
.content{
  padding: 18px 26px 0 26px;
}

.title{
  text-align:center;
  font-size:34px;
  letter-spacing:.6px;
  margin:8px 0 18px 0;
  font-weight:700;

}

.topTable{
  width:100%;
  border-collapse:collapse;
  table-layout:fixed;
}

.photoCol{
  width:44%;
}

.specCol{
  width:44%;
  padding-left:12%;
}
.photoFrame{
  border:none;
  padding:0;
}
.photoFrame img{
  width:100%;
  height:auto;
  display:block;
}

.specs{
  width:100%;
  border-collapse:collapse;
  font-size:13px;
  margin-top:4px;
}
.specs td{
  padding:6px 0;
  vertical-align:top;
}
.specs .label{ width:55%; }
.specs .value{ width:45%; text-align:right; }

/* ===== PRICE ===== */
.price{
  font-size:34px;
  margin:30px 0 30px 0;
  font-weight: 700;
}

/* ===== OPTIONS ===== */
.optsWrap{ margin-top:18px; }

.optsTitle{
  font-size:13px;
  margin-bottom:10px;
}

.optsTable{
  width:100%;
  border-collapse:collapse;
  table-layout:fixed;
}
.optsTable td{ width:50%; vertical-align:top; }
.optsTable .colL{ padding-right:20px; }
.optsTable .colR{ padding-left:20px; }

ul.bullets{
  margin:0;
  padding-left:18px;
  line-height:1.8;
  font-size:13px;
}
ul.bullets li{ margin:0 0 6px 0; }

/* ===== FOOTER ===== */
.footer{
  position:absolute;
  bottom:0;
  left:0;
  right:0;
  padding:24px 26px 30px 26px;
  text-align:center;
  font-size:16px;
  font-weight:700;
}
.footer .phone{
  font-size:20px;
  margin:6px 0 10px 0;
}
.footer .tagline{
  margin-top:8px;
}
</style>
</head>

<body>
@php
  $line1 = strtoupper(trim(($occasion->merk ?? '').' '.($occasion->model ?? '')));
  $line2 = strtoupper(trim(($occasion->type ?? '')));

  $km = !empty($occasion->tellerstand) ? number_format($occasion->tellerstand,0,',','.').' km' : '-';
  $pk = $occasion->vermogen_pk ?? $occasion->pk ?? null;
  $pk = !empty($pk) ? $pk.' pk' : '-';
  $apk = $occasion->apk_tot ? \Carbon\Carbon::parse($occasion->apk_tot)->format('d-m-Y') : '-';

  $allOptions = [];
  foreach (['exterieur_options','interieur_options','veiligheid_options','overige_options'] as $f) {
    $arr = $occasion->{$f} ?? [];
    if (is_string($arr)) $arr = json_decode($arr, true) ?: [];
    if (is_array($arr)) $allOptions = array_merge($allOptions, $arr);
  }
  $allOptions = array_values(array_filter(array_map('trim', $allOptions)));

  $half = (int) ceil(count($allOptions) / 2);
  $optL = array_slice($allOptions, 0, $half);
  $optR = array_slice($allOptions, $half);

  $prijs = !empty($occasion->prijs) ? '€ '.number_format($occasion->prijs, 0, ',', '.').',-' : '€ -';
@endphp

<div class="sheet">

  <div class="header">
    <table class="headerTable">
      <tr>
<td class="headerLeft">
  @if(!empty($logo) && file_exists($logo))
    <div class="logo">
      <img src="{{ $logo }}" alt="Gerritsen Automotive">
    </div>
  @endif
</td>
        <td class="headerRight">
          www.gerritsenautomotive.nl
        </td>
      </tr>
    </table>
  </div>

  <div class="content">

    <div class="title">
      {{ $line1 ?: 'OCCASION' }} {{ $line2 ? ' '.$line2 : '' }}
    </div>

    <table class="topTable">
      <tr>
        <td class="photoCol">
          <div class="photoFrame">
            @if(!empty($photoDataUri))
              <img src="{{ $photoDataUri }}" alt="Occasion foto">
            @endif
          </div>
        </td>

        <td class="specCol">
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

    <div class="price">
      Vraagprijs: {{ $prijs }}
    </div>

    <div class="optsWrap">
      <div class="optsTitle">Opties:</div>
      <table class="optsTable">
        <tr>
          <td class="colL">
            <ul class="bullets">
              @foreach($optL as $o)
                <li>{{ $o }}</li>
              @endforeach
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
    </div>

  </div>

  <div class="footer">
    <div>INTERESSE IN DEZE TOP AUTO? NEEM GERUST CONTACT OP MET ONS!</div>
    <div>WIJ ZIJN BEREIKBAAR OP</div>
    <div class="phone">+31 6 49951874</div>
    <div>www.gerritsenautomotive.nl</div>
    <div class="tagline">Gerritsen Automotive - Dat is fijn zaken doen!</div>
  </div>

</div>

</body>
</html>
