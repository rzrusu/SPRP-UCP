<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="theme-color" content="#1E1F22">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        <div class="uses_gangtags_background min-h-screen bg-gray-900">
            <div class=" max-w-2xl rounded-lg lg:mx-auto pt-16">
                <header class="space-y-2 inline-flex items-center justify-between w-full mx-auto">
                            <div class="space-y-4">
                                @if(Request::is('updates/*'))
                                    <a href="{{ route('updates') }}" class="text-gray-500 hover:text-gray-400 transition text-sm font-medium inline-flex items-center space-x-1" wire:navigate>
                                        <x-heroicon-c-arrow-left class="w-5 h-5 inline-block align-middle" />
                                        <span>Return to Updates</span>
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-400 transition text-sm font-medium inline-flex items-center space-x-1" wire:navigate>
                                        <x-heroicon-c-arrow-left class="w-5 h-5 inline-block align-middle" />
                                        <span>Return to UCP</span>
                                    </a>
                                @endif
                                <div class="space-y-1">
                                    <h1 class="text-white text-2xl font-bold">Release Notes</h1>
                                    <p class="text-gray-300">What's new in {{ config('app.name', 'Laravel') }}?</p>
                                </div>
                            </div>
                    <x-application-logo class="block h-28 w-auto fill-current text-gray-800" />
                </header>
                <main>
                    {{ $slot }}
                </main>
            </div>
            <div class="max-w-2xl mx-auto w-full text-[#4A4A4A] px-2 lg:px-0 pb-4">
                <div class="inline-flex items-center justify-between w-full">
                    <p class="text-sm mt-4">
                        &copy; 2024 {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </p>
                    <p class="text-sm mt-4">
                        Version {{ config('app.version', '') }}
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
