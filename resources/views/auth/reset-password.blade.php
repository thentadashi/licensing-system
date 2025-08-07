@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input name="email" type="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input name="password_confirmation" type="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Reset Password</button>
        <a href="{{ route('login') }}" class="btn btn-link mt-3">← Back to Login</a>

    </form>
</div>
@endsection
