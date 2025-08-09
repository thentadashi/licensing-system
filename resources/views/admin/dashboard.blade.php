@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="text-center mb-4">
        <h1 class="fw-bold">Dashboard</h1>
        <p class="text-muted">Overview & quick stats</p>
    </div>

    <div class="row g-3">
        <!-- Students -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:64px; height:64px; font-size:1.25rem;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-0">Students</h5>
                        <p class="mb-1 text-muted small">Total registered students</p>
                        <h3 class="mb-0">{{ number_format($studentsCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width:64px; height:64px; font-size:1.25rem;">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-0">Pending Applications</h5>
                        <p class="mb-1 text-muted small">Applications awaiting review</p>
                        <h3 class="mb-0">{{ number_format($pendingCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder cards (you can expand later) -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="mb-0">Quick Actions</h5>
                    <p class="small text-muted mb-2">Shortcuts</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-primary btn-sm">View Applications</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Student List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
