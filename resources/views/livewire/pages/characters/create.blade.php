<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\Character\Character;
use Livewire\Attributes\Validate;
use App\Models\SkinData;

new
#[Title('Create a character')]
#[Layout('layouts.app')]
class extends Component {

    #[Validate('required|string|unique:characters|max:32|min:3|regex:/^[A-Z][a-zA-Z]*_[A-Z][a-zA-Z]*$/i')]
    public string $player_name = '';

    #[Validate('required|in:1,2|numeric')]
    public int $character_gender = 1;

    #[Validate('required|in:0,1,2,3,4,5,6|numeric')]
    public int $character_race = 0;

    #[Validate('required|between:18,100|numeric')]
    public int $character_age;

    #[Validate('required|in:0,1,2,3,4,5|numeric')]
    public int $character_eyes = 0;

    #[Validate('required|in:0,1,2,3,4,5,6|numeric')]
    public int $character_hair = 0;

    #[Validate('required|in:0,1,2,3,4,5,6|numeric')]
    public int $character_body = 0;

    #[Validate('required|between:150,220|numeric')]
    public int $character_height;

    #[Validate('required')]
    public $selected_skin;

    public $skins = [];

    public $skin_picker_open = false;


    public function toggleSkinPicker()
    {
        $this->skin_picker_open = !$this->skin_picker_open;
    }

    public function getNameFromRaceId($id){
        switch($id){
            case 0:
                return 'white';
            case 1:
                return 'black';
            case 2:
                return 'hispanic';
            case 3:
                return 'asian';
            default:
                return 'other';
        }
    }

    public function create()
    {
        $this->validate();

        $user = auth()->user();

        if ($user->characters()->count() >= 2) {
            return $this->redirect('/characters');
        }

        if (is_null($this->selected_skin)) {
            $this->addError('selected_skin', 'Please select a skin.');
            return;
        }

        // We will manually capitalize the letters to make sure the name is always in the correct format.
        $this->player_name = ucwords(str_replace('_', ' ', $this->player_name));
        $this->player_name = str_replace(' ', '_', $this->player_name);

        $user->characters()->create([
            'account_id' => auth()->id(),
            'player_name' => $this->player_name,

            'player_attribute_sex' => $this->character_gender,
            'player_attribute_race' =>$this->character_race,
            'player_attribute_age' => $this->character_age,
            'player_attribute_eyes' => $this->character_eyes,
            'player_attribute_hair' => $this->character_hair,
            'player_attribute_body' => $this->character_body,
            'player_attribute_height' => $this->character_height,

            'player_registerdate' => now()->timestamp,

            'player_skinid' => $this->selected_skin->skin_id,
        ]);

        return $this->redirect('/characters');
    }


    public function refreshSkinOptions()
    {
        $this->getSkinOptions();
    }

    public function getSkinOptions()
    {
        // return skins based on the gender and race choices
        $this->skins = SkinData::where('gender', ($this->character_gender > 1) ? 'female' : 'male')->where('race', $this->getNameFromRaceId($this->character_race))->where('usable', true)->get();
    }

    public function setSelectedSkin($skin_id)
    {
        $this->selected_skin = SkinData::find($skin_id);
        $this->skin_picker_open = false;
    }

    // reset skin selection when changing race or gender.
    public function updated($property)
    {
        if ($property === 'character_gender' || $property === 'character_race') {
            $this->selected_skin = null;
            $this->refreshSkinOptions();
        }
    }

    public function mount()
    {
        if (auth()->user()->characters()->count() >= 2) {
            return $this->redirect('/characters');
        }

        $this->getSkinOptions();
    }

}; ?>

<div x-data="{open: false}">

    <div x-show="open">
        <x-modal-container :closeable="true">
            <div wire:loading class="w-full h-96">
                <div class="w-full h-full flex flex-col space-y-2 items-center justify-center justify-items-center">
                    <div role="status" >
                        <svg aria-hidden="true" class="w-8 h-8 animate-spin text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                    </div>
                    <span class="text-gray-500 ">Please wait...</span>
                </div>
            </div>
            <div wire:loading.remove class="space-y-4">
                <div>
                    <h2 class="text-gray-100 font-bold text-xl mb-2">Choose a skin</h2>
                    <p class="text-gray-400">Results are shown based on your choice of gender and race. If the skin you are looking for isn’t present, it may be unavailable for your choices.</p>
                </div>
                <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3 max-h-96 rounded-lg overflow-y-scroll scrollbar-thin scrollbar-thumb-[#34353D] scrollbar-corner-rounded-full scrollbar-track-rounded-full scrollbar-thumb-rounded scrollbar-track-gray-800 p-1">
                    @forelse($skins as $skin)
                        <x-characters.skin-slot :skin="$skin" :wire:key="$skin->skin_id" />
                    @empty
                        <div class="mb-1 bg-gray-200">
                            No skins found
                        </div>
                    @endforelse
                </div>
            </div>
        </x-modal-container>
    </div>

    <div class="w-full inline-flex items-start justify-center p-6 space-x-4">
        <a href="{{ url()->previous() }}" class="h-10 w-10 hidden md:block rounded-full bg-[#2D2F34] p-2 text-gray-500 hover:text-gray-400 transition">
            <x-heroicon-c-arrow-left-circle class="w-6 h-6" />
        </a>
        <div class="w-full md:w-2/3 lg:w-1/2 space-y-4">
            <h1 class="text-2xl font-bold text-gray-200">{{ __('Create a character') }}</h1>
                <form wire:submit="create" class="mt-4 w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col space-y-2">
                        <button type="button"  @click="open = true" class="w-full group uses_character_bg rounded-lg py-8 space-y-4 hover:character_slot_effect hover:ring ring-white/10 transition">
                            @if(!is_null($selected_skin))
                                <img src="{{ asset('assets/skins/'.$selected_skin->skin_id.'.png') }}" alt="Character Slot" class="w-full h-auto">
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center space-x-2">
                                        <span class="font-semibold text-gray-300 text-lg group-hover:text-gray-100 transition">{{Str::limit($selected_skin->name, 24, '...')}}</span>
                                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-gray-300 group-hover:text-gray-100 transition" />
                                    </div>
                                    <span class="text-gray-400 group-hover:text-gray-300 transition text-sm">Change Skin</span>
                                </div>
                            @else
                                <img src="{{ asset('assets/skins/no_skin.png') }}" alt="Character Slot" class="w-full h-auto">
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center">
                                        <span class="font-semibold text-gray-300 text-lg group-hover:text-gray-100 transition">No Skin</span>
                                    </div>
                                    <span class="text-gray-400 group-hover:text-gray-300 transition text-sm">Change Skin</span>
                                </div>
                            @endif
                        </button>
                        <x-input-error :messages="$errors->get('selected_skin')" class="mt-2" />
                    </div>
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <x-input-label for="player_name" :value="__('Name')" />
                            <x-text-input wire:model="player_name" id="player_name" class="block mt-1 w-full" type="text" name="player_name" required autofocus autocomplete="player_name" placeholder="Firstname_Lastname" />
                            <x-input-error :messages="$errors->get('player_name')" class="mt-2" />
                        </div>

                        <div class="inline-flex justify-between items-center space-x-4">
                            <!-- Age -->
                            <div class="w-full">
                                <x-input-label for="character_age" :value="__('Age')" />
                                <x-text-input wire:model="character_age" id="character_age" class="block mt-1 w-full" type="text" name="character_age" required autofocus autocomplete="character_age" placeholder="18" />
                                <x-input-error :messages="$errors->get('character_age')" class="mt-2" />
                            </div>

                            <!-- Height -->
                            <div class="w-full">
                                <x-input-label for="character_height" :value="__('Height')" />
                                <x-text-input wire:model="character_height" id="character_height" class="block mt-1 w-full" type="text" name="character_height" required autofocus autocomplete="character_height" placeholder="170" />
                                <x-input-error :messages="$errors->get('character_height')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="w-full">
                            <x-input-label for="character_gender" :value="__('Gender')" />
                            <select wire:model.live="character_gender" id="character_gender" class="bg-form-input w-full mt-1 py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="characterGender" required autofocus autocomplete="character_gender">
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('character_gender')" class="mt-2" />
                        </div>

                        <!-- Race -->
                        <div class="w-full">
                            <x-input-label for="character_race" :value="__('Race')" />
                            <select wire:model.live="character_race" id="character_race" class="bg-form-input w-full mt-1 py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="character_race" required autofocus autocomplete="character_race">
                                <option value="0">White</option>
                                <option value="1">Black</option>
                                <option value="2">Latino</option>
                                <option value="3">Asian</option>
                            </select>
                            <x-input-error :messages="$errors->get('character_race')" class="mt-2" />
                        </div>

                        <div class="inline-flex justify-between items-center space-x-4 w-full">
                            <!-- Hair -->
                            <div class="w-full">
                                <x-input-label for="character_race" :value="__('Hair')" />
                                <select wire:model.live="character_hair" id="character_hair" class="bg-form-input w-full mt-1 py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="character_hair" required autofocus autocomplete="character_hair">
                                    <option value="0">None</option>
                                    <option value="1">Black</option>
                                    <option value="2">Brown</option>
                                    <option value="3">Blonde</option>
                                    <option value="4">White</option>
                                    <option value="5">Gray</option>
                                    <option value="6">Red</option>
                                </select>
                                <x-input-error :messages="$errors->get('character_hair')" class="mt-2" />
                            </div>

                            <!-- Eyes -->
                            <div class="w-full">
                                <x-input-label for="character_race" :value="__('Eyes')" />
                                <select wire:model.live="character_eyes" id="character_eyes" class="bg-form-input w-full mt-1 py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="character_eyes" required autofocus autocomplete="character_eyes">
                                    <option value="0">Amber</option>
                                    <option value="1">Blue</option>
                                    <option value="2">Brown</option>
                                    <option value="3">Gray</option>
                                    <option value="4">Green</option>
                                    <option value="5">Hazel</option>
                                </select>
                                <x-input-error :messages="$errors->get('character_eyes')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="w-full">
                            <x-input-label for="character_gender" :value="__('Body')" />
                            <select wire:model.live="character_body" id="character_body" class="bg-form-input w-full mt-1 py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="character_body" required autofocus autocomplete="character_body">
                                <option value="0">Skinny</option>
                                <option value="1">Slim</option>
                                <option value="2">Normal</option>
                                <option value="3">Plump</option>
                                <option value="4">Obese</option>
                                <option value="5">Muscular</option>
                            </select>
                            <x-input-error :messages="$errors->get('character_body')" class="mt-2" />
                        </div>


                        <x-primary-button class="w-fit ml-auto h-fit" type="submit">
                            {{ __('Create Character') }}
                        </x-primary-button>
                    </div>
                </form>
        </div>
    </div>
</div>
