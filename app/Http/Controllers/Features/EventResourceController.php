<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventResource;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventResourceController extends Controller
{
    /**
     * Display the resource upload form for an event.
     */
    public function create(Event $event): View
    {
        // Only organizers can upload resources
        if (auth()->id() !== $event->organizer_id && !auth()->user()->hasRole(User::ROLE_ADMIN)) {
            abort(403, 'Only event organizers can upload resources.');
        }

        return view('features.resources.create', compact('event'));
    }

    /**
     * Store a newly uploaded resource.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        // Only organizers can upload resources
        if (auth()->id() !== $event->organizer_id && !auth()->user()->hasRole(User::ROLE_ADMIN)) {
            abort(403, 'Only event organizers can upload resources.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:10240'], // Max 10MB
            'file_type' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $filePath = $file->store('event-resources', 'public');

        EventResource::create([
            'event_id' => $event->id,
            'uploaded_by' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $validated['file_type'] ?? $this->guessFileType($file),
            'file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('features.events.show', $event)
            ->with('success', 'Resource uploaded successfully.');
    }

    /**
     * Display a listing of resources for an event.
     */
    public function index(Event $event): View
    {
        $resources = $event->resources()->latest()->get();

        return view('features.resources.index', compact('event', 'resources'));
    }

    /**
     * Download a resource.
     */
    public function download(EventResource $eventResource)
    {
        if (!Storage::disk('public')->exists($eventResource->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $eventResource->file_path,
            $eventResource->file_name
        );
    }

    /**
     * Delete a resource.
     */
    public function destroy(EventResource $eventResource): RedirectResponse
    {
        $event = $eventResource->event;
        
        // Only the uploader or event organizer can delete
        if (auth()->id() !== $eventResource->uploaded_by && auth()->id() !== $event->organizer_id) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($eventResource->file_path);
        $eventResource->delete();

        return redirect()
            ->route('features.resources.index', $event)
            ->with('success', 'Resource deleted successfully.');
    }

    /**
     * Guess file type based on extension.
     */
    private function guessFileType($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $types = [
            'pdf' => 'document',
            'doc' => 'document',
            'docx' => 'document',
            'ppt' => 'slides',
            'pptx' => 'slides',
            'jpg' => 'media',
            'jpeg' => 'media',
            'png' => 'media',
            'gif' => 'media',
            'mp4' => 'media',
            'mov' => 'media',
            'mp3' => 'media',
            'wav' => 'media',
        ];

        return $types[$extension] ?? 'other';
    }
}

