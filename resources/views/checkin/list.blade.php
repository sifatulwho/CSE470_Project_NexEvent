@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <a href="{{ route('analytics.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-medium mb-4 block">
                        ← Back to Analytics
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900">{{ $event->title }}</h1>
                    <p class="mt-2 text-gray-600">Manual Check-in Management</p>
                </div>
                <form action="{{ route('checkin.export-csv', $event) }}" method="GET" class="inline-block">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">
                        📥 Export CSV
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Event Date</p>
                <p class="text-xl font-bold text-gray-900 mt-2">{{ $event->start_date->format('M d, Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $event->start_date->format('H:i') }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Total Registered</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalRegistered }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Checked In</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $checkedInCount }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Check-in Rate</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $checkInRate }}%</p>
            </div>
        </div>

        <!-- Messages -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                {{ session('warning') }}
            </div>
        @endif

        <!-- Check-in Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Manual Check-in</h2>
            <form action="{{ route('checkin.checkin', $event) }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <label for="attendee_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Attendee
                    </label>
                    <select id="attendee_id" name="attendee_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choose an attendee --</option>
                        @foreach($attendeesList as $item)
                            @if(!$item['is_checked_in'])
                                <option value="{{ $item['attendee']->id }}">
                                    {{ $item['attendee']->name }} ({{ $item['attendee']->email }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (Optional)
                    </label>
                    <input type="text" id="notes" name="notes" placeholder="Add any notes..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        Check In
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendees List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Attendee List</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($attendeesList as $item)
                            <tr class="hover:bg-gray-50 @if($item['is_checked_in']) bg-green-50 @endif">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item['attendee']->profile_photo_url }}" 
                                             alt="{{ $item['attendee']->name }}"
                                             class="w-8 h-8 rounded-full">
                                        {{ $item['attendee']->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item['attendee']->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item['registration']->registered_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($item['is_checked_in'])
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✓ Checked In
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Not Checked In
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($item['is_checked_in'])
                                        <form action="{{ route('checkin.undo', [$event, $item['attendee']->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                Undo
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('checkin.checkin', $event) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="attendee_id" value="{{ $item['attendee']->id }}">
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                Check In
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No attendees registered for this event.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
