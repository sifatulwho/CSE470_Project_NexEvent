<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Event;
use App\Notifications\NewReview;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'recommended' => 'nullable|boolean',
        ]);

        $data['user_id'] = auth()->id();
        $data['event_id'] = $event->id;

        // Only allow reviews from users who registered for this event
        $registered = \App\Models\EventRegistration::where('event_id', $event->id)->where('user_id', auth()->id())->exists();
        if (! $registered) {
            return redirect()->back()->with('error', 'You must register for the event before leaving a review.');
        }

        $review = Review::updateOrCreate(['event_id' => $event->id, 'user_id' => auth()->id()], $data);

        Notification::send($event->organizer, new NewReview($review));

        return redirect()->back()->with('success', 'Review submitted.');
    }
}
