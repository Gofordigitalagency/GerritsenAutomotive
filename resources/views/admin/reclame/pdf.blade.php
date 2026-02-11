<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">

<style>
  * { font-family: DejaVu Sans, Arial, Helvetica, sans-serif !important; }
  @page { margin:0; }
  body { margin:0; padding:0; }

  .topbar{
    background:#0e0e0e;
    height:75px;
    padding-left:22px;
  }
  .topbar img{ height:44px; margin-top:16px; }

  .hero{
    background:#fff;
    padding: 26px 0 20px;
  }

.hero-title{
  text-align:center;
  font-size:46px;
  letter-spacing:2px;
  color:#111;
  font-weight:700; /* 👈 maakt WEKENAANBIEDING! dik */
}
  .hero-title .ing{ color:#f4b400; }

  /* lijn + pill */
  .line-wrap{
    position:relative;
    height:32px;
    margin-top:18px;
  }

  /* doorlopende lijn (achter) */
  .line-wrap .line{
    position:absolute;
    left:0;
    right:0;
    top:16px;        /* geen transform */
    height:2px;
    background:#111;
    z-index:1;
  }

  /* pill exact gecentreerd via table */
  .pill-table{
    width:100%;
    border-collapse:collapse;
    position:relative;
    z-index:2;
  }
  .pill-table td{
    text-align:center;
    padding:0;
  }
  .pill{
    display:inline-block;
    background:#f4b400;
    color:#000;
    padding:7px 18px;
    font-size:12px;
    letter-spacing:1px;
    text-transform:uppercase;
  }

/* ===== TITLE ===== */
.title {
  text-align: center;
  margin: 18px 0 6px;
  font-size: 32px;
}

.subtitle {
  text-align: center;
  background: var(--green);
  color: #fff;
  display: inline-block;
  padding: 6px 16px;
  margin: 0 auto 20px;
}

/* ===== GRID ===== */
table.grid {
  width: 100%;
  border-collapse: separate;
  border-spacing: 16px;
}

td.cell {
  width: 50%;
  vertical-align: top;
}

/* ===== CARD ===== */
.card {
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
}

/* IMAGE */
.card-img {
  height: 190px;
  overflow: hidden;
  background: #000;
}

.card-img img {
  width: 100%;
  height: auto;
  display: block;
  margin-top: -25px; /* 👈 schuift foto omhoog */
}

/* BODY */
.card-body {
  padding: 12px;
}

.car-title {
  font-size: 13px;
  margin-bottom: 4px;
}

.car-meta {
  color: #666;
  margin-bottom: 8px;
}

.year {
  display: inline-block;
  background: var(--green);
  color: #fff;
  padding: 3px 8px;
  font-size: 11px;
}

.price {
  float: right;
  font-size: 18px;
  color: var(--green);
}

/* ===== FOOTER ===== */
.footer {
  margin-top: 22px;
  border-top: 1px solid var(--border);
  padding-top: 14px;
}

.footer-table {
  width: 100%;
}

.phone {
  font-size: 18px;
}

.domain {
  background: #000;
  color: #fff;
  display: inline-block;
  padding: 6px 14px;
  margin: 6px 0;
}

.address {
  color: #666;
}

.rdw {
  text-align: right;
}
</style>
</head>

<body>
@php
function photo($o) {
  if (!$o->hoofdfoto_path) return null;
  $p = public_path('storage/'.$o->hoofdfoto_path);
  if (!file_exists($p)) return null;
  $type = pathinfo($p, PATHINFO_EXTENSION);
  return 'data:image/'.$type.';base64,'.base64_encode(file_get_contents($p));
}

$items = $reclame->items->pluck('occasion')->take(4);
while ($items->count() < 4) $items->push(null);
@endphp

<div class="page">

<div class="topbar">
  <img src="{{ public_path('assets/gerritsen-logo-white.png') }}" alt="Gerritsen">
</div>

<div class="hero">
  <div class="hero-title">
    WEKENAANBIED<span class="ing">ING!</span>
  </div>

  <div class="line-wrap">
    <div class="line"></div>

    <table class="pill-table">
      <tr>
        <td>
          <span class="pill">{{ strtoupper($reclame->subtitle ?? 'ALLEEN DEZE WEEK SCHERP GEPRIJSD!') }}</span>
        </td>
      </tr>
    </table>
  </div>
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
              <img src="{{ $img }}">
            @endif
          </div>
          <div class="card-body">
            <div class="car-title">{{ $o->merk }} {{ $o->model }} {{ $o->type }}</div>
            <div class="car-meta">{{ $o->bouwjaar }} • {{ number_format($o->tellerstand,0,',','.') }} km</div>
            <span class="year">JAAR {{ $o->bouwjaar }}</span>
            <span class="price">€ {{ number_format($o->prijs,0,',','.') }},-</span>
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
              <img src="{{ $img }}">
            @endif
          </div>
          <div class="card-body">
            <div class="car-title">{{ $o->merk }} {{ $o->model }} {{ $o->type }}</div>
            <div class="car-meta">{{ $o->bouwjaar }} • {{ number_format($o->tellerstand,0,',','.') }} km</div>
            <span class="year">JAAR {{ $o->bouwjaar }}</span>
            <span class="price">€ {{ number_format($o->prijs,0,',','.') }},-</span>
          </div>
        </div>
        @endif
      </td>
      @endfor
    </tr>
  </table>

  {{-- FOOTER --}}
  <div class="footer">
    <table class="footer-table">
      <tr>
        <td>
          <div class="phone">
            <img src="{{ public_path('assets/phone-call (6).svg') }}" height="16">
            +31 6 38257987
          </div>
          <div class="domain">GERRITSENAUTOMOTIVE.NL</div>
          <div class="address">Roggenstraat 1 • 8081 JN • Elburg</div>
        </td>
        <td class="rdw">
          <img src="{{ public_path('assets/rdw.svg') }}" height="42">
        </td>
      </tr>
    </table>
  </div>

</div>
</body>
</html>
