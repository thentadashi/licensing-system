<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WCC Licensing System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f8f9fa;
        }

        main {
            flex: 1;
        }
        .btn-primary {
            background-color: #262e70 !important;
            border-color: #262e70 !important;
        }
    </style>
</head>
<body>

    <main class="d-flex align-items-center justify-content-center text-center">
        <div>
            <!-- Logo -->
            <img src="{{ asset('build/assets/images/logo.png') }}" alt="WCC Logo" style="max-width: 400px;" class="mb-4">
            <h1 class="display-4 fw-bold mb-4">WCC Licensing System</h1>
            <p class="lead mb-4">A secure portal for aviation students to manage license applications.</p>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-primary px-4">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary px-4">Register</a>
            </div>
        </div>
    </main>
    <footer class="text-center py-3 bg-dark text-white small">
        &copy; {{ date('Y') }} WCC Licensing System. All rights reserved.
    </footer>

</body>
</html>
