@extends('admin.layout')
@section('title','Occasions')
@section('page_title','Occasions')

@section('content')
  <div class="occasions-head">
    <div>
      <h2 class="oc-title">Occasions</h2>
      <p class="oc-sub">Beheer je voorraad. Klik op een kaart om te bewerken.</p>
    </div>
    <div class="oc-actions">
      <a href="{{ route('admin.occasions.create') }}" class="btn primary">+ Nieuwe occasion</a>
    </div>
  </div>

  @if($items->count() === 0)
    <div class="empty-state">
      <h3>Nog geen occasions</h3>
      <p>Voeg je eerste occasion toe om hier te tonen.</p>
    </div>
  @else
    <div class="occasions-grid">
      @foreach($items as $o)
        <div class="car-card">
          <a class="car-photo" href="{{ route('admin.occasions.edit', $o) }}" aria-label="Bewerken: {{ $o->titel }}">
            <img
              src="{{ $o->hoofdfoto_path ? asset('storage/'.$o->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}"
              alt="{{ $o->titel }}"
            >
            <span class="price-chip">€ {{ number_format($o->prijs ?? 0, 0, ',', '.') }},-</span>
          </a>

          <div class="car-body">
            <h3 class="car-title">{{ $o->titel }}</h3>

            <ul class="specs">
              <li><span class="k">Transmissie</span><span class="v">{{ $o->transmissie }}</span></li>
              <li><span class="k">Brandstof</span><span class="v">{{ $o->brandstof }}</span></li>
              <li><span class="k">Bouwjaar</span><span class="v">{{ $o->bouwjaar }}</span></li>
            </ul>

        <div class="car-actions">

  <a href="{{ route('admin.occasions.edit', $o) }}" class="btn sm">Bewerken</a>

  {{-- VERKOCHT / BESCHIKBAAR --}}
  <form action="{{ route('admin.occasions.toggleStatus', $o) }}" method="post" style="display:inline;">
    @csrf
    @php
      $isVerkocht = str_contains($o->titel, '(VERKOCHT)');
    @endphp

    @if($isVerkocht)
      <button type="submit" class="btn sm" style="background:#ccc;color:#333;">
        Beschikbaar
      </button>
    @else
      <button type="submit" class="btn sm" style="background:#1DA1F2;color:white;">
        Verkocht
      </button>
    @endif
  </form>

  {{-- Verwijderen --}}
  <form action="{{ route('admin.occasions.destroy', $o) }}" method="post" onsubmit="return confirm('Verwijderen?')" style="display:inline;">
    @csrf @method('DELETE')
    <button class="btn sm danger" type="submit">Verwijderen</button>
  </form>

</div>

          </div>
        </div>
      @endforeach
    </div>

    <div class="pagination-wrap">
      {{ $items->links() }}
    </div>
  @endif
@endsection
