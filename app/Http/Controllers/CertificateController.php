<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Certificate;
use App\Models\EventCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Generate certificate for a user.
     */
    public function generate(Event $event)
    {
        // Check if user is registered and checked in
        $registration = $event->registrations()
            ->where('attendee_id', Auth::id())
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->with('error', 'You must be registered for this event to receive a certificate.');
        }

        $checkin = EventCheckin::where('event_id', $event->id)
            ->where('attendee_id', Auth::id())
            ->first();

        if (!$checkin) {
            return redirect()->back()
                ->with('error', 'You must be checked in to the event to receive a certificate.');
        }

        // Check if certificate already exists
        $certificate = Certificate::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($certificate) {
            return redirect()->route('certificates.show', $certificate);
        }

        // Generate certificate
        $certificateNumber = 'CERT-' . strtoupper(uniqid());
        $certificate = Certificate::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
        ]);

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'Certificate generated successfully!');
    }

    /**
     * Display the certificate.
     */
    public function show(Certificate $certificate)
    {
        $this->authorize('view', $certificate);

        $certificate->load(['event', 'user']);

        return view('certificates.show', compact('certificate'));
    }
}
