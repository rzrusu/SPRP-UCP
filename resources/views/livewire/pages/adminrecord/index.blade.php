<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\User;

new
#[Layout('layouts.app')]
#[Title('Admin Record')]
class extends Component {

    public $adminRecords;
    public $activeRecords;

    // TODO: ban checking!!!!!!!!!

    public function getActiveRecords()
    {
        $characters = auth()->user()->characters;
        $activeRecords = [];

        foreach ($characters as $character) {
            if ($character->player_ajail_time > 0) {
                $activeRecords[] = [$character->player_oajail_reason, $character->player_oajail_admin, $character->player_ajail_time];
            }
        }

        $this->adminRecords = $this->getAdminRecordsProcessed();

        foreach ($this->adminRecords as $adminRecord) {
            foreach ($activeRecords as $record) {
                if($record[0] == $adminRecord->record_reason && $record[1] == $adminRecord->record_admin && $record[2] <= $adminRecord->record_time) {
                    $adminRecord->active = true;
                } else {
                    $adminRecord->active = false;
                }
            }
        }

        return $activeRecords;
    }

    public function getAdminRecordsProcessed()
    {
        $adminRecords = auth()->user()->adminRecords;

        foreach ($adminRecords as $record) {
            $record->record_admin = User::find($record->record_admin)->account_name ?? 'Unknown';
            $record->record_date = Carbon\Carbon::createFromFormat('d/m/Y, H:i', $record->record_date);
            $record->record_human_date = $record->record_date->shortRelativeDiffForHumans();
        }

        return $adminRecords;
    }

    public function mount()
    {
        $this->activeRecords = $this->getActiveRecords();
    }

}; ?>

<div>
    <div class="w-full inline-flex items-start justify-center p-6 space-x-4">
        <a href="{{ url()->previous() }}" class="h-10 w-10 hidden md:block rounded-full bg-[#2D2F34] p-2 text-gray-500 hover:text-gray-400 transition">
            <x-heroicon-c-arrow-left-circle class="w-6 h-6" />
        </a>
        <div class="w-full md:w-2/3 lg:w-1/2 space-y-3">
            <h1 class="text-2xl font-bold text-gray-200">{{ __('Your Admin Record') }}</h1>
            <div class="text-gray-400 inline-flex items-center">
                <div class="inline-flex items-center mr-1 space-x-1">
                    <x-heroicon-m-user class="w-5 h-5" />
                    <span>{{Auth::user()->account_name}}</span>
                </div>· ID {{Auth::user()->account_id}}
            </div>
            <div>
                <div class="space-y-2 mb-6">
                    @if(count($activeRecords) > 0)
                        <p class="text-[#F79046] font-semibold">You currently have active punishments.</p>
                    @endif
                    @foreach($adminRecords as $record)
                       @if($record->active)
                                <x-admin-record-entry :record_active="true" :record_type="$record->record_type" :record_time="$record->record_time" :record_human_date="$record->record_human_date" :record_date="$record->record_date" :record_admin="$record->record_admin" :record_reason="$record->record_reason" />
                            @endif
                    @endforeach
                </div>
                <div>
                    <p class="text-gray-500 font-medium mb-2">Previous Punishments <span class="text-gray-400">· {{count($adminRecords) - count($activeRecords) }}</span></p>
                    <div class="space-y-2">
                        @foreach($adminRecords as $record)
                            @if(!$record->active)
                                <x-admin-record-entry :record_active="false" :record_type="$record->record_type" :record_time="$record->record_time" :record_human_date="$record->record_human_date" :record_date="$record->record_date" :record_admin="$record->record_admin" :record_reason="$record->record_reason" />
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
