@props(['icon', 'active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex w-full justify-center space-x-2 items-center px-1 pt-1 border-b-4 border-[#586DC0] text-sm font-medium leading-5 text-[#586DC0] focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex w-full justify-center space-x-2 h-16 items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-[#787878] hover:text-[#C2C2C2] hover:border-[#C2C2C2] focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{$slot}}
</a>
