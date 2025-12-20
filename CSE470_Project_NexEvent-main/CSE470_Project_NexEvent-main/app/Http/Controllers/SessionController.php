<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Session;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function create(Event $event)
    {
        $this->authorize('update', $event);

        $speakers = Speaker::orderBy('name')->get();
        return view('sessions.create', compact('event', 'speakers'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'speakers' => 'nullable|array',
            'speakers.*' => 'nullable|exists:speakers,id',
        ]);

        $session = $event->sessions()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'] ?? null,
        ]);

        if (!empty($validated['speakers'])) {
            $session->speakers()->sync($validated['speakers']);
        }

        return redirect()->route('events.show', $event)->with('success', 'Session added to event.');
    }

    public function edit(Event $event, Session $session)
    {
        $this->authorize('update', $event);
        $speakers = Speaker::orderBy('name')->get();
        return view('sessions.edit', compact('event', 'session', 'speakers'));
    }

    public function update(Request $request, Event $event, Session $session)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'speakers' => 'nullable|array',
            'speakers.*' => 'nullable|exists:speakers,id',
        ]);

        $session->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'] ?? null,
        ]);

        $session->speakers()->sync($validated['speakers'] ?? []);

        return redirect()->route('events.show', $event)->with('success', 'Session updated.');
    }

    public function destroy(Event $event, Session $session)
    {
        $this->authorize('update', $event);
        $session->delete();
        return redirect()->route('events.show', $event)->with('success', 'Session removed.');
    }
}
