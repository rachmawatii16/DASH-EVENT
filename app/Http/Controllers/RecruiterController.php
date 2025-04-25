<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Event;
use App\Models\Webinar;
use App\Models\RecruiterProfile;
use PDF;

class RecruiterController extends Controller
{
    public function index()
    {
        $recruiterId = Auth::id();
        $user = Auth::user();
        
        // $jobsCount = Job::where('recruiter_id', $recruiterId)->count();
        $eventsCount = Event::where('recruiter_id', $recruiterId)->count();
        // $webinarsCount = Webinar::where('recruiter_id', $recruiterId)->count();

        // $recentJobs = Job::where('recruiter_id', $recruiterId)->latest()->take(3)->get();
        $recentEvents = Event::where('recruiter_id', $recruiterId)->latest()->take(3)->get();
        // $recentWebinars = Webinar::where('recruiter_id', $recruiterId)->latest()->take(3)->get();

        return view('recruiter.dashboard', compact('user', 'eventsCount', 'recentEvents'));
    }

    public function showRegistrationForm()
    {
        return view('recruiter.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'organization' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->organization,
            'password' => bcrypt($request->password),
            'role' => 'recruiter',
        ]);

        // Flash success message to session
        session()->flash('success', 'Welcome! You have successfully registered as a recruiter. You can now create and manage events.');

        return redirect()->route('recruiter.dashboard');
    }

    public function downloadReport($id)
    {
        $event = Event::with(['applicants' => function($query) {
            $query->wherePivot('status_pembayaran', 'approved');
        }])->findOrFail($id);
        
        // Check if the event belongs to the authenticated recruiter
        if ($event->recruiter_id !== Auth::id()) {
            abort(403);
        }

        // Calculate total revenue from approved applicants only
        $totalRevenue = $event->applicants->count() * $event->price;

        $pdf = PDF::loadView('recruiter.events.report', [
            'event' => $event,
            'totalRevenue' => $totalRevenue
        ]);

        return $pdf->download('event-report-' . $event->id . '.pdf');
    }
}
