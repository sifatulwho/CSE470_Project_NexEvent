<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Announcements - {{ $event->title }}
            </h2>
            @can('update', $event)
                <a href="{{ route('announcements.create', $event) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Create Announcement
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Event</a>
            </div>

            @if($announcements->count() > 0)
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $announcement->is_important ? 'border-l-4 border-red-500' : '' }}">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                            @if($announcement->is_important)
                                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Important</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mb-3">
                                            By {{ $announcement->user->name }} on {{ $announcement->created_at->format('M d, Y H:i') }}
                                        </p>
                                        <p class="text-gray-700 whitespace-pre-line">{{ $announcement->content }}</p>
                                    </div>
                                    @can('update', $event)
                                        <div class="ml-4 space-x-2">
                                            <a href="{{ route('announcements.edit', [$event, $announcement]) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                            <form method="POST" action="{{ route('announcements.destroy', [$event, $announcement]) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this announcement?')" class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500">No announcements yet.</p>
                    @can('update', $event)
                        <a href="{{ route('announcements.create', $event) }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Create First Announcement
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

