@props([
    'href' => '#',
    'active' => false,
])

@php
$baseClasses = 'border-b-2 px-1 py-4 text-sm font-medium whitespace-nowrap';
$activeClasses = 'border-primary-500 text-primary-600 dark:border-primary-400 dark:text-primary-400';
$inactiveClasses = 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-white/20 dark:hover:text-gray-200';

$classes = $active ? $baseClasses . ' ' . $activeClasses : $baseClasses . ' ' . $inactiveClasses;
@endphp

<a href="{{ $href }}" @if($active) aria-current="page" @endif {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
