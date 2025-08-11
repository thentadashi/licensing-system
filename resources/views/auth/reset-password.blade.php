@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<h4 class="mb-3 fw-bold text-center">Reset Password</h4>

<p class="text-center text-muted mb-4">
  Please enter your new password below to regain access to your account.
</p>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ request()->route('token') }}">

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input id="email" name="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', request()->email) }}" required autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- New Password -->
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input id="password" name="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>
            <button type="button" class="btn btn-outline-secondary toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="form-control" required>
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Reset Password</button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to Login</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Password toggle
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
</script>
@endpush
