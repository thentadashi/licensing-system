@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="row">
    <!-- Section 2: Announcements -->
    <div class="col-lg-8 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0" style="color: #262e70;">
                <i class="bi bi-megaphone-fill me-2" style="color: #262e70"></i>
                Announcements
            </h4>
            <small style="color: #262e70">{{ $announcements->count() }} announcement(s)</small>
        </div>

        @forelse($announcements as $announcement)
            <div class="card shadow-sm mb-3 border-start border-1">
                @if($announcement->image)
                    <div class="announcement-image-container">
                        <img src="{{ asset('storage/' . $announcement->image) }}" 
                             class="announcement-image" 
                             alt="Announcement Image">
                    </div>
                @endif
                
                <div class="card-body">
                    @if($announcement->title)
                        <h5 class="card-title text-primary">
                            <i class="bi bi-pin-angle-fill me-1"></i>
                            {{ $announcement->title }}
                        </h5>
                    @else
                        <h5 class="card-title text-primary">
                            <i class="bi bi-pin-angle-fill me-1"></i>
                            Announcement #{{ $announcement->id }}
                        </h5>
                    @endif
                    
                    @if($announcement->content)
                        <div class="card-text mb-3" style="line-height: 1.6;">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>
                    @else
                        <div class="card-text mb-3 text-muted">
                            <em>No content provided</em>
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            Posted {{ $announcement->created_at->diffForHumans() }}
                        </small>
                        @if($announcement->publish_date)
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                Published {{ \Carbon\Carbon::parse($announcement->publish_date)->diffForHumans() }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            {{-- Default announcement when no announcements exist --}}
            <div class="card shadow-sm mb-3 border-0" style="background: linear-gradient(135deg, #ffffff 0%, #f7f7f7 100%);">
                <div class="card-body text-center py-5">
                    <i class="bi bi-megaphone" style="font-size: 4rem; opacity: 1;color: #262e70;"></i>
                    <h5 class="card-title mt-3" style="color: #262e70;">Welcome to the WCC Licensing Portal</h5>
                    <p class="card-text text-muted mb-4">Stay tuned for updates from the administration. All announcements will appear here.</p>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="bi bi-bell-fill" style="font-size: 1.5rem; color: #262e70;"></i>
                            <p class="small mt-2 mb-0">Get Notified</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-calendar-check-fill" style="font-size: 1.5rem; color: #262e70;"></i>
                            <p class="small mt-2 mb-0">Stay Updated</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-people-fill" style="font-size: 1.5rem; color: #262e70;"></i>
                            <p class="small mt-2 mb-0">Connect</p>
                        </div>
                    </div>
                    <small class="text-success mt-3 d-block">Portal is active Manila Ph. - {{ now()->format('T- h:i A - M d, Y') }}</small>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Section 3: License Requirements & Forms -->
    <div class="col-lg-4">
        <!-- License Requirements -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-custom-primary text-white fw-bold">
                License Application Requirements
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"  style="color: #262e70;">PEL Number</li>
                <li class="list-group-item"  style="color: #262e70;">Medical Certificate</li>
                <li class="list-group-item"  style="color: #262e70;">RT License</li>
                <li class="list-group-item"  style="color: #262e70;">English Language Proficiency</li>
                <li class="list-group-item"  style="color: #262e70;">Private Pilot License</li>
                <li class="list-group-item"  style="color: #262e70;">Commercial Pilot License</li>
                <li class="list-group-item"  style="color: #262e70;">Additional Rating</li>
                <li class="list-group-item"  style="color: #262e70;">Ground Instructor License</li>
                <li class="list-group-item"  style="color: #262e70;">Flight Instructor License</li>
            </ul>
        </div>

        <!-- Downloadable Forms -->
        <div class="card shadow-sm">
            <div class="card-header bg-custom-primary text-white fw-bold">
                Downloadable Forms
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none"  style="color: #262e70;">
                        <i class="bi bi-file-earmark-arrow-down"></i> 541 Form
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none"  style="color: #262e70;">
                        <i class="bi bi-file-earmark-arrow-down"></i> Medical Forms
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none"  style="color: #262e70;">
                        <i class="bi bi-file-earmark-arrow-down"></i> Radiotelephony Form
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
/* Facebook-style image container */
.announcement-image-container {
    width: 100%;
    height: 600px; /* Fixed height for consistency */
    overflow: hidden;
    position: relative;
    background-color: #fFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
}

.announcement-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* This makes the image cover the entire container while maintaining aspect ratio */
    object-position: center; /* Centers the image within the container */
    transition: transform 0.3s ease;
}

/* Optional hover effect */
.announcement-image:hover {
    transform: scale(1.02);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .announcement-image-container {
        height: 250px; /* Smaller height on mobile */
    }
}

@media (max-width: 576px) {
    .announcement-image-container {
        height: 200px; /* Even smaller on very small screens */
    }
}
</style>
@endsection
