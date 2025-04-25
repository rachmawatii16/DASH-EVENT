@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-6">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>Available Events</h1>
        <div>
            <a href="{{ route('events.history') }}" class="btn btn-primary me-2">History</a>
            <a href="{{ route('applicant.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($events as $event)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <p class="card-text">{{ $event->description }}</p>
                    <p class="card-text">
                        <small class="text-muted">{{ $event->date }} at {{ $event->time }}</small>
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Location:</strong> {{ $event->location }}</small>
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Price:</strong> {{ $event->price }}</small>
                    </p>
                
                    <div class="d-flex justify-content-between align-items-center">
                        @if($event->applicants->contains(auth()->user()))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventDetailsModal{{ $event->id }}">Details</button>
                            @if($event->status === 'closed')
                                <form action="{{ route('applicant.events.certificate', $event->id) }}" method="GET" class="d-inline">
                                    <button type="submit" class="btn btn-secondary">Download Certificate</button>
                                </form>
                            @else
                                <?php $ev = $event->applicants->firstWhere('id', auth()->id()) ?? 0; ?>
                                @if($ev->pivot->status_pembayaran === 'approved')
                                <form action="{{ route('applicant.events.registration-proof', $event->id) }}" method="GET" class="d-inline">
                                    <button type="submit" class="btn btn-primary">Registration Proof</button>
                                </form>
                                @elseif($ev->pivot->status_pembayaran === 'rejected')
                                <button type="submit" class="btn btn-danger" disabled>Rejected</button>
                                @else
                                    <button type="submit" class="btn btn-warning" disabled>Pending</button>
                                @endif
                            @endif
                        @else
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventDetailsModal{{ $event->id }}">Details</button>
                            @if($event->status === 'open')
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#joinEventModal{{ $event->id }}">Join Event</button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>Event Closed</button>
                            @endif
                        @endif
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
                        <p><strong>Date:</strong> {{ $event->date }}</p>
                        <p><strong>Time:</strong> {{ $event->time }}</p>
                        <p><strong>Location:</strong> {{ $event->location }}</p>
                        <p><strong>Price:</strong> {{ $event->price }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </p>
                        
                        @if(!$event->applicants->contains(auth()->user()) && $event->status === 'open')
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#joinEventModal{{ $event->id }}" data-bs-dismiss="modal">Join Event</button>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Join Event Confirmation Modal -->
        <div class="modal fade" id="joinEventModal{{ $event->id }}" tabindex="-1" aria-labelledby="joinEventModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="joinEventModalLabel{{ $event->id }}">Join Event Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="mb-4">{{ $event->title }}</h5>
                        <div class="alert alert-info">
                            <p class="mb-0">By joining this event, I hereby declare that:</p>
                            <ul class="mt-2">
                                <li>I will attend the event on {{ $event->date }} at {{ $event->time }}</li>
                                <li>I will follow all event rules and guidelines</li>
                                <li>I understand that my attendance will be recorded</li>
                            </ul>
                        </div>
                        <form action="{{ route('applicant.events.join', $event->id) }}" method="POST" class="d-inline" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="payment_proof" class="form-label required">Upload Payment Receipt</label>
                            <input type="file" class="form-control" id="payment_proof" name="payment_proof" required accept="image/*">
                            <div class="form-text">Allowed Format: JPG, JPEG, PNG (max. 2MB)</div>

                            <div id="imagePreview" class="mt-3 text-center d-none">
                                <img src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                    <i class="fas fa-times"></i> Delete Image
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        
                            @csrf
                            <button type="submit" class="btn btn-success">Pay & Agree</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection
