<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Notifications\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Chat $chat): JsonResponse
    {
        // Ensure the authenticated user is a participant
        $user = auth()->user();
        $isParticipant = $chat->participants()->where('user_id', $user->id)->exists();
        if (! $isParticipant) {
            abort(403);
        }

        $messages = $chat->messages()->with('sender')->get();
        return response()->json($messages);
    }

    public function store(Request $request, Chat $chat): JsonResponse
    {
        // Ensure the authenticated user is a participant
        $user = auth()->user();
        $isParticipant = $chat->participants()->where('user_id', $user->id)->exists();
        if (! $isParticipant) {
            abort(403);
        }

        $data = $request->validate([
            'body' => 'required|string',
        ]);
        $data['sender_id'] = auth()->id();
        $message = $chat->messages()->create($data);

        // Notify participants
        $participants = $chat->participants()->with('user')->get()->pluck('user');
        Notification::send($participants, new NewMessage($message));

        return response()->json($message->load('sender'));
    }
}
