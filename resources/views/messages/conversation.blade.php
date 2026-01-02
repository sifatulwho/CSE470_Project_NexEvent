<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('messages.conversations') }}" class="text-indigo-600 hover:text-indigo-800">← Back</a>
            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Messages -->
                    <div class="space-y-4 mb-6" style="max-height: 500px; overflow-y: auto;">
                        @if($messages && $messages->count() > 0)
                            @foreach($messages as $message)
                                @if($message->sender)
                                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-xs lg:max-w-md">
                                            <div class="flex items-start space-x-2 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                                <img src="{{ $message->sender->profile_photo_url }}" alt="{{ $message->sender->name }}" class="w-8 h-8 rounded-full">
                                                <div class="{{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900' }} rounded-lg px-4 py-2">
                                                    <p class="text-sm">{{ $message->message }}</p>
                                                    <p class="text-xs mt-1 opacity-70">{{ $message->created_at->format('H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center py-4">No messages yet. Start the conversation!</p>
                        @endif
                    </div>

                    <!-- Message Form -->
                    <form method="POST" action="{{ route('messages.store-individual', $user) }}" class="border-t pt-4">
                        @csrf
                        <div class="flex space-x-2">
                            <input type="text" name="message" required placeholder="Type a message..." 
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

