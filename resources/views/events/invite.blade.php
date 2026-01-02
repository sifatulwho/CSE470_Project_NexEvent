<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h3 class="text-lg font-semibold mb-4">This is an invite-only event</h3>
                <p class="text-gray-600 mb-6">Please enter the invite code to view this event.</p>
                
                <form method="GET" action="{{ route('events.show', $event) }}" class="space-y-4">
                    <div>
                        <label for="invite_code" class="block text-sm font-medium text-gray-700">Invite Code</label>
                        <input type="text" name="invite_code" id="invite_code" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Access Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

