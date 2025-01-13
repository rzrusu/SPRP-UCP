<?php

use Livewire\Volt\Component;
use App\Models\QuizQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

enum QuizSteps: int
{
    case QuizStepStart = 1;
    case QuizStepQuestions = 2;
    case QuizStepEnglish = 3;
    case QuizStepFinish = 4;
    case QuizStepCooldown = 5;
}

new class extends Component {

    public $step;
    public $questions = [];

    private int $MIN_CORRECT_ANSWERS = 3;

    public int $currentQuestion = 0;
    public string $englishAnswer;

    public $englishTestAnswer1;
    public $englishTestAnswer2;

    public $displayError = false;

    public $question1answer;
    public $question2answer;
    public $question3answer;
    public $question4answer;
    public $question5answer;

    public function mount(): void
    {
        if (auth()->user()->account_quiz_cooldown && now()->lessThan(auth()->user()->account_quiz_cooldown)) {
            $this->step = QuizSteps::QuizStepCooldown;
            return;
        } else {
            auth()->user()->account_quiz_cooldown = null;
            auth()->user()->setQuizStatus('pending');
            auth()->user()->save();
        }

        $this->step = QuizSteps::QuizStepStart;
    }


    // feels jank
    function submitQuestions(): void
    {
        $this->nextQuestion();

        if ($this->currentQuestion >= 5) {

            $validated = $this->validate([
                'question1answer' => 'required',
                'question2answer' => 'required',
                'question3answer' => 'required',
                'question4answer' => 'required',
                'question5answer' => 'required',
            ]);

            if (!$validated) {
                $this->resetQuiz(true);
                return;
            }

            $correctAnswers = 0;
            foreach ($this->questions as $i => $question) {
                if ($question->correct_answer == $this->{'question' . ($i + 1) . 'answer'}) {
                    $correctAnswers++;
                }
            }

            if ($correctAnswers < $this->MIN_CORRECT_ANSWERS) {
                $this->resetQuiz(true);
                return;
            }

            Cookie::queue('quiz_status', 'passed_basic_quiz', 60 * 24);
            $this->step = QuizSteps::QuizStepEnglish;
        }
    }

    function submitEnglishQuestion(): void
    {
        if ($this->englishAnswer != 4) {
            $this->resetQuiz(true);
            return;
        }

        $user = User::find(auth()->id());

        if (!$user) {
            $this->resetQuiz(true);
            return;
        }

        $user->setQuizStatus('passed');
        $this->step = QuizSteps::QuizStepFinish;

        return;
    }

    function nextStep()
    {
        $this->displayError = false;
        $this->step++;
    }

    function startQuiz()
    {
        // check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // check if user hasn't already passed the quiz
        if (auth()->user()->account_quiz_status == 'passed') {
            return;
        }

        // check if they have a cooldown active
        if (auth()->user()->account_quiz_cooldown && now()->lessThan(auth()->user()->account_quiz_cooldown)) {
            $this->step = QuizSteps::QuizStepCooldown;
            return;
        }

        // check if the user has already passed the quiz questions
        $request = request();
        if ($request->cookie('quiz_status') && $request->cookie('quiz_status') == 'passed_basic_quiz') {
            $this->step = QuizSteps::QuizStepEnglish;
            return;
        }

        // else, start the quiz from the beginning.
        $questions = QuizQuestion::inRandomOrder()->limit(5)->get();
        $this->questions = $questions;

        if (!$this->questions)
            throw new \Exception('No questions found');

        $this->step = QuizSteps::QuizStepQuestions;
    }

    function resetQuiz(bool $failedQuiz = false): void
    {

        $failedQuiz ? $this->displayError = true : $this->displayError = false;

        if ($failedQuiz) {

            $user = User::find(auth()->id());
            $user->account_quiz_failed_tries++;
            $user->save();

            if ($user->account_quiz_failed_tries >= 3) {
                $user->setQuizStatus('failed');

                $user->account_quiz_cooldown = now()->addHour();
                $user->account_quiz_failed_tries = 0;
                $user->save();

                Cookie::queue(Cookie::forget('quiz_status'));

                $this->closeQuizModal();
                return;
            }
        }

        $this->question1answer = null;
        $this->question2answer = null;
        $this->question3answer = null;
        $this->question4answer = null;
        $this->question5answer = null;

        $this->questions = [];
        $this->currentQuestion = 0;
        $this->step = QuizSteps::QuizStepStart;
    }

    function nextQuestion(): void
    {
        $this->currentQuestion++;
    }

    public function closeQuizModal()
    {
        return redirect()->route('dashboard');
    }
}; ?>

<div>
    <div>
        @switch($step)
            @case(QuizSteps::QuizStepStart)
                @if($displayError)
                    <div>
                        <h2 class="text-gray-100 text-2xl text-center font-bold mb-2">Try Again</h2>
                        <p class="text-gray-400 mb-6">Sorry, but you have not answered enough questions correctly to
                            proceed. Please try again.</p>
                        <x-primary-button wire:click="resetQuiz" class="w-full">Try Again</x-primary-button>
                    </div>
                    {{--<div class="w-full p-2 bg-red-500/10 rounded-lg mb-4 border border-red-500 text-red-400 font-semibold">
                        Sorry, but you have not answered enough questions correctly to proceed. Please try again.
                    </div>--}}
                @else
                    <div>
                        <h2 class="text-gray-100 text-2xl text-center font-bold mb-2">Finish Registration</h2>
                        <p class="text-gray-400 mb-6">To finish registration, you have to complete a short quiz about
                            your experience with roleplay servers. <br><br> This is so we can get a simple assessment of
                            your skills and therefore be able to offer you a better, more streamlined experience. This
                            process is automated and does not require human examination, so it will not take long.</p>
                        <x-primary-button wire:click="startQuiz" class="w-full">Start Quiz</x-primary-button>
                    </div>
                @endif
                @break
            @case(QuizSteps::QuizStepQuestions)
                <div class="flex flex-col space-y-5">
                    <div class="inline-flex items-center space-x-1.5">
                        <div class="w-8 h-8">
                            <span class="absolute text-[#6B6E7A] text-lg font-bold">1</span>
                            <span class="absolute mt-0.5 ml-2 text-lg text-[#6B6E7A] font-bold">/</span>
                            <span class="absolute mt-1 ml-4 text-lg text-[#6B6E7A] font-bold">2</span>
                        </div>
                        <span class="text-gray-100 font-bold text-xl">Roleplay</span>
                    </div>
                    <div>
                        @foreach($questions as $question)
                            <form class="flex flex-col space-y-4" wire:submit="submitQuestions">
                                @if($loop->index == $currentQuestion)
                                    @php($currentLoop = $loop->index + 1)
                                    <div class="flex flex-col">
                                        <h3 class="text-gray-100 font-semibold mb-3">{{$loop->index + 1}}
                                            . {{$question->question}}</h3>
                                        @foreach($question->answers() as $i => $answer)
                                            <div class="flex items-center space-x-3 mb-2">
                                                <input type="radio" name="answer" id="answer-{{$i}}" value="{{$i + 1}}"
                                                       wire:model="question{{$currentLoop}}answer" required
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="answer-{{$i}}" class="text-gray-400">{{$answer}}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="inline-flex items-center space-x-2">
                                        <div class="inline-flex items-center space-x-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <span class="relative flex h-3 w-3">
                                                          <span
                                                              class="@if($i == $currentQuestion) animate-ping bg-blue-500 @elseif($i < $currentQuestion) bg-blue-500 @else bg-gray-600 @endif absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                                                          <span
                                                              class="relative inline-flex rounded-full h-3 w-3 @if($i <= $currentQuestion) bg-blue-500 @else bg-gray-600 @endif "></span>
                                                        </span>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-[#676974]">{{5 - $currentQuestion}} questions remaining</span>
                                    </div>

                                    <x-primary-button type="submit" class="w-fit">Next Step</x-primary-button>
                                @endif
                            </form>
                        @endforeach
                    </div>
                </div>
                @break

            @case(QuizSteps::QuizStepEnglish)
                <div class="flex flex-col space-y-5">
                    <div class="inline-flex items-center space-x-1.5">
                        <div class="w-8 h-8">
                            <span class="absolute text-[#6B6E7A] text-lg font-bold">2</span>
                            <span class="absolute mt-0.5 ml-3 text-lg text-[#6B6E7A] font-bold">/</span>
                            <span class="absolute mt-1 ml-5 text-lg text-[#6B6E7A] font-bold">2</span>
                        </div>
                        <span class="text-gray-100 font-bold text-xl">English</span>
                    </div>
                    <form class="flex flex-col space-y-4" wire:submit="submitEnglishQuestion">
                        <div class="flex flex-col">
                            <h3 class="text-gray-100 font-semibold mb-3">Choose the correct sentence</h3>
                            <div class="flex items-center space-x-3 mb-2">
                                <input type="radio" name="english_answer" id="english_answer-1" value="1"
                                       wire:model="englishAnswer" required
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="english_answer-1" class="text-gray-400">Matthew went to the park and meets
                                    with his friend.</label>
                            </div>
                            <div class="flex items-center space-x-3 mb-2">
                                <input type="radio" name="english_answer" id="english_answer-2" value="2"
                                       wire:model="englishAnswer" required
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="english_answer-2" class="text-gray-400">Matthew goes to the park and met his
                                    friend.</label>
                            </div>
                            <div class="flex items-center space-x-3 mb-2">
                                <input type="radio" name="english_answer" id="english_answer-3" value="3"
                                       wire:model="englishAnswer" required
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="english_answer-3" class="text-gray-400">Matthew went to the park and will
                                    meet with his friend.</label>
                            </div>
                            <div class="flex items-center space-x-3 mb-2">
                                <input type="radio" name="english_answer" id="english_answer-4" value="4"
                                       wire:model="englishAnswer" required
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="english_answer-4" class="text-gray-400">Matthew went to the park and met
                                    with his friend.</label>
                            </div>
                        </div>
                        <x-primary-button type="submit" class="w-fit">Next Step</x-primary-button>
                    </form>
                </div>
                @break

                {{--
            @case(4)
                <div class="flex flex-col space-y-5">
                    <div class="inline-flex items-center space-x-1.5">
                        <div class="w-8 h-8">
                            <span class="absolute text-[#6B6E7A] text-lg font-bold">2</span>
                            <span class="absolute mt-0.5 ml-3 text-lg text-[#6B6E7A] font-bold">/</span>
                            <span class="absolute mt-1 ml-5 text-lg text-[#6B6E7A] font-bold">2</span>
                        </div>
                        <span class="text-gray-100 font-bold text-xl">English</span>
                    </div>
                    <form class="flex flex-col space-y-4" wire:submit="submitEnglishTest">
                        <div class="flex flex-col">
                            <h3 class="text-gray-100 font-semibold mb-3">Write two /me commands that describes your character doing whatever actions you can think of.  You must write them grammatically correct and using proper punctuation. The actions do not have to be related to eachother.</h3>
                            <textarea wire:model="englishTestAnswer1" name="englishTestAnswer1" placeholder="/me does something" required minlength="64" class="h-24 mb-1 bg-form-input py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" data-gramm="false"
                                      data-gramm_editor="false"
                                      data-enable-grammarly="false"></textarea>
                            <textarea wire:model="englishTestAnswer2" name="englishTestAnswer2" placeholder="/me does something" required minlength="64" class="h-24 mb-1 bg-form-input py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      data-gramm="false"
                                      data-gramm_editor="false"
                                      data-enable-grammarly="false"></textarea>
                            <span class="text-gray-500 text-sm">Minimum 64 characters (~10 words) per action.</span>
                        </div>
                        <x-primary-button type="submit" class="w-fit">Finish Quiz</x-primary-button>
                    </form>
                </div>
                @break--}}
            @case(QuizSteps::QuizStepFinish)
                <div>
                    <h2 class="text-gray-100 text-2xl text-center font-bold mb-2">Congratulations!</h2>
                    <p class="text-gray-400 mb-6">You have successfully passed the quiz. Your account now has full
                        access to all features. Thank you for playing and have fun!</p>
                    <x-primary-button wire:click="closeQuizModal" class="w-full">Thanks!</x-primary-button>
                </div>
                @break
            @case(QuizSteps::QuizStepCooldown)
                <div wire:poll>
                    <h2 class="text-gray-100 text-2xl text-center font-bold mb-2">Quiz Cooldown</h2>
                    <p class="text-gray-400 mb-6">Sorry, but because you have failed multiple quizzes in a row, there
                        has been a cooldown applied to your account.</p>
                    @if(auth()->user()->QuizCooldown < now())
                        <x-primary-button wire:click="closeQuizModal" class="w-full">Try Again</x-primary-button>
                    @else
                        <p class="text-gray-400 mb-6">It will expire in {{auth()->user()->QuizCooldown->diffForHumans()}}</p>
                    @endif
                </div>
                @break
            @default
                <div>
                    <h2 class="text-gray-100 text-2xl text-center font-bold mb-2">Try Again</h2>
                    <p class="text-gray-400 mb-6">Sorry, but you have not answered enough questions correctly to
                        proceed. Please try again.</p>
                    <x-primary-button wire:click="resetQuiz" class="w-full">Try Again</x-primary-button>
                </div>
                @break
        @endswitch
    </div>
</div>
