<!-- Jobs -->
<!-- <div class="mt-4">
    <h2>Jobs</h2>
    @if($jobs->isEmpty())
        <p>No jobs found matching your query.</p>
    @else
        <div class="row">
            @foreach($jobs as $job)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $job->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($job->description, 100) }}</p>
                        <a href="{{ route('applicant.jobs.index') }}" class="btn btn-primary">View Job</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div> -->

<!-- Events -->
<div class="mt-4">
    <h2>Events</h2>
    @if($events->isEmpty())
        <p>No events found matching your query.</p>
    @else
        <div class="row">
            @foreach($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                        <a href="{{ route('applicant.events.index') }}" class="btn btn-primary">View Event</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Webinars -->
<!-- <div class="mt-4">
    <h2>Webinars</h2>
    @if($webinars->isEmpty())
        <p>No webinars found matching your query.</p>
    @else
        <div class="row">
            @foreach($webinars as $webinar)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $webinar->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($webinar->description, 100) }}</p>
                        <a href="{{ route('webinars.index') }}" class="btn btn-primary">View Webinar</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div> -->
