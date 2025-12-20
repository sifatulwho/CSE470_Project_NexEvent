<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900">Organizer Workspace</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-emerald-600">Plan and orchestrate</h3>
                <p class="mt-2 text-sm text-gray-600">Welcome to your planning hub. Create new experiences, monitor pipeline progress, and coordinate vendors from one place.</p>

                <ul class="mt-6 space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-500"></span>Launch events with detailed schedules, speaker profiles, and resource uploads.</li>
                    <li class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-500"></span>Track registrations in real time and export attendee manifests for on-site check-ins.</li>
                    <li class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-500"></span>Collaborate with your team through shared tasks, reminders, and vendor handoffs.</li>
                </ul>
                <div class="mt-6">
                    <a href="{{ route('speakers.index') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded">Manage Speakers</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

