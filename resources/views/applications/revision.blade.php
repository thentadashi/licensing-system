@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-semibold">📤 Upload Requested Files</div>
        <div class="card-body">

            @if($application->revision_notes)
                <div class="alert alert-warning">
                    <strong>Notes from Admin:</strong> {{ $application->revision_notes }}
                </div>
            @endif

            <form action="{{ route('applications.submitRevision', $application->id) }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf

                @foreach($application->revision_files ?? [] as $fileField)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ ucfirst(str_replace('_', ' ', $fileField)) }}</label>
                        <div class="input-group">
                            <input type="file" name="files[{{ $fileField }}]" 
                                class="form-control file-input" required>
                            <label class="input-group-text bg-primary text-white">
                                <i class="bi bi-upload"></i>
                            </label>
                        </div>
                    </div>
                @endforeach


                <a href="{{ route('applications') }}" class="btn btn-secondary">Back to Applications</a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Submit Revision
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
