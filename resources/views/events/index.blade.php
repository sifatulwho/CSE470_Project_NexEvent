<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Available Events') }}
            </h2>
            @auth
                @if(auth()->user()->hasRole(\App\Models\User::ROLE_ORGANIZER))
                    <a href="{{ route('events.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Create Event
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('events.index') }}" class="flex gap-4">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search events..." 
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <select name="category" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        <option value="seminar" {{ request('category') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="workshop" {{ request('category') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="concert" {{ request('category') == 'concert' ? 'selected' : '' }}>Concert</option>
                        <option value="orientation" {{ request('category') == 'orientation' ? 'selected' : '' }}>Orientation</option>
                        <option value="reunion" {{ request('category') == 'reunion' ? 'selected' : '' }}>Reunion</option>
                        <option value="conference" {{ request('category') == 'conference' ? 'selected' : '' }}>Conference</option>
                        <option value="meetup" {{ request('category') == 'meetup' ? 'selected' : '' }}>Meetup</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Search
                    </button>
                    @if(request('q') || request('category'))
                        <a href="{{ route('events.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            @if($event->image_url)
                                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white text-sm">No image</span>
                                </div>
                            @endif

                            <div class="p-6">
                                <h3 class="font-semibold text-lg text-gray-900 mb-2 truncate">{{ $event->title }}</h3>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>

                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $event->start_date->format('M d, Y H:i') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->location }}
                                    </div>
                                    @if($event->max_attendees)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zM5 20H0v-2a6 6 0 016-6v6zm15-6a6 6 0 016 6v2h-5v-6z"></path>
                                            </svg>
                                            {{ $event->activeRegistrationsCount() }}/{{ $event->max_attendees }}
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('events.show', $event) }}" class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500">No events available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
