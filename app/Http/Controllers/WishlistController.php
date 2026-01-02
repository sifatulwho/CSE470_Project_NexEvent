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
            ->with(['wishlistable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add an event to wishlist.
     */
    public function addEvent(Event $event)
    {
        $wishlist = Wishlist::firstOrCreate([
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
        $wishlist = Wishlist::firstOrCreate([
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
        $this->authorize('delete', $wishlist);

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
