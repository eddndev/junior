{{--
    Componente de Modal - Sistema de Diseño Junior

    Uso:
    <x-ui-modal
        id="confirm-deletion"
        title="Confirmar eliminación"
        :danger="true"
    >
        <x-slot:content>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                ¿Estás seguro de que deseas eliminar este registro?
            </p>
        </x-slot:content>

        <x-slot:actions>
            <button type="button" command="close" commandfor="confirm-deletion" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto dark:bg-red-500 dark:shadow-none dark:hover:bg-red-400">
                Eliminar
            </button>
            <button type="button" command="close" commandfor="confirm-deletion" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-xs inset-ring-1 inset-ring-neutral-300 hover:bg-neutral-50 sm:mt-0 sm:w-auto dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">
                Cancelar
            </button>
        </x-slot:actions>
    </x-ui-modal>

    Para abrir: <button command="show-modal" commandfor="confirm-deletion">Abrir Modal</button>
--}}

@props([
    'id',
    'title' => null,
    'maxWidth' => 'lg',
    'danger' => false,
    'success' => false,
    'warning' => false,
    'info' => false,
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    default => 'sm:max-w-lg',
};

// Determinar el icono y color según el tipo
$iconBgClass = 'bg-primary-100 dark:bg-primary-500/10';
$iconColorClass = 'text-primary-600 dark:text-primary-400';
$iconPath = 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';

if ($danger) {
    $iconBgClass = 'bg-red-100 dark:bg-red-500/10';
    $iconColorClass = 'text-red-600 dark:text-red-400';
    $iconPath = 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z';
} elseif ($success) {
    $iconBgClass = 'bg-emerald-100 dark:bg-emerald-500/10';
    $iconColorClass = 'text-emerald-600 dark:text-emerald-400';
    $iconPath = 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';
} elseif ($warning) {
    $iconBgClass = 'bg-amber-100 dark:bg-amber-500/10';
    $iconColorClass = 'text-amber-600 dark:text-amber-400';
    $iconPath = 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z';
}
@endphp

<el-dialog>
    <dialog
        id="{{ $id }}"
        aria-labelledby="{{ $id }}-title"
        class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent"
    >
        <el-dialog-backdrop class="fixed inset-0 bg-neutral-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in dark:bg-neutral-900/50"></el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full {{ $maxWidthClass }} sm:p-6 data-closed:sm:translate-y-0 data-closed:sm:scale-95 dark:bg-neutral-800 dark:outline dark:-outline-offset-1 dark:outline-white/10">

                <!-- Close Button -->
                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                    <button
                        type="button"
                        command="close"
                        commandfor="{{ $id }}"
                        class="rounded-md bg-white text-neutral-400 hover:text-neutral-500 focus:outline-2 focus:outline-offset-2 focus:outline-primary-600 dark:bg-neutral-800 dark:hover:text-neutral-300 dark:focus:outline-white"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="sm:flex sm:items-start">
                    @if($danger || $success || $warning || $info || isset($icon))
                    <div class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full {{ $iconBgClass }} sm:mx-0 sm:size-10">
                        @if(isset($icon))
                            {{ $icon }}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 {{ $iconColorClass }}">
                                <path d="{{ $iconPath }}" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @endif
                    </div>
                    @endif

                    <div class="mt-3 text-center sm:mt-0 {{ ($danger || $success || $warning || $info || isset($icon)) ? 'sm:ml-4' : '' }} sm:text-left">
                        @if($title)
                        <h3 id="{{ $id }}-title" class="text-base font-semibold text-neutral-900 dark:text-white">
                            {{ $title }}
                        </h3>
                        @endif

                        <div class="{{ $title ? 'mt-2' : '' }}">
                            {{ $content ?? $slot }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if(isset($actions))
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    {{ $actions }}
                </div>
                @endif

            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>
