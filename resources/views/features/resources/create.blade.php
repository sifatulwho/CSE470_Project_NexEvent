<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Upload Resource</h2>
                <p class="mt-1 text-sm text-gray-500">Add materials for {{ $event->title }}</p>
            </div>
            <a href="{{ route('features.events.show', $event) }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                ← Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg">
                <form action="{{ route('features.resources.store', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                                Resource Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g., Presentation Slides, Event Recording">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Optional description of the resource">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-900 mb-2">
                                File <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="file" id="file" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.mov,.mp3,.wav">
                            <p class="mt-2 text-xs text-gray-500">Maximum file size: 10MB. Supported formats: PDF, DOC, PPT, Images, Videos, Audio</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file_type" class="block text-sm font-semibold text-gray-900 mb-2">
                                Resource Type
                            </label>
                            <select name="file_type" id="file_type"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Auto-detect</option>
                                <option value="slides" {{ old('file_type') === 'slides' ? 'selected' : '' }}>Slides</option>
                                <option value="document" {{ old('file_type') === 'document' ? 'selected' : '' }}>Document</option>
                                <option value="media" {{ old('file_type') === 'media' ? 'selected' : '' }}>Media</option>
                                <option value="other" {{ old('file_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('file_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('features.events.show', $event) }}" class="rounded-lg border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                                Upload Resource
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

