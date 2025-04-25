<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Event;
use App\Models\Webinar;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // $jobs = Job::where('title', 'like', "%$query%")
        //             ->orWhere('description', 'like', "%$query%")
        //             ->get();

        $events = Event::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->get();

        // $webinars = Webinar::where('title', 'like', "%$query%")
        //                     ->orWhere('description', 'like', "%$query%")
        //                     ->get();

        return view('search.results', compact('events', 'query'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->input('query');

        // $jobs = Job::where('title', 'like', "%$query%")
        //             ->orWhere('description', 'like', "%$query%")
        //             ->get();

        $events = Event::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->get();

        // $webinars = Webinar::where('title', 'like', "%$query%")
        //                     ->orWhere('description', 'like', "%$query%")
        //                     ->get();

        $results = view('search.partials.results', compact('events'))->render();

        return response()->json(['results' => $results]);
    }
}
