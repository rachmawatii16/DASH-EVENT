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
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-radius: 10px 10px 0 0;
        font-weight: bold;
    }

    .btn-warning,
    .btn-secondary,
    .btn-primary {
        border-radius: 20px;
        font-weight: bold;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .section-title {
        color: #007bff;
        font-weight: bold;
        text-align: center;
    }

    .profile-photo img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .profile-photo p {
        margin-top: 10px;
    }

    .education-info, .experience-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .education-info hr, .experience-info hr {
        margin-top: 0;
    }

    .btn-link {
        color: #dc3545;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Container for Profile Photo and Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-center">Foto Profil</div>
                <div class="card-body text-center profile-photo">
                    @if($user->profile_photo)
                        <img src="{{ asset('images/profile/' . $user->profile_photo) }}" alt="Profile Photo">
                    @else
                        <p>No photo available</p>
                    @endif
                    <button type="button" onclick="window.location='{{ route('applicant.detailProfile') }}'" class="btn btn-warning mt-3">Edit</button>
                </div>
            </div>
        </div>

        <!-- Container for Education and Experience -->
        <div class="col-md-6">
            <!-- Personal Information Section -->
            <div class="card mb-4">
                <div class="card-header">Informasi Pribadi</div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div class="card mb-4">
                <div class="card-header">Pendidikan</div>
                <div class="card-body">
                    @foreach($education as $edu)
                        <div class="education-info">
                            <div>
                                <p class="mb-1"><strong>{{ $edu->degree }}</strong></p>
                                <p class="mb-1">{{ $edu->institution }}</p>
                                <p class="mb-1">{{ $edu->start_date }} - {{ $edu->end_date ?? 'Sekarang' }}</p>
                            </div>
                            <button type="button" onclick="window.location='{{ route('applicant.editEducation', $edu->id) }}'" class="btn btn-secondary btn-sm"><i class="fa fa-pencil-alt"></i> Edit</button>
                        </div>
                        <hr>
                    @endforeach
                    <button type="button" onclick="window.location='{{ route('applicant.educationProfile') }}'" class="btn btn-primary mt-3">Tambah Pendidikan</button>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="card">
                <div class="card-header">Pengalaman</div>
                <div class="card-body">
                    @foreach($user->experience as $exp)
                        <div class="experience-info">
                            <div>
                                <p class="mb-1"><strong>{{ $exp->job_title }}</strong></p>
                                <p class="mb-1">{{ $exp->company }}</p>
                                <p class="mb-1">{{ $exp->start_date }} - {{ $exp->end_date ?? 'Sekarang' }}</p>
                            </div>
                            <button type="button" onclick="window.location='{{ route('applicant.editExperience', $exp->id) }}'" class="btn btn-secondary btn-sm"><i class="fa fa-pencil-alt"></i> Edit</button>
                        </div>
                        <hr>
                    @endforeach
                    <button type="button" onclick="window.location='{{ route('applicant.experienceProfile') }}'" class="btn btn-primary mt-3">Tambah Pengalaman</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
