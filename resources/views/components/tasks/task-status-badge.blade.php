@props(['status'])

@php
$classes = match($status) {
    'pending' => 'bg-neutral-50 text-neutral-700 ring-neutral-600/20 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30',
    'in_progress' => 'bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30',
    'completed' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30',
    'cancelled' => 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30',
    default => 'bg-neutral-50 text-neutral-700 ring-neutral-600/20 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30',
};

$label = match($status) {
    'pending' => 'Pendiente',
    'in_progress' => 'En Progreso',
    'completed' => 'Completada',
    'cancelled' => 'Cancelada',
    default => ucfirst($status),
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {$classes}"]) }}>
    {{ $label }}
</span>
