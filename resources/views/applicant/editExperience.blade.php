@extends('layouts.app')

@section('title', 'Edit Pengalaman Kerja - Dash')

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

    .btn-danger,
    .btn-success {
        border-radius: 20px;
        font-weight: bold;
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
        <form action="{{ route('applicant.updateExperience', $experience->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card p-4" style="width: 600px;">
                <h3 class="section-title mb-4 text-center">Edit Pengalaman</h3>
                <div class="form-group">
                    <label for="job_title">Peran</label>
                    <input type="text" name="job_title" class="form-control mb-3" id="job_title"
                        value="{{ $experience->job_title }}" required>
                </div>
                <div class="form-group">
                    <label for="company">Perusahaan</label>
                    <input type="text" name="company" class="form-control mb-3" id="company"
                        value="{{ $experience->company }}" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control mb-3" id="start_date"
                        value="{{ $experience->start_date }}" required>
                </div>
                <div class="form-group">
                    <label for="end_date">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control mb-3" id="end_date"
                        value="{{ $experience->end_date }}">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" onclick="window.location='{{ route('applicant.profile') }}'"
                        class="btn btn-danger" style="margin-top: 10px;">Batal</button>
                    <button type="submit" class="btn btn-success" style="margin-top: 10px;">Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <form action="{{ route('applicant.deleteExperience', $experience->id) }}" method="POST"
            style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link text-danger">Hapus Pengalaman</button>
        </form>
    </div>
</div>
@endsection