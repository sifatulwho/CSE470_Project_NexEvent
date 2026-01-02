<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Announcement - {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Event</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('announcements.store', $event) }}" class="p-8 space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" id="content" rows="8" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                        @error('content')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Mark as important</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('events.show', $event) }}" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                            Create Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

