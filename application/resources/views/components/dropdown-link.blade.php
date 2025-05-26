<a {{ $attributes->merge([
    'class' =>
        'block w-full px-4 py-2 text-start text-sm rounded-md transition duration-150 ease-in-out
        text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-200
        dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:bg-gray-600'
]) }}>
    {{ $slot }}
</a>
