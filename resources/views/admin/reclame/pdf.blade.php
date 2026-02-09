@php
  // LIME GREEN (vervang oranje)
  $lime = '#32CD32';

  // Helpers (pas aan naar jouw occasion velden)
  function occ_title($o) {
    $brand = $o->brand ?? '';
    $model = $o->model ?? '';
    $type  = $o->type ?? $o->uitvoering ?? '';
    $t = trim("$brand $model $type");
    return $t ?: ($o->title ?? 'Onbekend');
  }

  function occ_price($o) {
    $p = (float)($o->price ?? 0);
    return '€ ' . number_format($p, 0, ',', '.') . ',-';
  }

  function occ_km($o) { return $o->km ?? $o->mileage ?? '-'; }
  function occ_year($o) { return $o->year ?? $o->bouwjaar ?? '-'; }
  function occ_fuel($o) { return $o->fuel ?? $o->brandstof ?? '-'; }
  function occ_trans($o) { return $o->transmission ?? $o->transmissie ?? '-'; }

  // foto: pak hoofdfoto of eerste galerij (pas aan op jouw storage-setup)
  function occ_img($o) {
    $path = $o->photo ?? $o->hoofdfoto ?? null; // pas aan
    if (!$path) return null;
    // dompdf wil een lokaal pad
    return public_path('storage/' . ltrim($path, '/'));
  }

  $items = $reclame->items->values();
@endphp

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @page { margin: 0; }
    body { margin:0; font-family: DejaVu Sans, Arial, sans-serif; }

    .wrap { width: 210mm; height: 297mm; background:#fff; position: relative; }

    /* Header */
    .topbar { background:#111; height: 52mm; position: relative; overflow:hidden; }
    .topbar .curve {
      position:absolute; right:-30mm; top:-25mm;
      width: 160mm; height: 90mm;
      background: {{ $lime }};
      transform: rotate(-6deg);
      border-radius: 50mm;
      opacity: 0.95;
    }
    .brand {
      position:absolute; left:14mm; top:10mm; color:#fff;
      font-weight:700; letter-spacing:0.5px; font-size: 18px;
    }
    .title {
      position:absolute; left:14mm; top:20mm;
      color:#fff; font-weight:900; font-size: 30px;
      background: {{ $lime }};
      display:inline-block; padding: 4mm 6mm;
      border-radius: 2mm;
      text-transform: uppercase;
    }
    .subtitle {
      position:absolute; left:14mm; top:36mm;
      color:#fff; font-weight:600; font-size: 12px;
      background: rgba(255,255,255,0.12);
      padding: 2mm 4mm; border-radius: 2mm;
    }

    /* Grid */
    .content { padding: 8mm 10mm 0 10mm; }
    .grid { width:100%; border-collapse:separate; border-spacing: 6mm 6mm; table-layout: fixed; }
    .card { border: 1px solid #e6e6e6; border-radius: 3mm; overflow:hidden; }
    .imgbox { height: 42mm; background:#f2f2f2; text-align:center; }
    .imgbox img { height: 42mm; width:auto; }

    .cardbody { padding: 4mm; }
    .carname { font-weight:800; font-size: 11px; text-transform: uppercase; }
    .price { color: {{ $lime }}; font-weight:900; font-size: 18px; margin-top:2mm; }
    .meta { margin-top:2mm; font-size: 9px; color:#333; }
    .meta span { display:inline-block; margin-right: 3mm; }
    .small { font-size: 8px; color:#555; margin-top: 2mm; }

    /* Footer */
    .footer { position:absolute; left:0; right:0; bottom:0; height: 34mm; background:#111; color:#fff; }
    .footer .line { position:absolute; left:0; right:0; top:0; height: 7mm; background: {{ $lime }}; }
    .footer .inner { padding: 10mm 12mm 0 12mm; font-size: 10px; }
    .footer .inner strong { font-size: 12px; }
    .footer .cols { width:100%; }
    .footer td { vertical-align: top; }
    .footer .right { text-align:right; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="topbar">
      <div class="curve"></div>
      <div class="brand">GERRITSEN AUTOMOTIVE</div>
      <div class="title">{{ $reclame->title }}</div>
      <div class="subtitle">{{ $reclame->subtitle }}</div>
    </div>

    <div class="content">
      <table class="grid">
        @for($r=0; $r<2; $r++)
          <tr>
            @for($c=0; $c<2; $c++)
              @php $idx = $r*2 + $c; $it = $items[$idx] ?? null; $o = $it?->occasion; @endphp
              <td class="card">
                @if($o)
                  <div class="imgbox">
                    @php $img = occ_img($o); @endphp
                    @if($img && file_exists($img))
                      <img src="{{ $img }}" alt="">
                    @endif
                  </div>

                  <div class="cardbody">
                    <div class="carname">{{ occ_title($o) }}</div>
                    <div class="price">{{ occ_price($o) }}</div>

                    <div class="meta">
                      <span>KM: <strong>{{ occ_km($o) }}</strong></span>
                      <span>Bouwjaar: <strong>{{ occ_year($o) }}</strong></span>
                    </div>
                    <div class="meta">
                      <span>Brandstof: <strong>{{ occ_fuel($o) }}</strong></span>
                      <span>Transmissie: <strong>{{ occ_trans($o) }}</strong></span>
                    </div>

                    <div class="small">• Incl. nieuwe APK en garantie!</div>
                  </div>
                @else
                  <div class="imgbox"></div>
                  <div class="cardbody">
                    <div class="carname">—</div>
                    <div class="price">&nbsp;</div>
                    <div class="meta">&nbsp;</div>
                  </div>
                @endif
              </td>
            @endfor
          </tr>
        @endfor
      </table>
    </div>

    <div class="footer">
      <div class="line"></div>
      <div class="inner">
        <table class="cols" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <strong>gerritsenautomotive.nl</strong><br>
              0341 252520
            </td>
            <td class="right">
              info@gerritsenautomotive.nl
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
