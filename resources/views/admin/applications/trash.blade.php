@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Trash</h3>
        <a href="{{ route('admin.applications.archives.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-archive"></i> View Archives
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
                            <th>Trashed At</th>
                            <th>Prev. Note</th>
                            <th>Prev. Status</th>
                            <th>Prev. Progress</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trashes as $t)
                            <tr>
                                <td class="fw-semibold">{{ $t->application->user->name }}</td>
                                <td>{{ $t->application->user->program }}</td>
                                <td>{{ $t->application->application_type }}</td>
                                <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
                                <td class="text-truncate" style="max-width:240px;">{{ $t->reason }}</td>
                                <td><span class="badge bg-secondary">{{ $t->previous_status ?? '—' }}</span></td>
                                <td>{{ $t->previous_progress_stage ?? '—' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.applications.trash.restore', $t) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.applications.trash.destroy', $t) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Permanently delete this application and all files? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-circle"></i> Delete Forever
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">Trash is empty.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $trashes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
