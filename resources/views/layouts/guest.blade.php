<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Assets -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js" defer></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="min-h-screen flex flex-col">
            <header class="py-8">
                <a href="/" class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-gray-200 bg-white shadow-lg">
                    <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                </a>
            </header>

            <main class="flex-1 pb-16">
                <div class="relative mx-auto flex w-full max-w-6xl items-center justify-center px-4 sm:px-6">
                    {{ $slot }}
                </div>
            </main>

            <footer class="py-6 text-center text-xs text-gray-500">
                Crafted to keep your events effortlessly on track.
            </footer>
        </div>
    </body>
</html>
