<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventResourceController extends Controller
{
    /**
     * Display a listing of resources for an event.
     */
    public function index(Event $event)
    {
        $resources = $event->resources()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resources.index', compact('event', 'resources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('resources.create', compact('event'));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $filePath = $file->store('event-resources', 'public');

        $event->resources()->create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'original_filename' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('events.resources.index', $event)
            ->with('success', 'Resource uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, EventResource $resource)
    {
        return view('resources.show', compact('event', 'resource'));
    }

    /**
     * Download the resource file.
     */
    public function download(Event $event, EventResource $resource)
    {
        return Storage::disk('public')->download($resource->file_path, $resource->original_filename ?? 'resource');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Event $event, EventResource $resource)
    {
        $this->authorize('update', $event);

        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();

        return redirect()->route('events.resources.index', $event)
            ->with('success', 'Resource deleted successfully!');
    }
}
