@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-3">Welcome to Dash Event</h1>
            <p class="lead">Start your learning journey! Join our platform to explore various events and connect with potential employers.</p>
        </div>
    </div>

    <h2 class="text-center mb-4">Available Events</h2>
    
    <div class="row justify-content-center">
        @foreach($events as $event)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title" style="font-size: 1.1rem;">{{ $event->title }}</h5>
                        <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <p class="card-text small">{{ $event->description }}</p>
                    <p class="card-text">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> {{ $event->date }}<br>
                            <i class="fas fa-clock"></i> {{ $event->time }}<br>
                            <i class="fas fa-map-marker-alt"></i> {{ $event->location }}<br>
                            <i class="fas fa-money-bill"></i> Rp {{ number_format($event->price, 0, ',', '.') }}
                        </small>
                    </p>
                </div>
                <div class="card-footer bg-transparent p-3">
                    <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-sm">
                        Login to Join Event
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;
    margin: 0 auto;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.display-4 {
    font-weight: 600;
}

.lead {
    font-size: 1.2rem;
}

.card-title {
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.card-text.small {
    font-size: 0.875rem;
    line-height: 1.4;
    color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 767px) {
    .col-12 {
        padding: 0 15px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .col-md-6 {
        padding: 0 15px;
    }
}

@media (min-width: 992px) {
    .col-lg-4 {
        padding: 0 15px;
    }
}
</style>
@endsection 