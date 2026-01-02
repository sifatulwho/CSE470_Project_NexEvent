<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Ticket;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of all events.
     */
    public function index(Request $request)
    {
        $query = Event::where('status', 'published');

        // Search functionality
        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by visibility (handle null as public for backward compatibility)
        if (Auth::check()) {
            $query->where(function($q) {
                $q->where(function($q1) {
                    $q1->where('visibility', 'public')
                       ->orWhereNull('visibility');
                })
                  ->orWhere(function($q2) {
                      $q2->where('visibility', 'private')
                         ->where(function($q3) {
                             $q3->where('organizer_id', Auth::id())
                                ->orWhereHas('registrations', function($q4) {
                                    $q4->where('attendee_id', Auth::id());
                                });
                         });
                  })
                  ->orWhere(function($q2) {
                      $q2->where('visibility', 'invite_only')
                         ->where(function($q3) {
                             $q3->where('organizer_id', Auth::id())
                                ->orWhereHas('registrations', function($q4) {
                                    $q4->where('attendee_id', Auth::id());
                                });
                         });
                  });
            });
        } else {
            $query->where(function($q) {
                $q->where('visibility', 'public')
                  ->orWhereNull('visibility');
            });
        }

        $events = $query->with(['organizer', 'tags'])
            ->orderBy('start_date', 'asc')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', compact('events'));
    }

    /**
     * Show a specific event details.
     */
    public function show(Event $event, Request $request)
    {
        $this->authorize('view', $event);

        // Check invite code for invite_only events
        if ($event->visibility === 'invite_only' && !Auth::check()) {
            if (!$request->has('invite_code') || $request->invite_code !== $event->invite_code) {
                return view('events.invite', compact('event'));
            }
        }
        
        // Set default visibility if null
        if (!$event->visibility) {
            $event->visibility = 'public';
        }

        $isRegistered = Auth::check() ? 
            EventRegistration::where('event_id', $event->id)
                ->where('attendee_id', Auth::id())
                ->exists() : false;

        $registration = Auth::check() ? 
            EventRegistration::where('event_id', $event->id)
                ->where('attendee_id', Auth::id())
                ->first() : null;

        $event->load(['organizer', 'tags', 'sessions.speakers', 'announcements.user', 
                     'resources.user', 'comments.user', 'reviews.user', 'checkins']);

        $inWishlist = Auth::check() ? 
            \App\Models\Wishlist::where('user_id', Auth::id())
                ->where('wishlistable_id', $event->id)
                ->where('wishlistable_type', Event::class)
                ->exists() : false;

        return view('events.show', compact('event', 'isRegistered', 'registration', 'inWishlist'));
    }

    /**
     * Display event creation form for organizers.
     */
    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('events.create', compact('tags'));
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
            'category' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'capacity' => 'nullable|integer|min:1',
            'image_url' => 'nullable|url',
            'visibility' => 'required|in:public,private,invite_only',
            'tags' => 'nullable|string',
            'tags_input' => 'nullable|string',
        ]);

        $inviteCode = null;
        if ($validated['visibility'] === 'invite_only') {
            $inviteCode = Event::generateInviteCode();
        }

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'location' => $validated['location'],
            'category' => $validated['category'] ?? null,
            'max_attendees' => $validated['max_attendees'] ?? $validated['capacity'] ?? null,
            'capacity' => $validated['capacity'] ?? $validated['max_attendees'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'visibility' => $validated['visibility'],
            'invite_code' => $inviteCode,
            'organizer_id' => Auth::id(),
            'status' => 'draft',
        ]);

        // Handle tags
        $tagsInput = $validated['tags'] ?? $validated['tags_input'] ?? null;
        if (!empty($tagsInput)) {
            $tagNames = array_map('trim', explode(',', $tagsInput));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if ($tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
            $event->tags()->sync($tagIds);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display event edit form.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $tags = Tag::orderBy('name')->get();
        $event->load('tags');
        return view('events.edit', compact('event', 'tags'));
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
            'category' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'capacity' => 'nullable|integer|min:1',
            'image_url' => 'nullable|url',
            'visibility' => 'required|in:public,private,invite_only',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'tags' => 'nullable|string',
            'tags_input' => 'nullable|string',
        ]);

        $inviteCode = $event->invite_code;
        if ($validated['visibility'] === 'invite_only' && !$inviteCode) {
            $inviteCode = Event::generateInviteCode();
        } elseif ($validated['visibility'] !== 'invite_only') {
            $inviteCode = null;
        }

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'location' => $validated['location'],
            'category' => $validated['category'] ?? null,
            'max_attendees' => $validated['max_attendees'] ?? $validated['capacity'] ?? null,
            'capacity' => $validated['capacity'] ?? $validated['max_attendees'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'visibility' => $validated['visibility'],
            'invite_code' => $inviteCode,
            'status' => $validated['status'],
        ]);

        // Handle tags
        $tagsInput = $validated['tags'] ?? $validated['tags_input'] ?? null;
        if (!empty($tagsInput)) {
            $tagNames = array_map('trim', explode(',', $tagsInput));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if ($tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
            $event->tags()->sync($tagIds);
        } else {
            $event->tags()->sync([]);
        }

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
