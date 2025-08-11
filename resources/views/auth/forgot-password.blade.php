@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<h4 class="mb-3 fw-bold text-center">Forgot Password</h4>

<p class="text-center text-muted mb-4">
  Enter the email associated with your account and we'll send a link to reset your password.
</p>

@if (session('status'))
  <div class="alert alert-success alert-auto-hide d-flex align-items-center" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    <div>{!! session('status') !!}</div>
  </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input id="email" name="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to Login</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const alerts = document.querySelectorAll('.alert-auto-hide');
  alerts.forEach(function(alert){
    // auto-hide after 4s with a smooth fade/slide
    setTimeout(function(){
      alert.style.transition = 'opacity 400ms ease, max-height 400ms ease, margin 400ms ease, padding 400ms ease';
      alert.style.opacity = '0';
      alert.style.maxHeight = '0';
      alert.style.margin = '0';
      alert.style.padding = '0';
      setTimeout(function(){ alert.remove(); }, 420);
    }, 4000);
  });
});
</script>
@endpush
