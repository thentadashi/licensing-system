@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Confirm Password</h2>

    <div class="mb-3">
        This is a secure area. Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn btn-warning">Confirm</button>
        <a href="{{ url()->previous() }}" class="btn btn-link mt-3">← Back</a>

    </form>
</div>
@endsection
