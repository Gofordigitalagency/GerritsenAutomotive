<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title>Reclame export</title>

  <style>
    * { font-family: DejaVu Sans, Arial, Helvetica, sans-serif !important; }
    @page { margin: 0; }
    body { margin:0; padding:0; font-size: 12px; color:#111; }

    :root{
      --green:#32CD32;
      --dark:#111;
      --muted:#666;
      --card:#ffffff;
      --line:#e6e6e6;
    }

    .page{ padding: 28px 34px; background:#fff; }

    .header{
      position: relative;
      padding: 18px 18px 16px 18px;
      border-radius: 14px;
      background: #111;
      color:#fff;
      overflow:hidden;
    }
    .header:after{
      content:"";
      position:absolute;
      right:-120px;
      top:-120px;
      width:320px;height:320px;
      background: var(--green);
      border-radius: 999px;
    }

    .brand{
      position:relative;
      font-weight:800;
      letter-spacing:.8px;
      text-transform:uppercase;
      font-size: 13px;
      opacity:.95;
      margin-bottom: 8px;
    }

    .title{
      position:relative;
      display:inline-block;
      background: var(--green);
      color:#fff;
      padding: 10px 14px;
      border-radius: 10px;
      font-size: 26px;
      font-weight: 900;
      letter-spacing:.5px;
      text-transform: uppercase;
      margin: 0;
      line-height: 1;
    }

    .subtitle{
      position:relative;
      display:inline-block;
      margin-top: 8px;
      background: rgba(255,255,255,.15);
      padding: 6px 10px;
      border-radius: 10px;
      font-size: 12px;
      color:#fff;
    }

    .sp{ height: 18px; }

    table.grid{
      width:100%;
      border-collapse: separate;
      border-spacing: 14px;
      table-layout: fixed;
    }

    td.cell{ width:50%; vertical-align: top; }

    .card{
      border:1px solid var(--line);
      border-radius: 14px;
      overflow:hidden;
      background: var(--card);
    }

    .photo{
      height: 150px;
      background: #efefef;
      border-bottom:1px solid var(--line);
      overflow:hidden;
    }
    .photo img{
      width:100%;
      height:150px;
      object-fit: cover;
      display:block;
    }
    .noimg{
      height:150px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#999;
      font-weight:700;
      font-size: 12px;
    }

    .body{ padding: 12px; }

    .car-title{
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .2px;
      font-size: 12px;
      margin: 0 0 6px 0;
    }

    .price{
      font-size: 18px;
      font-weight: 900;
      color: var(--green);
      margin: 0 0 8px 0;
    }

    .meta{
      color:#222;
      font-size: 11px;
      line-height: 1.5;
    }

    .meta b{ font-weight: 900; }
    .dots{ color:#999; padding:0 6px; }

    .bullet{
      margin-top: 8px;
      font-size: 10px;
      color:#444;
    }

    .footer{
      margin-top: 14px;
      text-align:center;
      color:#666;
      font-size: 10px;
    }
  </style>
</head>

<body>
@php
  /**
   * ✅ DomPDF-proof foto via base64 data-uri (zoals raamkaart)
   */
  function occ_photo_datauri($occasion) {
    if (!$occasion) return null;
    if (empty($occasion->hoofdfoto_path)) return null;

    // Zorg dat we altijd uitkomen op public/storage/...
    $rel = ltrim($occasion->hoofdfoto_path, '/');
    $rel = preg_replace('#^storage/#', '', $rel);
    $rel = ltrim($rel, '/');

    $abs = public_path('storage/' . $rel);
    if (!file_exists($abs) || !is_readable($abs)) return null;

    $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
    $mime = null;

    if (in_array($ext, ['jpg','jpeg'])) $mime = 'image/jpeg';
    elseif ($ext === 'png') $mime = 'image/png';
    elseif ($ext === 'webp') $mime = 'image/webp';

    if (!$mime) return null;

    return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($abs));
  }

  function occ_title($o) {
    $merk  = $o->merk ?? '';
    $model = $o->model ?? '';
    $type  = $o->type ?? '';
    $t = trim("$merk $model $type");
    return $t ?: ('Occasion #'.$o->id);
  }

  function occ_price($o) {
    $p = (float)($o->prijs ?? 0);
    return '€ ' . number_format($p, 0, ',', '.') . ',-';
  }

  function occ_km($o) {
    $km = $o->tellerstand ?? null;
    return ($km !== null && $km !== '') ? number_format((float)$km, 0, ',', '.') : '-';
  }

  function occ_year($o)  { return $o->bouwjaar ?? '-'; }
  function occ_fuel($o)  { return $o->brandstof ?? '-'; }
  function occ_trans($o) { return $o->transmissie ?? '-'; }

  /**
   * ✅ BELANGRIJK: $reclame->items zijn ReclameItems -> we willen Occasion
   */
  $items = collect($reclame->items ?? [])
    ->sortBy('position')
    ->map(fn($it) => $it->occasion)
    ->filter()
    ->values()
    ->take(4);

  // altijd 4 slots voor 2x2
  $slots = [];
  for ($i=0; $i<4; $i++) $slots[] = $items[$i] ?? null;
@endphp

<div class="page">

  <div class="header">
    <div class="brand">GERRITSEN AUTOMOTIVE</div>
    <h1 class="title">{{ $reclame->title ?? 'WEKENAANBIEDING' }}</h1><br>
    <div class="subtitle">{{ $reclame->subtitle ?? 'Alleen deze week scherp geprijsd!' }}</div>
  </div>

  <div class="sp"></div>

  <table class="grid">
    <tr>
      @for($c=0; $c<2; $c++)
        @php $o = $slots[$c]; @endphp
        <td class="cell">
          @if($o)
            @php $img = occ_photo_datauri($o); @endphp
            <div class="card">
              <div class="photo">
                @if($img)
                  <img src="{{ $img }}" alt="Foto">
                @else
                  <div class="noimg">GEEN FOTO</div>
                @endif
              </div>

              <div class="body">
                <div class="car-title">{{ occ_title($o) }}</div>
                <div class="price">{{ occ_price($o) }}</div>

                <div class="meta">
                  <b>KM:</b> {{ occ_km($o) }} <span class="dots">•</span>
                  <b>Bouwjaar:</b> {{ occ_year($o) }} <br>
                  <b>Brandstof:</b> {{ occ_fuel($o) }} <span class="dots">•</span>
                  <b>Transmissie:</b> {{ occ_trans($o) }}
                </div>

                <div class="bullet">• Incl. nieuwe APK en garantie!</div>
              </div>
            </div>
          @endif
        </td>
      @endfor
    </tr>

    <tr>
      @for($c=2; $c<4; $c++)
        @php $o = $slots[$c]; @endphp
        <td class="cell">
          @if($o)
            @php $img = occ_photo_datauri($o); @endphp
            <div class="card">
              <div class="photo">
                @if($img)
                  <img src="{{ $img }}" alt="Foto">
                @else
                  <div class="noimg">GEEN FOTO</div>
                @endif
              </div>

              <div class="body">
                <div class="car-title">{{ occ_title($o) }}</div>
                <div class="price">{{ occ_price($o) }}</div>

                <div class="meta">
                  <b>KM:</b> {{ occ_km($o) }} <span class="dots">•</span>
                  <b>Bouwjaar:</b> {{ occ_year($o) }} <br>
                  <b>Brandstof:</b> {{ occ_fuel($o) }} <span class="dots">•</span>
                  <b>Transmissie:</b> {{ occ_trans($o) }}
                </div>

                <div class="bullet">• Incl. nieuwe APK en garantie!</div>
              </div>
            </div>
          @endif
        </td>
      @endfor
    </tr>
  </table>

  <div class="footer">
    Gerritsen Automotive • gerritsenautomotive.nl
  </div>

</div>
</body>
</html>
