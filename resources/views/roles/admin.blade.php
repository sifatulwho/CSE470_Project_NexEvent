<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900">Admin Control Room</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-indigo-100 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-indigo-700">System oversight</h3>
                <p class="mt-2 text-sm text-gray-600">This area is reserved for administrators. From here you can manage global settings, review analytics, and assign roles to team members.</p>

                <ul class="mt-6 space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>Monitor platform health, user growth, and storage usage.</li>
                    <li class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>Approve new organizers and adjust event capacity rules.</li>
                    <li class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>Define global announcements that are visible to every workspace.</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>

