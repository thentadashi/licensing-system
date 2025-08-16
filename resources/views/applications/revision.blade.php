@extends('layouts.app')

@section('content')
<h4>Upload Requested Files</h4>

@if($application->revision_notes)
    <div class="alert alert-warning">
        <strong>Notes from Admin:</strong> {{ $application->revision_notes }}
    </div>
@endif

<form action="{{ route('applications.submitRevision', $application->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    @foreach(json_decode($application->revision_files, true) as $fileField)
        <div class="mb-3">
            <label>{{ ucfirst(str_replace('_', ' ', $fileField)) }}</label>
            <input type="file" name="{{ $fileField }}" class="form-control" required>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary">Submit Revision</button>
</form>
@endsection
