{{-- filepath: z:\laravel\licensing-system\resources\views\admin\students\index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Manage Students</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete student?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection