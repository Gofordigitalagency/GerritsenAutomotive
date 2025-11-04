@php
    use Illuminate\Support\Facades\Storage;
@endphp

<h2>Nieuwe auto-inkoop lead</h2>

<p><strong>Kenteken:</strong> {{ $data['license_plate'] }}<br>
<strong>Kilometerstand:</strong> {{ number_format($data['mileage'],0,',','.') }} km<br>
<strong>Merk/Model:</strong> {{ $data['brand'] ?? '-' }} / {{ $data['model'] ?? '-' }}</p>

<p><strong>Opties:</strong> {{ !empty($data['options']) ? implode(', ', $data['options']) : '-' }}</p>
<p><strong>Bijzonderheden:</strong><br>{!! nl2br(e($data['remarks'] ?? '-')) !!}</p>

<hr>

<p><strong>Naam:</strong> {{ $data['name'] }}<br>
<strong>Tel:</strong> {{ $data['phone'] }}<br>
<strong>Email:</strong> {{ $data['email'] }}</p>

@if(!empty($photos))
  <hr>
  <p><strong>Foto’s ({{ count($photos) }}):</strong></p>

  @foreach($photos as $p)
    @php
        // Absoluut pad op schijf (public disk)
        $pathOnDisk = Storage::disk('public')->path($p);
        // CID genereren en gebruiken in <img>
        $cid = $message->embed($pathOnDisk);
    @endphp
    <p style="margin:10px 0;">
      <img src="{{ $cid }}" alt="Auto foto"
           style="max-width:100%;height:auto;border-radius:8px;display:block;">
    </p>
  @endforeach
@endif
