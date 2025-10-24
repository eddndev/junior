@props(['title', 'subtitle' => null, 'dialogId' => null])

{{--
    Dialog Header Component

    Componente reutilizable para el header de los dialogs.
    Incluye título, subtítulo opcional y botón de cerrar.

    Uso:
    <x-dialog-header title="Mi Dialog" subtitle="Descripción opcional" dialog-id="my-dialog" />
--}}

<div class="sticky top-0 z-10 border-b border-neutral-200 bg-white px-6 py-4 dark:border-neutral-700 dark:bg-neutral-900">
    <div class="flex items-start justify-between gap-4">
        {{-- Title and Subtitle --}}
        <div class="flex-1 min-w-0">
            <h2 id="{{ $dialogId ? $dialogId . '-title' : 'dialog-title' }}"
                class="text-lg font-semibold text-neutral-900 dark:text-white truncate">
                {{ $title }}
            </h2>

            @if($subtitle)
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $subtitle }}
                </p>
            @endif

            {{-- Optional slot for custom header content --}}
            @if(isset($description))
                <div class="mt-2">
                    {{ $description }}
                </div>
            @endif
        </div>

        {{-- Close Button --}}
        <button type="button"
                command="close"
                @if($dialogId)
                    commandfor="{{ $dialogId }}"
                @endif
                {{ $attributes->merge(['class' => 'flex-shrink-0 rounded-md p-2 text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 dark:hover:text-neutral-300 dark:hover:bg-neutral-800 transition-colors']) }}
                aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    {{-- Optional slot for additional header content (tabs, filters, etc.) --}}
    @if(isset($extra))
        <div class="mt-4">
            {{ $extra }}
        </div>
    @endif
</div>
