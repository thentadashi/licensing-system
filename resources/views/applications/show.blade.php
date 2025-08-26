@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Application Info -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <h3 class="card-title mb-3">{{ $application->application_type }}</h3>

            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Application ID:</strong> {{ $application->id }}</p>
                    <p class="mb-1"><strong>Status:</strong> 
                        <span class="badge bg-primary">{{ $application->status }}</span>
                    </p>
                    <p class="mb-0"><strong>Progress Stage:</strong> {{ $application->progress_stage }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>Submitted:</strong> {{ $application->created_at->format('M d, Y h:i A') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ $application->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submitted Files -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-folder2-open me-2 text-primary"></i> Submitted Files
        </div>
        <div class="card-body">
            @if($application->files->count())
                <div class="mt-2 d-flex flex-wrap gap-2">
                    @foreach($application->files as $file)
                        <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-file-earmark-text me-1"></i> {{ $file->requirement_label }}
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No files submitted yet.</p>
            @endif
        </div>
    </div>

    <!-- Extra Fields -->
    @if($application->extraFields->count())
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-info-circle me-2 text-primary"></i> Extra Fields
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($application->extraFields as $ef)
                        <li class="list-group-item">
                            <strong>{{ ucfirst(str_replace('_',' ',$ef->field_name)) }}:</strong> 
                            {{ $ef->field_value }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Revision Section -->
    @if($application->revision_files)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-warning bg-opacity-25 text-dark fw-semibold">
                <i class="bi bi-exclamation-triangle me-2"></i> Revision Required
            </div>
            <div class="card-body">

                <!-- Keep alert inside card -->
                <div class="alert alert-warning">
                    <strong>Please re-upload the following:</strong>
                    <ul class="mb-0">
                        @foreach($application->revision_files as $file)
                            <li>{{ ucfirst(str_replace('_', ' ', $file)) }}</li>
                        @endforeach
                    </ul>
                    @if($application->revision_notes)
                        <p class="mt-2 mb-0"><strong>Notes:</strong> {{ $application->revision_notes }}</p>
                    @endif
                </div>

                <!-- Upload Form -->
                <form action="{{ route('applications.reupload', $application->id) }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @foreach($application->revision_files as $file)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ ucfirst(str_replace('_',' ',$file)) }}</label>
                            
                            <!-- Modern File Input -->
                            <div class="input-group">
                                <input type="file" name="files[{{ $file }}]" 
                                    class="form-control file-input" required>
                                <label class="input-group-text bg-primary text-white">
                                    <i class="bi bi-upload"></i>
                                </label>
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Submit Revisions
                    </button>
                </form>
            </div>
        </div>
    @endif
    <div>
        <a href="{{ route('applications') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back to Applications</a>
    </div>
</div>
@endsection
