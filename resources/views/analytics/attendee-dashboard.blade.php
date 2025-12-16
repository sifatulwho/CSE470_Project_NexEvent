@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">My Events</h1>
            <p class="mt-2 text-gray-600">Track your event registrations and check-ins</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Registered Events</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalRegistrations }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Events Attended</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCheckedIn }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Attendance Rate</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            @if($totalRegistrations > 0)
                                {{ round(($totalCheckedIn / $totalRegistrations) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        @if($upcomingEvents->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Upcoming Events</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($upcomingEvents as $eventData)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $eventData['event']->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $eventData['event']->description }}</p>
                                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-600">
                                        <span>📅 {{ $eventData['event']->start_date->format('M d, Y H:i') }}</span>
                                        <span>📍 {{ $eventData['event']->location ?? 'TBA' }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        Registered
                                    </span>
                                    @if($eventData['checked_in'])
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✓ Checked In
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Past Events -->
        @if($pastEvents->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Past Events</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($pastEvents as $eventData)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $eventData['event']->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $eventData['event']->description }}</p>
                                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-600">
                                        <span>📅 {{ $eventData['event']->start_date->format('M d, Y H:i') }}</span>
                                        <span>📍 {{ $eventData['event']->location ?? 'TBA' }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @if($eventData['checked_in'])
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✓ Attended
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            Did Not Attend
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($upcomingEvents->count() === 0 && $pastEvents->count() === 0)
            <div class="text-center py-12">
                <p class="text-gray-500">No events found. Check out available events to register!</p>
            </div>
        @endif
    </div>
</div>
@endsection
