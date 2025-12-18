<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Event;
use App\Notifications\NewComment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $data['user_id'] = auth()->id();
        $data['event_id'] = $event->id;
        $comment = Comment::create($data);

        // Notify organizer
        Notification::send($event->organizer, new NewComment($comment));

        return redirect()->back()->with('success', 'Comment posted.');
    }
}
