@extends('layouts.plain')
@section('title', 'Register')

@section('content')
<style>
    .vh-100 {
        height: 80vh;
    }
    .card {
        max-width: 400px;
    }
</style>
<div class="row justify-content-center align-items-center vh-100">
    <div class="col-md-auto">
        <div class="col-12 text-center mb-4">
            <h1>Dash.</h1>
        </div>
        <div class="card shadow-lg">
            <div class="card-header text-center">Register to Dash.</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                            <i class="fas fa-eye toggle-password" data-target="password" onclick="togglePassword('password')"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                            <i class="fas fa-eye toggle-password" data-target="password_confirmation" onclick="togglePassword('password_confirmation')"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                Already have an account? <a href="{{ route('login') }}">Login Here</a>
            </div>
        </div>
    </div>
</div>
@endsection
