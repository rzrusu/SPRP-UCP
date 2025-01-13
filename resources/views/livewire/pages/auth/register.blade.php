<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;


new #[Layout('layouts.guest')] class extends Component
{
    public string $account_name = '';
    public string $account_email = '';
    public string $account_password = '';
    public string $account_password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'account_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'account_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['account_password'] = Hash::make($validated['account_password']);
        $validated['account_registerip'] = request()->ip();
        $validated['account_registerdate'] = now()->timestamp;

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center h-full w-full space-y-4">
    <div class="flex flex-col items-center justify-center">
        <h2 class="font-manrope mt-6 text-xl font-bold text-center text-gray-200">{{ __('Register an account') }}</h2>
    </div>

    <form wire:submit="register" class="mt-4 w-full">
        <!-- Name -->
        <div>
            <x-input-label for="account_name" :value="__('Name')" />
            <x-text-input wire:model="account_name" id="account_name" class="block mt-1 w-full" type="text" name="account_name" required autofocus autocomplete="account_name" placeholder="Enter your username" />
            <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="account_email" :value="__('Email')" />
            <x-text-input wire:model="account_email" id="account_email" class="block mt-1 w-full" type="email" name="account_email" required autocomplete="username" placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('account_email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="account_password" :value="__('Password')" />

            <x-text-input wire:model="account_password" id="account_password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Enter your password" />

            <x-input-error :messages="$errors->get('account_password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="account_password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="account_password_confirmation" id="account_password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="account_password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />

            <x-input-error :messages="$errors->get('account_password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center justify-center space-y-4">
            <x-primary-button class="w-full mt-4">
                {{ __('Register') }}
            </x-primary-button>
            <a class="text-sm text-gray-600 hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already have an account?') }}
                <span class="underline font-semibold">{{ __('Log in') }}</span>
            </a>
        </div>
    </form>
</div>
