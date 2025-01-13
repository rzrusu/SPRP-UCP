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
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{$head ?? ''}}
    </head>
    <body class="font-sans antialiased">

        @if(!Auth::user()->hasTakenQuiz())
            <x-modal-container :closeable="false">
                <livewire:quiz.quizmodal />
            </x-modal-container>
        @endif

        @if(!Auth::user()->account_email && Auth::user()->account_quiz_status == 'passed')
            <x-modal-container :closeable="false">
                <livewire:components.add-email />
            </x-modal-container>
        @endif

        <div class="uses_gangtags_background min-h-screen bg-gray-900">
            <livewire:layout.navigation />

            <div class="bg-gray-800 max-w-7xl rounded-lg border border-stroke-primary mt-5 mx-2 lg:mx-auto overflow-clip">
                <div class="mx-auto">
                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
            <div class="max-w-7xl mx-auto w-full text-[#4A4A4A] px-2 lg:px-0">
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
        {!!  $js ?? '' !!}
    </body>
</html>
