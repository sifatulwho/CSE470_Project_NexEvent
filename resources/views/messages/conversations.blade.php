<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Conversations</h3>
                    @if($conversations && $conversations->count() > 0)
                        <div class="space-y-2">
                            @foreach($conversations as $userId => $messages)
                                @if($messages->count() > 0)
                                    @php($firstMessage = $messages->first())
                                    @php($otherUser = $firstMessage->sender_id === auth()->id() ? $firstMessage->receiver : $firstMessage->sender)
                                    @if($otherUser)
                                        <a href="{{ route('messages.conversation', $otherUser) }}" class="block p-4 border rounded-lg hover:bg-gray-50">
                                            <div class="flex items-center space-x-4">
                                                <img src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900">{{ $otherUser->name }}</h4>
                                                    <p class="text-sm text-gray-600 truncate">{{ $firstMessage->message }}</p>
                                                    <p class="text-xs text-gray-500">{{ $firstMessage->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if(!$firstMessage->is_read && $firstMessage->receiver_id === auth()->id())
                                                    <span class="w-3 h-3 bg-indigo-600 rounded-full"></span>
                                                @endif
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No conversations yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

