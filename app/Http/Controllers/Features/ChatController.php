<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();
        $chats = Chat::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('participants.user')->get();

        return response()->json($chats);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => 'required|in:group,direct',
            'name' => 'nullable|string|max:255',
            'event_id' => 'nullable|integer|exists:events,id',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'integer|exists:users,id',
        ]);

        $data['created_by'] = auth()->id();
        $chat = Chat::create($data);

        $ids = $data['participant_ids'] ?? [];
        $ids[] = auth()->id();
        foreach (array_unique($ids) as $uid) {
            ChatParticipant::firstOrCreate(['chat_id' => $chat->id, 'user_id' => $uid]);
        }

        return response()->json($chat->load('participants.user'));
    }
}
