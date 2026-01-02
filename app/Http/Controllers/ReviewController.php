<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request, Event $event)
    {
        // Check if user is registered and attended
        $registration = $event->registrations()
            ->where('attendee_id', Auth::id())
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->with('error', 'You must be registered for this event to leave a review.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $event->reviews()->updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]
        );

        return redirect()->back()
            ->with('success', 'Review submitted successfully!');
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Event $event, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->back()
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Event $event, Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return redirect()->back()
            ->with('success', 'Review deleted successfully!');
    }
}
