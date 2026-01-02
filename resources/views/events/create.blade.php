<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('events.store') }}" class="p-8 space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @else border @endif">
                        @error('title')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="6" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @else border @endif">{{ old('description') }}</textarea>
                        @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- Start Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('start_date') border-red-500 @else border @endif">
                            @error('start_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                            <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('end_date') border-red-500 @else border @endif">
                            @error('end_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('location') border-red-500 @else border @endif">
                        @error('location')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category (optional)</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Category</option>
                            <option value="seminar" {{ old('category') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="workshop" {{ old('category') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="concert" {{ old('category') == 'concert' ? 'selected' : '' }}>Concert</option>
                            <option value="orientation" {{ old('category') == 'orientation' ? 'selected' : '' }}>Orientation</option>
                            <option value="reunion" {{ old('category') == 'reunion' ? 'selected' : '' }}>Reunion</option>
                            <option value="conference" {{ old('category') == 'conference' ? 'selected' : '' }}>Conference</option>
                            <option value="meetup" {{ old('category') == 'meetup' ? 'selected' : '' }}>Meetup</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags (comma-separated, optional)</label>
                        <input type="text" name="tags_input" id="tags_input" value="{{ old('tags_input') }}" 
                            placeholder="e.g., technology, networking, education"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Enter tags separated by commas</p>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                        <select name="visibility" id="visibility" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>Public - Visible to everyone</option>
                            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private - Only visible to registered attendees</option>
                            <option value="invite_only" {{ old('visibility') == 'invite_only' ? 'selected' : '' }}>Invite Only - Requires invite code</option>
                        </select>
                        @error('visibility')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- Max Attendees -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="max_attendees" class="block text-sm font-medium text-gray-700">Max Attendees (optional)</label>
                            <input type="number" name="max_attendees" id="max_attendees" value="{{ old('max_attendees') }}" min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('max_attendees') border-red-500 @else border @endif">
                            @error('max_attendees')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Image URL -->
                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL (optional)</label>
                            <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('image_url') border-red-500 @else border @endif">
                            @error('image_url')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('events.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition">
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
