@props(['priority'])

@php
$classes = match($priority) {
    'low' => 'bg-neutral-50 text-neutral-600 ring-neutral-500/10 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30',
    'medium' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-900/20 dark:text-yellow-400 dark:ring-yellow-600/30',
    'high' => 'bg-orange-50 text-orange-700 ring-orange-700/10 dark:bg-orange-900/20 dark:text-orange-400 dark:ring-orange-600/30',
    'critical' => 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30',
    default => 'bg-neutral-50 text-neutral-600 ring-neutral-500/10 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30',
};

$label = match($priority) {
    'low' => 'Baja',
    'medium' => 'Media',
    'high' => 'Alta',
    'critical' => 'Crítica',
    default => ucfirst($priority),
};

$icon = match($priority) {
    'critical' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    'high' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />',
    'medium' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />',
    'low' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />',
    default => '',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {$classes}"]) }}>
    @if($icon)
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    {{ $label }}
</span>
