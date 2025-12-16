<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Event Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <!-- Ticket Header -->
                <div class="text-center mb-8 pb-8 border-b">
                    <h2 class="text-3xl font-bold text-indigo-600 mb-2">Digital Ticket</h2>
                    <p class="text-gray-600">{{ $registration->event->title }}</p>
                </div>

                <!-- Event Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase">Event Date</h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $registration->event->start_date->format('M d, Y') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $registration->event->start_date->format('H:i') }} - {{ $registration->event->end_date->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase">Location</h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $registration->event->location }}
                        </p>
                    </div>
                </div>

                <!-- Attendee Information -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Attendee Information</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->attendee->name }}</p>
                    <p class="text-sm text-gray-600">{{ $registration->attendee->email }}</p>
                </div>

                <!-- Ticket ID and QR Code -->
                <div class="border-t pt-8 mb-8">
                    <div class="text-center">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-4">Your Unique Ticket ID</h4>
                        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-6 mb-6">
                            <code class="text-2xl font-mono font-bold text-indigo-600 break-all">
                                {{ $ticket->ticket_id }}
                            </code>
                        </div>

                        @if($ticket->qr_code)
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-4">QR Code</h4>
                            <div class="flex justify-center">
                                <img src="{{ $ticket->qr_code }}" alt="QR Code" class="w-48 h-48 border-2 border-gray-300 rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Information -->
                <div class="border-t pt-8 mb-8">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase">Registration Date</h4>
                            <p class="mt-2 text-lg font-semibold text-gray-900">
                                {{ $registration->registered_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase">Ticket Status</h4>
                            <p class="mt-2">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $ticket->is_used ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $ticket->is_used ? 'Used' : 'Valid' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                    <p class="text-sm text-yellow-800">
                        <strong>Important:</strong> Please save your ticket ID and QR code. You will need to present this ticket at the event entrance.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('registrations.downloadTicket', $registration) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition">
                        📥 Download Ticket
                    </a>
                    <a href="{{ route('events.show', $registration->event) }}" class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded transition">
                        ← Back to Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
