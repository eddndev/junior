{{--
    Componente de Header de Tabla - Sistema de Diseño Junior

    Uso:
    <x-ui-table-header class="min-w-48">Nombre</x-ui-table-header>
--}}

@props([
    'align' => 'left',
    'srOnly' => false,
])

@php
$alignClass = match($align) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
@endphp

<th {{ $attributes->merge(['scope' => 'col', 'class' => "px-3 py-3.5 {$alignClass} text-sm font-semibold text-neutral-900 dark:text-white"]) }}>
    @if($srOnly)
    <span class="sr-only">{{ $slot }}</span>
    @else
    {{ $slot }}
    @endif
</th>