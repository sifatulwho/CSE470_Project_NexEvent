<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $event->allComments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Event $event, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return redirect()->back()
            ->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Event $event, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment deleted successfully!');
    }
}
