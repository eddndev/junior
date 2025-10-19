@props([
    'color' => 'neutral',
    'dot' => false,
])

@php
    // Define color variations
    $dotColors = [
        'red' => 'fill-red-500 dark:fill-red-400',
        'yellow' => 'fill-yellow-500 dark:fill-yellow-400',
        'green' => 'fill-green-500 dark:fill-green-400',
        'blue' => 'fill-blue-500 dark:fill-blue-400',
        'primary' => 'fill-primary-500 dark:fill-primary-400',
        'purple' => 'fill-purple-500 dark:fill-purple-400',
        'pink' => 'fill-pink-500 dark:fill-pink-400',
        'neutral' => 'fill-neutral-500 dark:fill-neutral-400',
    ];

    $dotClass = $dotColors[$color] ?? $dotColors['neutral'];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium text-neutral-900 inset-ring inset-ring-neutral-200 dark:text-white dark:inset-ring-white/10'
]) }}>
    @if($dot)
        <svg viewBox="0 0 6 6" aria-hidden="true" class="size-1.5 {{ $dotClass }}">
            <circle r="3" cx="3" cy="3" />
        </svg>
    @endif
    {{ $slot }}
</span>