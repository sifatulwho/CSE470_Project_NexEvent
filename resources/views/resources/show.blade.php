<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $resource->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('events.resources.index', $event) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Resources</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="flex-shrink-0">
                            <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $resource->title }}</h1>
                            @if($resource->description)
                                <p class="text-gray-700 whitespace-pre-line mb-4">{{ $resource->description }}</p>
                            @endif
                            <div class="text-sm text-gray-500 space-y-1">
                                <p>Event: <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-800">{{ $event->title }}</a></p>
                                <p>Uploaded by: {{ $resource->user->name }}</p>
                                <p>Uploaded on: {{ $resource->created_at->format('F d, Y H:i') }}</p>
                                @if($resource->file_size)
                                    <p>File size: {{ number_format($resource->file_size / 1024, 2) }} KB</p>
                                @endif
                                @if($resource->file_type)
                                    <p>File type: {{ $resource->file_type }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4 pt-6 border-t">
                        <a href="{{ route('events.resources.download', [$event, $resource]) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded">
                            Download Resource
                        </a>
                        @auth
                            @php($inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                ->where('wishlistable_id', $resource->id)
                                ->where('wishlistable_type', 'App\Models\EventResource')
                                ->exists())
                            @if(!$inWishlist)
                                <form method="POST" action="{{ route('wishlist.add-resource', $resource) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded">
                                        Add to Wishlist
                                    </button>
                                </form>
                            @else
                                <span class="bg-gray-300 text-gray-700 font-bold py-3 px-4 rounded">Already in Wishlist</span>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

