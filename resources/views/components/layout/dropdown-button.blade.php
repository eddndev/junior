{{--
    Componente de Botón para Dropdown - Sistema de Diseño Junior

    Uso dentro de un dropdown (para forms):
    <form action="/logout" method="POST">
        <x-ui-dropdown-button type="submit">Cerrar sesión</x-ui-dropdown-button>
    </form>
--}}

@props([
    'type' => 'button',
])

<button {{ $attributes->merge(['type' => $type, 'class' => 'flex w-full items-center gap-3 px-4 py-2 text-left text-sm text-neutral-700 focus:bg-neutral-100 focus:text-neutral-900 focus:outline-hidden dark:text-neutral-300 dark:focus:bg-white/5 dark:focus:text-white']) }}>
    {{ $slot }}
</button>
