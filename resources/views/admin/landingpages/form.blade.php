@extends('admin.layout')
@section('title', ($page->exists ? 'Landingspagina bewerken' : 'Nieuwe landingspagina') . ' — Gerritsen Admin')
@section('page_title', $page->exists ? 'Landingspagina bewerken' : 'Nieuwe landingspagina')

@section('content')
@php $faqRows = old('faq', $page->faq ?? []); @endphp
<div class="adm-dash lp-form">

  <form method="POST"
        action="{{ $page->exists ? route('admin.landingpages.update', $page) : route('admin.landingpages.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if($page->exists) @method('PUT') @endif

    {{-- ALGEMEEN --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Algemeen</h3></div>
      <div class="form-card-body">
        <div class="grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <label class="input-row">
            <span>Interne titel</span>
            <input name="title" value="{{ old('title', $page->title) }}" required placeholder="bv. Occasions Arnhem">
          </label>
          <label class="input-row">
            <span>URL (slug)</span>
            <input name="slug" value="{{ old('slug', $page->slug) }}" placeholder="occasions-arnhem">
            <small style="color:var(--muted)">De pagina komt op <b>/{slug}</b>. Leeg laten = automatisch uit de titel.</small>
          </label>
        </div>
        <label class="lp-toggle">
          <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
          <span>Live zetten (zichtbaar voor bezoekers)</span>
        </label>
      </div>
    </div>

    {{-- HERO --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Hero <small style="color:var(--muted);font-weight:400">— de bovenkant, zelfde opmaak als de homepage</small></h3></div>
      <div class="form-card-body">
        <label class="input-row">
          <span>Eyebrow (klein label boven de titel)</span>
          <input name="hero_eyebrow" value="{{ old('hero_eyebrow', $page->hero_eyebrow) }}" placeholder="Gerritsen Automotive · Arnhem">
        </label>
        <label class="input-row">
          <span>Hero-titel (H1)</span>
          <input name="hero_title" value="{{ old('hero_title', $page->hero_title) }}" required placeholder="Betrouwbare occasions in Arnhem">
        </label>
        <label class="input-row">
          <span>Hero-subtitel</span>
          <textarea name="hero_subtitle" rows="2" placeholder="Korte pakkende zin onder de titel.">{{ old('hero_subtitle', $page->hero_subtitle) }}</textarea>
        </label>

        <div class="grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <label class="input-row">
            <span>CTA-knop tekst</span>
            <input name="cta_label" value="{{ old('cta_label', $page->cta_label) }}" placeholder="Bekijk aanbod">
          </label>
          <label class="input-row">
            <span>CTA-knop link</span>
            <input name="cta_url" value="{{ old('cta_url', $page->cta_url) }}" placeholder="/aanbod">
          </label>
        </div>

        <label class="input-row">
          <span>Achtergrondfoto hero (optioneel)</span>
          <input type="file" name="hero_image" accept="image/*">
          <small style="color:var(--muted)">Leeg laten = standaard homepage-achtergrond.</small>
        </label>
        @if($page->hero_image)
          <div class="lp-current-img">
            <img src="{{ asset('storage/'.$page->hero_image) }}" alt="Huidige hero">
            <span>Huidige achtergrond</span>
          </div>
        @endif

        <label class="lp-toggle">
          <input type="checkbox" name="show_occasions" value="1" {{ old('show_occasions', $page->show_occasions) ? 'checked' : '' }}>
          <span>Toon het actuele occasion-aanbod op deze pagina</span>
        </label>
      </div>
    </div>

    {{-- INHOUD --}}
    <div class="form-card">
      <div class="form-card-head"><h3>Tekstblok</h3></div>
      <div class="form-card-body">
        <label class="input-row">
          <span>SEO-tekst</span>
          <textarea name="body" rows="12" placeholder="Schrijf hier je SEO-tekst…">{{ old('body', $page->body) }}</textarea>
          <small style="color:var(--muted)">
            Ondersteunt Markdown: <code># Kop</code>, <code>**vet**</code>, <code>- lijst</code>, <code>[link](https://…)</code>. Lege regel = nieuwe alinea.
          </small>
        </label>
      </div>
    </div>

    {{-- FAQ --}}
    <div class="form-card">
      <div class="form-card-head"><h3>FAQ <small style="color:var(--muted);font-weight:400">— veelgestelde vragen onderaan de pagina</small></h3></div>
      <div class="form-card-body">
        <div id="faqList">
          @forelse($faqRows as $i => $row)
            <div class="lp-faq-row">
              <div class="lp-faq-fields">
                <input name="faq[{{ $i }}][question]" value="{{ $row['question'] ?? '' }}" placeholder="Vraag">
                <textarea name="faq[{{ $i }}][answer]" rows="2" placeholder="Antwoord">{{ $row['answer'] ?? '' }}</textarea>
              </div>
              <button type="button" class="btn sm lp-faq-remove" aria-label="Verwijder vraag">✕</button>
            </div>
          @empty
            <div class="lp-faq-row">
              <div class="lp-faq-fields">
                <input name="faq[0][question]" placeholder="Vraag">
                <textarea name="faq[0][answer]" rows="2" placeholder="Antwoord"></textarea>
              </div>
              <button type="button" class="btn sm lp-faq-remove" aria-label="Verwijder vraag">✕</button>
            </div>
          @endforelse
        </div>
        <button type="button" class="btn sm" id="faqAdd" style="margin-top:10px">+ Vraag toevoegen</button>
      </div>
    </div>

    {{-- SEO --}}
    <div class="form-card">
      <div class="form-card-head"><h3>SEO (zoekmachines)</h3></div>
      <div class="form-card-body">
        <label class="input-row">
          <span>Meta-titel</span>
          <input name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" maxlength="200" placeholder="Leeg = hero-titel + bedrijfsnaam">
        </label>
        <label class="input-row">
          <span>Meta-omschrijving</span>
          <textarea name="meta_description" rows="2" maxlength="300" placeholder="Korte omschrijving voor Google (max ~160 tekens).">{{ old('meta_description', $page->meta_description) }}</textarea>
        </label>
      </div>
    </div>

    <div class="page-actions">
      <a href="{{ route('admin.landingpages.index') }}" class="btn">Annuleren</a>
      <div class="spacer"></div>
      @if($page->exists)
        <a href="{{ $page->url() }}" target="_blank" rel="noopener" class="btn">Bekijk pagina ↗</a>
      @endif
      <button type="submit" class="btn primary">{{ $page->exists ? 'Opslaan' : 'Aanmaken' }}</button>
    </div>
  </form>
</div>

<style>
  .lp-form .input-row{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
  .lp-form .input-row span{font-size:13.5px;font-weight:600}
  .lp-form .input-row input,
  .lp-form .input-row textarea{width:100%}
  .lp-form .grid-2{margin-bottom:0}
  @media(max-width:720px){.lp-form .grid-2{grid-template-columns:1fr !important}}
  .lp-toggle{display:flex;align-items:center;gap:10px;margin-top:6px;font-size:14px;cursor:pointer}
  .lp-toggle input{width:18px;height:18px}
  .lp-current-img{display:flex;align-items:center;gap:12px;margin:4px 0 16px}
  .lp-current-img img{width:120px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--border)}
  .lp-current-img span{font-size:12.5px;color:var(--muted)}
  .lp-faq-row{display:flex;gap:10px;align-items:flex-start;margin-bottom:12px}
  .lp-faq-fields{flex:1;display:flex;flex-direction:column;gap:6px}
  .lp-faq-remove{flex:0 0 auto;color:#ef4444}
</style>

<script>
  (function () {
    const list = document.getElementById('faqList');
    const addBtn = document.getElementById('faqAdd');
    let idx = list.querySelectorAll('.lp-faq-row').length;

    addBtn.addEventListener('click', function () {
      const row = document.createElement('div');
      row.className = 'lp-faq-row';
      row.innerHTML =
        '<div class="lp-faq-fields">' +
          '<input name="faq[' + idx + '][question]" placeholder="Vraag">' +
          '<textarea name="faq[' + idx + '][answer]" rows="2" placeholder="Antwoord"></textarea>' +
        '</div>' +
        '<button type="button" class="btn sm lp-faq-remove" aria-label="Verwijder vraag">✕</button>';
      list.appendChild(row);
      idx++;
    });

    list.addEventListener('click', function (e) {
      if (e.target.closest('.lp-faq-remove')) {
        const rows = list.querySelectorAll('.lp-faq-row');
        if (rows.length > 1) {
          e.target.closest('.lp-faq-row').remove();
        } else {
          // laatste rij: alleen leegmaken
          e.target.closest('.lp-faq-row').querySelectorAll('input,textarea').forEach(el => el.value = '');
        }
      }
    });
  })();
</script>
@endsection
