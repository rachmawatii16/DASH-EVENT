@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Events</h1>
    @if(auth()->user()->role == 'recruiter')
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createEventModal">
            Create Event
        </button>
    @endif
    <a href="{{ route('recruiter.dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    @foreach($events as $event)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <span class="badge {{ $event->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                <p class="card-text">{{ strlen($event->description) > 100 ? substr($event->description, 0, 100) . '...' : $event->description }}</p>
                <p class="card-text">
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> {{ $event->date }} at {{ $event->time }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ $event->location }}<br>
                        <i class="fas fa-money-bill"></i> Rp {{ number_format($event->price, 0, ',', '.') }}
                    </small>
                </p>
                <button type="button" class="btn btn-primary" onclick='showEventDetails(@json($event))'>
                    Details
                </button>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#applicantsModal{{ $event->id }}">
                    View Applicants
                </button>
                <a href="{{ route('recruiter.events.report', $event->id) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download Report
                </a>
                @if($event->status === 'open')
                    <form action="{{ route('recruiter.events.close', $event->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to close this event?')">Close Event</button>
                    </form>
                @endif
                <a href="{{ route('recruiter.events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('recruiter.events.destroy', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                </form>
            </div>
        </div>

        <!-- Applicants Modal -->
        <div class="modal fade bd-example-modal-lg" id="applicantsModal{{ $event->id }}" tabindex="-1" aria-labelledby="applicantsModalLabel{{ $event->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicantsModalLabel{{ $event->id }}">Applicants for {{ $event->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($event->applicants->count() > 0)
                    <div class="list-group">
                        @foreach($event->applicants as $applicant)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $applicant->name }}</h6>
                                    <small>{{ $applicant->email }}</small>
                                </div>

                                <button type="button" onclick='showProofDetails("{{$applicant->name}}", `{{ asset("storage/" . $applicant->pivot->payment_proof) }}` , `{{ asset("storage/" . $applicant->pivot->status) }}`, @json($applicant->pivot) )' class="btn btn-info btn-sm"
                                    data-name="{{ $applicant->name }}"
                                    data-proof="{{ asset('storage/' . $applicant->pivot->payment_proof) }}">
                                    <i class=" fas fa-image"></i> Payment Proof
                                </button>

                                <span class="badge bg-success">{{ $applicant->pivot->status_pembayaran }}</span>

                                @if($applicant->pivot->status_pembayaran === 'rejected')
                                <form action="{{ route('recruiter.events.applicant.delete') }}" method="POST" class="form-inline">
                                    @csrf
                                    <input type="hidden" name="event_id" id="event_id" value="{{ $applicant->pivot->event_id }}">
                                    <input type="hidden" name="applicant_id" value="{{ $applicant->pivot->applicant_id }}">
                                    <button type="submit" class="btn btn-danger btn-inline">
                                        <i class="fas fa-check"></i> Remove
                                    </button>
                                </form>
                                @else
                                @if($event->status === 'closed')
                                <span class="badge bg-success">Completed</span>
                                @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
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

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('recruiter.events.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="title">Event Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Event Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="date">Event Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="time">Event Time</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="location">Event Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" name="price" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h5 id="modalEventTitle" class="fw-bold"></h5>
                    <span id="modalEventStatus" class="badge bg-success mb-2"></span>
                    <p id="modalEventDescription" class="text-muted"></p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><i class="fas fa-calendar"></i> <strong>Date:</strong> <span id="modalEventDate"></span></p>
                        <p><i class="fas fa-clock"></i> <strong>Time:</strong> <span id="modalEventTime"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> <span id="modalEventLocation"></span></p>
                        <p><i class="fas fa-money-bill"></i> <strong>Price:</strong> <span id="modalEventPrice"></span></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Total Applicants</h6>
                                <p class="card-text display-6" id="modalEventTotalApplicants"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Total Revenue</h6>
                                <p class="card-text display-6" id="modalEventTotalRevenue"></p>
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

<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h6 id="aplicantName"></h6>
                <img id="proofImage" src="" alt="Bukti Pembayaran" class="img-fluid mt-2">
                
            </div>
            <div class="modal-footer">
                <div id="formStatus1" class="d-none">
                    <form action="{{ route('recruiter.events.applicant.update') }}" method="POST" class="form-inline">
                        @csrf
                        <input type="hidden" name="event_id" id="event_id1">
                        <input type="hidden" name="applicant_id" id="applicant_id1">
                        <input type="hidden" name="status" id="status" value="approved">
                        <button type="submit" class="btn btn-success btn-inline">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                </div>
                <div id="formStatus2" class="d-none">
                    <form action="{{ route('recruiter.events.applicant.update') }}" method="POST" class="form-inline">
                        @csrf
                        <input type="hidden" id="event_id2" name="event_id">
                        <input type="hidden" name="applicant_id" id="applicant_id2">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger btn-inline">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script>
    function showEventDetails(event) {
        const modal = document.getElementById('eventDetailsModal');
        
        // Set event details in modal
        document.getElementById('modalEventTitle').textContent = event.title;
        document.getElementById('modalEventStatus').textContent = event.status.toUpperCase();
        document.getElementById('modalEventStatus').className = `badge ${event.status === 'open' ? 'bg-success' : 'bg-secondary'} mb-2`;
        document.getElementById('modalEventDescription').textContent = event.description;
        document.getElementById('modalEventDate').textContent = event.date;
        document.getElementById('modalEventTime').textContent = event.time;
        document.getElementById('modalEventLocation').textContent = event.location;
        document.getElementById('modalEventPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(event.price);
        
        // Calculate and display total approved applicants only
        const approvedApplicants = event.applicants ? event.applicants.filter(applicant => applicant.pivot.status_pembayaran === 'approved') : [];
        const totalApprovedApplicants = approvedApplicants.length;
        document.getElementById('modalEventTotalApplicants').textContent = totalApprovedApplicants;
        
        // Calculate and display total revenue from approved applicants only
        const totalRevenue = totalApprovedApplicants * event.price;
        document.getElementById('modalEventTotalRevenue').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalRevenue);
        
        // Show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
    
    function showProofDetails(name, image, status, event) {
        const proofModal = document.getElementById('proofModal');

        // Set event details in modal
        // document.getElementById('aplicantName').textContent = name;
        // document.getElementById('proofImage').textContent = event.status.toUpperCase();
        const applicantName = proofModal.querySelector('#aplicantName');
        const proofImage = proofModal.querySelector('#proofImage')
        const formStatus = proofModal.querySelector('#formStatus1')
        const formStatus2 = proofModal.querySelector('#formStatus2')

        document.getElementById('event_id1').value = event.event_id;
        document.getElementById('applicant_id1').value = event.applicant_id;
        document.getElementById('event_id2').value = event.event_id;
        document.getElementById('applicant_id2').value = event.applicant_id;

        applicantName.textContent = name;
        proofImage.src = image;

        if (event.status_pembayaran != 'pending') {
            formStatus.classList.add('d-none');
            formStatus2.classList.add('d-none');
        } else {
            formStatus.classList.remove('d-none');
            formStatus2.classList.remove('d-none');
        }

        console.log(event.status_pembayaran)

        // Show modal
        const bsModal = new bootstrap.Modal(proofModal);
        bsModal.show();
    }

    // document.addEventListener('DOMContentLoaded', function() {
    //     const proofModal = document.getElementById('proofModal');
    //     proofModal.addEventListener('show.bs.modal', function(event) {
    //         const button = event.relatedTarget;
    //         const name = button.getAttribute('data-name');
    //         const image = button.getAttribute('data-proof');

    //         const applicantName = proofModal.querySelector('#aplicantName');
    //         const proofImage = proofModal.querySelector('#proofImage');

    //         applicantName.textContent = name;
    //         proofImage.src = image;
    //     });
    // });
</script>

@endsection