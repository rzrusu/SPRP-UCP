@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center space-x-2 w-full ps-3 pe-4 py-2 border-l-4 border-[#586DC0] text-start text-base font-medium text-[#586DC0] focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center space-x-2 w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#787878] hover:text-[#C2C2C2] hover:bg-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-800 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
