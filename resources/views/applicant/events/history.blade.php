@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h1>My Event History</h1>
            <p class="text-muted mb-0">List of events where your registration has been approved</p>
        </div>
        <a href="{{ route('applicant.events.index') }}" class="btn btn-secondary">Back to Events</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @forelse($events as $event)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <div>
                            <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                            <span class="badge bg-success">Approved</span>
                        </div>
                    </div>
                    <p class="card-text">{{ $event->description }}</p>
                    <p class="card-text">
                        <small class="text-muted">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} at {{ $event->time }}</small>
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Location:</strong> {{ $event->location }}</small>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventDetailsModal{{ $event->id }}">Details</button>
                        @if($event->status === 'closed')
                            <a href="{{ route('applicant.events.certificate', $event) }}" class="btn btn-secondary">Download Certificate</a>
                        @endif
                        <a href="{{ route('applicant.events.registration-proof', $event) }}" class="btn btn-success">Bukti Pendaftaran</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Details Modal -->
        <div class="modal fade" id="eventDetailsModal{{ $event->id }}" tabindex="-1" aria-labelledby="eventDetailsModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventDetailsModalLabel{{ $event->id }}">{{ $event->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>Event Details</h5>
                        <p><strong>Description:</strong> {{ $event->description }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                        <p><strong>Time:</strong> {{ $event->time }}</p>
                        <p><strong>Location:</strong> {{ $event->location }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                You haven't joined any events yet.
            </div>
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection 