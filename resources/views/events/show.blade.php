<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            @auth
                @if(auth()->user()->hasRole(\App\Models\User::ROLE_ORGANIZER) && auth()->id() === $event->organizer_id)
                    <div class="space-x-2">
                        <a href="{{ route('events.edit', $event) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('events.destroy', $event) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-indigo-400 to-indigo-600"></div>
                @endif

                <div class="p-8">
                    <!-- Event Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Start Date</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $event->start_date->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">End Date</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $event->end_date->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $event->location }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Organizer</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $event->organizer->name }}</p>
                        </div>
                        @if($event->max_attendees)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Capacity</h3>
                                <p class="mt-1 text-lg text-gray-900">
                                    {{ $event->activeRegistrationsCount() }} / {{ $event->max_attendees }}
                                    <span class="text-sm text-gray-500">
                                        ({{ $event->max_attendees - $event->activeRegistrationsCount() }} available)
                                    </span>
                                </p>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status</h3>
                            <p class="mt-1">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($event->status === 'published') bg-green-100 text-green-800
                                    @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                                    @elseif($event->status === 'completed') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Event</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $event->description }}</p>
                    </div>

                                    <!-- Schedule -->
                                    <div class="mb-8 border-t pt-8">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900">Schedule</h3>
                                            @can('update', $event)
                                                <a href="{{ route('events.sessions.create', $event) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Session</a>
                                            @elseauth
                                                <div class="text-sm text-gray-500">
                                                    <p>You are logged in as <strong>{{ auth()->user()->name }}</strong> (role: <strong>{{ auth()->user()->role }}</strong>), but you don't have permission to add sessions to this event.</p>
                                                    <p class="mt-1">Only the event organizer <strong>{{ $event->organizer->name ?? '—' }}</strong> or an admin can add sessions. If you should be the organizer, update your user role or contact the organizer.</p>
                                                    <p class="mt-2"><a href="{{ route('organizer.hub') }}" class="text-indigo-600">Open Organizer Hub</a> · <a href="{{ route('speakers.index') }}" class="text-indigo-600">Manage Speakers</a></p>
                                                </div>
                                            @endauth
                                        </div>

                                        @if($event->sessions->count() > 0)
                                            <div class="space-y-4">
                                                @foreach($event->sessions->sortBy('start_time') as $session)
                                                    <div class="p-4 bg-gray-50 rounded-lg">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h4 class="font-semibold">{{ $session->title }}</h4>
                                                                <p class="text-sm text-gray-600">{{ $session->start_time->format('M d, Y H:i') }} — {{ $session->end_time->format('H:i') }} @if($session->location) · {{ $session->location }} @endif</p>
                                                                <p class="mt-2 text-gray-700">{{ $session->description }}</p>
                                                                @if($session->speakers->count())
                                                                    <p class="mt-2 text-sm text-gray-600">Speakers: {{ $session->speakers->pluck('name')->join(', ') }}</p>
                                                                @endif
                                                            </div>
                                                            @auth
                                                                @if(auth()->user()->hasRole(\App\Models\User::ROLE_ORGANIZER) && auth()->id() === $event->organizer_id)
                                                                    <div class="space-x-2">
                                                                        <a href="{{ route('events.sessions.edit', [$event, $session]) }}" class="text-indigo-600">Edit</a>
                                                                        <form method="POST" action="{{ route('events.sessions.destroy', [$event, $session]) }}" class="inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" onclick="return confirm('Remove session?')" class="text-red-600">Remove</button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-500">No sessions scheduled yet.</p>
                                        @endif
                                    </div>

                    <!-- Registration Section -->
                    <div class="border-t pt-8">
                        @auth
                            @if($isRegistered)
                                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-green-800 font-semibold">✓ You are registered for this event</p>
                                </div>
                                <div class="flex space-x-4">
                                    <a href="{{ route('registrations.show', $registration) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition">
                                        View Your Ticket
                                    </a>
                                    <a href="{{ route('registrations.confirmCancel', $registration) }}" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded transition">
                                        Cancel Registration
                                    </a>
                                </div>
                            @else
                                @if($event->hasAvailableSeats())
                                    <form action="{{ route('registrations.store', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition">
                                            Register for This Event
                                        </button>
                                    </form>
                                @else
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-red-800 font-semibold">Event is at maximum capacity</p>
                                    </div>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition">
                                Login to Register
                            </a>
                        @endauth
                    </div>

                    <!-- Attendees Count -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600">
                            <strong>{{ $event->activeRegistrationsCount() }}</strong> 
                            {{ $event->activeRegistrationsCount() === 1 ? 'person' : 'people' }} registered
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
