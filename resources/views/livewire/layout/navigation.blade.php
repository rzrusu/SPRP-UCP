<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use App\Helpers\SampQueryAPI;

new class extends Component
{

    public int $online;

    public function mount(): void
    {
        $this->online = SampQueryAPI::getServerPlayerCount();
    }
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }


}; ?>

<nav x-data="{ open: false }">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto py-4 w-full px-4 lg:px-0">
        <div class="flex justify-between h-auto">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-24 w-auto fill-current text-gray-800 my-2" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            {{-- TODO: Connect player count to an api, maybe openmps if not our own. --}}

            <div class="hidden lg:flex sm:items-center sm:ms-6 space-x-4 w-fit">
                <a href="samp://{{config('app.server_ip')}}:{{config('app.server_ip_port')}}" class="inline-flex items-center space-x-2">
                    @if($online != -1)
                        <div class="h-3 w-3 rounded-full bg-green-600"></div>
                        <span class="text-green-600 font-semibold">{{$online}} Players Online</span>
                    @else
                        <div class="h-3 w-3 rounded-full bg-red-600"></div>
                        <span class="text-red-600 font-semibold">Server Offline</span>
                    @endif
                </a>
                <a href="samp://{{config('app.server_ip')}}:{{config('app.server_ip_port')}}" class="py-2 px-4 h-10 rounded-full inline-flex items-center justify-center bg-button-primary text-white font-semibold hover:bg-button-primary-hover transition">
                   Play Now
                </a>
                <button title="Logout" wire:click="logout" class="text-start h-10 w-10 rounded-full inline-flex items-center justify-center bg-gray-800 hover:bg-gray-700 text-[#787878] hover:text-[#C2C2C2] transition">
                        <x-heroicon-m-arrow-right-start-on-rectangle class="h-6 w-6" />
                </button>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="hidden bg-gray-800 max-w-7xl mx-auto px-5 rounded-lg border border-stroke-primary space-x-8 lg:flex">
        <x-nav-link :href="route('dashboard')" :active="request()->is('dashboard') || request()->is('dashboard/*')" wire:navigate>
            <x-heroicon-m-cube-transparent class="h-6 w-6" />
            <span>{{ __('Dashboard') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('characters')" :active="request()->is('characters') || request()->is('characters/*')" wire:navigate>
            <x-heroicon-m-user-group class="h-6 w-6" />
            <span>{{ __('Characters') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('adminrecord')" :active="request()->is('adminrecord') || request()->routeIs('adminrecord/*')" wire:navigate>
            <x-heroicon-m-scale class="h-6 w-6" />
            <span>{{ __('Admin Record') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('connections')" :active="request()->is('help') || request()->routeIs('help/*')" wire:navigate>
            <x-heroicon-m-ticket class="h-6 w-6" />
            <span>{{ __('Help') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('marketplace/*')" wire:navigate>
            <x-heroicon-m-banknotes class="h-6 w-6" />
            <span>{{ __('Marketplace') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('premium/*')" wire:navigate>
            <x-heroicon-m-star class="h-6 w-6" />
            <span>{{ __('Premium') }}</span>
        </x-nav-link>
        <!-- Settings Dropdown -->
        <div class="hidden lg:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <x-heroicon-m-bars-3 class="h-6 w-6 text-[#787878] hover:text-[#C2C2C2] transition" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('account')" wire:navigate>
                        {{ __('Account') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('connections')" wire:navigate>
                        {{ __('Connections') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('map')">
                        {{ __('Map') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('updates')" wire:navigate>
                        {{ __('Updates') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-gray-800 mx-2 rounded-lg border border-stroke-primary">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->is('dashboard') || request()->is('dashboard/*')" wire:navigate>
                <x-heroicon-m-cube-transparent class="h-6 w-6" />
                <span>{{ __('Dashboard') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('characters')" :active="request()->is('characters') || request()->is('characters/*')" wire:navigate>
                <x-heroicon-m-user-group class="h-6 w-6" />
                <span>{{ __('Characters') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('adminrecord')" :active="request()->is('adminrecord') || request()->routeIs('adminrecord/*')" wire:navigate>
                <x-heroicon-m-scale class="h-6 w-6" />
                <span>{{ __('Admin Record') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('connections')" :active="request()->is('help') || request()->routeIs('help/*')" wire:navigate>
                <x-heroicon-m-ticket class="h-6 w-6" />
                <span>{{ __('Help') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('marketplace/*')" wire:navigate>
                <x-heroicon-m-banknotes class="h-6 w-6" />
                <span>{{ __('Marketplace') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('premium/*')" wire:navigate>
                <x-heroicon-m-star class="h-6 w-6" />
                <span>{{ __('Premium') }}</span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-gray-300" x-data="{{ json_encode(['name' => auth()->user()->account_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-400">Player</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('account')" wire:navigate>
                    {{ __('Account') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('connections')" wire:navigate>
                    {{ __('Connections') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('map')" wire:navigate>
                    {{ __('Map') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('updates')" wire:navigate>
                    {{ __('Updates') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
