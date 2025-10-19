@props([
    'variant' => 'primary', // primary, secondary, danger, ghost
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseClasses = 'inline-flex items-center rounded-md font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 transition-colors';

    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-3.5 py-2.5 text-sm',
        'lg' => 'px-4 py-3 text-base',
        default => 'px-3.5 py-2.5 text-sm',
    };

    // Variant classes
    $variantClasses = match($variant) {
        'primary' => 'bg-primary-600 text-white shadow-xs hover:bg-primary-500 focus-visible:outline-primary-600 dark:bg-primary-500 dark:shadow-none dark:hover:bg-primary-400 dark:focus-visible:outline-primary-500',

        'secondary' => 'bg-white text-neutral-900 shadow-xs inset-ring inset-ring-neutral-300 hover:bg-neutral-50 focus-visible:outline-primary-600 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20 dark:focus-visible:outline-primary-500',

        'danger' => 'bg-red-600 text-white shadow-xs hover:bg-red-500 focus-visible:outline-red-600 dark:bg-red-500 dark:shadow-none dark:hover:bg-red-400 dark:focus-visible:outline-red-500',

        'ghost' => 'text-neutral-700 hover:bg-neutral-100 focus-visible:outline-primary-600 dark:text-neutral-300 dark:hover:bg-white/5 dark:focus-visible:outline-primary-500',

        default => 'bg-primary-600 text-white shadow-xs hover:bg-primary-500 focus-visible:outline-primary-600 dark:bg-primary-500 dark:shadow-none dark:hover:bg-primary-400 dark:focus-visible:outline-primary-500',
    };

    // Disabled classes
    $disabledClasses = $disabled
        ? 'opacity-50 cursor-not-allowed pointer-events-none'
        : '';
@endphp

<button
    type="{{ $type }}"
    @if($disabled) disabled @endif
    {{ $attributes->merge([
        'class' => implode(' ', array_filter([
            $baseClasses,
            $sizeClasses,
            $variantClasses,
            $disabledClasses,
        ]))
    ]) }}
>
    {{ $slot }}
</button>
