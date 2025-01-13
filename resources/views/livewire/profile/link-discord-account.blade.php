<?php

use function Livewire\Volt\{state};

//

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-200">
            {{ __('Link your Discord account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('Link your Discord account for another layer of security and to receive account alerts directly in your DMs.') }}
            <span class="text-gray-500">2FA Required</span>
        </p>
    </header>

    <a href="#" class="w-full p-3 bg-[#404EED] rounded-lg inline-flex items-center space-x-2 mt-4">
        <img src="{{ asset('assets/logos/discord.svg') }}" alt="Discord Logo" class="h-12 w-12">
        <span class="text-gray-200 font-semibold">Sign In with Discord</span>
    </a>

</section>
