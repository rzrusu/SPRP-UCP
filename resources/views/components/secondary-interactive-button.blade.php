<a {{ $attributes->merge(['class' => 'inline-flex text-center items-center justify-center px-4 py-2.5 bg-button-secondary border border-transparent rounded-lg font-semibold text-white hover:bg-button-secondary-hover focus:bg-button-secondary-focus active:bg-button-secondary-focus focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
