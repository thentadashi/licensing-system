<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Panel - @yield('title','Dashboard')</title>

  <!-- Bootstrap + Icons (CDN for simplicity) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* page background */
    body { background-color: #f8f9fa; min-height:100vh; }

    /* topbar */
    .topbar { background: #f2f2f2; border-bottom: 1px solid #e6e6e6; }

    /* sidebar */
    .sidebar {
      width: 250px;
      background-color: #262e70; /* dark blue */
      color: #fff;
      flex-shrink: 0;
      min-height: 100vh;
    }
    .sidebar .sidebar-inner { padding: 1rem; }
    .sidebar .nav-link {
      color: rgba(255,255,255,0.95);
      padding: 10px 14px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: .6rem;
    }
    .sidebar .nav-link i { font-size: 1.05rem; width:20px; text-align:center; }
    .sidebar .nav-link:hover { background-color: rgba(255,255,255,0.06); color:#fff; text-decoration:none; }
    .sidebar .nav-link.active { background-color: rgba(255,255,255,0.10); font-weight:600; }

    /* logo */
    .sidebar-logo { text-align:center; padding: 5px 0px; background-color: white}

    /* main content */
    .main-content { flex:1; }

    /* primary color override */
    .btn-primary {
      background-color: #262e70;
      border-color: #262e70;
    }
    .btn-primary:hover {
      background-color: #1d245c;
      border-color: #1d245c;
    }

    /* icon circle used in cards */
    .icon-circle {
      width:64px; height:64px; border-radius:50%;
      display:inline-flex; align-items:center; justify-content:center;
      font-size:1.25rem;
    }

    /* responsive sidebar: mobile slide-in */
    @media (max-width: 991px) {
      .sidebar { position: fixed; left: -260px; top:0; transition:left .22s ease; z-index:1045; }
      .sidebar.show { left: 0; }
      .page-backdrop { position:fixed; inset:0; background: rgba(0,0,0,0.35); z-index:1040; display:none; }
      .page-backdrop.show { display:block; }
      .main-content { padding: 1rem; }
    }
        #successAlert {
            transition: opacity 0.5s ease;
        }

  </style>

  @stack('styles')
</head>
<body>
  <div class="d-flex">

    <!-- Sidebar -->
    <aside id="adminSidebar" class="sidebar">
      <div class="sidebar-inner d-flex flex-column h-100">
        <div class="sidebar-logo mb-3">
          <img src="{{ asset('build/assets/images/logo.png') }}" alt="Logo" style="width:120px; height:auto;">
        </div>
        <nav class="nav flex-column mb-3">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i> Dashboard / Home
          </a>

          <a href="{{ route('admin.applications.index') ?? '#' }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Accounts
          </a>

          <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
            <i class="bi bi-inbox-fill"></i> Applications
          </a>

          <a href="#" class="nav-link"><i class="bi bi-box-arrow-up"></i> Release</a>
          <a href="#" class="nav-link"><i class="bi bi-airplane-fill"></i> Check ride</a>
          <a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Schedule</a>
          <a href="#" class="nav-link"><i class="bi bi-megaphone-fill"></i> Post Agenda</a>
          <a href="#" class="nav-link"><i class="bi bi-bar-chart-line-fill"></i> Reports</a>
          <a href="#" class="nav-link"><i class="bi bi-gear-fill"></i> Settings</a>
        </nav>

        <div class="mt-auto small text-white-50 px-3 pb-3">
          WCC Licensing System
        </div>
      </div>
    </aside>

    <!-- backdrop for mobile -->
    <div id="pageBackdrop" class="page-backdrop"></div>

    <!-- Main -->
    <div class="main-content d-flex flex-column" style="min-height:100vh;">
      <!-- Topbar -->
      <nav class="topbar navbar navbar-expand-lg">
        <div class="container-fluid px-3">
          <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none me-2">
            <i class="bi bi-list"></i>
          </button>

          <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
              <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2">{{ Auth::user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down"></i>
              </a>

              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                <li>
                  <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <!-- Content area -->
      <main class="p-4">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function () {
      const sidebar = document.getElementById('adminSidebar');
      const toggle = document.getElementById('sidebarToggle');
      const backdrop = document.getElementById('pageBackdrop');

      function openSidebar() {
        sidebar.classList.add('show');
        backdrop.classList.add('show');
      }
      function closeSidebar() {
        sidebar.classList.remove('show');
        backdrop.classList.remove('show');
      }

      if (toggle) toggle.addEventListener('click', openSidebar);
      if (backdrop) backdrop.addEventListener('click', closeSidebar);
    })();
  </script>

  @stack('scripts')
</body>
</html>
