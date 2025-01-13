@props(['character', 'locked' => false, 'has_premium' => false])

@switch($locked)
    @case(true)
        <a href="{{route('characters.create')}}" class="w-full h-full min-h-[500px] group uses_missing_character_bg flex flex-col items-center justify-center rounded-lg py-8 space-y-4 hover:character_slot_effect_missing hover:ring ring-white/10 transition" wire:navigate>
            <x-heroicon-s-plus-circle class="w-40 h-40 text-gray-500 group-hover:text-gray-300 transition" />
                <span class="text-xl text-gray-500 group-hover:text-gray-300 text-center transition font-semibold">Create<br>Character</span>
        </a>
        @break
    @case(false)
        <a href="#" class="w-full group uses_character_bg rounded-lg py-8 space-y-4 hover:character_slot_effect hover:ring ring-white/10 transition">
            <img src="{{ asset('assets/skins/'.$character->player_skinid.'.png') }}" alt="Character Slot" class="w-full h-auto">
            <div class="flex flex-col items-center">
                <div class="inline-flex items-center space-x-2">
                    <span class="font-semibold text-gray-300 text-lg group-hover:text-gray-100 transition">{{$character->getCleanName()}}</span>
                    <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-gray-300 group-hover:text-gray-100 transition" />
                </div>
                <span class="text-gray-400 group-hover:text-gray-300 transition text-sm">Level 84 · LSPD</span>
            </div>
        </a>

        @break
@endswitch
