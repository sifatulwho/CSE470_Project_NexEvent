<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of past events.
     */
    public function index(): View
    {
        $events = Event::where('end_date', '<', now())
            ->with(['organizer', 'resources'])
            ->latest('end_date')
            ->paginate(12);

        return view('features.events.index', compact('events'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event): View
    {
        $event->load(['organizer', 'resources.uploader']);
        $isWishlisted = false;

        if (auth()->check()) {
            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('event_id', $event->id)
                ->whereNull('event_resource_id')
                ->exists();
        }

        return view('features.events.show', compact('event', 'isWishlisted'));
    }
}

