<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new
#[Title('Feature Voting')]
#[Layout('layouts.app')]
class extends Component {

}; ?>

<div>
    <div class="w-full inline-flex items-start justify-center p-6 space-x-4">
        <a href="{{ url()->previous() }}" class="h-10 w-10 hidden md:block rounded-full bg-[#2D2F34] p-2 text-gray-500 hover:text-gray-400 transition">
            <x-heroicon-c-arrow-left-circle class="w-6 h-6" />
        </a>
        <div class="w-full md:w-2/3 lg:w-1/2 space-y-4">
            <div class="inline-flex w-full justify-between">
                <h1 class="text-2xl font-bold text-gray-200">{{ __('Feature Voting') }}</h1>
                <div class="inline-flex items-center space-x-1 rounded-lg px-2 py-1 bg-yellow-300/10">
                    <x-heroicon-s-archive-box class="w-5 h-5 text-yellow-300" />
                    <p class="text-yellow-300">You have <b>0 votes</b> left</p>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-3">
                hello
            </div>
        </div>
    </div>
</div>
