<x-guest-layout>
    <div class="w-full">
        <div class="grid gap-0 bg-white border border-gray-200 shadow-2xl rounded-3xl overflow-hidden md:grid-cols-5">
            <div class="relative hidden h-full flex-col justify-between p-10 text-white md:flex md:col-span-2 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700">
                <div>
                    <span class="inline-flex items-center rounded-full bg-white bg-opacity-10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-indigo-100">Welcome back</span>
                    <h2 class="mt-6 text-3xl font-semibold leading-tight">Your event command center</h2>
                    <p class="mt-4 text-sm text-indigo-100">Monitor registrations, manage vendors, and keep every attendee delighted—all from one dashboard.</p>
                </div>

                <ul class="mt-10 space-y-4 text-sm text-indigo-100">
                    <li class="flex items-start">
                        <span class="mt-1 h-2 w-2 rounded-full bg-white"></span>
                        <span class="ml-3">Real-time analytics to track registrations and revenue as they happen.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mt-1 h-2 w-2 rounded-full bg-white"></span>
                        <span class="ml-3">Smart task automation that keeps vendors and staff moving together.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mt-1 h-2 w-2 rounded-full bg-white"></span>
                        <span class="ml-3">Personalized attendee journeys that boost engagement and loyalty.</span>
                    </li>
                </ul>

                <div class="mt-10 text-sm">
                    <span class="text-indigo-100">New to the platform?</span>
                    <a href="{{ route('register') }}" class="ml-2 inline-flex items-center font-semibold text-white hover:text-indigo-100">
                        Create an account
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14"></path>
                            <path d="M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="md:col-span-3">
                <div class="p-8 sm:p-12" x-data="{ showPassword: false }">
                    <div class="flex items-baseline justify-between">
                        <div>
                            <h1 class="text-3xl font-semibold text-gray-900">Sign in</h1>
                            <p class="mt-2 text-sm text-gray-500">Enter your credentials to access the experiences you are crafting.</p>
                        </div>
                        <div class="hidden text-right text-xs font-medium text-gray-400 sm:block">
                        </div>
                    </div>

                    <x-auth-session-status class="mt-6" :status="session('status')" />

                    <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
        @csrf

        <div>
                            <label for="email" class="text-sm font-medium text-gray-700">Email address</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
        </div>

                        <div>
                            <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-2 relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full rounded-2xl border border-gray-200 px-4 py-3 pr-24 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-500 focus:outline-none">
                                    <svg x-show="!showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.963 9.963 0 012.307-4.172m2.438-1.992A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7-.27.862-.648 1.68-1.118 2.43M15 12a3 3 0 00-3-3" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                    </svg>
                                    <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2">Keep me signed in</span>
            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                            @endif
        </div>

                        <button type="submit" class="flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white">
                            Sign in to dashboard
                            <svg class="ml-3 h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 12h14"></path>
                                <path d="M12 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>

                    <div class="mt-6 grid gap-4 text-sm text-gray-500 sm:grid-cols-2">
                        <p>Having trouble signing in? Reach out to us directly at <span class="font-semibold text-gray-700">admin@nexevent.org</span>.</p>
                        <!-- <p class="text-sm">No account yet? <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Create one in a minute</a>.</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
