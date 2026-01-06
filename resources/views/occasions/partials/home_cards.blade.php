@foreach($nieuw as $car)
  <a class="car-card" href="{{ route('occasions.show', $car->slug) }}">
    <div class="car-photo">
      <img
        src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}"
        alt="{{ $car->titel }}"
      >
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

      <div class="car-price">€ {{ number_format($car->prijs ?? 0, 0, ',', '.') }},-</div>
    </div>
  </a>
@endforeach
