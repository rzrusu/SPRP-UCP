<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\ReleaseNote;

new
#[Layout('layouts.update')]
#[Title('Release Notes')]
class extends Component {

    public $updates;

    public function mount(): void
    {
        $this->updates = ReleaseNote::orderBy('created_at', 'desc')->get();
    }

}; ?>

<div>
    <div class="mt-8 space-y-8">
        @foreach($updates as $update)
            <livewire:components.update-block :update="$update" :key="$update->id" />
        @endforeach
    </div>
</div>
