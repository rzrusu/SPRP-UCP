<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new
#[Title('Profile')]
#[Layout('layouts.app')]
class extends Component {

    public $last_login;
    public $most_active_character;
    public $total_time_played;


    public function mount()
    {

        $this->last_login = auth()->user()->characters->sortByDesc('player_logindate')->first()->player_logindate;
        $this->most_active_character = auth()->user()->characters->sortByDesc('player_hours')->first();
        $this->total_time_played = auth()->user()->characters->sum('player_hours');

        if($this->last_login) {
            $this->last_login = \Carbon\Carbon::createFromTimestamp($this->last_login)->diffForHumans();
        }

        if($this->most_active_character) {
            $this->most_active_character = str_replace('_', ' ', $this->most_active_character->player_name);
        }

    }
}; ?>

<div>
    <div class="p-6">
        <header>
            <h2 class="font-semibold text-2xl text-gray-200">
                {{ __('My Account') }}
            </h2>
            <p class="text-gray-400">Manage your account’s details.</p>
        </header>

        <div class="py-6">
            <h2 class="text-lg font-medium text-gray-200">
                {{ __('Account Statistics') }}
            </h2>
            <div
                class="grid grid-cols-1 md:grid-cols-6 gap-0 border border-stroke-primary rounded-lg overflow-clip mt-2">
                <div class="md:col-span-3 p-4 inline-flex items-center bg-gray-700 space-x-3 ">
                    <img src="{{ asset('assets/no_avatar.jpg') }}" alt="avatar"
                         class="h-12 w-12 rounded-full bg-gray-300">
                    <div class="flex flex-col">
                        <span class="text-gray-200 font-semibold">{{ Auth::user()->account_name }}</span>
                        @if(Auth::user()->is_admin())
                            <div class="text-orange-400 inline-flex items-center space-x-1">
                                    <x-heroicon-m-shield-check class="h-4 w-4"/>
                                    <span class="text-sm">Administrator</span>
                            </div>
                        @else
                            <div class="text-gray-400 inline-flex items-center space-x-1">
                                <x-heroicon-m-check-badge class="h-4 w-4"/>
                                <span class="text-sm">Player</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div
                    class="inline-flex items-center justify-center border-r border-stroke-primary bg-gray-900 py-2 md:py-0">
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-gray-200 font-semibold">{{$this->total_time_played ? : 'Unknown'}} Hours</span>
                        <span class="text-gray-400 text-sm text-center">Total Time Played</span>
                    </div>
                </div>
                <div
                    class="inline-flex items-center justify-center border-r border-stroke-primary bg-gray-900 py-2 md:py-0">
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-gray-200 font-semibold">{{$this->most_active_character ? : 'Unknown'}}</span>
                        <span class="text-gray-400 text-sm text-center">Most Active Character</span>
                    </div>
                </div>
                <div class="inline-flex items-center justify-center bg-gray-900 py-2 md:py-0">
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-gray-200 font-semibold">{{$last_login ?  : 'Unknown'}}</span>
                        <span class="text-gray-400 text-sm text-center">Last Login</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col space-y-8">
                    <livewire:profile.update-profile-information-form/>
                    <livewire:profile.link-discord-account/>
                </div>
                <div>
                    <livewire:profile.update-password-form/>
                </div>

            </div>
            {{--
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
            --}}
        </div>
    </div>
</div>
