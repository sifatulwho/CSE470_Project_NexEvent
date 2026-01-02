<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Resource - {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('events.resources.index', $event) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Resources</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('events.resources.store', $event) }}" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (optional)</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                        <input type="file" name="file" id="file" required
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">Maximum file size: 10MB</p>
                        @error('file')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('events.resources.index', $event) }}" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                            Upload Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

