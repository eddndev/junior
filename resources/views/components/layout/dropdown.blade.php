{{--
    Componente de Dropdown - Sistema de Diseño Junior

    Uso:
    <x-ui-dropdown>
        <x-slot:trigger>
            <button class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-xs inset-ring-1 inset-ring-neutral-300 hover:bg-neutral-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">
                Opciones
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="-mr-1 size-5 text-neutral-400">
                    <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                </svg>
            </button>
        </x-slot:trigger>

        <x-ui-dropdown-link href="#">Configuración</x-ui-dropdown-link>
        <x-ui-dropdown-link href="#">Soporte</x-ui-dropdown-link>
        <x-ui-dropdown-divider />
        <x-ui-dropdown-link href="#">Cerrar sesión</x-ui-dropdown-link>
    </x-ui-dropdown>
--}}

@props([
    'width' => '56',
    'anchor' => 'bottom end',
])

@php
$widthClass = match ($width) {
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    '72' => 'w-72',
    '80' => 'w-80',
    default => 'w-56',
};
@endphp

<el-dropdown class="inline-block">
    {{ $trigger }}

    <el-menu
        anchor="{{ $anchor }}"
        popover
        class="{{ $widthClass }} origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:bg-neutral-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
    >
        <div class="py-1">
            {{ $slot }}
        </div>
    </el-menu>
</el-dropdown>
