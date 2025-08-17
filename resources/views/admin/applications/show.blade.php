@extends('admin.layouts.app')

@section('content')

<!-- Revision Request Header -->
<div class="mb-4">
    <h3 class="fw-bold text-dark">Applications | Revision Request</h3>
</div>

@if($application->revision_files)
    <div class="card mb-4 border-1 shadow-sm">
        <div class="card-header text-white small" style="background-color: rgba(38, 45, 112, 0.952); border: 0;">
            <p class="mb-0">List of Files Requested for Revision to <strong>{{ $application->user->name }} | {{$application->user->email}}</strong></p>
        </div>
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                @foreach(json_decode($application->revision_files, true) as $file)
                    <span class="badge" style="background-color: rgba(38, 45, 112, 0.952); color: #ffffff; border: rgba(38, 45, 112, 0.952); border-radius: 0.25rem; padding: 1rem 1rem; font-size: 0.8em;">
                        <i class="fas fa-file-alt me-1"></i> {{ ucfirst(str_replace('_', ' ', $file)) }}
                    </span>
                @endforeach
            </div>
            @if($application->revision_notes)
                <div class="mt-3 p-3 rounded" style="background-color: rgba(38, 45, 112, 0.048); color: rgba(38, 45, 112, 0.952); border-color: rgba(38, 45, 112, 0.952);border: 1px dotted;">
                    <h6 class="mb-2 fw-semibold">Notes from Admin:</h6>
                    <p class="mb-0">{{ $application->revision_notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Revision Request Form -->
<div class="card border-1 shadow-sm">
    <div class="card-header text-white small" style="background-color: rgba(38, 45, 112, 0.952); border: 0;">
        <p class="mb-0">Send a Revision Request <strong>{{ $application->user->name }} | {{ $application->user->email }}</strong></p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.applications.requestRevision', $application->id) }}" method="POST" novalidate>
            @csrf

            <!-- Files to re-upload -->
            <div class="mb-4">
                <label class="form-label fw-semibold mb-2">Select Files for Re-upload:</label>
                @php
                    $existingFiles = $application->files->pluck('requirement_key')->toArray();
                @endphp
                <div class="row g-3">
                    @foreach($existingFiles as $file)
                        <div class="col-12 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="files[]" value="{{ $file }}" id="file-{{ $file }}">
                                <label class="form-check-label" for="file-{{ $file }}">
                                    {{ ucfirst(str_replace('_', ' ', $file)) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Notes textarea -->
            <div class="mb-4">
                <label for="notes" class="form-label fw-semibold mb-2">Notes to the Applicant:</label>
                <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Add specific instructions or notes..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary fw-semibold me-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to Applications
                </a> 
                <button type="submit" class="btn btn-primary fw-semibold">
                    <i class="fas fa-paper-plane me-2"></i>Send Revision Request
                </button>
            </div>
        </form>
    </div>
</div>

@endsection