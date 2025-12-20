<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Session: {{ $session->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('events.sessions.update', [$event, $session]) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input name="title" value="{{ $session->title }}" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 w-full border rounded px-3 py-2">{{ $session->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm">Start Time (Y-m-d H:i)</label>
                            <input name="start_time" value="{{ $session->start_time->format('Y-m-d H:i') }}" required class="mt-1 w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm">End Time (Y-m-d H:i)</label>
                            <input name="end_time" value="{{ $session->end_time->format('Y-m-d H:i') }}" required class="mt-1 w-full border rounded px-3 py-2" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Location</label>
                        <input name="location" value="{{ $session->location }}" class="mt-1 w-full border rounded px-3 py-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Speakers (select multiple)</label>
                        <select name="speakers[]" multiple class="mt-1 w-full border rounded px-3 py-2">
                            @foreach($speakers as $s)
                                <option value="{{ $s->id }}" @if($session->speakers->contains($s->id)) selected @endif>{{ $s->name }} @if($s->company) — {{ $s->company }} @endif</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update Session</button>
                        <a href="{{ route('events.show', $event) }}" class="px-4 py-2 border rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
