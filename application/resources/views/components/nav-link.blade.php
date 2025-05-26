@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 border-b-2 border-blue-600 text-sm font-semibold text-blue-600 dark:text-blue-400'
    : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 hover:border-blue-400 dark:hover:text-blue-400 dark:hover:border-blue-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
