<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

  <title>Reserveren</title>
  <style>
    :root{
      --bg:#ffffff;           /* pagina-achtergrond */
      --card:#5B5B5B;         /* formulierkaart */
      --text:#ffffff;         /* tekst in kaart */
      --muted:#f1f1f1;        /* subtiele tekst */
      --line:rgba(255,255,255,.28);
      --chip:rgba(255,255,255,.10);
      --chip-hover:rgba(255,255,255,.18);
      --chip-active:rgba(255,255,255,.22);
      --accent:#3C3C3C;       /* i.p.v. rood: donkergrijs voor selectie */
      --accent-strong:#2A2A2A;
      --shadow:0 10px 30px rgba(0,0,0,.10);
      --radius:14px;
      --gap:14px;
    }

    *{box-sizing:border-box}
    html,body{margin:0;background:var(--bg);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#111}
    .wrap{max-width:980px;margin:36px auto;padding:0 16px}
    .title{font-size:34px;font-weight:800;margin:0 0 18px}

    /* Kaart */
    .card{
      background:var(--card);
      color:var(--text);
      border:1px solid var(--line);
      border-radius:18px;
      padding:20px;
      box-shadow:var(--shadow);
    }

    /* Layout helpers */
    .row{display:grid;grid-template-columns:1fr 1fr;gap:var(--gap)}
    .row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
    .input-row{display:flex;flex-direction:column;gap:8px}
    label span{font-size:14px;color:var(--muted)}

    /* === Input styling (geldt voor ALLES, incl. NAAM) === */
    .control{
      -webkit-appearance:none; appearance:none;
      width:100%;
      background:var(--chip);
      color:var(--text);
      border:1px solid var(--line);
      border-radius:12px;
      padding:12px 14px;
      line-height:1.35;
      font-size:16px;
      outline:none;
      transition:border-color .15s, background .15s, box-shadow .15s, color .15s;
    }
    .control::placeholder{color:rgba(255,255,255,.8)}
    .control:focus{
      background:var(--chip-hover);
      border-color:#fff;
      box-shadow:0 0 0 3px rgba(255,255,255,.15);
    }
    /* Date picker icoon beter zichtbaar */
    input[type="date"].control::-webkit-calendar-picker-indicator{
      filter: invert(1) opacity(.85);
      cursor:pointer;
    }
    /* Autofill (Chrome) */
    input.control:-webkit-autofill{
      -webkit-text-fill-color:#fff;
      box-shadow:0 0 0 30px var(--chip) inset !important;
      transition:background-color 5000s ease-in-out 0s;
    }
    input.control:-webkit-autofill:focus{
      box-shadow:0 0 0 30px var(--chip-hover) inset !important;
    }

    /* Segment knoppen (Aanhanger/Stofzuiger) */
    .seg{display:flex;gap:10px;flex-wrap:wrap}
    .seg a{
      padding:10px 14px; border:1px solid var(--line); border-radius:12px;
      background:var(--chip); color:var(--text); text-decoration:none;
      transition:background .15s,border-color .15s,transform .05s;
    }
    .seg a:hover{background:var(--chip-hover)}
    .seg a:active{transform:translateY(1px)}
    .seg a.active{background:#111;border-color:#111;color:#fff}

    /* Tijdsloten */
    .times{display:flex;flex-wrap:wrap;gap:10px;min-height:44px}
    .times button{
      padding:10px 12px; border-radius:12px;
      border:1px solid var(--line);
      background:var(--chip); color:var(--text);
      cursor:pointer;
      transition:background .15s,border-color .15s,transform .05s,color .15s;
    }
    .times button:hover{background:var(--chip-hover)}
    .times button:active{transform:translateY(1px)}
    .times button.sel-start{background:var(--accent);border-color:var(--accent)}
    .times button.range{background:var(--chip-active)}
    .times button.selected{background:var(--accent-strong);border-color:var(--accent-strong);color:#fff}

    .muted{color:var(--muted)}
    .divider{height:1px;background:var(--line);margin:18px 0}

    .btn{
      display:inline-block; padding:12px 16px; border-radius:12px;
      border:1px solid var(--accent-strong); background:var(--accent-strong);
      color:#fff; font-weight:700; cursor:pointer;
      transition:background .15s,border-color .15s,transform .05s;
    }
    .btn:hover{background:#1f1f1f;border-color:#1f1f1f}
    .btn:active{transform:translateY(1px)}
    .btn:disabled{opacity:.6;cursor:not-allowed}

    .alert{padding:12px 14px;border-radius:12px;margin-bottom:12px;border:1px solid #cfe8d2;background:#e9f6eb;color:#244a2b}
    .error{border-color:#e9b3b6;background:#fdeced;color:#6b1c20}

    .summary{display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text)}

    /* Responsive */
    @media (max-width:900px){ .row,.row-3{grid-template-columns:1fr} }
    @media (max-width:480px){
      .wrap{padding:0 12px}
      .title{font-size:28px}
      .seg a, .times button{padding:9px 12px}
      .control{font-size:15px;padding:11px 12px}
    }
  </style>
</head>
<body>
  <div class="wrap">
    @if(session('ok')) <div class="alert">{{ session('ok') }}</div> @endif
    @if($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif

    <h1 class="title">Reserveren</h1>

    <div class="card">
      <div class="input-row">
        <span>Onderdeel</span>
        <div class="seg">
          <a href="{{ route('booking.show',['type'=>'aanhanger']) }}" class="{{ $type==='aanhanger'?'active':'' }}">Aanhanger</a>
          <a href="{{ route('booking.show',['type'=>'stofzuiger']) }}" class="{{ $type==='stofzuiger'?'active':'' }}">Tapijtreiniger</a>
        </div>
      </div>

      <div class="row" style="margin-top:14px">
        <label class="input-row">
          <span>Datum</span>
          <input class="control" type="date" id="date" min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}">
        </label>

        <div class="input-row">
          <span>Beschikbare tijden</span>
          <div id="times" class="times">Laden...</div>
        </div>
      </div>

      <div class="divider"></div>

      <div id="pickRange" class="summary">Selecteer eerst een starttijd (klik), daarna een eindtijd (tweede klik).</div>

      <form id="bookForm" method="post" action="{{ route('booking.store') }}" style="margin-top:10px;display:none">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="start_at" id="start_at">
        <input type="hidden" name="end_at" id="end_at">

        <div class="row" style="margin-top:12px">
          <label class="input-row"><span>Naam</span>
            <input class="control" required name="name" placeholder="Voor- en achternaam" value="{{ old('name') }}">
          </label>
          <label class="input-row"><span>Telefoon</span>
            <input class="control" required type="tel" name="phone" placeholder="06…" value="{{ old('phone') }}">
          </label>
          <label class="input-row" style="grid-column:1/-1"><span>E-mail</span>
            <input class="control" required type="email" name="email" placeholder="jij@email.nl" value="{{ old('email') }}">
          </label>
        </div>

        <div class="row-3" style="align-items:center;margin-top:12px">
          <div class="summary" id="selSummary">Nog geen tijd geselecteerd</div>
          <span></span>
          <button class="btn" type="submit" id="submitBtn" disabled>Reservering bevestigen</button>
        </div>
        <p class="muted" style="margin:8px 0 0">Betaling vindt plaats bij Gerritsen Automotive.</p>
      </form>
    </div>
  </div>

  <script>
    const type = @json($type);
    const dateEl = document.getElementById('date');
    const timesEl = document.getElementById('times');
    const bookForm = document.getElementById('bookForm');
    const startInput = document.getElementById('start_at');
    const endInput = document.getElementById('end_at');
    const selSummary = document.getElementById('selSummary');
    const submitBtn = document.getElementById('submitBtn');

    let slots = [];             // [{start:"YYYY-MM-DD HH:mm", label:"HH:mm"}]
    let selStartIndex = null;   // index in slots
    let selEndIndex   = null;

    function resetSelection(){
      selStartIndex = selEndIndex = null;
      startInput.value = endInput.value = '';
      submitBtn.disabled = true;
      selSummary.textContent = 'Nog geen tijd geselecteerd';
      bookForm.style.display = 'none';
      [...timesEl.children].forEach(b => { b.classList.remove('sel-start','range','selected'); });
    }

    async function loadSlots(){
      resetSelection();
      timesEl.innerHTML = 'Laden...';
      const url = @json(route('booking.slots')) + `?type=${encodeURIComponent(type)}&date=${encodeURIComponent(dateEl.value)}`;
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
        if(!res.ok) throw new Error('HTTP '+res.status);
        slots = await res.json();
        renderSlots();
      } catch(err){
        console.error(err);
        timesEl.innerHTML = '<span class="muted">Kon tijden niet laden.</span>';
      }
    }

    function renderSlots(){
      if(!Array.isArray(slots) || slots.length===0){
        timesEl.innerHTML = '<span class="muted">Geen tijden beschikbaar.</span>';
        return;
      }
      timesEl.innerHTML = '';
      slots.forEach((s, i) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = s.label;
        b.dataset.index = i;
        b.addEventListener('click', () => handleClick(i, b));
        timesEl.appendChild(b);
      });
    }

    function contiguous(fromIdx, toIdx){
      if(toIdx <= fromIdx) return false;
      for(let j = fromIdx; j < toIdx; j++){
        if(!slots[j] || !slots[j+1]) return false;
      }
      return true;
    }

    function handleClick(i, btn){
      if(selStartIndex === null){
        selStartIndex = i;
        btn.classList.add('sel-start','selected');
        selSummary.textContent = `Start: ${slots[i].label}`;
        bookForm.style.display = 'none';
        submitBtn.disabled = true;
        return;
      }
      if(i <= selStartIndex) { resetSelection(); handleClick(i, btn); return; }
      if(!contiguous(selStartIndex, i)) { resetSelection(); handleClick(i, btn); return; }

      selEndIndex = i;

      // highlight range
      [...timesEl.children].forEach(b => b.classList.remove('range','selected'));
      for(let k=selStartIndex; k<=selEndIndex; k++){
        timesEl.children[k].classList.add('range');
      }
      timesEl.children[selStartIndex].classList.add('sel-start','selected');
      timesEl.children[selEndIndex].classList.add('selected');

      // end = slotEnd + 30 min
      const start = new Date(slots[selStartIndex].start.replace(' ', 'T'));
      const endBase = new Date(slots[selEndIndex].start.replace(' ', 'T'));
      const end = new Date(endBase.getTime() + 30*60000);

      const pad = (n)=> (n<10?'0':'')+n;
      const toISOshort = (d)=> d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())+' '+pad(d.getHours())+':'+pad(d.getMinutes());

      startInput.value = toISOshort(start);
      endInput.value   = toISOshort(end);

      selSummary.textContent = `Gekozen tijd: ${slots[selStartIndex].label} – ${pad(end.getHours())}:${pad(end.getMinutes())}`;
      bookForm.style.display = 'block';
      submitBtn.disabled = false;
    }

    dateEl.addEventListener('change', loadSlots);
    loadSlots();
  </script>
</body>
</html>
