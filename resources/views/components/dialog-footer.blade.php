@props([
    'cancelText' => 'Cancelar',
    'submitText' => 'Guardar',
    'submitAction' => null,
    'cancelAction' => null,
    'dialogId' => null,
    'align' => 'end', // 'start', 'center', 'end', 'between'
    'loading' => false,
])

@php
$alignClasses = [
    'start' => 'justify-start',
    'center' => 'justify-center',
    'end' => 'justify-end',
    'between' => 'justify-between',
];
@endphp

{{--
    Dialog Footer Component

    Componente reutilizable para el footer de los dialogs.
    Incluye botones de cancelar y guardar con estados de loading.

    Uso básico:
    <x-dialog-footer dialog-id="my-dialog" submit-action="save" />

    Uso avanzado con slots:
    <x-dialog-footer dialog-id="my-dialog">
        <button wire:click="delete" class="...">Eliminar</button>
        <button wire:click="save" class="...">Guardar</button>
    </x-dialog-footer>
--}}

<div class="sticky bottom-0 border-t border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800">
    <div class="flex items-center gap-3 {{ $alignClasses[$align] }}">
        @if($slot->isEmpty())
            {{-- Default buttons --}}

            {{-- Cancel Button --}}
            <button type="button"
                    @if($cancelAction)
                        wire:click="{{ $cancelAction }}"
                    @else
                        command="close"
                        @if($dialogId)
                            commandfor="{{ $dialogId }}"
                        @endif
                    @endif
                    @if($loading)
                        disabled
                    @endif
                    class="rounded-md px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-200 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                {{ $cancelText }}
            </button>

            {{-- Submit Button --}}
            @if($submitAction)
                <button type="button"
                        wire:click="{{ $submitAction }}"
                        @if($loading)
                            disabled
                        @endif
                        class="inline-flex items-center gap-2 rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    @if($loading)
                        {{-- Loading Spinner --}}
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    @endif
                    <span>{{ $submitText }}</span>
                </button>
            @endif
        @else
            {{-- Custom buttons via slot --}}
            {{ $slot }}
        @endif
    </div>

    {{-- Optional slot for additional footer content (warnings, help text, etc.) --}}
    @if(isset($extra))
        <div class="mt-3 border-t border-neutral-200 pt-3 dark:border-neutral-700">
            {{ $extra }}
        </div>
    @endif
</div>
