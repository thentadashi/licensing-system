@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Archived Applications</h3>
        <a href="{{ route('admin.applications.trash.index') }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-trash"></i> View Trash
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Application Type</th>
                            <th>Archived At</th>
                            <th>Archived By</th>
                            <th>Note</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archives as $arch)
                            <tr>
                                <td class="fw-semibold">{{ $arch->application->user->name }}</td>
                                <td>{{ $arch->application->user->program }}</td>
                                <td>{{ $arch->application->application_type }}</td>
                                <td>{{ $arch->created_at->format('M d, Y h:i A') }}</td>
                                <td>{{ $arch->archivedBy->name ?? '—' }}</td>
                                <td class="text-truncate" style="max-width:240px;">{{ $arch->note }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.applications.archives.unarchive', $arch) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
                                            onclick="return confirm('Remove from archive?');">
                                            <i class="bi bi-arrow-counterclockwise"></i> Unarchive
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No archived applications.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $archives->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
