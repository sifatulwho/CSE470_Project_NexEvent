<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements for an event.
     */
    public function index(Event $event)
    {
        $announcements = $event->announcements()
            ->with('user')
            ->orderBy('is_important', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('announcements.index', compact('event', 'announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('announcements.create', compact('event'));
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
        ]);

        $event->announcements()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Event $event, Announcement $announcement)
    {
        $this->authorize('update', $event);
        return view('announcements.edit', compact('event', 'announcement'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Event $event, Announcement $announcement)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
        ]);

        $announcement->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Event $event, Announcement $announcement)
    {
        $this->authorize('update', $event);

        $announcement->delete();

        return redirect()->route('events.show', $event)
            ->with('success', 'Announcement deleted successfully!');
    }
}
