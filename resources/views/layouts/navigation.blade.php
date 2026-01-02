<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                @php($navUser = Auth::user())
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Events') }}
                    </x-nav-link>

                    @if ($navUser && $navUser->hasRole(\App\Models\User::ROLE_ADMIN))
                        <x-nav-link :href="route('admin.overview')" :active="request()->routeIs('admin.overview')">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif

                    @if ($navUser && $navUser->hasRole(\App\Models\User::ROLE_ORGANIZER))
                        <x-nav-link :href="route('organizer.hub')" :active="request()->routeIs('organizer.hub')">
                            {{ __('Organizer Hub') }}
                        </x-nav-link>
                    @endif

                    @if ($navUser && $navUser->hasRole(\App\Models\User::ROLE_ATTENDEE))
                        <x-nav-link :href="route('attendee.space')" :active="request()->routeIs('attendee.space')">
                            {{ __('My Events') }}
                        </x-nav-link>
                    @endif
                    
                    @auth
                        <x-nav-link :href="route('wishlist.index')" :active="request()->routeIs('wishlist.*')">
                            {{ __('Wishlist') }}
                        </x-nav-link>
                        <x-nav-link :href="route('messages.conversations')" :active="request()->routeIs('messages.*')">
                            {{ __('Messages') }}
                        </x-nav-link>
                    @endauth
                </div>
                
                <!-- Search Bar -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <form method="GET" action="{{ route('search') }}" class="flex">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search events..." 
                            class="rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 w-64">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 sm:space-x-4">
                {{-- Notifications dropdown --}}
                @auth
                    @php($unread = auth()->user()->unreadNotifications->count())
                    <div class="relative mr-4">
                        <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unread > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded">{{ $unread }}</span>
                            @endif
                        </a>
                    </div>
                @endauth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-full border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-600 shadow-sm transition hover:border-indigo-400 hover:text-indigo-600 focus:outline-none">
                            <img src="{{ $navUser->profile_photo_url }}" alt="{{ $navUser->name }}" class="h-8 w-8 rounded-full object-cover" />
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900">{{ $navUser->name }}</div>
                                <div class="text-xs uppercase tracking-wide text-indigo-500">{{ $navUser->role }}</div>
                            </div>
                            <svg class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $navUser->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $navUser->email }}</div>
                <div class="mt-1 inline-flex rounded-full bg-indigo-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-indigo-600">{{ $navUser->role }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
