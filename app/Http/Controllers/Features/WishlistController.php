<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventResource;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index(): View
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['event', 'eventResource.event'])
            ->latest()
            ->get();

        return view('features.wishlist.index', compact('wishlists'));
    }

    /**
     * Add an event to wishlist.
     */
    public function addEvent(Event $event): RedirectResponse
    {
        // Only allow adding past events to wishlist
        if (!$event->isPast()) {
            return redirect()
                ->back()
                ->with('error', 'You can only add past events to your wishlist.');
        }

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'event_resource_id' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Event added to wishlist.');
    }

    /**
     * Add an event resource to wishlist.
     */
    public function addResource(EventResource $eventResource): RedirectResponse
    {
        // Only allow adding resources from past events
        if (!$eventResource->event->isPast()) {
            return redirect()
                ->back()
                ->with('error', 'You can only add resources from past events to your wishlist.');
        }

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'event_id' => null,
            'event_resource_id' => $eventResource->id,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Resource added to wishlist.');
    }

    /**
     * Remove an item from wishlist.
     */
    public function destroy(Wishlist $wishlist): RedirectResponse
    {
        // Ensure user owns this wishlist item
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $wishlist->delete();

        return redirect()
            ->route('features.wishlist.index')
            ->with('success', 'Item removed from wishlist.');
    }

    /**
     * Toggle wishlist status (add/remove).
     */
    public function toggle(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:event,resource'],
            'id' => ['required', 'integer'],
        ]);

        if ($request->type === 'event') {
            $event = Event::findOrFail($request->id);
            
            if (!$event->isPast()) {
                return redirect()->back()->with('error', 'You can only add past events to your wishlist.');
            }

            $wishlist = Wishlist::where('user_id', auth()->id())
                ->where('event_id', $event->id)
                ->whereNull('event_resource_id')
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                $message = 'Event removed from wishlist.';
            } else {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'event_id' => $event->id,
                    'event_resource_id' => null,
                ]);
                $message = 'Event added to wishlist.';
            }
        } else {
            $resource = EventResource::findOrFail($request->id);
            
            if (!$resource->event->isPast()) {
                return redirect()->back()->with('error', 'You can only add resources from past events to your wishlist.');
            }

            $wishlist = Wishlist::where('user_id', auth()->id())
                ->where('event_resource_id', $resource->id)
                ->whereNull('event_id')
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                $message = 'Resource removed from wishlist.';
            } else {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'event_id' => null,
                    'event_resource_id' => $resource->id,
                ]);
                $message = 'Resource added to wishlist.';
            }
        }

        return redirect()->back()->with('success', $message);
    }
}

