{{--
    Componente de Fila de Tabla - Sistema de Diseño Junior

    Uso:
    <x-ui-table-row :selectable="true">
        <td>...</td>
    </x-ui-table-row>
--}}

@props([
    'selectable' => false,
    'highlight' => false,
])

<tr {{ $attributes->merge(['class' => 'group has-checked:bg-neutral-50 dark:has-checked:bg-neutral-800/50' . ($highlight ? ' hover:bg-neutral-50 dark:hover:bg-neutral-800/30 transition-colors' : '')]) }}>
    @if($selectable)
    <td class="relative px-7 sm:w-12 sm:px-6">
        <div class="absolute inset-y-0 left-0 hidden w-0.5 bg-primary-600 group-has-checked:block dark:bg-primary-500"></div>

        <div class="group absolute top-1/2 left-4 -mt-2 grid size-4 grid-cols-1">
            <input
                type="checkbox"
                class="col-start-1 row-start-1 appearance-none rounded-sm border border-neutral-300 bg-white checked:border-primary-600 checked:bg-primary-600 indeterminate:border-primary-600 indeterminate:bg-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:border-neutral-300 disabled:bg-neutral-100 disabled:checked:bg-neutral-100 dark:border-white/20 dark:bg-neutral-800/50 dark:checked:border-primary-500 dark:checked:bg-primary-500 dark:indeterminate:border-primary-500 dark:indeterminate:bg-primary-500 dark:focus-visible:outline-primary-500 dark:disabled:border-white/10 dark:disabled:bg-neutral-800 dark:disabled:checked:bg-neutral-800 forced-colors:appearance-auto"
            />
            <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-neutral-950/25 dark:group-has-disabled:stroke-white/25">
                <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
            </svg>
        </div>
    </td>
    @endif

    {{ $slot }}
</tr>
