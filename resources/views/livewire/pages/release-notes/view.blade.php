<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\ReleaseNote;
use Illuminate\Support\Str;

new
#[Layout('layouts.update')]
#[Title('View Update')]
class extends Component {

    public $slug;
    public $update;

    public string $description;
    public string $content;

    public string $added;
    public string $removed;
    public string $changed;
    public string $fixed;

    // This method will render the markdown content for the update.
    public function renderMarkdown(): void
    {
        $this->description = Str::of($this->update->description)->markdown();
        $this->added = Str::of($this->update->added)->markdown();
        $this->removed = Str::of($this->update->removed)->markdown();
        $this->changed = Str::of($this->update->changed)->markdown();
        $this->fixed = Str::of($this->update->fixed)->markdown();
        $this->content = Str::of($this->update->content)->markdown();
        $this->description = Str::of($this->update->description)->markdown();
    }

    public function mount(): void
    {
        $this->update = ReleaseNote::where('slug', $this->slug)->first();
        $this->renderMarkdown();
    }

}; ?>

<div>
    <div class="bg-gray-800 p-5 rounded-lg border border-stroke-primary mt-5">
        <div class="space-y-3">
            <span class="bg-[#83B41C]/25 font-ibm text-[#83B41C] font-bold tracking-widest text-xs rounded-full py-0.5 px-2">FEATURE OVERVIEW</span>
            <h1 class="text-white text-2xl font-manrope">{{$update->title}}</h1>
            <span class="text-[#666666] text-sm text-semibold">Written by {{$update->author}} · {{$update->created_at->diffForHumans()}}</span>
        </div>
        @if($update->image)
            <img src="{{$update->image}}" alt="{{$update->title}}" class="w-full h-96 object-cover rounded-lg my-4">
        @endif
        <div class="text-gray-300 uses_discs render_markdown">
            {!! $description !!}
        </div>
        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
        <div class="text-gray-300 uses_discs render_markdown">
            {!! $content !!}
        </div>
    </div>
</div>
