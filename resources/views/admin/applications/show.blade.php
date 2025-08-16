@extends('admin.layouts.app')

@section('content')

@if($application->revision_files)
    <div class="mb-3">
        <h5>Files Requested for Revision:</h5>
        <ul>
            @foreach(json_decode($application->revision_files, true) as $file)
                <li>{{ ucfirst(str_replace('_', ' ', $file)) }}</li>
            @endforeach
        </ul>
        @if($application->revision_notes)
            <p><strong>Notes:</strong> {{ $application->revision_notes }}</p>
        @endif
    </div>
@endif

{{-- Always show the request form for admin --}}
<h4>Request Revision</h4>
<form action="{{ route('admin.applications.requestRevision', $application->id) }}" method="POST" style="display:inline;">
    @csrf
    <div>
        <label>Select Files for Re-upload:</label><br>

        @php
            // Get the list of uploaded files for this application
            $existingFiles = $application->files->pluck('requirement_key')->toArray();
        @endphp

        @foreach($existingFiles as $file)
            <label>
                <input type="checkbox" name="files[]" value="{{ $file }}">
                {{ ucfirst(str_replace('_', ' ', $file)) }}
            </label><br>
        @endforeach
    </div>

    <div class="mt-2">
        <label>Notes:</label>
        <textarea name="notes" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-warning mt-2">Send Revision Request</button>
</form>

@endsection
