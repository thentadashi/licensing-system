@extends('admin.layouts.app')

@section('title', 'Edit Announcement')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Announcement</h2>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Announcements
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title (optional)</label>
                    <input type="text" 
                           name="title" 
                           id="title"
                           class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title', $announcement->title) }}"
                           placeholder="Enter announcement title">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" 
                              id="content"
                              rows="6" 
                              class="form-control @error('content') is-invalid @enderror"
                              placeholder="Enter announcement content">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image (optional)</label>
                    @if($announcement->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($announcement->image) }}" 
                                 alt="Current Image" 
                                 style="width: 150px; height: 100px; object-fit: cover;"
                                 class="border rounded">
                            <div class="form-text">Current image</div>
                        </div>
                    @endif
                    <input type="file" 
                           name="image" 
                           id="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text">Supported formats: JPG, JPEG, PNG. Max size: 10MB. Leave empty to keep current image.</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="publish_date" class="form-label">Publish Date</label>
                    <input type="datetime-local" 
                           name="publish_date" 
                           id="publish_date"
                           class="form-control @error('publish_date') is-invalid @enderror"
                           value="{{ old('publish_date', $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->format('Y-m-d\TH:i') : '') }}">
                    @error('publish_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Announcement
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
