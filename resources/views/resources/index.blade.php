<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Resources - {{ $event->title }}
            </h2>
            @can('update', $event)
                <a href="{{ route('events.resources.create', $event) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Upload Resource
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Event</a>
            </div>

            @if($resources->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($resources as $resource)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $resource->title }}</h3>
                                        @if($resource->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ \Illuminate\Support\Str::limit($resource->description, 100) }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">
                                            Uploaded by {{ $resource->user->name }} on {{ $resource->created_at->format('M d, Y') }}
                                        </p>
                                        @if($resource->file_size)
                                            <p class="text-xs text-gray-500">
                                                Size: {{ number_format($resource->file_size / 1024, 2) }} KB
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('events.resources.show', [$event, $resource]) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        View
                                    </a>
                                    <a href="{{ route('events.resources.download', [$event, $resource]) }}" class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Download
                                    </a>
                                    @auth
                                        @php($inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                            ->where('wishlistable_id', $resource->id)
                                            ->where('wishlistable_type', 'App\Models\EventResource')
                                            ->exists())
                                        @if(!$inWishlist)
                                            <form method="POST" action="{{ route('wishlist.add-resource', $resource) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    ♥ Save
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                    @can('update', $event)
                                        <form method="POST" action="{{ route('events.resources.destroy', [$event, $resource]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this resource?')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500">No resources uploaded yet.</p>
                    @can('update', $event)
                        <a href="{{ route('events.resources.create', $event) }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Upload First Resource
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

