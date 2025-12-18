<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $event->title }}</h2>
                <p class="mt-1 text-sm text-gray-500">Event details and resources</p>
            </div>
            <a href="{{ route('features.events.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                ← Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                        
                        @if ($event->description)
                            <p class="text-gray-600 mb-6">{{ $event->description }}</p>
                        @endif

                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Date</p>
                                    <p class="text-sm text-gray-600">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                                </div>
                            </div>

                            @if ($event->location)
                                <div class="flex items-start gap-3">
                                    <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Location</p>
                                        <p class="text-sm text-gray-600">{{ $event->location }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Organizer</p>
                                    <p class="text-sm text-gray-600">{{ $event->organizer->name }}</p>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <form action="{{ route('features.wishlist.toggle') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="event">
                                    <input type="hidden" name="id" value="{{ $event->id }}">
                                    <button type="submit" class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition {{ $isWishlisted ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100' }}">
                                        <svg class="h-5 w-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        {{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                                    </button>
                                </form>
                            </div>
                        @endauth
                    
                    @auth
                        <div class="mt-6">
                            @php($registered = auth()->check() ? \App\Models\EventRegistration::where('event_id', $event->id)->where('user_id', auth()->id())->exists() : false)
                            @if (! $registered)
                                <form action="{{ route('features.events.register', $event) }}" method="POST">
                                    @csrf
                                    <button class="mt-4 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Register</button>
                                </form>
                            @else
                                <form action="{{ route('features.events.unregister', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="mt-4 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold">Cancel registration</button>
                                </form>
                            @endif
                        </div>
                    @endauth

                    @php($schedules = \App\Models\EventSchedule::where('event_id', $event->id)->with('sessions.speaker')->orderBy('start_time')->get())
                    @if ($schedules->isNotEmpty())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-3">Schedule</h3>
                            @foreach ($schedules as $schedule)
                                <div class="mb-4 rounded-lg border p-4 bg-white">
                                    <h4 class="font-semibold">{{ $schedule->title }}</h4>
                                    @if($schedule->description)
                                        <p class="text-sm text-gray-500">{{ $schedule->description }}</p>
                                    @endif
                                    @foreach ($schedule->sessions as $session)
                                        <div class="mt-3 pl-3">
                                            <div class="text-sm font-medium">{{ $session->title }} <span class="text-xs text-gray-400">({{ optional($session->start_time)->format('M d H:i') }})</span></div>
                                            <div class="text-xs text-gray-500">{{ $session->speaker?->name }} — {{ $session->location }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                    </div>

                    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Event Resources</h3>
                            @if (auth()->check() && (auth()->id() === $event->organizer_id || auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN)))
                                <a href="{{ route('features.resources.create', $event) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    Upload Resource
                                </a>
                            @endif
                        </div>
                        {{-- Comments --}}
                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-2">Comments</h4>
                            @auth
                                <form action="{{ route('features.events.comments.store', $event) }}" method="POST">
                                    @csrf
                                    <textarea name="body" rows="3" class="w-full rounded-md border-gray-200 p-2" placeholder="Add a comment"></textarea>
                                    <div class="mt-2 text-right">
                                        <button class="rounded-lg bg-indigo-600 px-3 py-1 text-sm text-white">Post comment</button>
                                    </div>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}" class="text-indigo-600">login</a> to comment.</p>
                            @endauth

                            <div class="mt-4 space-y-4">
                                @foreach(\App\Models\Comment::where('event_id', $event->id)->whereNull('parent_id')->with('user.replies')->orderBy('created_at','desc')->get() as $comment)
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="text-sm font-semibold">{{ $comment->user->name }} <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span></div>
                                        <div class="text-sm text-gray-700 mt-1">{{ $comment->body }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Reviews --}}
                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-2">Reviews</h4>

                            @auth
                                <form action="{{ route('features.events.reviews.store', $event) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm">Rating</label>
                                        <select name="rating" class="rounded-md border-gray-200">
                                            @for($i=1;$i<=5;$i++)
                                                <option value="{{ $i }}">{{ $i }} &starf;</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <input name="title" class="w-full rounded-md border-gray-200 p-2" placeholder="Title (optional)">
                                    </div>
                                    <div class="mt-2">
                                        <textarea name="body" rows="3" class="w-full rounded-md border-gray-200 p-2" placeholder="Write your review"></textarea>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <button class="rounded-lg bg-indigo-600 px-3 py-1 text-sm text-white">Submit review</button>
                                    </div>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}" class="text-indigo-600">login</a> to leave a review.</p>
                            @endauth

                            <div class="mt-4 space-y-4">
                                @foreach(\App\Models\Review::where('event_id', $event->id)->with('user')->orderBy('created_at','desc')->get() as $review)
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="text-sm font-semibold">{{ $review->user->name }} <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span></div>
                                        <div class="text-sm text-yellow-600">Rating: {{ $review->rating }} / 5</div>
                                        @if($review->title)
                                            <div class="text-sm font-medium mt-1">{{ $review->title }}</div>
                                        @endif
                                        @if($review->body)
                                            <div class="text-sm text-gray-700 mt-1">{{ $review->body }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('features.wishlist.index') }}" class="block rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 text-center">
                                View My Wishlist
                            </a>
                            @if (auth()->check() && (auth()->id() === $event->organizer_id || auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN)))
                                <a href="{{ route('features.resources.create', $event) }}" class="block rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500 text-center">
                                    Upload Resources
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

