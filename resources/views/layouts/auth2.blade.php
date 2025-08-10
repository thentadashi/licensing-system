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
    :root {
      --primary-color: #262e70;
      --page-bg: #f8f9fa;
    }
    h5 {
        color: #262e70; /* dark blue */
    }
    body {
      background: var(--page-bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .auth-card {
      width: 100%;
      max-width: 600px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 2rem;
    }
    .auth-logo {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    .btn-primary:hover {
      background-color: #1d245c;
      border-color: #1d245c;
    }
  </style>

  @stack('styles')
</head>
<body>

  <div class="auth-card">
    <div class="auth-logo">
      <img src="{{ asset('build/assets/images/logo.png') }}" alt="Logo" style="height:50px;">
    </div>
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
