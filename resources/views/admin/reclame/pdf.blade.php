<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">

<style>
  * { font-family: DejaVu Sans, Arial, Helvetica, sans-serif !important; }
  @page { margin:0; }
  body { margin:0; padding:0; }

  :root{
    --yellow:#f4b400;
    --border:#d9d9d9;
  }

  .page{ padding:0; }

  /* ===== TOPBAR ===== */
.topbar{
  background:#0e0e0e;
  height:110px;
  padding:0 22px;
}

.topbarTable{
  width:100%;
  height:110px;
  border-collapse:collapse;
}

.topbarLeft{
  height:110px;
  padding:0;
  vertical-align:middle;
}

.topLogo{
  height:75px;
  display:block;
  position: relative;
  top: 2px;   /* probeer 2px */
}

  /* ===== HERO ===== */
  .hero{
    background:#fff;
    padding: 22px 22px 16px;
  }

  .hero-title{
    text-align:center;
    font-size:54px;
    letter-spacing:2px;
    color:#111;
    font-weight:700;
    margin:0;
  }
.hero-title .ing{ color:#111; }

  .line-wrap{
    position:relative;
    height:32px;
    margin-top:12px;
  }
  .line-wrap .line{
    position:absolute;
    left:0;
    right:0;
    top:16px;
    height:2px;
    background:#111;
    opacity:.55;
    z-index:1;
  }
  .pill-table{
    width:100%;
    border-collapse:collapse;
    position:relative;
    z-index:2;
  }
  .pill-table td{ text-align:center; padding:0; }
  .pill{
    display:inline-block;
    background:var(--yellow);
    color:#000;
    font-weight:700;
    padding:8px 22px;
    font-size:13px;
    letter-spacing:1px;
    text-transform:uppercase;
  }

  /* ===== GRID (2x2) ===== */
table.grid {
  border-collapse: collapse;
  padding: 0 22px;
}
  td.cell{
    width:50%;
    padding:5px;
    vertical-align:top;
  }

  /* ===== CARD ===== */
  .card{
    border:1px solid var(--border);
    border-radius:12px;
    overflow:hidden;
    background:#fff;
  }

  .card-img{
    height:190px;
    overflow:hidden;
    background:#eee;
  }
  .card-img img{
    width:100%;
    height:auto;
    display:block;
    margin-top:-22px; /* foto iets omhoog zoals voorbeeld */
  }

  .card-body{
    padding: 12px 12px 10px;
  }

  .car-title{
    font-size:22px;
    font-weight:700;
    margin:0 0 4px 0;
    line-height:1.05;
  }

  .car-meta{
    color:#666;
    font-size:13px;
    margin:0 0 10px 0;
  }

  .year{
    display:inline-block;
    background:var(--yellow);
    color:#000;
    padding:5px 10px;
    font-size:12px;
    font-weight:700;
    letter-spacing:.5px;
    text-transform:uppercase;
  }

  .price{
    float:right;
    font-size:26px;
    font-weight:700;
    color:#111;
    white-space:nowrap;
  }

  /* clearfix */
  .row-clear{ clear:both; height:1px; }

  /* ===== FOOTER ===== */
  .footer{
    margin-top: 6px;
    padding: 0 22px 18px 22px;
  }
.footer-inner{
  padding-top:14px;
}

  table.footer-table{ width:100%; border-collapse:collapse; }
  .phone{
    font-size:28px;
    font-weight:700;
    color:#111;
    margin-bottom:10px;
  }
  .phone img{ vertical-align:middle; margin-right:8px; }

  .domain{
    background:#0e0e0e;
    color:#fff;
    display:inline-block;
    padding:8px 16px;
    font-weight:700;
    letter-spacing:.8px;
    margin: 2px 0 10px 0;
  }

  .address{
    color:#666;
    font-size:13px;
  }

  .rdw{ text-align:right; }


  .phone table{
  border-collapse: collapse;
}

.phoneIcon{
  display:block;
  position:relative;
  top:12px;
}

.phoneNumber{
  vertical-align: middle;
  font-size:28px;
  font-weight:700;
}
.phoneIcon img{
  display:block;
  position:relative;
  top:3px;   /* speel tussen 2px en 4px */
}
</style>
</head>

<body>
@php
  function photo($o) {
    if (!$o || !$o->hoofdfoto_path) return null;
    $p = public_path('storage/'.$o->hoofdfoto_path);
    if (!file_exists($p)) return null;
    $type = strtolower(pathinfo($p, PATHINFO_EXTENSION));
    $mime = $type === 'jpg' ? 'jpeg' : $type;
    return 'data:image/'.$mime.';base64,'.base64_encode(file_get_contents($p));
  }

  $items = $reclame->items->pluck('occasion')->take(4);
  while ($items->count() < 4) $items->push(null);
@endphp

<div class="page">

<div class="topbar">
  <table class="topbarTable">
    <tr>
      <td class="topbarLeft" valign="middle">
        <img class="topLogo" src="{{ public_path('assets/gerritsen-logo-white.png') }}" alt="Gerritsen">
      </td>
    </tr>
  </table>
</div>

  <div class="hero">
    <h1 class="hero-title">
      WEEKAANBIED<span class="ing">ING!</span>
    </h1>

    <div class="line-wrap">
      <div class="line"></div>
      <table class="pill-table">
        <tr>
          <td>
            <span class="pill">{{ strtoupper($reclame->subtitle ?? 'ALLEEN DEZE WEEK GELDIG') }}</span>
          </td>
        </tr>
      </table>
    </div>
  </div>

  {{-- GRID --}}
  <table class="grid">
    <tr>
      @for($i=0;$i<2;$i++)
        @php $o = $items[$i]; @endphp
        <td class="cell">
          @if($o)
            <div class="card">
              <div class="card-img">
                @if($img = photo($o))
                  <img src="{{ $img }}" alt="">
                @endif
              </div>
              <div class="card-body">
                <div class="car-title">{{ trim($o->merk.' '.$o->model) }}</div>
                <div class="car-meta">{{ $o->bouwjaar }} • {{ number_format($o->tellerstand,0,',','.') }} km • {{ $occasion->brandstof ?? '-' }} • {{ $occasion->transmissie ?? '-' }}</div>
                <span class="year">JAAR {{ $o->bouwjaar }}</span>
                <span class="price">€ {{ number_format($o->prijs,0,',','.') }},-</span>
                <div class="row-clear"></div>
              </div>
            </div>
          @endif
        </td>
      @endfor
    </tr>

    <tr>
      @for($i=2;$i<4;$i++)
        @php $o = $items[$i]; @endphp
        <td class="cell">
          @if($o)
            <div class="card">
              <div class="card-img">
                @if($img = photo($o))
                  <img src="{{ $img }}" alt="">
                @endif
              </div>
              <div class="card-body">
                <div class="car-title">{{ trim($o->merk.' '.$o->model) }}</div>
                <div class="car-meta">{{ $o->bouwjaar }} • {{ number_format($o->tellerstand,0,',','.') }} km</div>
                <span class="year">JAAR {{ $o->bouwjaar }}</span>
                <span class="price">€ {{ number_format($o->prijs,0,',','.') }},-</span>
                <div class="row-clear"></div>
              </div>
            </div>
          @endif
        </td>
      @endfor
    </tr>
  </table>

  {{-- FOOTER --}}
  <div class="footer">
    <div class="footer-inner">
      <table class="footer-table">
        <tr>
          <td>
           <div class="phone">
  <table>
    <tr>
      <td class="phoneIcon">
        <img src="{{ public_path('assets/phone-call (6).svg') }}" height="20">
      </td>
      <td class="phoneNumber">
        +31 6 38257987
      </td>
    </tr>
  </table>
</div>

            <div class="domain">GERRITSENAUTOMOTIVE.NL</div>

            <div class="address">Gelderse Rooslaan 14 A, 6841 BE Arnhem</div>
          </td>
          <td class="rdw">
            <img src="{{ public_path('assets/rdw.svg') }}" height="46" alt="RDW">
          </td>
        </tr>
      </table>
    </div>
  </div>

</div>
</body>
</html>
