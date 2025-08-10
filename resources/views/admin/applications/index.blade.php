@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Applications</h2>
    </div>

    @if(session('success'))
        <div id="successAlert" class="alert alert-success alert-sm d-inline-flex align-items-right py-1 px-2 shadow-sm" role="alert" style="font-size: 0.85rem;">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('successAlert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 2000); // Hide after 2 seconds
        </script>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Files</th>
                            <th>Status</th>
                            <th>Update Status</th>
                            @if($userRole === 'super_admin')
                                <th>Admin Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td>{{ $app->user->name }}</td>
                                <td>{{ $app->application_type }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($app->form_541)<a href="{{ asset('storage/' . $app->form_541) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Form 541</a>@endif
                                        @if($app->picture)<a href="{{ asset('storage/' . $app->picture) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Picture</a>@endif
                                        @if($app->signature)<a href="{{ asset('storage/' . $app->signature) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Signature</a>@endif
                                        @if($app->receipt)<a href="{{ asset('storage/' . $app->receipt) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Receipt</a>@endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($app->status === 'Approved') bg-success
                                        @elseif($app->status === 'Rejected') bg-danger
                                        @else bg-secondary @endif">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.applications.update', $app) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm">
                                            <select name="status" class="form-select">
                                                <option value="Pending" {{ $app->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved" {{ $app->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="Rejected" {{ $app->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </form>
                                </td>
                                @if($userRole === 'super_admin')
                                    <td>
                                        <a href="{{ route('admin.students.index') }}" class="btn btn-warning btn-sm">Manage Users</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Bootstrap 5) -->
            <div class="d-flex justify-content-center mt-3">
                {{ $applications->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
