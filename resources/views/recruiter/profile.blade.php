@extends('layouts.layout')

@section('title', 'Pengaturan Akun - Dash')

@section('content')
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
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

    .btn-warning {
        border-radius: 20px;
        font-weight: bold;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-center">
        <div class="card p-4" style="width: 600px;">
            <h3 class="section-title mb-4 text-center">Profile Akun Recruiter</h3>
            <div class="text-center mb-4">
                @if($user->profile_photo)
                    <img src="{{ asset('images/profile/' . $user->profile_photo) }}" class="img-fluid mb-3 rounded-circle"
                        style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <p>No photo available</p>
                @endif
            </div>
            <form>
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" class="form-control mb-3" id="name" value="{{ $user->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control mb-3" id="email" value="{{ $user->email }}" readonly>
                </div>
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control mb-3" id="company_name" value="{{ $user->company_name }}"
                        readonly>
                </div>
                <div class="text-center">
                    <button type="button" onclick="window.location='{{ route('recruiter.detailProfile') }}'"
                        class="btn btn-warning mt-3">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection