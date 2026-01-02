<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            @auth
                @if(auth()->user()->hasRole(\App\Models\User::ROLE_ORGANIZER) && auth()->id() === $event->organizer_id)
                    <div class="space-x-2">
                        <a href="{{ route('events.edit', $event) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('events.destroy', $event) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($event->image_url)
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                        @else
                            <div class="w-full h-96 bg-gradient-to-br from-indigo-400 to-indigo-600"></div>
                        @endif

                        <div class="p-8">
                            <!-- Event Details -->
                            <div class="flex items-center space-x-4 mb-6">
                                @if($event->category)
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">{{ ucfirst($event->category) }}</span>
                                @endif
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                                    @if($event->visibility === 'public') Public
                                    @elseif($event->visibility === 'private') Private
                                    @else Invite Only
                                    @endif
                                </span>
                                @if($event->tags && $event->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($event->tags as $tag)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Start Date</h3>
                                    <p class="mt-1 text-lg text-gray-900">{{ $event->start_date->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">End Date</h3>
                                    <p class="mt-1 text-lg text-gray-900">{{ $event->end_date->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</h3>
                                    <p class="mt-1 text-lg text-gray-900">{{ $event->location }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Organizer</h3>
                                    <p class="mt-1 text-lg text-gray-900">{{ $event->organizer->name }}</p>
                                </div>
                                @if($event->max_attendees)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Capacity</h3>
                                        <p class="mt-1 text-lg text-gray-900">
                                            {{ $event->activeRegistrationsCount() }} / {{ $event->max_attendees }}
                                            <span class="text-sm text-gray-500">
                                                ({{ $event->max_attendees - $event->activeRegistrationsCount() }} available)
                                            </span>
                                        </p>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status</h3>
                                    <p class="mt-1">
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                            @if($event->status === 'published') bg-green-100 text-green-800
                                            @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                                            @elseif($event->status === 'completed') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </p>
                                </div>
                                @if($event->average_rating > 0)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rating</h3>
                                        <p class="mt-1 text-lg text-gray-900">
                                            {{ number_format($event->average_rating, 1) }} / 5.0
                                            <span class="text-yellow-500">★</span>
                                            ({{ $event->reviews->count() }} reviews)
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Event</h3>
                                <p class="text-gray-700 whitespace-pre-line">{{ $event->description }}</p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 mb-8">
                                @auth
                                    @if($inWishlist)
                                        <form method="POST" action="{{ route('wishlist.remove', \App\Models\Wishlist::where('user_id', auth()->id())->where('wishlistable_id', $event->id)->where('wishlistable_type', 'App\Models\Event')->first()) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Remove from Wishlist
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('wishlist.add-event', $event) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                                Add to Wishlist
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('events.messages.index', $event) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Event Chat
                                    </a>
                                    @if($event->resources->count() > 0)
                                        <a href="{{ route('events.resources.index', $event) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                            Resources ({{ $event->resources->count() }})
                                        </a>
                                    @endif
                                @endauth
                                <button onclick="navigator.clipboard.writeText('{{ $event->shareable_link }}'); alert('Link copied!');" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Share Event
                                </button>
                            </div>

                            <!-- Schedule -->
                            <div class="mb-8 border-t pt-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Schedule</h3>
                                    @can('update', $event)
                                        <a href="{{ route('events.sessions.create', $event) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">Add Session</a>
                                    @endcan
                                </div>

                                @if($event->sessions->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($event->sessions->sortBy('start_time') as $session)
                                            <div class="p-4 bg-gray-50 rounded-lg">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-semibold">{{ $session->title }}</h4>
                                                        <p class="text-sm text-gray-600">{{ $session->start_time->format('M d, Y H:i') }} — {{ $session->end_time->format('H:i') }} @if($session->location) · {{ $session->location }} @endif</p>
                                                        <p class="mt-2 text-gray-700">{{ $session->description }}</p>
                                                        @if($session->speakers->count())
                                                            <p class="mt-2 text-sm text-gray-600">Speakers: {{ $session->speakers->pluck('name')->join(', ') }}</p>
                                                        @endif
                                                    </div>
                                                    @can('update', $event)
                                                        <div class="space-x-2">
                                                            <a href="{{ route('events.sessions.edit', [$event, $session]) }}" class="text-indigo-600">Edit</a>
                                                            <form method="POST" action="{{ route('events.sessions.destroy', [$event, $session]) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Remove session?')" class="text-red-600">Remove</button>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">No sessions scheduled yet.</p>
                                @endif
                            </div>

                            <!-- Announcements -->
                            @if($event->announcements->count() > 0)
                                <div class="mb-8 border-t pt-8">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                                        <a href="{{ route('announcements.index', $event) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View All</a>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach($event->announcements->sortByDesc('is_important')->sortByDesc('created_at')->take(3) as $announcement)
                                            <div class="p-4 bg-{{ $announcement->is_important ? 'red' : 'blue' }}-50 border-l-4 border-{{ $announcement->is_important ? 'red' : 'blue' }}-500 rounded">
                                                <h4 class="font-semibold text-gray-900">{{ $announcement->title }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($announcement->content, 150) }}</p>
                                                <p class="text-xs text-gray-500 mt-2">{{ $announcement->created_at->diffForHumans() }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Registration Section -->
                            <div class="border-t pt-8">
                                @auth
                                    @if($isRegistered)
                                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                            <p class="text-green-800 font-semibold">✓ You are registered for this event</p>
                                        </div>
                                        <div class="flex space-x-4">
                                            <a href="{{ route('registrations.show', $registration) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition">
                                                View Your Ticket
                                            </a>
                                            <a href="{{ route('registrations.confirmCancel', $registration) }}" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded transition">
                                                Cancel Registration
                                            </a>
                                            @if($event->checkins->where('attendee_id', auth()->id())->count() > 0)
                                                <form method="POST" action="{{ route('certificates.generate', $event) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded transition">
                                                        Get Certificate
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        @if($event->hasAvailableSeats())
                                            <form action="{{ route('registrations.store', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition">
                                                    Register for This Event
                                                </button>
                                            </form>
                                        @else
                                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-red-800 font-semibold">Event is at maximum capacity</p>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition">
                                        Login to Register
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Comments</h3>
                            @auth
                                <form method="POST" action="{{ route('comments.store', $event) }}" class="mb-6">
                                    @csrf
                                    <textarea name="content" rows="3" required placeholder="Add a comment..." 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    <button type="submit" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                        Post Comment
                                    </button>
                                </form>
                            @endauth

                            @if($event->comments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($event->comments as $comment)
                                        <div class="border-b pb-4">
                                            <div class="flex items-start space-x-3">
                                                <img src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2">
                                                        <h4 class="font-semibold text-gray-900">{{ $comment->user->name }}</h4>
                                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-gray-700 mt-1">{{ $comment->content }}</p>
                                                    @can('update', $comment)
                                                        <div class="mt-2 space-x-2">
                                                            <a href="#" class="text-indigo-600 text-sm">Edit</a>
                                                            <form method="POST" action="{{ route('comments.destroy', [$event, $comment]) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 text-sm">Delete</button>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No comments yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reviews</h3>
                            @auth
                                @if($isRegistered && !$event->reviews->where('user_id', auth()->id())->first())
                                    <form method="POST" action="{{ route('reviews.store', $event) }}" class="mb-6 bg-gray-50 p-4 rounded-lg">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                            <select name="rating" required class="rounded-md border-gray-300 shadow-sm">
                                                <option value="5">5 - Excellent</option>
                                                <option value="4">4 - Very Good</option>
                                                <option value="3">3 - Good</option>
                                                <option value="2">2 - Fair</option>
                                                <option value="1">1 - Poor</option>
                                            </select>
                                        </div>
                                        <textarea name="review" rows="3" placeholder="Write your review..." 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                        <button type="submit" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                            Submit Review
                                        </button>
                                    </form>
                                @endif
                            @endauth

                            @if($event->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($event->reviews as $review)
                                        <div class="border-b pb-4">
                                            <div class="flex items-start space-x-3">
                                                <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2">
                                                        <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                                        <div class="text-yellow-500">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                {{ $i <= $review->rating ? '★' : '☆' }}
                                                            @endfor
                                                        </div>
                                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    @if($review->review)
                                                        <p class="text-gray-700 mt-1">{{ $review->review }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No reviews yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Event Stats</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Registered</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $event->getTotalRegisteredCount() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Checked In</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $event->getTotalCheckedInCount() }}</p>
                            </div>
                            @if($event->reviews->count() > 0)
                                <div>
                                    <p class="text-sm text-gray-500">Average Rating</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($event->average_rating, 1) }} / 5.0</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

