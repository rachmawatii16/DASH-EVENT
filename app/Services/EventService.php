<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventService
{
    public function getRecruiterEvents()
    {
        return Event::where('recruiter_id', Auth::id())->get();
    }

    public function getAllEvents()
    {
        return Event::all();
    }

    public function createEvent(array $data)
    {
        $data['recruiter_id'] = Auth::id();
        $data['status'] = 'open';
        return Event::create($data);
    }

    public function updateEvent(Event $event, array $data)
    {
        if ($event->recruiter_id !== Auth::id()) {
            throw new \Exception('Unauthorized action.');
        }

        $event->update($data);
        return $event;
    }

    public function getEventById(Int $id) {
        $event = Event::findOrFail($id);

        return $event;
    }

    public function deleteEvent(Event $event)
    {
        if ($event->recruiter_id !== Auth::id()) {
            throw new \Exception('Unauthorized action.');
        }

        return $event->delete();
    }

    public function joinEvent(Event $event)
    {
        if ($event->status === 'closed') {
            throw new \Exception('This event has been closed.');
        }
        $event->applicants()->attach(Auth::id());
        return $event;
    }

    public function verifyEventOwnership(Event $event)
    {
        return $event->recruiter_id === Auth::id();
    }

    public function closeEvent(Event $event)
    {
        if ($event->recruiter_id !== Auth::id()) {
            throw new \Exception('Unauthorized action.');
        }

        $event->update(['status' => 'closed']);
        return $event;
    }
} 