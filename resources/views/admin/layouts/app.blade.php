<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #0d3b66; /* Dark blue */
            color: white;
            flex-shrink: 0;
        }
        .sidebar .nav-link {
            color: white;
            padding: 10px 20px;
        }
        .sidebar .nav-link:hover {
            background-color: #145ea8;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .topbar {
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .sidebar-logo {
            text-align: center;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
                position: fixed;
                height: 100%;
                z-index: 1000;
            }
            .sidebar.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">WCC Licensing</div>
        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="#" class="nav-link">Accounts</a>
            <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}">Applications</a>
            <a href="#" class="nav-link">Release</a>
            <a href="#" class="nav-link">Check Ride</a>
            <a href="#" class="nav-link">Schedule</a>
            <a href="#" class="nav-link">Post Agenda</a>
            <a href="#" class="nav-link">Reports</a>
            <a href="#" class="nav-link">Settings</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-primary d-md-none" id="toggleSidebar">☰</button>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    {{ auth()->user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div class="mt-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
