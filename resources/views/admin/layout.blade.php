<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Gerritsen Admin')</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('images/FAVICON-GERRITSEN.png') }}">

</head>
<body>
  <div class="admin-shell">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Gerritsen Automotive">
      </div>

      <nav class="menu">
        <a href="{{ route('admin.occasions.index') }}" class="menu-item {{ request()->routeIs('admin.occasions.*') ? 'active' : '' }}">
          <img src="{{ asset('images/car.svg') }}" alt="" aria-hidden="true">
          <span>Occasions</span>
        </a>

        <a href="{{ route('admin.aanhanger.index') }}" class="menu-item {{ request()->routeIs('admin.aanhanger.*') ? 'active' : '' }}">
          <img src="{{ asset('images/car-trailer.svg') }}" alt="" aria-hidden="true">
          <span>Aanhanger</span>
        </a>

        <a href="{{ route('admin.stofzuiger.index') }}" class="menu-item {{ request()->routeIs('admin.stofzuiger.*') ? 'active' : '' }}">
          <img src="{{ asset('images/vacuum-cleaner.svg') }}" alt="" aria-hidden="true">
          <span>Stofzuiger</span>
        </a>

        <a href="{{ route('admin.agenda.index') }}" class="menu-item {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
        <img src="{{ asset('images/agenda.svg') }}" alt="" aria-hidden="true">
        <span>Agenda</span>
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
