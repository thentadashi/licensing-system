@extends('layouts.auth2')

@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- ================= SECTION 1: PERSONAL INFORMATION ================= --}}
    <h5 class="mb-3 border-bottom pb-2"><i class="bi bi-person-fill"></i> Personal Information</h5>
    <div class="row g-3">
        {{-- First Name --}}
        <div class="col-md-4">
            <label for="first_name" class="form-label">First Name<span class="text-danger">*</span></label>
            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                   name="first_name" value="{{ old('first_name') }}" required>
            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Middle Name --}}
        <div class="col-md-4">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror"
                   name="middle_name" value="{{ old('middle_name') }}">
            @error('middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Last Name --}}
        <div class="col-md-4">
            <label for="last_name" class="form-label">Last Name<span class="text-danger">*</span></label>
            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                   name="last_name" value="{{ old('last_name') }}" required>
            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row g-3 mt-2">
        {{-- Gender --}}
        <div class="col-md-6">
            <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                <option value="" disabled selected>Select your gender</option>
                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Birthdate --}}
        <div class="col-md-6">
            <label for="birthdate" class="form-label">Birthdate<span class="text-danger">*</span></label>
            <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror"
                   name="birthdate" value="{{ old('birthdate') }}" required>
            @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- Address --}}
    <div class="mt-3">
        <label for="address" class="form-label">Address<span class="text-danger">*</span></label>
        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address"
                  rows="2" required>{{ old('address') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- ================= SECTION 2: ACADEMIC INFORMATION ================= --}}
    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="bi bi-mortarboard-fill"></i> Academic Information</h5>
    <div class="row g-3">
        {{-- Student ID --}}
        <div class="col-md-4">
            <label for="student_id" class="form-label">Student I.D. no.<span class="text-danger">*</span></label>
            <input id="student_id" type="text" class="form-control @error('student_id') is-invalid @enderror"
                   name="student_id" value="{{ old('student_id') }}" required>
            @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Program --}}
        <div class="col-md-8">
            <label for="program" class="form-label">Program<span class="text-danger">*</span></label>
            <input id="program" type="text" class="form-control @error('program') is-invalid @enderror"
                   name="program" value="{{ old('program') }}" required>
            @error('program') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- ================= SECTION 3: ACCOUNT INFORMATION ================= --}}
    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="bi bi-person-badge-fill"></i> Account Information</h5>
    <div class="row g-3">
        {{-- Username --}}
        <div class="col-md-6">
            <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                   name="username" value="{{ old('username') }}" required>
            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div class="col-md-6">
            <label for="email" class="form-label">Email Address<span class="text-danger">*</span></label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- Contact Number --}}
    <div class="mt-3">
        <label for="contact_number" class="form-label">Contact Number (+63)<span class="text-danger">*</span></label>
        <input id="contact_number" type="text" class="form-control @error('contact_number') is-invalid @enderror"
               name="contact_number" value="{{ old('contact_number') }}" required>
        @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Password --}}
    <div class="mt-3">
        <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
        <div class="input-group">
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required>
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror

        {{-- Password Requirements --}}
        <div class="form-text mt-1">
            <strong>Password Requirements:</strong>
            <ul class="mb-0 ps-3">
                <li>At least 8 characters long</li>
                <li>At least one uppercase letter (A - Z)</li>
                <li>At least one lowercase letter (a - z)</li>
                <li>At least one number (0 - 9)</li>
                <li>At least one symbol (e.g., !@#$%^&*)</li>
            </ul>
        </div>
    </div>

    {{-- Confirm Password --}}
    <div class="mt-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-group">
            <input id="password_confirmation" type="password" class="form-control"
                   name="password_confirmation" required>
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    {{-- Submit --}}
    <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Register
        </button>
    </div>

    {{-- Link to Login --}}
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="btn-sm ms-2">Already have an Account</a>
    </div>
</form>

{{-- Show/Hide Password Script --}}
<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (targetInput.type === 'password') {
            targetInput.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            targetInput.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
});
</script>
@endsection
