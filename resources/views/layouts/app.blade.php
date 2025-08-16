<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', config('app.name', 'WCC Licensing'))</title>

  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .bg-custom-primary {
    background-color: #262e70 !important;
    color: #fff !important;
    }
    :root {
      --sidebar-bg: #262e70; /* dark blue - sidebar */
      --primary-color: #262e70; /* button/brand color */
      --page-bg: #f8f9fa; /* slightly off-white */
      --topbar-bg: #ffffff;
    }

    html,body { height:100%; }
    body { background: var(--page-bg); min-height:100vh; margin:0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }

    /* Logo bar (white strip above sidebar) */
    .logo-bar { background: #fff; border-bottom: 1px solid #e9ecef; padding: 0.6rem 1rem; display:flex; align-items:center; justify-content:flex-start; }

    /* layout container */
    .app-shell { display:flex; min-height:calc(100vh - 0px); }
    /* Sidebar */
    .app-sidebar {
      width: 200px;
      background: var(--sidebar-bg);
      color: #fff;
      flex-shrink:0;
      display:flex;
      flex-direction:column;
      padding: 0.5rem 0;
    }
    .app-sidebar .nav-link {
      color: rgba(255,255,255,0.95);
      padding: 10px 16px;
      border-radius: 6px;
      margin: 3px 8px;
      display:flex;
      gap:.6rem;
      align-items:center;
    }
    .app-sidebar .nav-link i { width:20px; text-align:center; }
    .app-sidebar .nav-link:hover { background: rgba(255,255,255,0.06); text-decoration:none; color:#fff; }
    .app-sidebar .nav-link.active { background: rgba(255,255,255,0.10); font-weight:600; }

    /* main area */
    .app-main { flex:1; display:flex; flex-direction:column; min-height:100vh; }

    /* topbar */
    .app-topbar { background: var(--topbar-bg); border-bottom:1px solid #e9ecef; padding: .5rem 1rem; display:flex; align-items:center; justify-content:space-between; }
    .app-topbar .user-btn { background:transparent; border:0; color: #222; text-decoration:none; display:inline-flex; gap:.5rem; align-items:center; }

    /* content */
    .app-content { padding: 1.25rem; flex:1; }

    /* btn primary override */
    .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
    .btn-primary:hover { background-color: #1d245c; border-color:#1d245c; }

    /* responsive: mobile */
    @media (max-width: 991px) {
      .app-sidebar { position:fixed; left:-260px; top:0; bottom:0; z-index:1045; transition:left .22s ease; width:220px; }
      .app-sidebar.show { left:0; }
      .page-backdrop { position:fixed; inset:0; background: rgba(0,0,0,0.35); z-index:1040; display:none; }
      .page-backdrop.show { display:block; }
      .logo-bar .logo-wrap { padding-left:.5rem; }
    }

    /* small helpers */
    .small-muted { color:#6c757d; font-size:.95rem; }
  </style>

  @stack('styles')
</head>
<body>
  <!-- Logo bar (white) -->
  <div class="logo-bar">
    <div class="logo-wrap">
      <img src="{{ asset('build/assets/images/logo.png') }}" alt="logo" style="height:36px;">
    </div>
  </div>

  <div class="app-shell">
    <!-- Sidebar -->
    <aside id="appSidebar" class="app-sidebar">
      <nav class="nav flex-column mt-2">
        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a class="nav-link {{ request()->is('applications') ? 'active' : '' }}" href="{{ url('/applications') }}"><i class="bi bi-file-earmark-text"></i> Application</a>
        <a class="nav-link" href="#"><i class="bi bi-box-arrow-up-right"></i> Release</a>
        <a class="nav-link" href="#"><i class="bi bi-calendar-event"></i> Appointments</a>
      </nav>

      <div class="mt-auto px-3 pb-3 small text-white-50">
        WCC Licensing
      </div>
    </aside>

    <!-- backdrop for mobile sidebar -->
    <div id="pageBackdrop" class="page-backdrop"></div>

    <!-- Main -->
    <div class="app-main">
      <!-- Topbar -->
      <header class="app-topbar">
        <div class="d-flex align-items-center">
          <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none me-2" aria-label="Toggle menu"><i class="bi bi-list"></i></button>
          <div class="small-muted"><i class="bi bi-calendar"></i> {{ now()->format('M d, Y') }}</div>
        </div>

        <div>
          @auth
          <div class="dropdown">
            <a href="#" class="user-btn dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="fw-semibold">{{ Str::upper(auth()->user()->name ?? auth()->user()->email) }}</span>
              <i class="bi bi-chevron-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenu">
              <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item" type="submit">Sign out</button>
                </form>
              </li>
            </ul>
          </div>
          @endauth
          @guest
            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Sign in</a>
            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary">Register</a>
          @endguest
        </div>
      </header>

      <!-- Content -->
      <main class="app-content">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function(){
      const sidebar = document.getElementById('appSidebar');
      const toggle = document.getElementById('sidebarToggle');
      const backdrop = document.getElementById('pageBackdrop');

      function openSidebar(){ sidebar.classList.add('show'); backdrop.classList.add('show'); }
      function closeSidebar(){ sidebar.classList.remove('show'); backdrop.classList.remove('show'); }

      if (toggle) toggle.addEventListener('click', openSidebar);
      if (backdrop) backdrop.addEventListener('click', closeSidebar);
    })();
  </script>

  @stack('scripts')
</body>
</html>
