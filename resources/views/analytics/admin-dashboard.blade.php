@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Admin Analytics Dashboard</h1>
            <p class="mt-2 text-gray-600">System-wide statistics and insights</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Users Section -->
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-500 text-sm font-medium">Organizers</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalOrganizers }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-500 text-sm font-medium">Attendees</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalAttendees }}</p>
                </div>
            </div>

            <!-- Events Section -->
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-500 text-sm font-medium">Total Events</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalEvents }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-500 text-sm font-medium">Total Registrations</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalRegistrations }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-500 text-sm font-medium">Total Check-ins</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalCheckedIn }}</p>
                </div>
            </div>

            <!-- Overall Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Overall Check-in Rate</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            @if($totalRegistrations > 0)
                                {{ round(($totalCheckedIn / $totalRegistrations) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-xs text-gray-600 font-medium">Average Users per Event</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            @if($totalEvents > 0)
                                {{ round($totalRegistrations / $totalEvents, 1) }}
                            @else
                                0
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Events</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($events as $eventData)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $eventData['event']->title }}</h3>
                                    <p class="mt-1 text-xs text-gray-600">
                                        Organizer: {{ $eventData['event']->organizer->name }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        {{ $eventData['event']->start_date->format('M d, Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-gray-600">
                                        <span class="text-blue-600">{{ $eventData['registered'] }}</span> registered
                                    </p>
                                    <p class="text-xs font-semibold text-gray-600 mt-1">
                                        <span class="text-green-600">{{ $eventData['checked_in'] }}</span> checked in
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No events found.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Organizers -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Top Organizers</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($eventsByOrganizer as $organizer)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <img src="{{ $organizer->organizer->profile_photo_url }}" 
                                         alt="{{ $organizer->organizer->name }}"
                                         class="w-10 h-10 rounded-full inline-block mr-3">
                                    <span class="text-sm font-semibold text-gray-900">{{ $organizer->organizer->name }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-indigo-600">{{ $organizer->count }} events</p>
                                    <p class="text-xs text-gray-600">{{ $organizer->organizer->email }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No organizers found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
