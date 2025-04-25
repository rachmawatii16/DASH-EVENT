<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Event;
use App\Models\Webinar;
use PDF;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function accounts()
    {
        $users = User::all();
        return view('admin.accounts', compact('users'));
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:applicant,recruiter',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.accounts')->with('success', 'User created successfully.');
    }

    public function jobs()
    {
        $jobs = Job::all();
        return view('admin.jobs', compact('jobs'));
    }

    public function events()
    {
        $events = Event::with('applicants', 'recruiter')->get();
        return view('admin.events', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'price' => 'required|string',
        ]);

        // Get admin user or create one if not exists
        $admin = User::where('email', 'admin@gmail.com')->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]);
        }

        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->location = $request->location;
        $event->price = $request->price;
        $event->status = 'open';
        $event->recruiter_id = $admin->id;
        $event->save();

        return redirect()->route('admin.events')->with('success', 'Event created successfully.');
    }

    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.editEvent', compact('event'));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $event->update($request->all());

        return redirect()->route('admin.events')->with('success', 'Event updated successfully.');
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->applicants()->detach();
        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully.');
    }

    public function webinars()
    {
        $webinars = Webinar::all();
        return view('admin.webinars', compact('webinars'));
    }

    public function editAccount($id)
    {
        $user = User::findOrFail($id);
        return view('admin.editAccount', compact('user'));
    }

    public function updateAccount(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:applicant,recruiter,admin',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.accounts')->with('success', 'Account updated successfully.');
    }

    public function deleteAccount($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.accounts')->with('success', 'Account deleted successfully.');
    }

    public function editJob($id)
    {
        $job = Job::findOrFail($id);
        return view('admin.editJob', compact('job'));
    }

    public function updateJob(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|in:open,closed',
        ]);

        $job->update($request->all());

        return redirect()->route('admin.jobs')->with('success', 'Job updated successfully.');
    }

    public function deleteJob($id)
    {
        Job::findOrFail($id)->delete();
        return redirect()->route('admin.jobs')->with('success', 'Job deleted successfully.');
    }

    public function editWebinar($id)
    {
        $webinar = Webinar::findOrFail($id);
        return view('admin.editWebinar', compact('webinar'));
    }

    public function updateWebinar(Request $request, $id)
    {
        $webinar = Webinar::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $webinar->update($request->all());

        return redirect()->route('admin.webinars')->with('success', 'Webinar updated successfully.');
    }

    public function deleteWebinar($id)
    {
        Webinar::findOrFail($id)->delete();
        return redirect()->route('admin.webinars')->with('success', 'Webinar deleted successfully.');
    }

    public function closeEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'closed';
        $event->save();

        return redirect()->back()->with('success', 'Event has been closed successfully.');
    }

    public function downloadReport($id)
    {
        $event = Event::with(['applicants' => function($query) {
            $query->wherePivot('status_pembayaran', 'approved');
        }])->findOrFail($id);

        // Calculate total revenue from approved applicants only
        $totalRevenue = $event->applicants->count() * $event->price;

        $pdf = PDF::loadView('recruiter.events.report', [
            'event' => $event,
            'totalRevenue' => $totalRevenue
        ]);

        return $pdf->download('event-report-' . $event->id . '.pdf');
    }
}
