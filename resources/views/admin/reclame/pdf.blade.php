{{-- resources/views/admin/reclame/pdf.blade.php --}}
<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title>{{ $reclame->title ?? 'WEKENAANBIEDING' }}</title>

  <style>
    /* DomPDF: gebruik simpele fonts */
    @page { margin: 18px 18px 18px 18px; }
    body { font-family: DejaVu Sans, sans-serif; color:#111; margin:0; padding:0; }

    .sheet { width: 100%; }

    /* Header */
    .header{
      position: relative;
      height: 150px;
      border-radius: 10px;
      overflow: hidden;
      background: #111;
      margin-bottom: 14px;
    }
    .header .green{
      position:absolute;
      right:-120px;
      top:-120px;
      width: 520px;
      height: 520px;
      border-radius: 260px;
      background:#32CD32; /* lime green */
    }
    .header .brand{
      position:absolute;
      left:18px;
      top:16px;
      font-weight:800;
      letter-spacing:1px;
      font-size: 14px;
      color:#fff;
    }
    .header .title-wrap{
      position:absolute;
      left:18px;
      top:48px;
    }
    .header .title{
      display:inline-block;
      background:#32CD32;
      color:#fff;
      font-weight:900;
      font-size: 26px;
      padding:10px 16px;
      border-radius: 6px;
      letter-spacing: .5px;
    }
    .header .subtitle{
      display:inline-block;
      margin-top:8px;
      background: rgba(0,0,0,.6);
      color:#fff;
      font-size: 12px;
      padding:6px 10px;
      border-radius: 999px;
    }

/* GRID */
table.grid{
  width:100%;
  border-collapse: separate;
  border-spacing: 14px 14px;
  table-layout: fixed;
}

/* CARD */
.card{
  border:1px solid #e6e6e6;
  border-radius: 12px;
  overflow:hidden;
  background:#fff;
  height: 320px;            /* ✅ alles even hoog */
}

/* IMAGE */
.img{
  width:100%;
  height: 155px;            /* ✅ vaste hoogte -> geen verspringen */
  background:#efefef;
  overflow:hidden;
}
.img img{
  width:100%;
  height:155px;
  object-fit:cover;         /* ✅ altijd netjes vullen */
  display:block;
}

/* CONTENT */
.pad{ padding: 10px 12px; }

.name{
  font-weight:900;
  font-size: 12px;
  letter-spacing:.3px;
  text-transform: uppercase;
  margin: 0 0 6px 0;
  height: 28px;             /* ✅ max 2 regels plek */
  overflow: hidden;         /* ✅ voorkomt duwen */
}

.price{
  color:#32CD32;
  font-weight: 900;
  font-size: 22px;
  margin: 0 0 6px 0;
}

.meta{
  font-size: 11px;
  color:#222;
  margin: 0;
  line-height: 1.55;
  height: 55px;             /* ✅ vaste plek voor specs */
  overflow:hidden;
}

.bullet{
  font-size: 10px;
  color:#444;
  margin-top: 8px;
}


    /* Footer */
    .footer{
      margin-top: 10px;
      background:#111;
      color:#fff;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 11px;
    }
    .footer .row{
      display: table;
      width: 100%;
    }
    .footer .col{
      display: table-cell;
      vertical-align: middle;
      width: 50%;
    }
    .footer .right{
      text-align:right;
    }
  </style>
</head>

<body>
  <div class="sheet">

    {{-- HEADER --}}
    <div class="header">
      <div class="green"></div>
      <div class="brand">GERRITSEN AUTOMOTIVE</div>
      <div class="title-wrap">
        <div class="title">{{ $reclame->title ?? 'WEKENAANBIEDING' }}</div><br>
        <div class="subtitle">{{ $reclame->subtitle ?? 'Alleen deze week scherp geprijsd!' }}</div>
      </div>
    </div>

    {{-- GRID 2x2 --}}
    @php
      $items = $items ?? ($reclame->items ?? collect());
      $items = collect($items)->take(4)->values();

      // zorg dat er altijd 4 plekken zijn (voor vaste layout)
      while($items->count() < 4) $items->push(null);

      // helper
      $fmtEuro = function($n){
        $n = (float)($n ?? 0);
        return '€ '.number_format($n, 0, ',', '.').',-';
      };
      $fmtInt = function($n){
        if($n === null || $n === '') return '-';
        return number_format((float)$n, 0, ',', '.');
      };
    @endphp

    <table class="grid">
      <tr>
        @for($i=0; $i<2; $i++)
          @php $it = $items[$i]; $o = $it?->occasion; @endphp
          <td>
            @if($o)
              @php
                $name = trim(($o->merk ?? '').' '.($o->model ?? '').' '.($o->type ?? ''));
                $rel = $o->hoofdfoto_path ?? null;
                $abs = $rel ? public_path('storage/'.$rel) : null;
                $hasImg = $abs && file_exists($abs);
              @endphp

              <div class="card">
                <div class="img">
                  @if($hasImg)
                    <img src="{{ $abs }}" alt="">
                  @endif
                </div>
                <div class="pad">
                  <div class="name">{{ $name ?: 'Occasion #'.$o->id }}</div>
                  <div class="price">{{ $fmtEuro($o->prijs) }}</div>
                  <div class="meta">
                    <b>KM:</b> {{ $fmtInt($o->tellerstand) }} &nbsp;•&nbsp;
                    <b>Bouwjaar:</b> {{ $o->bouwjaar ?? '-' }}<br>
                    <b>Brandstof:</b> {{ $o->brandstof ?? '-' }} &nbsp;&nbsp;
                    <b>Transmissie:</b> {{ $o->transmissie ?? '-' }}
                  </div>
                  <div class="bullet">• Incl. nieuwe APK en garantie!</div>
                </div>
              </div>
            @else
              {{-- lege plek --}}
              <div class="card">
                <div class="img"></div>
                <div class="pad">
                  <div class="name">&nbsp;</div>
                  <div class="price">&nbsp;</div>
                  <div class="meta">&nbsp;</div>
                </div>
              </div>
            @endif
          </td>
        @endfor
      </tr>

      <tr>
        @for($i=2; $i<4; $i++)
          @php $it = $items[$i]; $o = $it?->occasion; @endphp
          <td>
            @if($o)
              @php
                $name = trim(($o->merk ?? '').' '.($o->model ?? '').' '.($o->type ?? ''));
                $rel = $o->hoofdfoto_path ?? null;
                $abs = $rel ? public_path('storage/'.$rel) : null;
                $hasImg = $abs && file_exists($abs);
              @endphp

              <div class="card">
                <div class="img">
                  @if($hasImg)
                    <img src="{{ $abs }}" alt="">
                  @endif
                </div>
                <div class="pad">
                  <div class="name">{{ $name ?: 'Occasion #'.$o->id }}</div>
                  <div class="price">{{ $fmtEuro($o->prijs) }}</div>
                  <div class="meta">
                    <b>KM:</b> {{ $fmtInt($o->tellerstand) }} &nbsp;•&nbsp;
                    <b>Bouwjaar:</b> {{ $o->bouwjaar ?? '-' }}<br>
                    <b>Brandstof:</b> {{ $o->brandstof ?? '-' }} &nbsp;&nbsp;
                    <b>Transmissie:</b> {{ $o->transmissie ?? '-' }}
                  </div>
                  <div class="bullet">• Incl. nieuwe APK en garantie!</div>
                </div>
              </div>
            @else
              <div class="card">
                <div class="img"></div>
                <div class="pad">
                  <div class="name">&nbsp;</div>
                  <div class="price">&nbsp;</div>
                  <div class="meta">&nbsp;</div>
                </div>
              </div>
            @endif
          </td>
        @endfor
      </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
      <div class="row">
        <div class="col">
          gerritsenautomotive.nl
        </div>
        <div class="col right">
          0341 252520
        </div>
      </div>
    </div>

  </div>
</body>
</html>
