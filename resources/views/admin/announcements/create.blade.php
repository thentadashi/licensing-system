@extends('layouts.app')
@section('title', 'Create Announcement')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Announcement</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title (Optional)</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter announcement title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content (Optional)</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="5" 
                                      placeholder="Enter announcement content">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image (Optional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                        </div>

                        <div class="mb-3">
                            <label for="publish_date" class="form-label">Publish Date (Optional)</label>
                            <input type="datetime-local" class="form-control @error('publish_date') is-invalid @enderror" 
                                   id="publish_date" name="publish_date" value="{{ old('publish_date') }}">
                            @error('publish_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty to publish immediately</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Create Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
