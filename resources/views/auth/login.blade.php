@extends('layouts.plain')
@section('title', 'Login')
@section('content')
<style>
    .vh-100 {
        height: 80vh;
    }
    .card {
        max-width: 400px;
    }

</style>
<div class="row justify-content-center align-items-center vh-95 ">
    <div class="col-12 text-center mb-4">
        <h1>Dash.</h1>
    </div>
    <div class="col-md-auto">
        <div class="card shadow-lg">
            <div class="card-header text-center">Login to Dash.</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" name="password" id="password" required>
                            <i class="fas fa-eye toggle-password" data-target="password" onclick="togglePassword('password')"></i>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                Don't have an account? <a href="{{ route('register') }}">Register Here</a>
            </div>
        </div>
    </div>
</div>
@endsection
