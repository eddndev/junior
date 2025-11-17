@props([
    'src' => null,
    'alt' => '',
    'size' => 'md',
    'user' => null,
])

@php
    $sizes = [
        'xs' => 'size-6',
        'sm' => 'size-8',
        'md' => 'size-10',
        'lg' => 'size-12',
        'xl' => 'size-14',
        '2xl' => 'size-24',
    ];

    $textSizes = [
        'xs' => 'text-xs',
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-lg',
        '2xl' => 'text-2xl',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $textSizeClass = $textSizes[$size] ?? $textSizes['md'];

    // If user is provided, use their avatar_url accessor
    if ($user) {
        $src = $user->avatar_url;
        $alt = $user->name;
    }
@endphp

@if ($src)
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        {{ $attributes->merge([
            'class' => 'inline-block ' . $sizeClass . ' rounded-full object-cover outline -outline-offset-1 outline-black/5 dark:outline-white/10'
        ]) }}
    />
@else
    @php
        $initials = $user ? $user->initials : collect(explode(' ', $alt))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
    @endphp
    <span
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center ' . $sizeClass . ' rounded-full bg-primary-600 outline -outline-offset-1 outline-black/5 dark:outline-white/10'
        ]) }}
    >
        <span class="{{ $textSizeClass }} font-medium text-white">{{ $initials }}</span>
    </span>
@endif
