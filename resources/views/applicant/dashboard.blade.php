@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="container mt-4">
    <h1 class="mb-4">Welcome, {{ Auth::user()->name }}</h1>

    <!-- Add the search form -->
    <form class="d-flex mb-4" id="live-search-form" action="{{ route('search') }}" method="GET">
        <input class="form-control me-2 search-bar" type="search" name="query" placeholder="Search for jobs, events, webinars..." aria-label="Search">
        <button class="btn btn-outline-primary search-button" type="submit">Search</button>
    </form>

    <!-- Search results container -->
    <div id="search-results" class="mt-4"></div>

    <!-- Cards for Available Events -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Available Events</h5>
                    <p class="card-text">Join events that can help you grow your network and skills.</p>
                    <a href="{{ route('applicant.events.index') }}" class="btn btn-primary">View Events</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('live-search-form');
    const searchInput = searchForm.querySelector('input[name="query"]');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            fetch(`{{ route('live-search') }}?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = data.results;
                });
        } else {
            searchResults.innerHTML = '';
        }
    });
});
</script>
@endsection
