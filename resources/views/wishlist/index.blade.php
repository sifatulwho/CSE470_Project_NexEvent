<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($wishlists->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishlists as $wishlist)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            @if($wishlist->wishlistable_type === 'App\Models\Event')
                                @php($item = $wishlist->wishlistable)
                                @if($item)
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-indigo-400 to-indigo-600"></div>
                                @endif
                                <div class="p-6">
                                    <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $item->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-4">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('events.show', $item) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                            View Event
                                        </a>
                                        <form method="POST" action="{{ route('wishlist.remove', $wishlist) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @elseif($wishlist->wishlistable_type === 'App\Models\EventResource')
                                @php($item = $wishlist->wishlistable)
                                @if($item)
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="ml-4">
                                            <h3 class="font-semibold text-lg text-gray-900">{{ $item->title }}</h3>
                                            <p class="text-sm text-gray-600">From: {{ $item->event->title }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 mb-4">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('events.resources.show', [$item->event, $item]) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                            View Resource
                                        </a>
                                        <form method="POST" action="{{ route('wishlist.remove', $wishlist) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $wishlists->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500 text-lg">Your wishlist is empty.</p>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Browse Events
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

