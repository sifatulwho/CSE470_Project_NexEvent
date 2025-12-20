<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cancel Registration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <!-- Warning Alert -->
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ Cancel Registration</h3>
                    <p class="text-red-700">
                        Are you sure you want to cancel your registration for this event? This action cannot be undone.
                    </p>
                </div>

                <!-- Event Details -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Event Details</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->event->title }}</p>
                    <p class="text-gray-600">
                        {{ $registration->event->start_date->format('M d, Y H:i') }} at {{ $registration->event->location }}
                    </p>
                </div>

                <!-- Cancellation Form -->
                <form method="POST" action="{{ route('registrations.cancel', $registration) }}" class="space-y-6">
                    @csrf

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Cancellation (Optional)</label>
                        <textarea name="reason" id="reason" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border"
                            placeholder="Let us know why you're cancelling..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">Your feedback helps us improve.</p>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" name="confirm" id="confirm" required
                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="confirm" class="ml-3 text-sm text-gray-700">
                            I confirm that I want to cancel my registration for this event.
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('registrations.show', $registration) }}" class="px-6 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition">
                            Keep Registration
                        </a>
                        <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                            Cancel Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
