@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Student Dashboard</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 mb-4">
            @csrf

            <div class="mb-3">
                <label class="form-label">Application Type</label>
                <select name="application_type" class="form-select" required>
                    <option value="PPL">Private Pilot License</option>
                    <option value="CPL">Commercial Pilot License</option>
                    <option value="AR">Additional Rating</option>
                    <option value="FI">Flight Instructor</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">541 Form (PDF)</label>
                <input type="file" name="form_541" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">2x2 Picture</label>
                <input type="file" name="picture" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">E-signature (PNG)</label>
                <input type="file" name="signature" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Receipt</label>
                <input type="file" name="receipt" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>

        <h4>Your Applications</h4>
        <ul class="list-group mt-3">
            @forelse($applications as $app)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $app->application_type }}
                    <span class="badge bg-secondary">{{ $app->status }}</span>
                </li>
            @empty
                <li class="list-group-item">No applications yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
