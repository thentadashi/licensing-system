@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Email Verification</h2>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <p>Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn’t receive the email, we will gladly send you another.</p>

    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary mt-3">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
        @csrf
        <button type="submit" class="btn btn-outline-secondary mt-3">Log Out</button>
        <a href="{{ url()->previous() }}" class="btn btn-link mt-3">← Back</a>

    </form>
</div>
@endsection
