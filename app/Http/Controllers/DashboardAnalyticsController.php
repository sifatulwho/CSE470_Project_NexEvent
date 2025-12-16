<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard for organizers.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole(User::ROLE_ORGANIZER)) {
            return $this->getOrganizerAnalytics($user);
        }

        if ($user->hasRole(User::ROLE_ADMIN)) {
            return $this->getAdminAnalytics();
        }

        return $this->getAttendeeAnalytics($user);
    }

    /**
     * Get analytics for organizers.
     */
    private function getOrganizerAnalytics(User $user): View
    {
        $events = $user->organizedEvents()->get();

        $totalEvents = $events->count();
        $totalRegistrations = 0;
        $totalCheckedIn = 0;
        $totalAttendees = User::where('role', User::ROLE_ATTENDEE)->count();

        $eventAnalytics = [];

        foreach ($events as $event) {
            $registered = $event->getTotalRegisteredCount();
            $checkedIn = $event->getTotalCheckedInCount();

            $totalRegistrations += $registered;
            $totalCheckedIn += $checkedIn;

            $eventAnalytics[] = [
                'event' => $event,
                'registered' => $registered,
                'checked_in' => $checkedIn,
                'not_checked_in' => $registered - $checkedIn,
                'check_in_rate' => $registered > 0 ? round(($checkedIn / $registered) * 100, 2) : 0,
            ];
        }

        $upcomingEvents = $events->filter(function ($event) {
            return $event->start_date->isFuture();
        })->sortBy('start_date')->take(5);

        $recentCheckins = [];
        foreach ($events as $event) {
            $recentCheckins = array_merge(
                $recentCheckins,
                $event->checkins()->with('attendee')->latest()->take(10)->get()->toArray()
            );
        }
        usort($recentCheckins, function ($a, $b) {
            return strtotime($b['checked_in_at']) - strtotime($a['checked_in_at']);
        });
        $recentCheckins = array_slice($recentCheckins, 0, 10);

        return view('analytics.organizer-dashboard', [
            'totalEvents' => $totalEvents,
            'totalRegistrations' => $totalRegistrations,
            'totalCheckedIn' => $totalCheckedIn,
            'totalAttendees' => $totalAttendees,
            'eventAnalytics' => $eventAnalytics,
            'upcomingEvents' => $upcomingEvents,
            'recentCheckins' => $recentCheckins,
        ]);
    }

    /**
     * Get analytics for admin.
     */
    private function getAdminAnalytics(): View
    {
        $totalUsers = User::count();
        $totalOrganizers = User::where('role', User::ROLE_ORGANIZER)->count();
        $totalAttendees = User::where('role', User::ROLE_ATTENDEE)->count();
        $totalEvents = Event::count();
        $totalRegistrations = DB::table('event_registrations')->count();
        $totalCheckedIn = DB::table('event_checkins')->count();

        $events = Event::with('organizer')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($event) {
                return [
                    'event' => $event,
                    'registered' => $event->getTotalRegisteredCount(),
                    'checked_in' => $event->getTotalCheckedInCount(),
                ];
            });

        $eventsByOrganizer = Event::select('organizer_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('organizer_id')
            ->with('organizer')
            ->orderByRaw('count DESC')
            ->take(10)
            ->get();

        return view('analytics.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'totalOrganizers' => $totalOrganizers,
            'totalAttendees' => $totalAttendees,
            'totalEvents' => $totalEvents,
            'totalRegistrations' => $totalRegistrations,
            'totalCheckedIn' => $totalCheckedIn,
            'events' => $events,
            'eventsByOrganizer' => $eventsByOrganizer,
        ]);
    }

    /**
     * Get analytics for attendees.
     */
    private function getAttendeeAnalytics(User $user): View
    {
        $registeredEvents = $user->eventRegistrations()
            ->with('event')
            ->get();

        $checkedInEvents = $user->checkins()
            ->with('event')
            ->get();

        $upcomingEvents = [];
        $pastEvents = [];

        foreach ($registeredEvents as $registration) {
            $event = $registration->event;
            $checkedIn = $checkedInEvents->contains(function ($checkin) use ($event) {
                return $checkin->event_id == $event->id;
            });

            $eventData = [
                'event' => $event,
                'status' => $registration->status,
                'checked_in' => $checkedIn,
                'registered_at' => $registration->registered_at,
            ];

            if ($event->start_date->isFuture()) {
                $upcomingEvents[] = $eventData;
            } else {
                $pastEvents[] = $eventData;
            }
        }

        usort($upcomingEvents, function ($a, $b) {
            return strtotime($a['event']->start_date) - strtotime($b['event']->start_date);
        });

        usort($pastEvents, function ($a, $b) {
            return strtotime($b['event']->start_date) - strtotime($a['event']->start_date);
        });

        return view('analytics.attendee-dashboard', [
            'totalRegistrations' => $registeredEvents->count(),
            'totalCheckedIn' => $checkedInEvents->count(),
            'upcomingEvents' => array_slice($upcomingEvents, 0, 5),
            'pastEvents' => array_slice($pastEvents, 0, 5),
        ]);
    }
}
