@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 my-2 border-b-2 border-red-400 text-sm font-medium leading-2 text-gray-900 focus:outline-none focus:border-red-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 my-2 border-b-2 border-transparent text-sm font-medium leading-2 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
