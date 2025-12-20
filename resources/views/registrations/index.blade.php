<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Registrations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($registrations->count() > 0)
                <div class="space-y-4">
                    @foreach($registrations as $registration)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $registration->event->title }}</h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        <strong>Date:</strong> {{ $registration->event->start_date->format('M d, Y H:i') }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        <strong>Location:</strong> {{ $registration->event->location }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        <strong>Registered:</strong> {{ $registration->registered_at->format('M d, Y H:i') }}
                                    </p>
                                    @if($registration->ticket)
                                        <p class="text-gray-600 text-sm">
                                            <strong>Ticket ID:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ $registration->ticket->ticket_id }}</code>
                                        </p>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('registrations.show', $registration) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center transition">
                                        View Ticket
                                    </a>
                                    <a href="{{ route('registrations.confirmCancel', $registration) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center transition">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $registrations->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 mb-4">You have not registered for any events yet.</p>
                    <a href="{{ route('events.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                        Browse Events
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
