@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin - Manage Applications</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
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
                        <a href="{{ asset('storage/' . $app->form_541) }}" target="_blank">Form 541</a> |
                        <a href="{{ asset('storage/' . $app->picture) }}" target="_blank">Picture</a> |
                        <a href="{{ asset('storage/' . $app->signature) }}" target="_blank">Signature</a> |
                        <a href="{{ asset('storage/' . $app->receipt) }}" target="_blank">Receipt</a>
                    </td>
                    <td><span class="badge bg-secondary">{{ $app->status }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.applications.update', $app) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm">
                                <option value="Pending" {{ $app->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ $app->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ $app->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button class="btn btn-primary btn-sm mt-2">Update</button>
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
@endsection
