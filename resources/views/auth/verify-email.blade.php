@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<h4 class="mb-3 fw-bold text-center">Verify Your Email</h4>
<p class="text-center text-muted">
    Before proceeding, please check your email for a verification link.
    If you did not receive the email, click the button below to request another.
</p>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        <a href="{{ route('logout') }}" class="btn btn-outline-secondary"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
    </div>
</form>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection
