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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="uses_gangtags_background min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">

            <!-- Logo -->
            <div class="sm:hidden">
                <a href="/" wire:navigate>
                    <x-application-logo class="w-48 h-auto fill-current text-gray-500" />
                </a>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div>
                <div class="grid grid-cols-5 max-w-2xl mt-6 md:shadow-md p-4 sm:p-0">
                    <div class="col-span-5 sm:col-span-3 px-4 sm:px-6 pb-6 py-3 bg-gray-800 border-y border-l border-stroke-primary overflow-hidden sm:rounded-y-lg sm:rounded-l-lg sm:rounded-r-none shadow-md md:shadow-none rounded-lg">
                        {{ $slot }}
                    </div>
                    <div class="hidden sm:flex flex-col justify-between items-center sm:col-span-2 rounded-r-lg overflow-clip bg-[#10050B]">
                        <img class="w-full h-auto aspect-auto" src="{{asset('assets/backgrounds/auth.png')}}" alt="Illustration" />
                        <div class="w-full inline-flex space-x-4 items-center justify-center p-2">
                            <a href="#" class="text-[#404040] hover:text-[#595959] text-sm font-semibold inline-flex items-center space-x-1">
                                <x-heroicon-o-globe-alt class="w-4 h-4" />
                                <span>Forums</span>
                            </a>
                            <a href="#" class="text-[#404040] hover:text-[#595959] fill-[#404040] hover:fill-[#595959] text-sm font-semibold inline-flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 16 16">
                                    <path d="M13.545 2.907a13.2 13.2 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.2 12.2 0 0 0-3.658 0 8 8 0 0 0-.412-.833.05.05 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.04.04 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032q.003.022.021.037a13.3 13.3 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019q.463-.63.818-1.329a.05.05 0 0 0-.01-.059l-.018-.011a9 9 0 0 1-1.248-.595.05.05 0 0 1-.02-.066l.015-.019q.127-.095.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.05.05 0 0 1 .053.007q.121.1.248.195a.05.05 0 0 1-.004.085 8 8 0 0 1-1.249.594.05.05 0 0 0-.03.03.05.05 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.2 13.2 0 0 0 4.001-2.02.05.05 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.03.03 0 0 0-.02-.019m-8.198 7.307c-.789 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612m5.316 0c-.788 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612"/>
                                </svg>
                                <span>Discord</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="inline-flex w-full items-center justify-between text-[#4A4A4A] px-4 sm:px-0">
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
