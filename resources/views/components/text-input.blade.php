@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-form-input py-2 text-gray-200 placeholder:text-form-placeholder border-form-stroke focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
