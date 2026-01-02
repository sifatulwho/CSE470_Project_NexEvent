<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Certificate - {{ $certificate->event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-4 border-gray-800 p-12">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Certificate of Participation</h1>
                    <div class="border-b-2 border-gray-800 my-8"></div>
                    <p class="text-xl text-gray-700 mb-2">This certifies that</p>
                    <h2 class="text-3xl font-bold text-indigo-600 mb-2">{{ $certificate->user->name }}</h2>
                    <p class="text-xl text-gray-700 mb-8">has successfully participated in</p>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">{{ $certificate->event->title }}</h3>
                    <p class="text-lg text-gray-600 mb-2">
                        {{ $certificate->event->start_date->format('F d, Y') }}
                        @if($certificate->event->location)
                            at {{ $certificate->event->location }}
                        @endif
                    </p>
                    <div class="border-b-2 border-gray-800 my-8"></div>
                    <p class="text-sm text-gray-600 mb-4">Certificate Number: {{ $certificate->certificate_number }}</p>
                    <p class="text-sm text-gray-500">Issued on {{ $certificate->issued_at->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="mt-6 text-center">
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-4">
                    Print Certificate
                </button>
                <a href="{{ route('events.show', $certificate->event) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Event
                </a>
            </div>

            <style>
                @media print {
                    nav, .no-print {
                        display: none !important;
                    }
                }
            </style>
        </div>
    </div>
</x-app-layout>

