<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Event Resources</h2>
                <p class="mt-1 text-sm text-gray-500">Resources for {{ $event->title }}</p>
            </div>
            <div class="flex gap-3">
                @if (auth()->check() && (auth()->id() === $event->organizer_id || auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN)))
                    <a href="{{ route('features.resources.create', $event) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        Upload Resource
                    </a>
                @endif
                <a href="{{ route('features.events.show', $event) }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    View Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($resources->count() > 0)
                <div class="space-y-4">
                    @foreach ($resources as $resource)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-6 shadow-lg">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg font-semibold text-gray-900">{{ $resource->title }}</p>
                                    @if ($resource->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $resource->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 font-medium">{{ $resource->file_type ?? 'File' }}</span>
                                        <span>{{ $resource->file_size_human }}</span>
                                        <span>Uploaded by {{ $resource->uploader->name }}</span>
                                        <span>{{ $resource->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @auth
                                    @php
                                        $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                                            ->where('event_resource_id', $resource->id)
                                            ->whereNull('event_id')
                                            ->exists();
                                    @endphp
                                    <form action="{{ route('features.wishlist.toggle') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="resource">
                                        <input type="hidden" name="id" value="{{ $resource->id }}">
                                        <button type="submit" class="p-2 rounded-lg hover:bg-gray-100 transition" title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                            <svg class="h-6 w-6 {{ $isWishlisted ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endauth
                                <a href="{{ route('features.resources.download', $resource) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    Download
                                </a>
                                @if (auth()->check() && (auth()->id() === $resource->uploaded_by || auth()->id() === $event->organizer_id || auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN)))
                                    <form action="{{ route('features.resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resource?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No resources available</h3>
                    <p class="mt-2 text-sm text-gray-500">There are no resources uploaded for this event yet.</p>
                    @if (auth()->check() && (auth()->id() === $event->organizer_id || auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN)))
                        <a href="{{ route('features.resources.create', $event) }}" class="mt-4 inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                            Upload First Resource
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

