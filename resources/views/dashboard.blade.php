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
            
            <!-- ✅ FB-style header -->
            <div class="d-flex align-items-center p-3">
                <img src="{{ asset('build/assets/images/logo.png') }}" 
                    alt="logo" 
                    class="rounded-circle me-2"
                    style="height:46px; width:46px; object-fit:cover; border:1px solid #262e70;">

                <div>
                    <span class="fw-bold" style="color:#262e70;">WCC Licensing Department</span><br>
                    @php
                        $createdAt = \Carbon\Carbon::parse($announcement->created_at);
                        $now = \Carbon\Carbon::now();

                        if ($createdAt->isYesterday()) {
                            $formattedDate = 'Yesterday at ' . $createdAt->format('g:i A');
                        } elseif ($createdAt->isSameYear($now)) {
                            if ($createdAt->isSameMonth($now)) {
                                $formattedDate = $createdAt->format('F j \a\t g:i A');
                            } else {
                                $formattedDate = $createdAt->format('F j \a\t g:i A');
                            }
                        } else {
                            $formattedDate = $createdAt->format('F j, Y');
                        }
                    @endphp

                    <small class="text-muted">
                        {{ $formattedDate }}
                    </small>
                </div>
            </div>
            <!-- ✅ end header -->

            @if($announcement->image)
                <div class="announcement-image-container">
                    <img src="{{ asset('storage/' . $announcement->image) }}" 
                         class="announcement-image" 
                         alt="Announcement Image">
                </div>
            @endif
            
            <div class="card-body">
                @if($announcement->title)
                    <h5 class="card-title" style="color:#262e70">
                        <i class="bi bi-pin-angle me-1"></i>
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
    @include('partials.license_requirements')
</div>

<style>
.announcement-image-container {
    width: 100%;
    max-height: 700px; /* ✅ upper limit for large screens */
    min-height: 250px; /* ✅ lower limit to keep visibility */
    overflow: hidden;
    position: relative;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.announcement-image {
    max-width: 100%;
    height: auto;
    max-height: 700px; /* ✅ don’t exceed container */
    object-fit: contain; /* ✅ show full image without cropping */
    object-position: center;
    transition: transform 0.3s ease;
}

/* Hover effect */
.announcement-image:hover {
    transform: scale(1.02);
}

/* 📱 Mobile responsiveness */
@media (max-width: 992px) { /* tablets & small laptops */
    .announcement-image-container {
        max-height: 500px;
        min-height: 220px;
    }
}

@media (max-width: 768px) { /* large phones */
    .announcement-image-container {
        max-height: 350px;
        min-height: 200px;
    }
}

@media (max-width: 576px) { /* small phones */
    .announcement-image-container {
        max-height: 250px;
        min-height: 180px;
    }
}
</style>
@endsection
