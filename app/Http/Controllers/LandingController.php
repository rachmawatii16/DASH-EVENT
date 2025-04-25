<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'open')
            ->orderBy('date', 'asc')
            ->take(6)
            ->get();

        return view('landing', compact('events'));
    }
} 