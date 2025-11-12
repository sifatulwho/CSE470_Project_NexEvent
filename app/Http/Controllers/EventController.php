<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreAnnouncementRequest;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventAnnouncement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:organizer,admin'])->only(['create', 'store', 'edit', 'update', 'destroy', 'storeAnnouncement', 'deleteAnnouncement']);
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Event::query()->visibleTo($user)->withCount('announcements');

        $search = $request->string('q')->trim();
        $category = $request->string('category')->trim();
        $visibility = $request->string('visibility')->trim();
        $tags = $request->string('tags')->trim();

        if ($search->isNotEmpty()) {
            $searchTerm = $search->value();
            $query->where(function ($builder) use ($searchTerm): void {
                $builder->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('venue', 'like', "%{$searchTerm}%");
            });
        }

        if ($category->isNotEmpty()) {
            $query->where('category', $category->value());
        }

        if ($visibility->isNotEmpty()) {
            $query->where('visibility', $visibility->value());
        }

        $tagString = $tags->value();
        if ($tags->isNotEmpty()) {
            $tagList = Event::normalizeTags($tagString);
            foreach ($tagList as $tag) {
                $query->whereJsonContains('tags', $tag);
            }
        }

        $events = $query->orderBy('event_date')->paginate(9)->withQueryString();

        return view('create event.events.index', [
            'events' => $events,
            'categories' => Event::CATEGORY_OPTIONS,
            'visibilities' => Event::VISIBILITY_OPTIONS,
            'filters' => [
                'q' => $search->value(),
                'category' => $category->value(),
                'visibility' => $visibility->value(),
                'tags' => $tagString,
            ],
        ]);
    }

    public function search(Request $request): View
    {
        return $this->index($request);
    }

    public function create(): View
    {
        return view('create event.events.create', [
            'categories' => Event::CATEGORY_OPTIONS,
            'visibilities' => Event::VISIBILITY_OPTIONS,
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = Event::create([
            ...$request->validatedForStorage(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('events.show', $event)->with('status', 'Event created successfully.');
    }

    public function show(Request $request, Event $event): View|RedirectResponse
    {
        if (!$event->isVisibleTo($request->user())) {
            return redirect()->route('events.index')->with('error', 'You do not have access to that event.');
        }

        $event->load([
            'organizer',
            'announcements' => fn ($query) => $query->latest('published_at')->latest(),
        ]);

        return view('create event.events.show', [
            'event' => $event,
            'categories' => Event::CATEGORY_OPTIONS,
            'visibilities' => Event::VISIBILITY_OPTIONS,
        ]);
    }

    public function edit(Event $event): View|RedirectResponse
    {
        $user = request()->user();
        if (!$user || (!$user->hasRole(['admin']) && !$event->organizer?->is($user))) {
            return redirect()->route('events.show', $event)->with('error', 'You are not authorized to edit that event.');
        }

        return view('create event.events.edit', [
            'event' => $event,
            'categories' => Event::CATEGORY_OPTIONS,
            'visibilities' => Event::VISIBILITY_OPTIONS,
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validatedForUpdate());

        return redirect()->route('events.show', $event)->with('status', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $user = request()->user();
        if (!$user || (!$user->hasRole(['admin']) && !$event->organizer?->is($user))) {
            return redirect()->route('events.show', $event)->with('error', 'You are not authorized to delete that event.');
        }

        $event->delete();

        return redirect()->route('events.index')->with('status', 'Event deleted successfully.');
    }

    public function storeAnnouncement(StoreAnnouncementRequest $request, Event $event): RedirectResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $event->announcements()->create($validated);

        return redirect()->route('events.show', $event)->with('status', 'Announcement posted.');
    }

    public function deleteAnnouncement(Event $event, EventAnnouncement $announcement): RedirectResponse
    {
        $user = request()->user();
        if (
            !$user
            || (
                !$user->hasRole(['admin'])
                && !$event->organizer?->is($user)
            )
        ) {
            return redirect()->route('events.show', $event)->with('error', 'You are not authorized to remove that announcement.');
        }

        if (!$announcement->event->is($event)) {
            return redirect()->route('events.show', $event)->with('error', 'Announcement mismatch.');
        }

        $announcement->delete();

        return redirect()->route('events.show', $event)->with('status', 'Announcement deleted.');
    }
}

