{{--
    Componente de Celda de Tabla - Sistema de Diseño Junior

    Uso:
    <x-ui-table-cell :primary="true">Lindsay Walton</x-ui-table-cell>
--}}

@props([
    'primary' => false,
    'align' => 'left',
])

@php
$alignClass = match($align) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};

$textClass = $primary
    ? 'text-sm font-medium text-neutral-900 group-has-checked:text-primary-600 dark:text-white dark:group-has-checked:text-primary-400'
    : 'text-sm text-neutral-500 dark:text-neutral-400';
@endphp

<td {{ $attributes->merge(['class' => "px-3 py-4 whitespace-nowrap {$textClass} {$alignClass}"]) }}>
    {{ $slot }}
</td>