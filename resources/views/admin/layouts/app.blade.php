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
    :root {
      --sidebar-bg: #262e70;
      --primary-color: #262e70;
      --page-bg: #f8f9fa;
      --topbar-bg: #ffffff;
    }
    .custom-thead {
      background-color: rgba(38, 45, 112, 0.952) !important;
    }
    body {
      background-color: var(--page-bg);
      min-height: 100vh;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }
    .tosh_small{
      font-size: 0.75em; /* Example: 75% of parent font size */
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background-color: var(--sidebar-bg);
      color: #fff;
      flex-shrink: 0;
      min-height: 100vh;
      padding-top: 1rem;
    }
    .sidebar .nav-link {
      color: rgba(255,255,255,0.95);
      padding: 10px 30px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: .6rem;
    }
    .sidebar .nav-link i {
      font-size: 1.25rem;
      width: 20px;
      text-align: center;
    }
    .sidebar .nav-link:hover {
      background-color: rgba(255,255,255,0.06);
      color: #fff;
      text-decoration: none;
    }
    .sidebar .nav-link.active {
      background-color: rgba(255,255,255,0.10);
      font-weight: 600;
    }

    /* Topbar styled like student side */
    .app-topbar {
      background: var(--topbar-bg);
      border-bottom: 1px solid #e9ecef;
      padding: .5rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .app-topbar .logo-wrap img {
      height: 36px;
    }
    .app-topbar .user-btn {
      background: transparent;
      border: 0;
      color: #222;
      text-decoration: none;
      display: inline-flex;
      gap: .5rem;
      align-items: center;
    }

    /* Primary button override */
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    .btn-primary:hover {
      background-color: #1d245c;
      border-color: #1d245c;
    }

    /* Responsive sidebar for mobile */
    @media (max-width: 991px) {
      .sidebar { position: fixed; left: -260px; top: 0; transition: left .22s ease; z-index: 1045; }
      .sidebar.show { left: 0; }
      .page-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        z-index: 1040;
        display: none;
      }
      .page-backdrop.show { display: block; }
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="d-flex flex-column" style="min-height: 100vh;">

    <!-- Topbar -->
    <nav class="app-topbar">
      <div class="d-flex align-items-center">
        <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none me-2">
          <i class="bi bi-list"></i>
        </button>
        <div class="logo-wrap">
          <img src="{{ asset('build/assets/images/logo.png') }}" alt="logo">
        </div>
      </div>

      <div class="d-flex align-items-center ms-auto">

        {{-- Notifications --}}
        <div class="dropdown me-3">
          <a class="nav-link position-relative" href="#" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell fs-5"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->unreadNotifications->count() }}
              </span>
            @endif
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header fw-bold">Notifications</li>
            @forelse(auth()->user()->unreadNotifications as $notification)
              <li>
                <a href="#" class="dropdown-item small">
                  {{ $notification->data['message'] ?? 'New notification' }}
                  <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                </a>
              </li>
            @empty
              <li><span class="dropdown-item text-muted">No new notifications</span></li>
            @endforelse
            <li><hr class="dropdown-divider"></li>
            <li>
              <a href="{{ route('notifications.index') }}" class="dropdown-item text-center">View all</a>
            </li>
          </ul>
        </div>

        {{-- User dropdown --}}
        <div class="dropdown">
          <a class="user-btn dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span>{{ Auth::user()->name ?? 'Admin' }}</span>
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
    </nav>


    <div class="d-flex flex-grow-1">
        <!-- Sidebar -->
    <aside id="adminSidebar" class="sidebar">
      <div class="sidebar-inner d-flex flex-column h-100">

        <!-- Legend at the top -->
        <div class="px-3 py-2 small fw-bold text-uppercase text-white-50">
          Administrator Controls
        </div>

        <nav class="nav flex-column mb-3">

          <!-- Legend -->
          <div class="px-3 small text-white-50">Main</div>
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i> Dashboard
          </a>

          <!-- Legend -->
          <div class="px-3 small text-white-50">User Management</div>
          <a href="{{ route('students.index') ?? '#' }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Accounts
          </a>

          <div class="px-3 small text-white-50">Applications</div>

          <!-- Applications with Dropdown -->
          <a class="nav-link d-flex justify-content-between align-items-center" 
            data-bs-toggle="collapse" 
            href="#applicationsMenu" 
            role="button" 
            aria-expanded="false" 
            aria-controls="applicationsMenu">
            <span><i class="bi bi-inbox-fill me-2"></i> Applications</span>
            <i class="bi bi-chevron-down small"></i>
          </a>

          <div class="collapse ps-4 {{ request()->routeIs('admin.applications.*') ? 'show' : '' }}" id="applicationsMenu">
            <a class="nav-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}" style="font-size: 13px" 
              href="{{ route('admin.applications.index') }}">
              <i class="bi bi-folder me-2"></i> All Applications
            </a>
            <a class="nav-link {{ request()->routeIs('admin.applications.archives.*') ? 'active' : '' }}" style="font-size: 13px" 
              href="{{ route('admin.applications.archives.index') }}">
              <i class="bi bi-archive me-2"></i> Archives
            </a>
            <a class="nav-link {{ request()->routeIs('admin.applications.trash.*') ? 'active' : '' }}" style="font-size: 13px" 
              href="{{ route('admin.applications.trash.index') }}">
              <i class="bi bi-trash me-2"></i> Trash
            </a>
          </div>

          <!-- Legend -->
          <div class="px-3 small text-white-50">Operations</div>
          <a href="#" class="nav-link"><i class="bi bi-box-arrow-up"></i> Release</a>
          <a href="#" class="nav-link"><i class="bi bi-airplane-fill"></i> Check ride</a>
          <a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Schedule</a>

          <!-- Legend -->
          <div class="px-3 small text-white-50">Communication</div>
          <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i> Post Agenda
          </a>

          <!-- Legend -->
          <div class="px-3  small text-white-50">Reports & Settings</div>
          <a href="#" class="nav-link"><i class="bi bi-bar-chart-line-fill"></i> Reports</a>
          <a href="#" class="nav-link"><i class="bi bi-gear-fill"></i> Settings</a>

        </nav>

        <div class="mt-auto small text-white-50 px-3 pb-3">
          WCC Licensing System
        </div>
      </div>
    </aside>


      <!-- Backdrop for mobile -->
      <div id="pageBackdrop" class="page-backdrop"></div>

      <!-- Main Content -->
      <main class="flex-grow-1 p-4">
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
   @yield('scripts')
</body>
</html>
