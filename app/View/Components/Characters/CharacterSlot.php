<?php

namespace App\View\Components\Characters;

use App\Models\Character\Character;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CharacterSlot extends Component
{


    /**
     * Create a new component instance.
     */
    public function __construct()
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.characters.character-slot');
    }
}
