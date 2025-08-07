@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Login</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary">Log in</button>

        <div class="mt-3">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            @endif
        </div>
                <!-- Register button -->
        <div class="mt-3">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm ms-2">Register</a>
        </div>
    </form>
</div>
@endsection
