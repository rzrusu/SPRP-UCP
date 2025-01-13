<a {{ $attributes->merge(['class' => 'inline-flex text-center items-center justify-center px-4 py-2.5 bg-button-primary border border-transparent rounded-lg font-semibold text-white hover:bg-button-primary-hover focus:bg-button-primary-focus active:bg-button-primary-focus focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
