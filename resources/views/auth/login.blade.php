<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inloggen — Gerritsen Automotive</title>

    <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/preview.css') }}?v={{ filemtime(public_path('css/preview.css')) }}">

    <style>
      :root {
        --px-bg:        {{ setting('theme.bg') }};
        --px-bg-2:      {{ setting('theme.bg_alt') }};
        --px-surface:   {{ setting('theme.surface') }};
        --px-fg:        {{ setting('theme.fg') }};
        --px-fg-muted:  {{ setting('theme.fg_muted') }};
        --px-accent:        {{ setting('theme.accent') }};
        --px-accent-soft:   {{ setting('theme.accent_soft') }};
        --px-border:    {{ setting('theme.border') }};
      }
    </style>
</head>
<body class="px-body px-auth-body">

  <div class="px-auth-bg">
    <div class="px-auth-bg-img" style="background-image: url('{{ setting_image('hero.bg_image') }}');"></div>
    <div class="px-auth-bg-overlay"></div>
    <div class="px-hero-grain"></div>
  </div>

  <main class="px-auth-shell">
    <div class="px-auth-card">
      <a href="{{ route('home') }}" class="px-auth-logo" aria-label="Gerritsen Automotive">
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
      </a>

      <div class="px-auth-head">
        <div class="px-eyebrow"><span class="px-eyebrow-dot"></span>Beheer</div>
        <h1>Inloggen</h1>
        <p>Log in op het beheer-paneel om site-inhoud, occasions en reserveringen te beheren.</p>
      </div>

      @if(session('status'))
        <div class="px-form-success">{{ session('status') }}</div>
      @endif
      @if($errors->any())
        <div class="px-form-error">
          @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
      @endif

      <form action="{{ url('/login') }}" method="POST" class="px-auth-form">
        @csrf

        <div class="px-input-wrap">
          <label for="pxAuthEmail">E-mail</label>
          <input type="email" id="pxAuthEmail" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
        </div>

        <div class="px-input-wrap">
          <label for="pxAuthPw">Wachtwoord</label>
          <div class="px-auth-pw">
            <input type="password" id="pxAuthPw" name="password" autocomplete="current-password" required>
            <button type="button" class="px-auth-eye" aria-label="Toon wachtwoord" id="pxAuthEye">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <label class="px-vk-checkbox">
          <input type="checkbox" name="remember">
          <span>Ingelogd blijven</span>
        </label>

        <button type="submit" class="px-btn px-btn-primary px-btn-lg" data-magnetic>Inloggen</button>
      </form>

      <a href="{{ route('home') }}" class="px-auth-back">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Terug naar de site
      </a>
    </div>
  </main>

  <script src="{{ asset('js/preview.js') }}?v={{ filemtime(public_path('js/preview.js')) }}" defer></script>
  <script>
    (function () {
      const pw = document.getElementById('pxAuthPw');
      const eye = document.getElementById('pxAuthEye');
      eye?.addEventListener('click', () => {
        pw.type = pw.type === 'password' ? 'text' : 'password';
        eye.classList.toggle('is-on', pw.type === 'text');
      });
    })();
  </script>
</body>
</html>
