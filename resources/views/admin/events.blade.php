@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Events</h1>
        <!-- <a href="{{ route('admin.event.create') }}" class="btn btn-primary">Create Event</a> -->
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ $event->title }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($event->description, 100) }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</td>
                                <td>{{ $event->time }}</td>
                                <td>{{ $event->location }}</td>
                                <td>{{ $event->price }}</td>

                                <td>
                                    <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($event->recruiter->email === 'admin@gmail.com')
                                        Admin
                                    @else
                                        {{ $event->recruiter->name }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#eventDetailsModal{{ $event->id }}">
                                            Details
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#eventApplicantsModal{{ $event->id }}">
                                            View Applicants
                                        </button>
                                        <a href="{{ route('admin.event.report', $event->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> Report
                                        </a>
                                        <a href="{{ route('admin.event.edit', $event->id) }}" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>
                                        @if($event->status === 'open')
                                            <form action="{{ route('admin.event.close', $event->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to close this event?')">
                                                    Close Event
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.event.delete', $event->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No events found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals Container -->
<div class="modal-container">
    @foreach($events as $event)
        <!-- Event Details Modal -->
        <div class="modal fade" id="eventDetailsModal{{ $event->id }}" tabindex="-1" aria-labelledby="eventDetailsModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventDetailsModalLabel{{ $event->id }}">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <h5 class="fw-bold">{{ $event->title }}</h5>
                            <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }} mb-2">
                                {{ strtoupper($event->status) }}
                            </span>
                            <p class="text-muted">{{ $event->description }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><i class="fas fa-calendar"></i> <strong>Date:</strong> {{ $event->date }}</p>
                                <p><i class="fas fa-clock"></i> <strong>Time:</strong> {{ $event->time }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> {{ $event->location }}</p>
                                <p><i class="fas fa-money-bill"></i> <strong>Price:</strong> Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Applicants</h6>
                                        <p class="card-text display-6">{{ $event->applicants->where('pivot.status_pembayaran', 'approved')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Revenue</h6>
                                        <p class="card-text display-6">Rp {{ number_format($event->applicants->where('pivot.status_pembayaran', 'approved')->count() * $event->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Applicants Modal -->
        <div class="modal fade" id="eventApplicantsModal{{ $event->id }}" tabindex="-1" aria-labelledby="eventApplicantsModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventApplicantsModalLabel{{ $event->id }}">Event Applicants</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>{{ $event->title }}</h4>
                        <hr>
                        @if($event->applicants->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->applicants as $applicant)
                                            <tr>
                                                <td>{{ $applicant->name }}</td>
                                                <td>{{ $applicant->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center">No applicants yet.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
.btn-group {
    display: flex;
    gap: 5px;
}
.btn-group form {
    margin: 0;
}
.modal-container {
    position: relative;
    z-index: 1050;
}
</style>

@endsection
