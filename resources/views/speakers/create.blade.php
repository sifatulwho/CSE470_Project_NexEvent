<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Speaker</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('speakers.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm">Name</label>
                        <input name="name" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Title</label>
                        <input name="title" class="mt-1 w-full border rounded px-3 py-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Company</label>
                        <input name="company" class="mt-1 w-full border rounded px-3 py-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Bio</label>
                        <textarea name="bio" class="mt-1 w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Photo URL</label>
                        <input name="photo_url" class="mt-1 w-full border rounded px-3 py-2" />
                    </div>

                    <div class="flex space-x-2">
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Create</button>
                        <a href="{{ route('speakers.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
