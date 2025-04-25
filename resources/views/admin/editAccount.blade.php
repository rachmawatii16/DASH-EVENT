@extends('layouts.app')

@section('title', 'Edit Account')

@section('content')
<div class="container">
    <h1 class="my-4">Edit Account</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.account.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="applicant" {{ $user->role == 'applicant' ? 'selected' : '' }}>Applicant</option>
                <option value="recruiter" {{ $user->role == 'recruiter' ? 'selected' : '' }}>Recruiter</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Account</button>
    </form>
</div>
@endsection
