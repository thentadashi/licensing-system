{{-- filepath: z:\laravel\licensing-system\resources\views\admin\students\edit.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Edit Student</h2>
    <form method="POST" action="{{ route('admin.students.update', $student) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $student->name) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control" required>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection