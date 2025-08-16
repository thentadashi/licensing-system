@extends('admin.layouts.app')
@section('title', 'View Announcement')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-3">
                @if($announcement->image)
                    <img src="{{ asset('storage/' . $announcement->image) }}" 
                         class="card-img-top" 
                         alt="Announcement Image" 
                         style="max-height: 350px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <h3 class="fw-bold mb-2">{{ $announcement->title ?? 'Untitled Announcement' }}</h3>
                    
                    <div class="d-flex align-items-center text-muted small mb-3">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $announcement->publish_date 
                            ? $announcement->publish_date->format('F d, Y h:i A') 
                            : 'Published Immediately' }}
                    </div>

                    <p class="mb-4" style="white-space: pre-line;">
                        {{ $announcement->content ?? 'No content provided.' }}
                    </p>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
