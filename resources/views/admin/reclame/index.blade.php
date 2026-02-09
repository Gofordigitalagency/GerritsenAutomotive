@extends('admin.layout')
@section('title', 'Reclame export')

@section('content')
  <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <h1 style="margin:0;">Reclame export</h1>

    <a href="{{ route('admin.reclame.create') }}" class="btn-primary">
      + Nieuwe reclame
    </a>
  </div>

  @if(session('success'))
    <div class="alert-success" style="margin-top:12px;">{{ session('success') }}</div>
  @endif

  <div style="margin-top:16px;">
    @if($reclames->count() === 0)
      <div style="padding:14px;border:1px solid #eee;border-radius:10px;">
        Nog geen reclames aangemaakt.
      </div>
    @else
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="text-align:left;border-bottom:1px solid #eee;">
            <th style="padding:10px;">#</th>
            <th style="padding:10px;">Titel</th>
            <th style="padding:10px;">Subtitel</th>
            <th style="padding:10px;">Aangemaakt</th>
            <th style="padding:10px;text-align:right;">Acties</th>
          </tr>
        </thead>

        <tbody>
          @foreach($reclames as $r)
            <tr style="border-bottom:1px solid #f2f2f2;">
              <td style="padding:10px;">{{ $r->id }}</td>
              <td style="padding:10px;font-weight:700;">{{ $r->title }}</td>
              <td style="padding:10px;color:#555;">{{ $r->subtitle }}</td>
              <td style="padding:10px;color:#666;">{{ $r->created_at?->format('d-m-Y H:i') }}</td>
              <td style="padding:10px;text-align:right;white-space:nowrap;">
                <a class="btn" href="{{ route('admin.reclame.edit', $r) }}">Bewerken</a>
                <a class="btn" href="{{ route('admin.reclame.pdf', $r) }}" target="_blank">PDF</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div style="margin-top:12px;">
        {{ $reclames->links() }}
      </div>
    @endif
  </div>
@endsection
