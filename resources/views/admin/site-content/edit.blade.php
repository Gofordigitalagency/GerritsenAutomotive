@extends('admin.layout')
@section('title', 'Site-inhoud — ' . $groupConfig['label'])
@section('page_title', 'Site-inhoud bewerken')

@php
  use App\Http\Controllers\admin\SiteContentController;
@endphp

@section('content')

<style>
  /* Site-content editor — dark theme */
  .sc-wrap { display: grid; grid-template-columns: 240px 1fr; gap: 20px; align-items: start; }
  @media (max-width: 900px) { .sc-wrap { grid-template-columns: 1fr; } }

  .sc-tabs {
    display: flex;
    flex-direction: column;
    gap: 2px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 8px;
    position: sticky;
    top: 88px;
    max-height: calc(100vh - 110px);
    overflow-y: auto;
  }
  .sc-tab {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 8px;
    color: var(--muted);
    text-decoration: none;
    font-weight: 500;
    font-size: 13.5px;
    transition: background .15s, color .15s;
  }
  .sc-tab:hover { background: var(--surface-2); color: var(--text); }
  .sc-tab.active {
    background: var(--accent);
    color: #fff;
  }
  .sc-tab-icon { font-size: 16px; line-height: 1; flex-shrink: 0; }

  .sc-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px 30px;
  }
  .sc-card h2 {
    margin: 0 0 6px;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -.01em;
    color: var(--text);
  }
  .sc-card-sub { color: var(--muted); font-size: 14px; margin: 0 0 24px; }

  .sc-fields { display: grid; gap: 18px; }
  .sc-field { display: grid; gap: 6px; }
  .sc-field label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }
  .sc-field-help { font-size: 12px; color: var(--muted); font-weight: 400; }

  .sc-field input[type="text"],
  .sc-field input[type="email"],
  .sc-field input[type="tel"],
  .sc-field input[type="url"],
  .sc-field textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    background: var(--bg-2);
    color: var(--text);
    transition: border-color .15s, box-shadow .15s, background .15s;
  }
  .sc-field input:focus,
  .sc-field textarea:focus {
    outline: none;
    border-color: var(--accent);
    background: var(--surface);
    box-shadow: 0 0 0 3px rgba(230, 57, 70, .15);
  }
  .sc-field textarea { resize: vertical; min-height: 90px; line-height: 1.55; }

  /* Color picker */
  .sc-color { display: flex; align-items: center; gap: 12px; }
  .sc-color input[type="color"] {
    width: 56px;
    height: 42px;
    padding: 2px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--bg-2);
    cursor: pointer;
  }
  .sc-color input[type="text"] {
    flex: 1;
    font-family: 'Inter', monospace;
    font-size: 13px;
    text-transform: uppercase;
  }

  /* Image upload */
  .sc-image { display: grid; gap: 10px; }
  .sc-image-preview {
    width: 100%;
    max-width: 360px;
    aspect-ratio: 16/10;
    background: var(--bg-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--muted);
    font-size: 13px;
    overflow: hidden;
  }
  .sc-image-preview.has-img { background-color: #000; }
  .sc-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .sc-image-current {
    font-family: 'Inter', monospace;
    font-size: 11px;
    color: var(--muted);
    word-break: break-all;
  }

  /* File input — dark style */
  .sc-image input[type="file"] {
    padding: 8px;
    background: var(--bg-2);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text);
    font-size: 13px;
    cursor: pointer;
  }
  .sc-image input[type="file"]::file-selector-button {
    padding: 6px 12px;
    margin-right: 10px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    font-family: inherit;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
  }
  .sc-image input[type="file"]::file-selector-button:hover {
    border-color: rgba(255,255,255,.16);
  }

  .sc-foot {
    margin-top: 28px;
    padding-top: 22px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }
  .sc-foot-meta { font-size: 13px; color: var(--muted); }
  .sc-btn-save {
    background: var(--accent);
    color: #fff;
    padding: 11px 24px;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: background .15s, transform .15s;
  }
  .sc-btn-save:hover { background: var(--accent-soft); }
  .sc-btn-save:active { transform: translateY(1px); }
</style>

<div class="sc-wrap">

  <nav class="sc-tabs" aria-label="Categorieën">
    @foreach($schema as $gKey => $g)
      <a href="{{ route('admin.site-content.edit', $gKey) }}"
         class="sc-tab {{ $gKey === $group ? 'active' : '' }}">
        <span class="sc-tab-icon">{{ $g['icon'] ?? '•' }}</span>
        <span>{{ $g['label'] }}</span>
      </a>
    @endforeach
  </nav>

  <form action="{{ route('admin.site-content.update', $group) }}" method="post" enctype="multipart/form-data" class="sc-card">
    @csrf

    <h2>{{ $groupConfig['label'] }}</h2>
    <p class="sc-card-sub">Wijzigingen zijn direct zichtbaar op de site nadat je opslaat.</p>

    <div class="sc-fields">
      @foreach($fields as $key => $meta)
        @php
          $type     = $meta['type'] ?? 'text';
          $field    = SiteContentController::fieldName($key);
          $label    = $meta['label'] ?? $key;
          $help     = $meta['help'] ?? null;
          $stored   = $values[$key] ?? null;
          $current  = old($field, $stored ?? ($meta['default'] ?? ''));
        @endphp

        <div class="sc-field">
          <label for="f-{{ $field }}">
            <span>{{ $label }}</span>
            @if($help) <span class="sc-field-help">— {{ $help }}</span> @endif
          </label>

          @switch($type)

            @case('longtext')
              <textarea id="f-{{ $field }}" name="{{ $field }}" rows="4">{{ $current }}</textarea>
              @break

            @case('color')
              <div class="sc-color">
                <input type="color" id="f-{{ $field }}-pick"
                       value="{{ Str::startsWith($current,'#') ? $current : ($meta['default'] ?? '#000000') }}"
                       oninput="document.getElementById('f-{{ $field }}').value = this.value">
                <input type="text" id="f-{{ $field }}" name="{{ $field }}" value="{{ $current }}"
                       oninput="if (this.value.match(/^#[0-9a-fA-F]{6}$/)) document.getElementById('f-{{ $field }}-pick').value = this.value">
              </div>
              @break

            @case('image')
              <div class="sc-image">
                <div class="sc-image-preview {{ $current ? 'has-img' : '' }}">
                  @if($current)
                    @php
                      $previewSrc = (str_starts_with($current, 'site/') || str_starts_with($current, 'uploads/'))
                        ? asset('storage/' . $current)
                        : asset($current);
                    @endphp
                    <img src="{{ $previewSrc }}" alt="">
                  @else
                    Geen foto
                  @endif
                </div>
                <input type="file" id="f-{{ $field }}" name="{{ $field }}" accept="image/*">
                @if($current)
                  <span class="sc-image-current">Huidig: {{ $current }}</span>
                @endif
              </div>
              @break

            @case('email')
              <input type="email" id="f-{{ $field }}" name="{{ $field }}" value="{{ $current }}">
              @break

            @case('phone')
              <input type="tel" id="f-{{ $field }}" name="{{ $field }}" value="{{ $current }}">
              @break

            @case('url')
              <input type="url" id="f-{{ $field }}" name="{{ $field }}" value="{{ $current }}">
              @break

            @default
              <input type="text" id="f-{{ $field }}" name="{{ $field }}" value="{{ $current }}">
          @endswitch
        </div>
      @endforeach
    </div>

    <div class="sc-foot">
      <span class="sc-foot-meta">{{ count($fields) }} velden in deze groep</span>
      <button type="submit" class="sc-btn-save">Wijzigingen opslaan</button>
    </div>
  </form>

</div>

@endsection
