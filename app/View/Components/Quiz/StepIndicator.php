<?php

namespace App\View\Components\Quiz;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StepIndicator extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public int $currentStep, public int $totalSteps, public string $title)
    {
        $this->currentStep = $currentStep;
        $this->totalSteps = $totalSteps;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.quiz.step-indicator');
    }
}
