<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;

new class extends Component {

    public $update;
    public $key;

    public string $description;
    public string $content;

    public string $added;
    public string $removed;
    public string $changed;
    public string $fixed;

    // This method will render the markdown content for the update.
    public function renderMarkdown($inline = false): void
    {
        // If the update is inline, we will process all the Markdown and convert it to HTML.
        if ($inline) {
            $this->description = Str::of($this->update->description)->markdown();
            $this->added = Str::of($this->update->added)->markdown();
            $this->removed = Str::of($this->update->removed)->markdown();
            $this->changed = Str::of($this->update->changed)->markdown();
            $this->fixed = Str::of($this->update->fixed)->markdown();
            $this->content = Str::of($this->update->content)->markdown();
        } else {
            // If the update is not inline, we will only process only the description.
            $this->description = Str::of($this->update->description)->markdown();
        }
    }

    public function mount(): void
    {
        $this->renderMarkdown($this->update->inline);
    }

}; ?>

<div>
    @switch($update->inline)
        @case(0)
            <div>
                <div id="{{$update->slug}}"
                     class="bg-gray-800 rounded-t-lg border-x border-t border-stroke-primary @if($update->image) p-5 @else px-5 pt-5 pb-1 @endif space-y-3">
                    <div class="inline-flex items-center justify-between w-full">
                        <div>
                            <span class="bg-[#83B41C]/25 font-ibm text-[#83B41C] font-bold tracking-widest text-xs rounded-full py-0.5 px-2">FEATURE OVERVIEW</span>
                        </div>
                        <span
                            class="text-[#666666] text-sm text-semibold ">{{$update->author}} · {{$update->created_at->diffForHumans()}}</span>
                    </div>
                    <a href="{{route('update.view', $update->slug)}}" class="inline-flex items-center space-x-2"
                       wire:navigate>
                        <h2 class="text-white font-manrope text-2xl font-semibold">{{$update->title}}</h2>
                        <x-heroicon-m-arrow-right class="w-5 h-5 text-gray-400 inline-block align-middle"/>
                    </a>
                </div>
                @if($update->image)
                    <a href="{{route('update.view', $update->slug)}}" wire:navigate>
                        <img src="{{$update->image}}" alt="{{$update->title}}" class="w-full h-96 object-cover">
                    </a>
                @endif
                <div
                    class="bg-gray-800 rounded-b-lg border-x border-b border-stroke-primary @if($update->image) p-5 @else px-5 pb-5 pt-1 @endif space-y-4">
                    <div class="text-gray-400">
                        {!! $description !!}
                    </div>
                    <div class="inline-flex items-center justify-between w-full">
                        <a href="{{route('update.view', $update->slug)}}"
                           class="text-blue-400 hover:text-blue-300 transition" wire:navigate>View full post</a>
                        <a href="#"
                           class="inline-flex space-x-1 items-center text-gray-500 hover:text-gray-400 transition">
                            <x-heroicon-o-link class="w-4 h-4"/>
                            <span class="text-sm">Copy Link</span>
                        </a>
                    </div>
                </div>
            </div>
            @break
        @default
            <div x-data="{open: false}">
                <div
                    :class="open ? 'bg-gray-800 rounded-t-lg border-x border-t border-stroke-primary p-5 space-y-3' : 'bg-gray-800 rounded-lg border border-stroke-primary p-5 space-y-3'">
                    <div class="inline-flex items-center justify-between w-full">
                        <div>
                            <span
                                class="bg-orange-500/25 font-ibm text-orange-500 font-bold tracking-widest text-xs rounded-full py-0.5 px-2">GAME UPDATE</span>
                        </div>
                        <span
                            class="text-[#666666] text-sm text-semibold ">{{$update->author}} · {{$update->created_at->diffForHumans()}}</span>
                    </div>
                    <h2 class="text-white font-manrope text-2xl font-semibold">{{$update->title}}</h2>
                    <div class="text-gray-400">
                        {!! $description !!}
                    </div>
                    <div class="inline-flex items-center justify-between w-full">
                        <button x-show="!open" @click="open = ! open"
                                class="text-blue-400 hover:text-blue-300 transition">Show changelog
                        </button>
                        <button x-show="open" @click="open = ! open"
                                class="text-blue-400 hover:text-blue-300 transition">Hide changelog
                        </button>
                        <a href="#"
                           class="inline-flex space-x-1 items-center text-gray-500 hover:text-gray-400 transition">
                            <x-heroicon-o-link class="w-4 h-4"/>
                            <span class="text-sm">Copy Link</span>
                        </a>
                    </div>
                </div>
                <div x-show="open"
                     class="bg-[#18191B] border-x border-b rounded-b-lg border-stroke-primary p-5 space-y-3"
                     x-transition>
                    <div class="text-gray-400">
                        {!! $content !!}
                    </div>
                    @if($update->added)
                        <div>
                            <div class="text-green-500 inline-flex items-center space-x-1">
                                <x-heroicon-m-plus-circle class="w-5 h-5"/>
                                <h3 class="font-bold tracking-wide">ADDITIONS</h3>
                            </div>
                            <div class="text-gray-400 list-disc uses_discs ml-2">
                                {!! $added !!}
                            </div>
                        </div>
                    @endif
                    @if($update->changed)
                        <div>
                            <div class="text-blue-400 inline-flex items-center space-x-1">
                                <x-heroicon-m-pencil-square class="w-5 h-5"/>
                                <h3 class="font-bold tracking-wide">CHANGES</h3>
                            </div>
                            <div class="text-gray-400 list-disc uses_discs ml-2">
                                {!! $changed !!}
                            </div>
                        </div>
                    @endif
                    @if($update->fixed)
                        <div>
                            <div class="text-orange-500 inline-flex items-center space-x-1">
                                <x-heroicon-m-bug-ant class="w-5 h-5"/>
                                <h3 class="font-bold tracking-wide">FIXES</h3>
                            </div>
                            <div class="text-gray-400 list-disc uses_discs ml-2">
                                {!! $fixed !!}
                            </div>
                        </div>
                    @endif
                    @if($update->removed)
                        <div>
                            <div class="text-red-500 inline-flex items-center space-x-1">
                                <x-heroicon-m-trash class="w-5 h-5"/>
                                <h3 class="font-bold tracking-wide">REMOVED</h3>
                            </div>
                            <div class="text-gray-400 list-disc uses_discs ml-2">
                                {!! $removed !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @break
    @endswitch
</div>
