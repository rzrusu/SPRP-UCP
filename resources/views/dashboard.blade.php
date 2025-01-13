<x-app-layout>

    <x-slot:head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js" data-spa="auto" data-navigate-track></script>
    </x-slot:head>

    <div class="min-w-full h-auto sm:h-72 lg:h-80 md:uses_dashboard_background flex flex-col items-center space-y-8 sm:py-16 p-6 sm:px-0">
        <div class="flex flex-col items-center w-full space-y-2">
            <h1 class="text-white text-3xl font-bold text-center">Welcome back, {{Auth::user()->account_name}}</h1>
            @php $onlinePlayers = \App\Helpers\SampQueryAPI::getServerPlayerCount(); @endphp
            <p class="text-gray-400 text-center w-full sm:w-1/2 md:w-1/3 lg:w-1/4">The streetz are waiting for you. Hop in and join the other {{$onlinePlayers != -1 ? $onlinePlayers : '' }} players online.</p>
        </div>
        <div class="sm:space-x-2 space-y-2 md:space-y-0">
            <x-primary-interactive-button href="samp://{{config('app.server_ip')}}:{{config('app.server_ip_port')}}" class="w-full sm:w-fit">
                <x-heroicon-m-play class="w-6 h-6 mr-2"/>
                Play Now <span class="md:hidden ml-1">(PC Only)</span>
            </x-primary-interactive-button>
            <x-secondary-interactive-button href="https://www.sa-mp.mp/downloads/" class="w-full sm:w-fit">
                <img src="{{asset('assets/logos/samp.jpg')}}" class="w-6 h-6 mr-2" alt="GTA San Andreas Multiplayer Icon"/>
                Get SA:MP
            </x-secondary-interactive-button>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 p-6">
        <div class="md:col-span-4 space-y-8">
            <livewire:components.dashboard-stats />
            <div>
                <h2 class="text-lg font-medium text-gray-400">
                    {{ __('Discord') }}
                </h2>
                <a target="_blank" href="{{config('app.discord_url') ?? ''}}" class="w-full p-3 bg-[#404EED] rounded-lg inline-flex items-center space-x-2 mt-4">
                    <img src="{{ asset('assets/logos/discord.svg') }}" alt="Discord Logo" class="h-12 w-12">
                    <span class="text-gray-200 font-semibold">Join our Discord Server</span>
                </a>
            </div>
        </div>
        <div class="md:col-span-8 w-full">
            <livewire:components.dashboard-announcements/>
            <div class="w-full">
                <h2 class="text-lg font-medium text-gray-400">
                    {{ __('Player Count History') }}
                </h2>
                <div class="w-full">
                    <canvas id="online_players_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <x-slot:js>
        <script data-navigate-once="true">
            document.addEventListener('livewire:navigated', (event) => {

                const ctx = document.getElementById('online_players_chart');

                if (!ctx) {
                    return;
                }

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($xaxis->toArray()) !!},
                        datasets: [{
                            label: 'Number of Online Players',
                            data: {!! json_encode($yaxis->toArray()) !!},
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                               reverse: true
                            }
                        }
                    }
                });
         });
        </script>
        </x-slot:js>
</x-app-layout>
