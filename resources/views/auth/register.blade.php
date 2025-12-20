<x-guest-layout>
    <div class="w-full">
        <div class="grid gap-0 bg-white border border-gray-200 shadow-2xl rounded-3xl overflow-hidden md:grid-cols-5">
            <div class="relative hidden h-full flex-col justify-between p-10 text-white md:flex md:col-span-2 bg-gradient-to-br from-emerald-500 via-teal-500 to-indigo-600">
                <div>
                    <span class="inline-flex items-center rounded-full bg-white bg-opacity-10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-100">Create your space</span>
                    <h2 class="mt-6 text-3xl font-semibold leading-tight">Let every event feel unforgettable</h2>
                    <p class="mt-4 text-sm text-emerald-50">Launch incredible experiences with automated workflows, effortless collaboration, and insights that uncover what attendees love.</p>
                </div>

                <div class="mt-8 space-y-4 text-sm text-emerald-50">
                    <div class="rounded-2xl bg-white bg-opacity-10 p-4">
                        <p class="text-xs uppercase tracking-wide text-emerald-100">Starter perks</p>
                        <ul class="mt-3 space-y-2">
                            <li class="flex items-start"><span class="mt-1 h-2 w-2 rounded-full bg-white"></span><span class="ml-2">Plan unlimited events with collaborative task boards.</span></li>
                            <li class="flex items-start"><span class="mt-1 h-2 w-2 rounded-full bg-white"></span><span class="ml-2">Broadcast updates instantly across your vendor network.</span></li>
                            <li class="flex items-start"><span class="mt-1 h-2 w-2 rounded-full bg-white"></span><span class="ml-2">Forecast attendance with AI-powered projections.</span></li>
                        </ul>
                    </div>

                    <p class="text-xs">Need help onboarding your team? Our specialists migrate your existing workflows in under 48 hours.</p>
                </div>

                <div class="mt-8 text-sm">
                    <span class="text-emerald-100">Already a member?</span>
                    <a href="{{ route('login') }}" class="ml-2 inline-flex items-center font-semibold text-white hover:text-emerald-100">
                        Sign in instead
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 12H5"></path>
                            <path d="M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="md:col-span-3">
                <div class="p-8 sm:p-12" x-data="{ password: '', confirm: '' }">
                    <h1 class="text-3xl font-semibold text-gray-900">Create your account</h1>
                    <p class="mt-2 text-sm text-gray-500">Tell us a little about yourself and start orchestrating standout events in minutes.</p>

                    <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="name" class="text-sm font-medium text-gray-700">Full name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="Alex Morgan" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="email" class="text-sm font-medium text-gray-700">Work email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                            <p class="text-sm font-medium text-gray-700">How will you use NexEvent?</p>
                            <p class="mt-1 text-xs text-gray-500">Your role tailors dashboards, actions, and permissions automatically.</p>

                            @php(
                                $role = old('role', App\Models\User::ROLE_ATTENDEE)
                            )
                            @php(
                                $adminEmail = config('nexevent.admin_email')
                            )
                            @php(
                                $adminAvailable = filled($adminEmail) && App\Models\User::where('role', App\Models\User::ROLE_ADMIN)->doesntExist()
                            )

                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <label class="flex cursor-pointer flex-col gap-2 rounded-2xl border px-4 py-4 text-sm shadow-sm transition hover:border-indigo-400 hover:bg-white {{ $role === App\Models\User::ROLE_ATTENDEE ? 'border-indigo-500 bg-white ring-1 ring-indigo-100' : 'border-transparent bg-white' }}">
                                    <input type="radio" name="role" value="{{ App\Models\User::ROLE_ATTENDEE }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ $role === App\Models\User::ROLE_ATTENDEE ? 'checked' : '' }} />
                                    <span class="font-semibold text-gray-900">Attendee</span>
                                    <span class="text-xs text-gray-500">Register for events, download resources, and stay updated.</span>
                                </label>

                                <label class="flex cursor-pointer flex-col gap-2 rounded-2xl border px-4 py-4 text-sm shadow-sm transition hover:border-indigo-400 hover:bg-white {{ $role === App\Models\User::ROLE_ORGANIZER ? 'border-indigo-500 bg-white ring-1 ring-indigo-100' : 'border-transparent bg-white' }}">
                                    <input type="radio" name="role" value="{{ App\Models\User::ROLE_ORGANIZER }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ $role === App\Models\User::ROLE_ORGANIZER ? 'checked' : '' }} />
                                    <span class="font-semibold text-gray-900">Organizer</span>
                                    <span class="text-xs text-gray-500">Create experiences, manage schedules, and coordinate teams.</span>
                                </label>

                                @if ($adminAvailable)
                                    <label class="flex cursor-pointer flex-col gap-2 rounded-2xl border px-4 py-4 text-sm shadow-sm transition hover:border-indigo-400 hover:bg-white {{ $role === App\Models\User::ROLE_ADMIN ? 'border-indigo-500 bg-white ring-1 ring-indigo-100' : 'border-transparent bg-white' }}">
                                        <input type="radio" name="role" value="{{ App\Models\User::ROLE_ADMIN }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ $role === App\Models\User::ROLE_ADMIN ? 'checked' : '' }} />
                                        <span class="font-semibold text-gray-900">Administrator</span>
                                        <span class="text-xs text-gray-500">Full system oversight. Available only for {{ $adminEmail }}.</span>
                                    </label>
                                @endif
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" x-model="password" required autocomplete="new-password" placeholder="Minimum 8 characters" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                <div class="mt-3 flex items-center gap-2">
                                    <div class="relative h-1.5 flex-1 rounded-full bg-gray-200">
                                        <div class="absolute inset-y-0 left-0 rounded-full transition-all" :class="password.length === 0 ? 'bg-gray-200' : password.length < 8 ? 'bg-red-500' : password.length < 12 ? 'bg-yellow-500' : 'bg-green-500'" :style="`width: ${Math.min(password.length * 12.5, 100)}%`"></div>
                                    </div>
                                    <span class="text-xs font-medium" :class="password.length < 8 ? 'text-red-500' : password.length < 12 ? 'text-yellow-500' : 'text-green-600'" x-text="password.length === 0 ? 'Weak' : password.length < 8 ? 'Too short' : password.length < 12 ? 'Good' : 'Strong'"></span>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" x-model="confirm" required autocomplete="new-password" placeholder="Re-enter password" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                <p class="mt-3 text-xs font-semibold" :class="confirm.length === 0 ? 'text-gray-400' : confirm === password ? 'text-green-600' : 'text-orange-500'" x-text="confirm.length === 0 ? 'Confirm to ensure accuracy' : confirm === password ? 'Passwords match perfectly' : 'Passwords do not match yet'"></p>
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-start gap-3 text-sm text-gray-600">
                                <input type="checkbox" required class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span>By creating an account you agree to our <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Terms of Service</a> and <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Privacy Policy</a>.</span>
                            </label>

                            <button type="submit" class="flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white">
                                Launch my workspace
                                <svg class="ml-3 h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 grid gap-4 text-sm text-gray-500 sm:grid-cols-2">
                        <p>Invite your teammates once you are inside. You can manage roles and permissions from the control center anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
