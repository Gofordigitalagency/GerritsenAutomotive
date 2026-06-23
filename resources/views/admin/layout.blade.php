<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Gerritsen Admin')</title>

  <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">

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
<body>
  <div class="admin-shell">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
      </div>

      @php
        $openTasks = \App\Models\Task::whereNull('completed_at')->count();
        $isBookings = request()->routeIs('admin.bookings.*')
                   || request()->routeIs('admin.aanhanger.*')
                   || request()->routeIs('admin.stofzuiger.*')
                   || request()->routeIs('admin.koplampen.*')
                   || request()->routeIs('admin.workshop.*');
      @endphp

      <nav class="menu">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
          <span>Dashboard</span>
        </a>

        <div class="menu-section">Voorraad</div>

        <a href="{{ route('admin.occasions.index') }}" class="menu-item {{ request()->routeIs('admin.occasions.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l3-6h12l3 6M3 9v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9h18"/><circle cx="7.5" cy="14.5" r="1.5"/><circle cx="16.5" cy="14.5" r="1.5"/></svg>
          <span>Occasions</span>
        </a>

        <div class="menu-section">Werk</div>

        <a href="{{ route('admin.tasks.index') }}" class="menu-item {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
          <span>Taken</span>
          @if($openTasks > 0)<span class="menu-badge">{{ $openTasks }}</span>@endif
        </a>

        <a href="{{ route('admin.bookings.index') }}" class="menu-item {{ $isBookings ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span>Reserveringen</span>
        </a>

        <a href="{{ route('admin.agenda.index') }}" class="menu-item {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>Agenda</span>
        </a>

        <div class="menu-section">Beheer</div>

        <a href="{{ route('admin.reclame.index') }}" class="menu-item {{ request()->routeIs('admin.reclame.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-7-7 18-2-9-9-2z"/></svg>
          <span>Reclame</span>
        </a>

        <a href="{{ route('admin.site-content.edit') }}" class="menu-item {{ request()->routeIs('admin.site-content.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <span>Site-inhoud</span>
        </a>
      </nav>


    </aside>

    <!-- Main -->
    <main class="admin-main">
      <header class="admin-topbar">
        <button class="hamburger" id="sidebarToggle" aria-label="Menu">☰</button>
        <h1 class="page-title">@yield('page_title','Admin')</h1>

        <div class="spacer"></div>

        <form action="{{ route('logout') }}" method="post">
          @csrf
          <button class="btn" type="submit">Uitloggen</button>
        </form>
      </header>

      <section class="admin-content">
        @if(session('ok'))
          <div class="alert success">{{ session('ok') }}</div>
        @endif

        @if(session('success'))
          <div class="alert success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="alert error">
            <ul>
              @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        @yield('content')
      </section>
    </main>
  </div>

  <script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
      document.body.classList.toggle('sidebar-open');
    });
  </script>
</body>
</html>
