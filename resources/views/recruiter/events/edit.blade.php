@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Edit Event</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('recruiter.events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="title">Event Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $event->title }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="description">Event Description</label>
                    <textarea class="form-control" id="description" name="description" required>{{ $event->description }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="date">Event Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $event->date }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="time">Event Time</label>
                    <input type="time" class="form-control" id="time" name="time" value="{{ $event->time }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="location">Event Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ $event->location }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price" value="{{ $event->price }}" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Event</button>
                    <a href="{{ route('recruiter.events.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 