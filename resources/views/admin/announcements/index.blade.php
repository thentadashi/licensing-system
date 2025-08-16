@extends('admin.layouts.app')
@section('title', 'Manage Announcements')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
          <i class="bi bi-megaphone-fill me-2" style="color:#262e70;"></i>
          Manage Announcements
        </h2>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
          <i class="bi bi-plus-circle me-1"></i> Create Announcement
        </a>
      </div>

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="card shadow-sm">
        <div class="card-body">
          @if($announcements->count())
            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-light">
                  <tr>
                    <th style="width:70px;">ID</th>
                    <th>Title</th>
                    <th style="width:80px;">Image</th>
                    <th style="width:200px;">Publish Date</th>
                    <th style="width:140px;">Status</th>
                    <th style="width:240px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($announcements as $announcement)
                  <tr>
                    <td class="text-muted">#{{ $announcement->id }}</td>

                    <td>
                      <div class="fw-semibold">
                        {{ $announcement->title ?: 'Announcement #'.$announcement->id }}
                      </div>
                      <div class="text-muted small">
                        {{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 80) }}
                      </div>
                    </td>

                    <td>
                      @if($announcement->image)
                        <img src="{{ asset('storage/'.$announcement->image) }}"
                             class="rounded" style="width:50px;height:50px;object-fit:cover;" alt="image">
                      @else
                        <span class="text-muted small">None</span>
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
                      @php
                        $label = $announcement->status_label; // from accessor below
                      @endphp
                      <span class="badge
                        @if($label === 'Hidden') bg-secondary
                        @elseif($label === 'Scheduled') bg-warning
                        @else bg-success
                        @endif">
                        {{ $label }}
                      </span>
                    </td>

                    <td>
                      <div class="d-flex flex-wrap gap-2">
                        {{-- View --}}
                        <a href="{{ route('admin.announcements.show', $announcement) }}"
                           class="btn btn-sm btn-outline-secondary" title="View">
                          <i class="bi bi-eye"></i>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                           class="btn btn-sm btn-primary" title="Edit">
                          <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Hide / Unhide --}}
                        <form action="{{ route('admin.announcements.toggle', $announcement) }}"
                              method="POST" class="d-inline">
                          @csrf
                          @method('PATCH')
                          <button type="submit"
                                  class="btn btn-sm {{ $announcement->isVisible() ? 'btn-outline-secondary' : 'btn-success' }}"
                                  title="{{ $announcement->isVisible() ? 'Hide' : 'Unhide' }}">
                            <i class="bi {{ $announcement->isVisible() ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                          </button>
                        </form>

                        {{-- Delete --}}
                        <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this announcement? This cannot be undone.');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
          @else
            <div class="text-center py-5">
              <i class="bi bi-megaphone text-muted" style="font-size:3rem;"></i>
              <h5 class="mt-3 text-muted">No announcements yet</h5>
              <p class="text-muted">Create your first announcement to get started.</p>
              <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Create Announcement
              </a>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
