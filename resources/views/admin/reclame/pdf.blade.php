<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title>Reclame export</title>

  <style>
    * { font-family: DejaVu Sans, Arial, Helvetica, sans-serif !important; }

    @page { margin: 0; }
    body { margin:0; padding:0; font-size: 12px; color:#111; background:#fff; }

    :root{
      --orange:#F08A00;      /* flyer-oranje */
      --dark:#111;
      --muted:#666;
      --line:#e6e6e6;
      --card:#ffffff;
    }

    .page{
      padding: 18px 18px 16px 18px;
    }

    /* ===== HEADER (zwart + oranje) ===== */
    .header{
      background: var(--dark);
      color:#fff;
      border-radius: 14px;
      padding: 16px 16px 14px 16px;
      position: relative;
      overflow:hidden;
    }

    .brand{
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .8px;
      font-size: 12px;
      opacity: .95;
      margin-bottom: 8px;
    }

    /* “ribbon” effect */
    .ribbon{
      background: var(--orange);
      color:#fff;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .5px;
      display: inline-block;
      padding: 10px 14px;
      border-radius: 10px;
      font-size: 26px;
      line-height: 1;
    }

    .subtitle{
      margin-top: 8px;
      display:inline-block;
      background: rgba(255,255,255,.14);
      padding: 6px 10px;
      border-radius: 10px;
      font-size: 12px;
      color:#fff;
    }

    .sp { height: 14px; }

    /* ===== GRID 2x2 ===== */
    table.grid{
      width:100%;
      border-collapse: separate;
      border-spacing: 12px; /* gap tussen cards */
      table-layout: fixed;
    }
    td.cell{
      width:50%;
      vertical-align: top;
    }

    .card{
      border: 1px solid var(--line);
      border-radius: 14px;
      overflow: hidden;
      background: var(--card);
    }

    .photo{
      height: 150px;
      background: #f0f0f0;
      border-bottom: 1px solid var(--line);
      overflow:hidden;
    }
    .photo img{
      width:100%;
      height:150px;
      object-fit: cover;   /* ✅ voorkomt “kruis”/rare verhoudingen */
      display:block;
    }
    .noimg{
      height:150px;
      text-align:center;
      line-height:150px;
      color:#999;
      font-weight:800;
      font-size: 12px;
    }

    .body{
      padding: 10px 12px 12px 12px;
    }

    .car-title{
      font-weight: 900;
      text-transform: uppercase;
      font-size: 12px;
      margin: 0 0 6px 0;
      letter-spacing: .2px;
    }

    .price{
      color: var(--orange);
      font-weight: 900;
      font-size: 18px;
      margin: 0 0 8px 0;
    }

    .meta{
      font-size: 11px;
      line-height: 1.55;
      color:#111;
    }
    .meta b{ font-weight: 900; }
    .dot{ color:#999; padding:0 6px; }

    .bullet{
      margin-top: 8px;
      font-size: 10px;
      color:#333;
    }

    /* ===== FOOTER ===== */
    .footer{
      margin-top: 10px;
      background: #111;
      color:#fff;
      border-radius: 14px;
      padding: 10px 12px;
      font-size: 11px;
      text-align:center;
    }
    .footer strong{ font-weight: 900; }
    .footer .sep{ opacity:.6; padding:0 10px; }
  </style>
</head>

<body>
@php
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

  function occ_year($o) { return $o->bouwjaar ?? '-'; }
  function occ_fuel($o) { return $o->brandstof ?? '-'; }
  function occ_trans($o) { return $o->transmissie ?? '-'; }

  // ✅ DomPDF-proof (base64)
  function occ_img_datauri($o) {
    if (empty($o->hoofdfoto_path)) return null;

    $abs = public_path('storage/' . ltrim($o->hoofdfoto_path, '/'));
    if (!file_exists($abs)) return null;

    $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
    $mime = match ($ext) {
      'jpg','jpeg' => 'image/jpeg',
      'png'        => 'image/png',
      'webp'       => 'image/webp',
      default      => null,
    };
    if (!$mime) return null;

    return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($abs));
  }

  $items = ($items ?? ($reclame->items ?? collect()))->values()->take(4);

  // 4 vaste slots voor 2x2
  $slots = [];
  for ($i=0; $i<4; $i++) $slots[] = $items[$i] ?? null;
@endphp

<div class="page">

  <div class="header">
    <div class="brand">GERRITSEN AUTOMOTIVE</div>
    <div class="ribbon">{{ $reclame->title ?? 'WEKENAANBIEDING' }}</div><br>
    <div class="subtitle">{{ $reclame->subtitle ?? 'Alleen deze week scherp geprijsd!' }}</div>
  </div>

  <div class="sp"></div>

  <table class="grid">
    <tr>
      @for($c=0; $c<2; $c++)
        @php $o = $slots[$c]; @endphp
        <td class="cell">
          @if($o)
            @php $img = occ_img_datauri($o); @endphp
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
                  <b>KM:</b> {{ occ_km($o) }} <span class="dot">•</span>
                  <b>Bouwjaar:</b> {{ occ_year($o) }}<br>

                  <b>Brandstof:</b> {{ occ_fuel($o) }} <span class="dot">•</span>
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
            @php $img = occ_img_datauri($o); @endphp
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
                  <b>KM:</b> {{ occ_km($o) }} <span class="dot">•</span>
                  <b>Bouwjaar:</b> {{ occ_year($o) }}<br>

                  <b>Brandstof:</b> {{ occ_fuel($o) }} <span class="dot">•</span>
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
    <strong>gerritsenautomotive.nl</strong>
    <span class="sep">|</span>
    <strong>0341 252520</strong>
  </div>

</div>
</body>
</html>
