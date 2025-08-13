@extends('admin.layouts.app')
@section('title', 'Manage Announcements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-megaphone-fill text-primary me-2"></i>Manage Announcements</h2>
                <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Create New Announcement
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    @if($announcements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Publish Date</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcement)
                                        <tr>
                                            <td>{{ $announcement->id }}</td>
                                            <td>
                                                {{ $announcement->title ?: 'Announcement #' . $announcement->id }}
                                            </td>
                                            <td>
                                                @if($announcement->image)
                                                    <img src="{{ asset('storage/' . $announcement->image) }}" 
                                                         alt="Image" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($announcement->publish_date)
                                                    {{ $announcement->publish_date->format('M d, Y H:i') }}
                                                @else
                                                    <span class="text-muted">Immediate</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($announcement->publish_date) || $announcement->publish_date <= now())
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-warning">Scheduled</span>
                                                @endif
                                            </td>
                                            <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    {{-- <a href="{{ route('admin.announcements.show', $announcement) }}" 
                                                       class="btn btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a> --}}
                                                    <a href="#" 
                                                       class="btn btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                                                       class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" 
                                                                onclick="return confirm('Are you sure?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{-- {{ $announcements->links() }} --}}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No announcements yet</h5>
                            <p class="text-muted">Create your first announcement to get started.</p>
                            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Create Announcement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
