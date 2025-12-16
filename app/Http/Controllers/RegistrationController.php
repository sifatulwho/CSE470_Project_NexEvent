<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Register an attendee for an event.
     */
    public function register(Request $request, Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to register for an event.');
        }

        // Check if already registered
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('attendee_id', Auth::id())
            ->first();

        if ($existing && $existing->isActive()) {
            return redirect()->back()
                ->with('error', 'You are already registered for this event.');
        }

        // Check if event has available seats
        if (!$event->hasAvailableSeats()) {
            return redirect()->back()
                ->with('error', 'This event has reached its maximum capacity.');
        }

        // Create registration
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'attendee_id' => Auth::id(),
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        // Generate ticket
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'registration_id' => $registration->id,
            'ticket_id' => Ticket::generateTicketId(),
            'qr_code' => null, // Will be generated on display
        ]);

        // Send confirmation notification
        try {
            Auth::user()->notify(new \App\Notifications\RegistrationConfirmed($registration));
        } catch (\Exception $e) {
            // Log but don't fail the registration
            \Log::error('Failed to send registration confirmation: ' . $e->getMessage());
        }

        return redirect()->route('registrations.show', $registration)
            ->with('success', 'Successfully registered for the event! Your ticket has been generated.');
    }

    /**
     * Display user's registration and ticket.
     */
    public function show(EventRegistration $registration)
    {
        // Check authorization
        if ($registration->attendee_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $ticket = $registration->ticket;

        // Generate QR code if not already generated
        if ($ticket && !$ticket->qr_code) {
            $ticket->update([
                'qr_code' => $ticket->generateQrCode(),
            ]);
        }

        return view('registrations.show', compact('registration', 'ticket'));
    }

    /**
     * Display user's registrations list.
     */
    public function myRegistrations()
    {
        $registrations = EventRegistration::where('attendee_id', Auth::id())
            ->where('status', 'confirmed')
            ->with('event')
            ->orderBy('registered_at', 'desc')
            ->paginate(10);

        return view('registrations.index', compact('registrations'));
    }

    /**
     * Cancel a registration.
     */
    public function cancel(Request $request, EventRegistration $registration)
    {
        // Check authorization
        if ($registration->attendee_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // Check if already cancelled
        if ($registration->isCancelled()) {
            return redirect()->back()
                ->with('error', 'This registration is already cancelled.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $registration->cancel($validated['reason'] ?? null);

        return redirect()->route('registrations.myRegistrations')
            ->with('success', 'Your registration has been cancelled successfully.');
    }

    /**
     * Display cancellation form.
     */
    public function confirmCancel(EventRegistration $registration)
    {
        // Check authorization
        if ($registration->attendee_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return view('registrations.confirm-cancel', compact('registration'));
    }

    /**
     * Download ticket as PDF (if dompdf is installed).
     */
    public function downloadTicket(EventRegistration $registration)
    {
        // Check authorization
        if ($registration->attendee_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $ticket = $registration->ticket;
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        // For now, return the ticket view
        return view('registrations.ticket-pdf', compact('registration', 'ticket'));
    }
}
