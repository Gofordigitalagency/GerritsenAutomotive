<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inloggen – Gerritsen Automotive</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
</head>
<body class="auth-wrap">
  <div class="auth-card">
    <div class="auth-brand">
      <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive" class="auth-logo">
      <h1>Inloggen</h1>
    </div>

    @if(session('status'))
      <div class="auth-alert ok">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
      <div class="auth-alert error">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ url('/login') }}" method="post" class="auth-form">
      @csrf

      <label class="auth-field">
        <span>E-mail</span>
        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
      </label>

      <label class="auth-field">
        <span>Wachtwoord</span>
        <div class="auth-password">
          <input type="password" name="password" id="password" autocomplete="current-password" required>
          <button type="button" class="auth-eye" aria-label="Toon wachtwoord" onclick="togglePw()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
          </button>
        </div>
      </label>

      <label class="auth-check">
        <input type="checkbox" name="remember"> <span>Ingelogd blijven</span>
      </label>

      <button class="auth-btn" type="submit">Inloggen</button>
    </form>

    <div class="auth-footer">
      <a href="{{ route('home') }}" class="auth-link">← Terug naar home</a>
    </div>
  </div>

  <script>
    function togglePw(){
      const el = document.getElementById('password');
      el.type = el.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
