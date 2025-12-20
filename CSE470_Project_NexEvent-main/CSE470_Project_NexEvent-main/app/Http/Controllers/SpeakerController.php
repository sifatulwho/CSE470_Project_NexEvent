<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::orderBy('name')->paginate(20);
        return view('speakers.index', compact('speakers'));
    }

    public function create()
    {
        return view('speakers.create');
    }

    public function store(Request $request)
    {
        // Route is already protected by organizer middleware

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        Speaker::create($validated);

        return redirect()->route('speakers.index')->with('success', 'Speaker created.');
    }

    public function edit(Speaker $speaker)
    {
        return view('speakers.edit', compact('speaker'));
    }

    public function update(Request $request, Speaker $speaker)
    {
        // Route is protected by organizer middleware

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        $speaker->update($validated);

        return redirect()->route('speakers.index')->with('success', 'Speaker updated.');
    }

    public function destroy(Speaker $speaker)
    {
        // Route is protected by organizer middleware
        $speaker->delete();
        return redirect()->route('speakers.index')->with('success', 'Speaker removed.');
    }
}
