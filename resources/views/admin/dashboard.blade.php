@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h1 class="my-4">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Accounts</h5>
                    <a href="{{ route('admin.accounts') }}" class="btn btn-primary">Manage Accounts</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Events</h5>
                    <a href="{{ route('admin.events') }}" class="btn btn-primary">Manage Events</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
