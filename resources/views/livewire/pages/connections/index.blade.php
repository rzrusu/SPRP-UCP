<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new
#[Title('Connections')]
#[Layout('layouts.app')]
class extends Component {

    public $show_ip = false;
    public $connections;
    public $connection_data;

    public function getConnectionData()
    {

        foreach($this->connections as $conn) {

            // Check if we already have the data so we don't have to spam the api
            if (isset($this->connection_data[$conn->e_session_ip])) {

                if ($this->connection_data[$conn->e_session_ip]['location'] != 'Loading...')
                    continue;
            }

            try {
                $api_data = json_decode(file_get_contents('http://ip-api.com/json/'.$conn->e_session_ip));

                if (!$api_data || $api_data->status == 'fail') {
                    $this->connection_data[$conn->e_session_ip] = [
                        'location' => 'Unknown Location',
                        'picture' => 'assets/4x3-xx.svg',
                        'live' => false,
                    ];
                    continue;
                }
                // select only country and coutnry code
                $this->connection_data[$conn->e_session_ip] = [
                    'location' => $api_data->city.', '.$api_data->country,
                    'picture' => 'vendor/blade-country-flags/4x3-'.strtolower($api_data->countryCode).'.svg',
                    'live' => false,
                ];

            } catch (\Exception $e) { // failed to get data, set this one to unknown
                $api_data = null;
                $this->connection_data[$conn->connection_ip] = [
                    'location' => 'Unknown Location',
                    'picture' => 'assets/4x3-xx.svg',
                    'live' => false,
                ];
            }
        }
    }

    public function getConnectionTime($connection_timestamp)
    {
        // check if connection is within  the last minute
        if ($connection_timestamp > now()->subMinute()->timestamp) {
            return 'Just now';
        } else {
            return \Carbon\Carbon::createFromTimestamp($connection_timestamp)->diffForHumans();
        }
    }

    public function mount()
    {
        $this->connections = auth()->user()->connections;
        $this->connections = $this->connections->sortByDesc('e_session_unix_store')->take(10);

        foreach ($this->connections as $connection) {
            $this->connection_data[$connection->e_session_ip] = [
                'location' => 'Loading...',
                'picture' => 'assets/4x3-xx.svg',
                'live' => false,
            ];
        }
    }

}; ?>

<div>
    <div class="w-full inline-flex items-start justify-center p-6 space-x-4">
        <a href="{{ url()->previous() }}" class="h-10 w-10 hidden md:block rounded-full bg-[#2D2F34] p-2 text-gray-500 hover:text-gray-400 transition">
            <x-heroicon-c-arrow-left-circle class="w-6 h-6" />
        </a>
        <div class="w-full md:w-2/3 lg:w-1/2 space-y-4" x-data="{ showIp: false }">
            <div class="inline-flex w-full justify-between">
                <h1 class="text-2xl font-bold text-gray-200">{{ __('Connections') }}</h1>
                <label class="inline-flex items-center cursor-pointer" x-tooltip="tooltip" x-data="{ tooltip: 'Warning! This may show your general location. Make sure your screen isn\'t visible to others.' }">
                    <input @click="showIp = !showIp" type="checkbox" value="" class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Show Locations?</span>
                </label>
            </div>
            <div class="grid grid-cols-1 gap-3" x-data="{ safety: 'For safety, this field is hidden unless you enable it above.' }">

                @foreach($connections as $connection)
                    @if($connection_data[$connection->e_session_ip]['live'])
                        <div class="rounded-lg inline-flex items-start">
                            LIVE MF
                            <div class="w-14 h-14 rounded-l-lg bg-container-light inline-flex items-center justify-center">
                                <x-heroicon-o-server-stack class="w-6 h-6 text-[#4D71D0]" />
                            </div>
                            <div class="w-full inline-flex items-center bg-container-primary px-3 rounded-r-lg h-14 justify-between">
                                <div class="inline-flex items-center">
                                    <div wire:init="getConnectionData" class="mr-2">
                                        <template x-if="!showIp">
                                            <span class="text-white font-semibold">Hidden Location</span>
                                        </template>
                                        <div class="h-8 w-fit inline-flex items-center" x-show="showIp">
                                            <img class="rounded-sm mr-2" src="{{ asset(strtolower($connection_data[$connection->e_session_ip]['picture']))}}" width="16"/>
                                            <span class="text-white font-semibold">{{$connection_data[$connection->e_session_ip]['location']}}</span>
                                        </div>
                                    </div>
                                    <span class="text-gray-400 text-sm mr-2">·</span>
                                    <span class="text-gray-400 text-sm">{{$this->getConnectionTime($connection->e_session_unix_store)}}</span>
                                </div>
                                <span class="text-gray-600 text-sm font-semibold">{{$connection->e_session_web ? 'UCP Connection' : 'In-Game Connection'}}</span>
                            </div>
                        </div>
                    @else
                        @continue
                    @endif
                @endforeach

                @forelse($connections as $connection)

                    @if($connection_data[$connection->e_session_ip]['live'])
                        @continue
                    @endif

                    <div class="rounded-lg inline-flex items-start">
                        @switch($connection->e_session_web)
                            @case(0)
                                <div class="w-14 h-14 rounded-l-lg bg-container-light inline-flex items-center justify-center">
                                    <x-heroicon-o-server-stack class="w-6 h-6 text-[#4D71D0]" />
                                </div>
                            @break
                            @default
                                <div class="w-14 h-14 rounded-l-lg bg-container-light inline-flex items-center justify-center">
                                    <x-heroicon-o-globe-alt class="w-6 h-6 text-[#EFB358]" />
                                </div>
                            @break
                        @endswitch
                        <div class="w-full inline-flex items-center bg-container-primary px-3 rounded-r-lg h-14 justify-between">
                            <div class="inline-flex items-center">
                                <div wire:init="getConnectionData" class="mr-2">
                                    <template x-if="!showIp">
                                        <span class="text-white font-semibold">Hidden Location</span>
                                    </template>
                                    <div class="h-8 w-fit inline-flex items-center" x-show="showIp">
                                        <img class="rounded-sm mr-2" src="{{ asset(strtolower($connection_data[$connection->e_session_ip]['picture']))}}" width="16"/>
                                        <span class="text-white font-semibold">{{$connection_data[$connection->e_session_ip]['location']}}</span>
                                    </div>
                                </div>
                                <span class="text-gray-400 text-sm mr-2">·</span>
                                <span class="text-gray-400 text-sm">{{$this->getConnectionTime($connection->e_session_unix_store)}}</span>
                            </div>
                            <span class="text-gray-600 text-sm font-semibold">{{$connection->e_session_web ? 'UCP Connection' : 'In-Game Connection'}}</span>
                        </div>
                    </div>
                    @empty
                    <p class="w-full text-gray-600">No connections for this account found.</p>
                @endforelse
            </div>
            @if($connections->count() > 0)
                <div class="w-full">
                    <p class="text-right w-full text-gray-600 text-sm">Showing your last 10 connections</p>
                </div>
                <div class="mt-2">
                    <h2 class="text-lg font-medium text-gray-200">
                        {{ __('Don\'t recognize a connection?') }}
                    </h2>

                    <p class="mt-1 text-gray-400">
                        {{ __("If you think someone else logged into your account, immediately change your password and contact a Staff Member In-Game or on our Discord Server.") }}
                    </p>

                    <x-primary-interactive-button class="mt-4" href="{{route('account')}}">
                    <span>
                            {{ __('Change Password') }}
                        </span>
                    </x-primary-interactive-button>
                </div>
            @endif
        </div>
    </div>
</div>
