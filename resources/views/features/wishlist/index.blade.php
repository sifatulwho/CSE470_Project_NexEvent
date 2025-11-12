<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900">My Wishlist</h2>
        <p class="mt-1 text-sm text-gray-500">Events and resources you've bookmarked for later.</p>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($wishlists->count() > 0)
                <div class="space-y-6">
                    @foreach ($wishlists as $wishlist)
                        @if ($wishlist->event)
                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Event</span>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $wishlist->event->title }}</h3>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($wishlist->event->description ?? 'No description available.', 150) }}</p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span>{{ $wishlist->event->start_date->format('M d, Y') }}</span>
                                            @if ($wishlist->event->location)
                                                <span>{{ $wishlist->event->location }}</span>
                                            @endif
                                            <span>{{ $wishlist->event->resources->count() }} resource(s)</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 ml-4">
                                        <a href="{{ route('features.events.show', $wishlist->event) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                            View Event
                                        </a>
                                        <form action="{{ route('features.wishlist.remove', $wishlist) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif ($wishlist->eventResource)
                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4 flex-1">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Resource</span>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $wishlist->eventResource->title }}</h3>
                                            </div>
                                            @if ($wishlist->eventResource->description)
                                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($wishlist->eventResource->description, 150) }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 font-medium">{{ $wishlist->eventResource->file_type ?? 'File' }}</span>
                                                <span>{{ $wishlist->eventResource->file_size_human }}</span>
                                                <span>From: <a href="{{ route('features.events.show', $wishlist->eventResource->event) }}" class="text-indigo-600 hover:underline">{{ $wishlist->eventResource->event->title }}</a></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 ml-4">
                                        <a href="{{ route('features.resources.download', $wishlist->eventResource) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                            Download
                                        </a>
                                        <a href="{{ route('features.events.show', $wishlist->eventResource->event) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                            View Event
                                        </a>
                                        <form action="{{ route('features.wishlist.remove', $wishlist) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Your wishlist is empty</h3>
                    <p class="mt-2 text-sm text-gray-500">Start bookmarking events and resources you're interested in.</p>
                    <a href="{{ route('features.events.index') }}" class="mt-4 inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                        Browse Past Events
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

