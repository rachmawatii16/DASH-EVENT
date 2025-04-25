@extends('layouts.app')

@section('title', 'Pengaturan Akun - Dash')

@section('content')
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        border: 2px solid #ced4da;
    }

    .section-title {
        color: #007bff;
        font-weight: bold;
    }

    .btn-danger,
    .btn-success {
        border-radius: 20px;
        font-weight: bold;
        padding: 8px 24px;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-center">
        <div class="card p-4" style="width: 600px;">
            <h3 class="section-title mb-4 text-center">Profile Akun Applicant</h3>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('applicant.updateProfile') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')
                
                <!-- File Upload -->
                <div class="form-group mb-3">
                    <label for="profile_photo">Choose File</label>
                    <input type="file" name="profile_photo" class="form-control" id="profile_photo">
                </div>

                <!-- Full Name -->
                <div class="form-group mb-3">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}" required>
                </div>

                <!-- Current Password -->
                <div class="form-group mb-3">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" class="form-control" id="current_password">
                </div>

                <!-- New Password -->
                <div class="form-group mb-3">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" class="form-control" id="new_password">
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-3">
                    <label for="new_password_confirmation">Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation">
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" onclick="window.location='{{ route('applicant.profile') }}'"
                        class="btn btn-danger">Cancel</button>
                    <button type="button" onclick="submitForms()" class="btn btn-success">Save</button>
                </div>
            </form>

            <!-- Hidden Password Update Form -->
            <form action="{{ route('applicant.updatePassword') }}" method="POST" id="passwordForm" style="display: none;">
                @csrf
                @method('PUT')
            </form>
        </div>
    </div>
</div>

<script>
function submitForms() {
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;

    // If password fields are filled, submit password form first
    if (currentPassword && newPassword && confirmPassword) {
        // Copy password fields to hidden form
        const currentPasswordInput = document.createElement('input');
        currentPasswordInput.type = 'hidden';
        currentPasswordInput.name = 'current_password';
        currentPasswordInput.value = currentPassword;

        const newPasswordInput = document.createElement('input');
        newPasswordInput.type = 'hidden';
        newPasswordInput.name = 'new_password';
        newPasswordInput.value = newPassword;

        const confirmPasswordInput = document.createElement('input');
        confirmPasswordInput.type = 'hidden';
        confirmPasswordInput.name = 'new_password_confirmation';
        confirmPasswordInput.value = confirmPassword;

        passwordForm.appendChild(currentPasswordInput);
        passwordForm.appendChild(newPasswordInput);
        passwordForm.appendChild(confirmPasswordInput);

        // Submit password form and wait for completion
        passwordForm.submit();
        return; // Stop here, don't submit profile form yet
    }

    // If no password change, just submit profile form
    profileForm.submit();
}
</script>
@endsection
