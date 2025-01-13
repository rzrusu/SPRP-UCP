<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $account_email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'account_email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('account_email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('account_email', __($status));

            return;
        }

        $this->reset('account_email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="flex flex-col items-center justify-center h-full w-full space-y-4">
    <div class="flex flex-col items-center justify-center space-y-2">
        <h2 class="font-manrope mt-6 text-xl font-bold text-center text-gray-200">{{ __('Forgot your password?') }}</h2>
        <p class="mt-2 text-sm text-center text-gray-400">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="w-full">
        <!-- Email Address -->
        <div>
            <x-input-label for="account_email" :value="__('Email')" />
            <x-text-input wire:model="account_email" id="account_email" class="block mt-1 w-full" type="email" name="account_email" required autofocus />
            <x-input-error :messages="$errors->get('account_email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</div>
