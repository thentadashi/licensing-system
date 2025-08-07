@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Forgot Password</h2>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input name="email" type="email" class="form-control" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary">Send Reset Link</button>
        <a href="{{ route('login') }}" class="btn btn-secondary">Back to Login</a>

    </form>
</div>
@endsection
