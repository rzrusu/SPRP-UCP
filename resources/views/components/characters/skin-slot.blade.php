@props(['skin'])

<button wire:click="setSelectedSkin({{$skin->skin_id}})" @click="open = false" type="button" class="w-full h-fit group uses_character_bg rounded-lg py-4 space-y-4 hover:character_slot_effect hover:ring ring-white/10 transition">
    <img src="{{ asset('assets/skins/'.$skin->skin_id.'.png') }}" alt="Character Slot" class="w-[120px] h-[150px]">
    <div class="flex flex-col items-center">
        <div class="inline-flex items-center">
            <span class="font-semibold text-gray-300 text-sm group-hover:text-gray-100 transition truncate max-w-24">{{$skin->name}}</span>
        </div>
        <span class="text-gray-400 group-hover:text-gray-300 transition text-sm">({{$skin->skin_id}})</span>
    </div>
</button>
