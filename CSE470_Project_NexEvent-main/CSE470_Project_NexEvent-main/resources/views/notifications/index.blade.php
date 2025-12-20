<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Your Notifications</h3>
                    <form method="POST" action="{{ route('notifications.readAll') }}">
                        @csrf
                        <button class="text-sm text-indigo-600">Mark all as read</button>
                    </form>
                </div>

                @if($notifications->count())
                    <ul class="space-y-4">
                        @foreach($notifications as $n)
                            <li class="p-4 rounded border @if(!$n->read_at) bg-indigo-50 border-indigo-100 @else bg-gray-50 border-gray-100 @endif">
                                <div class="flex justify-between">
                                    <div>
                                        <div class="text-sm text-gray-800">{!! $n->data['message'] ?? ( $n->data['event_id'] ? 'Event update' : 'Notification' ) !!}</div>
                                        <div class="text-xs text-gray-500">{{ $n->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if(!$n->read_at)
                                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                                @csrf
                                                <button class="text-sm text-indigo-600">Mark read</button>
                                            </form>
                                        @else
                                            <div class="text-xs text-gray-400">Read</div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6">{{ $notifications->links() }}</div>
                @else
                    <p class="text-gray-500">You have no notifications.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
