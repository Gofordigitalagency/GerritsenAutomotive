@foreach($nieuw as $car)
  <a class="car-card" href="{{ route('occasions.show', $car->slug) }}">
    <div class="car-photo">
      <img
        src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}"
        alt="{{ $car->titel }}"
      >
      @if(!empty($car->oude_prijs) && $car->oude_prijs > $car->prijs)
        <span class="car-sale-badge">Aanbieding</span>
      @endif
    </div>

    <div class="car-info">
      @php
        $merkModel = trim(($car->merk ?? '').' '.($car->model ?? ''));
        $type = $car->type ?? '';

        if ($merkModel === '' && !empty($car->titel)) {
          $titel = trim($car->titel);
          $merkModel = $type
            ? trim(preg_replace('/\s*' . preg_quote($type, '/') . '\s*$/i', '', $titel))
            : $titel;
        }

        if ($merkModel === '') {
          $merkModel = $car->titel ?? '';
        }

        $hasDiscount = !empty($car->oude_prijs) && $car->oude_prijs > $car->prijs;
      @endphp

      <h3 class="car-title">{{ $merkModel }}</h3>

      @if(!empty($type))
        <div class="car-type">{{ $type }}</div>
      @endif

      <div class="car-meta">
        <span>{{ ucfirst($car->transmissie ?? '') }}</span>
        <span>{{ $car->bouwjaar ?? '—' }}</span>
        <span>{{ ucfirst($car->brandstof ?? '') }}</span>
        <span>{{ number_format($car->tellerstand ?? 0, 0, ',', '.') }} km</span>
      </div>

      @if($hasDiscount)
        <div class="car-price-block">
          <span class="car-price-old">€ {{ number_format($car->oude_prijs, 0, ',', '.') }},-</span>
          <span class="car-price car-price-sale">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }},-</span>
        </div>
      @else
        <div class="car-price">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }},-</div>
      @endif
    </div>
  </a>
@endforeach
