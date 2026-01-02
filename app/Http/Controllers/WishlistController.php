<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['wishlistable' => function($query) {
                // Eager load relationships for events
                if (method_exists($query->getModel(), 'organizer')) {
                    $query->with('organizer');
                }
                // Eager load relationships for resources
                if (method_exists($query->getModel(), 'event')) {
                    $query->with('event');
                }
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add an event to wishlist.
     */
    public function addEvent(Event $event)
    {
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('wishlistable_id', $event->id)
            ->where('wishlistable_type', Event::class)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('info', 'Event is already in your wishlist!');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'wishlistable_id' => $event->id,
            'wishlistable_type' => Event::class,
        ]);

        return redirect()->back()
            ->with('success', 'Event added to wishlist!');
    }

    /**
     * Add a resource to wishlist.
     */
    public function addResource(EventResource $resource)
    {
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('wishlistable_id', $resource->id)
            ->where('wishlistable_type', EventResource::class)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('info', 'Resource is already in your wishlist!');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'wishlistable_id' => $resource->id,
            'wishlistable_type' => EventResource::class,
        ]);

        return redirect()->back()
            ->with('success', 'Resource added to wishlist!');
    }

    /**
     * Remove an item from wishlist.
     */
    public function remove(Wishlist $wishlist)
    {
        // Check if user owns this wishlist item
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $wishlist->delete();

        return redirect()->back()
            ->with('success', 'Item removed from wishlist!');
    }

    /**
     * Check if event is in wishlist.
     */
    public function checkEvent(Event $event)
    {
        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('wishlistable_id', $event->id)
            ->where('wishlistable_type', Event::class)
            ->exists();

        return response()->json(['in_wishlist' => $inWishlist]);
    }
}
