<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of all events.
     */
    public function index()
    {
        $events = Event::where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Show a specific event details.
     */
    public function show(Event $event)
    {
        $isRegistered = Auth::check() ? 
            EventRegistration::where('event_id', $event->id)
                ->where('attendee_id', Auth::id())
                ->exists() : false;

        $registration = Auth::check() ? 
            EventRegistration::where('event_id', $event->id)
                ->where('attendee_id', Auth::id())
                ->first() : null;

        return view('events.show', compact('event', 'isRegistered', 'registration'));
    }

    /**
     * Display event creation form for organizers.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i|after:start_date',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'image_url' => 'nullable|url',
        ]);

        $event = Event::create([
            ...$validated,
            'organizer_id' => Auth::id(),
            'status' => 'draft',
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display event edit form.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i|after:start_date',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'image_url' => 'nullable|url',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete the specified event.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
