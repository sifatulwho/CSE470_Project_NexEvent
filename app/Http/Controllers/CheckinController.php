<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCheckin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    /**
     * Display the check-in list for an event.
     */
    public function show(Event $event): View
    {
        // Authorize that the user is the organizer
        $this->authorize('manageCheckin', $event);

        $registrations = $event->registrations()
            ->with(['attendee', 'event'])
            ->get();

        $checkedInIds = $event->checkins()->pluck('attendee_id')->toArray();

        $attendeesList = $registrations->map(function ($registration) use ($checkedInIds) {
            return [
                'registration' => $registration,
                'is_checked_in' => in_array($registration->attendee_id, $checkedInIds),
                'attendee' => $registration->attendee,
            ];
        });

        $checkedInCount = count($checkedInIds);
        $totalRegistered = $registrations->count();

        return view('checkin.list', [
            'event' => $event,
            'attendeesList' => $attendeesList,
            'checkedInCount' => $checkedInCount,
            'totalRegistered' => $totalRegistered,
            'checkInRate' => $totalRegistered > 0 ? round(($checkedInCount / $totalRegistered) * 100, 2) : 0,
        ]);
    }

    /**
     * Mark an attendee as checked in.
     */
    public function checkin(Event $event, Request $request): RedirectResponse
    {
        // Authorize that the user is the organizer
        $this->authorize('manageCheckin', $event);

        $validated = $request->validate([
            'attendee_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendee = User::findOrFail($validated['attendee_id']);

        // Check if attendee is registered for this event
        $registration = $event->registrations()
            ->where('attendee_id', $attendee->id)
            ->first();

        if (!$registration) {
            return back()->with('error', 'Attendee is not registered for this event.');
        }

        // Check if already checked in
        $existingCheckin = EventCheckin::where('event_id', $event->id)
            ->where('attendee_id', $attendee->id)
            ->first();

        if ($existingCheckin) {
            return back()->with('warning', $attendee->name . ' is already checked in.');
        }

        // Create check-in record
        EventCheckin::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
            'checked_in_by' => auth()->id(),
            'checked_in_at' => now(),
            'check_in_method' => 'manual',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', $attendee->name . ' has been checked in successfully.');
    }

    /**
     * Undo check-in for an attendee.
     */
    public function undoCheckin(Event $event, int $attendeeId): RedirectResponse
    {
        // Authorize that the user is the organizer
        $this->authorize('manageCheckin', $event);

        $checkin = EventCheckin::where('event_id', $event->id)
            ->where('attendee_id', $attendeeId)
            ->first();

        if (!$checkin) {
            return back()->with('error', 'Check-in record not found.');
        }

        $attendee = $checkin->attendee;
        $checkin->delete();

        return back()->with('success', 'Check-in for ' . $attendee->name . ' has been undone.');
    }

    /**
     * Export check-in list as CSV.
     */
    public function exportCsv(Event $event)
    {
        // Authorize that the user is the organizer
        $this->authorize('manageCheckin', $event);

        $registrations = $event->registrations()
            ->with('attendee')
            ->get();

        $checkedInIds = $event->checkins()->pluck('attendee_id')->toArray();

        $fileName = 'checkin_' . $event->id . '_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($registrations, $checkedInIds) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Attendee Name', 'Email', 'Registration Date', 'Check-in Status', 'Check-in Time']);

            foreach ($registrations as $registration) {
                $isCheckedIn = in_array($registration->attendee_id, $checkedInIds);
                $checkinTime = '';

                if ($isCheckedIn) {
                    $checkin = \App\Models\EventCheckin::where('event_id', $registration->event_id)
                        ->where('attendee_id', $registration->attendee_id)
                        ->first();
                    if ($checkin) {
                        $checkinTime = $checkin->checked_in_at->format('Y-m-d H:i:s');
                    }
                }

                fputcsv($file, [
                    $registration->attendee->name,
                    $registration->attendee->email,
                    $registration->registered_at->format('Y-m-d H:i:s'),
                    $isCheckedIn ? 'Checked In' : 'Not Checked In',
                    $checkinTime,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
