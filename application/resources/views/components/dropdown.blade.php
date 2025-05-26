@props(['align' => 'left', 'width' => '48', 'contentClasses' => 'py-2 bg-white dark:bg-gray-800'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right left-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left right-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="cursor-pointer select-none">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 {{ $width }} {{ $alignmentClasses }}"
         style="display: none;"
         @click="open = false"
         role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
        <div class="rounded-md {{ $contentClasses }} divide-y divide-gray-100 dark:divide-gray-700">
            {{ $content }}
        </div>
    </div>
</div>
