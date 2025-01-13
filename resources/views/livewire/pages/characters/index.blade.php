<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\ReleaseNote;

new
#[Layout('layouts.app')]
#[Title('Characters')]
class extends Component {

    public $characters;

    public $has_premium = false;
    public $charCount = 0;


    public function mount()
    {
        $this->characters = auth()->user()->characters;
        $this->charCount = $this->characters->count();

    }

}; ?>

<div>
        <div class="w-full inline-flex items-start justify-center p-6 space-x-4">
            <a href="{{ url()->previous() }}" class="h-10 w-10 hidden md:block rounded-full bg-[#2D2F34] p-2 text-gray-500 hover:text-gray-400 transition">
                <x-heroicon-c-arrow-left-circle class="w-6 h-6" />
            </a>
            <div class="w-full md:w-2/3 lg:w-1/2 space-y-4">
                <h1 class="text-2xl font-bold text-gray-200">{{ __('My Characters') }}</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($characters as $character)
                        <x-characters.character-slot :character="$character" :locked="false" :has-premium="false" />
                    @endforeach

                    @for($i = $charCount; $i < 2; $i++)
                        <x-characters.character-slot :character="null" :locked="true" :has-premium="$has_premium" />
                    @endfor

                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-200">
                        {{ __('Need another character?') }}
                    </h2>

                    <p class="mt-1 text-gray-400">
                        {{ __("You can unlock another slot by purchasing a Premium subscription or by purchasing the extra slot separately.") }}
                    </p>

                    <x-primary-interactive-button class="mt-4" href="#">
                        <x-heroicon-m-star class="w-5 h-5 mr-2" />
                        <span>
                            {{ __('Premium Shop') }}
                        </span>
                    </x-primary-interactive-button>
                </div>
            </div>
        </div>
</div>
