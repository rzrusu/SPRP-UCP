<?php

use Livewire\Volt\Component;
use App\Models\DashboardAnnouncement;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

new class extends Component {

    public Collection $announcements;
    public $areThereAnnouncements = false;

    public function mount(): void {
        $this->announcements = DashboardAnnouncement::where('expires_at', '>', now())->orderBy('created_at', 'desc')->get();
        $this->areThereAnnouncements = $this->announcements->isNotEmpty();
        $this->renderMarkdown();
    }

    public function renderMarkdown(): void {
        $this->announcements->each(function($announcement) {
            $announcement->content = Str::of($announcement->content)->markdown();
        });
    }

}; ?>

<section class="space-y-2 @if($areThereAnnouncements) mb-6 @endif">

    @if($areThereAnnouncements)
        <header>
            <h2 class="text-lg font-medium text-gray-400">
                {{ __('Announcements Board') }}
            </h2>
        </header>
        <div class="space-y-2">
            @foreach($announcements as $announcement)
                <div class="bg-gray-900 sm:space-x-4 flex flex-col sm:flex-row w-full items-start rounded-lg p-4 border @if($announcement->force_highlight || $loop->index == 0) border-[#586DC0] @else border-gray-700 @endif">
                    @switch($announcement->type)
                        @case('info')
                            <x-heroicon-s-information-circle class="w-10 h-10 text-blue-500"/>
                            @break
                        @case('warning')
                            <x-heroicon-s-exclamation-circle class="w-10 h-10 text-yellow-500"/>
                            @break
                        @case('change')
                            <x-heroicon-s-arrow-path class="w-10 h-10 text-green-500"/>
                            @break
                    @endswitch
                    <div class="w-full mt-2 sm:mt-0">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full mb-2 md:mb-1">
                            <h3 class="text-lg font-medium text-gray-100">{{$announcement->title}}</h3>
                            <span class="text-gray-600">{{$announcement->author}}</span>
                        </div>
                        <div class="text-gray-400 uses_discs render_markdown">{!! $announcement->content !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</section>
