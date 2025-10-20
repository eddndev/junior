{{--
    Componente de Link para Dropdown - Sistema de Diseño Junior

    Uso:
    <x-ui-dropdown-link href="/profile">Mi Perfil</x-ui-dropdown-link>
    <x-ui-dropdown-link href="/settings">Configuración</x-ui-dropdown-link>
--}}

@props([
    'active' => false,
])

@php
$classes = $active
    ? 'flex items-center gap-3 px-4 py-2 text-sm bg-neutral-100 text-neutral-900 focus:outline-hidden dark:bg-white/5 dark:text-white'
    : 'flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 focus:bg-neutral-100 focus:text-neutral-900 focus:outline-hidden dark:text-neutral-300 dark:focus:bg-white/5 dark:focus:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
