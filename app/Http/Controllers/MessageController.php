<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display messages for an event (group chat).
     */
    public function index(Event $event)
    {
        $messages = $event->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $event->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.index', compact('event', 'messages'));
    }

    /**
     * Store a new group message.
     */
    public function storeGroup(Request $request, Event $event)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $event->messages()->create([
            'sender_id' => Auth::id(),
            'receiver_id' => null,
            'message' => $validated['message'],
        ]);

        return redirect()->back()
            ->with('success', 'Message sent!');
    }

    /**
     * Display individual messages (conversation list).
     */
    public function conversations()
    {
        $conversations = Message::where(function($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->whereNull('event_id')
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->filter(function($message) {
                // Filter out messages where sender or receiver is null
                return $message->sender && $message->receiver;
            })
            ->groupBy(function($message) {
                return $message->sender_id === Auth::id() 
                    ? $message->receiver_id 
                    : $message->sender_id;
            });

        return view('messages.conversations', compact('conversations'));
    }

    /**
     * Display conversation with a specific user.
     */
    public function conversation(User $user)
    {
        $messages = Message::where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($user) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
                });
            })
            ->whereNull('event_id')
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.conversation', compact('user', 'messages'));
    }

    /**
     * Store a new individual message.
     */
    public function storeIndividual(Request $request, User $user)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'event_id' => null,
            'message' => $validated['message'],
        ]);

        return redirect()->back()
            ->with('success', 'Message sent!');
    }
}
