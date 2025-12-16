<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">Speakers</h2>
            <a href="{{ route('speakers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Add Speaker</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                @if($speakers->count())
                    <table class="w-full text-sm">
                        <thead class="text-left text-gray-600">
                            <tr><th>Name</th><th>Title</th><th>Company</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach($speakers as $s)
                                <tr class="border-t">
                                    <td class="py-3">{{ $s->name }}</td>
                                    <td class="py-3">{{ $s->title }}</td>
                                    <td class="py-3">{{ $s->company }}</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('speakers.edit', $s) }}" class="text-indigo-600 mr-3">Edit</a>
                                        <form method="POST" action="{{ route('speakers.destroy', $s) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600" onclick="return confirm('Delete speaker?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $speakers->links() }}</div>
                @else
                    <p class="text-gray-500">No speakers yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
