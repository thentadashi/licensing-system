@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
<h4 class="mb-3 fw-bold text-center">Admin Login</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="mb-3">
            <i class="bi bi-envelope-fill"> </i><label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                   class="form-control" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-3">
            <i class="bi bi-key-fill"> </i><label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
@endsection