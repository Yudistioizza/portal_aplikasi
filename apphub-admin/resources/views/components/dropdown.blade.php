@props([
    'align' => 'right', 
    'width' => '48', 
    'contentClasses' => 'py-1 bg-white', 
    'sidebarOpen' => true // Boolean dari parent layout untuk mendeteksi state sidebar
])

@php
$widthClass = match ($width) {
    'auto' => 'w-auto',
    'full' => 'w-full',
    default => $width,
};

$alignmentClass = match ($align) {
    'right' => $sidebarOpen ? 'right-0' : 'left-full',
    'left' => 'left-0',
    default => $align,
};
@endphp

<div class="w-full" x-data="{ open: false }" @click.outside="open = false" x-init="open = false" x-cloak>
    {{-- Trigger --}}
    <div @click="open = !open" class="cursor-pointer w-full">
        {{ $trigger }}
    </div>

    {{-- Dropdown content (pushes items below it) --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-100 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="mt-1 {{ $alignmentClass }} {{ $widthClass }} shadow-md border {{ $contentClasses }} {{ $sidebarOpen ? 'left-0' : 'left-full ml-1' }}"
    >
        {{ $content }}
    </div>
</div>
