<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventService;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EventApplicant;
use Illuminate\Http\Request;
use App\Models\User;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function applicantIndex()
    {
        $user = Auth::user();
        
        // Get all open events
        $events = Event::where('status', 'open')
            ->whereDoesntHave('applicants', function($query) use ($user) {
                $query->where('applicant_id', $user->id)
                    ->where('status_pembayaran', 'approved');
            })
            ->get();
            
        return view('applicant.events.index', compact('events'));
    }
    
    public function recruiterIndex()
    {
        // Get system admin user
        // harcoded
        $systemAdmin = User::where('email', 'system@admin.com')->first();

        // Only show events created by the current recruiter (exclude system admin events)
        $events = Event::where('recruiter_id', Auth::id())
            ->where('recruiter_id', '!=', $systemAdmin ? $systemAdmin->id : 0)
            ->with('applicants')  // Eager load the applicants relationship
            ->get();

        // echo "<pre>";
        // dd($events);
        // echo "<pre>";

        return view('recruiter.events.index', compact('events'));
    }
    
    public function create()
    {
        return view('recruiter.events.create');
    }
    
    public function store(EventRequest $request)
    {
        try {
            $this->eventService->createEvent($request->validated());
            
            if ($request->ajax()) {
                return response()->json(['success' => 'Event created successfully.']);
            }
            
            return redirect()->route('recruiter.events.index')
                ->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function edit(Event $event)
    {
        try {
            if (!$this->eventService->verifyEventOwnership($event)) {
                return redirect()->route('recruiter.events.index')
                    ->with('error', 'Unauthorized action.');
            }
            
            return view('recruiter.events.edit', compact('event'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(EventRequest $request, Event $event)
    {
        try {
            $this->eventService->updateEvent($event, $request->validated());
            return redirect()->route('recruiter.events.index')
                ->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        try {
            $this->eventService->deleteEvent($event);
            return redirect()->route('recruiter.events.index')
                ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function join(Request $request, Event $event)
    {

        try {

            $ev = $this->eventService->getEventById($event->id);

            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('payment_proofs', $filename, 'public');

                // echo "<pre>";
                // dd($ev);
                // echo "<pre>";

                $ev->applicants()->attach(Auth::id(), [
                    'payment_proof' => $path,
                    'status_pembayaran' => 'pending'
                ]);
            }

            // $this->eventService->joinEvent($event);
            return redirect()->route('applicant.events.index')
                ->with('success', 'You have joined the event.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function close(Event $event)
    {
        try {
            $this->eventService->closeEvent($event);
            return redirect()->route('recruiter.events.index')
                ->with('success', 'Event has been closed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function downloadRegistrationProof(Event $event)
    {
        try {
            $applicant = Auth::user();
            
            if (!$event->applicants()->find($applicant->id)) {
                return redirect()->back()->with('error', 'You have not joined this event.');
            }

            $data = [
                'event' => $event,
                'applicant' => $applicant,
            ];
        
            $pdf = Pdf::loadView('registration-confirmation.template', $data);
            return $pdf->download('registration-proof-' . $applicant->name . '-' . $event->title . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function downloadCertificate(Event $event)
    {
        try {
            $applicant = Auth::user();
            
            if (!$event->applicants()->find($applicant->id)) {
                return redirect()->back()->with('error', 'You have not joined this event.');
            }

            if ($event->status !== 'closed') {
                return redirect()->back()->with('error', 'Certificates are only available after the event is closed.');
            }
        
            $data = [
                'title' => $event->title,
                'applicantName' => $applicant->name,
                'date' => now()->format('F d, Y'),
            ];
        
            $pdf = Pdf::loadView('certificates.template', $data);
            return $pdf->download('certificate-' . $applicant->name . '-' . $event->title . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $applicant = Auth::user();
        $events = $applicant->events()
            ->wherePivot('status_pembayaran', 'approved')
            ->orderBy('date', 'desc')
            ->get();
        return view('applicant.events.history', compact('events'));
    }

    public function applicantUpdateStatus(Request $request)
    {
        $applicant = EventApplicant::where(['event_id' => $request->event_id, 'applicant_id' => $request->applicant_id]);
        
        $applicant->update([
            'status_pembayaran' => $request->status
        ]);

        return redirect()->route('recruiter.events.index')
                ->with('success', 'Applicant has been update successfully.');
    }

    public function applicantDelete(Request $request)
    {
        $applicant = EventApplicant::where(['event_id' => $request->event_id, 'applicant_id' => $request->applicant_id]);

        $applicant->delete();

        return redirect()->route('recruiter.events.index')
                ->with('success', 'Applicant has been remove successfully.');
    }
}
