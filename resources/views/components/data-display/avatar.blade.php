@props([
    'src' => null,
    'alt' => '',
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => 'size-6',
        'sm' => 'size-8',
        'md' => 'size-10',
        'lg' => 'size-12',
        'xl' => 'size-14',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    {{ $attributes->merge([
        'class' => 'inline-block ' . $sizeClass . ' rounded-full outline -outline-offset-1 outline-black/5 dark:outline-white/10'
    ]) }}
/>
