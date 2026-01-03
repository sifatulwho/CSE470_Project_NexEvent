<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventPromotionController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'label' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:50',
        ]);

        $token = Str::random(10);

        $promo = EventPromotion::create([
            'event_id' => $event->id,
            'generated_by' => $request->user()->id,
            'token' => $token,
            'label' => $request->input('label'),
            'platform' => $request->input('platform'),
        ]);

        return response()->json([
            'url' => route('promotions.redirect', $promo->token),
            'token' => $promo->token,
        ]);
    }

    public function redirect($token)
    {
        $promo = EventPromotion::where('token', $token)->firstOrFail();

        $promo->increment('clicks');

        return redirect()->route('events.show', $promo->event);
    }
}
