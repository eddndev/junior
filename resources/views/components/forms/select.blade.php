@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'error' => null,
    'description' => null,
    'cornerHint' => null,
    'id' => null,
    'placeholder' => 'Selecciona una opción',
])

@php
    $selectId = $id ?? $name ?? uniqid('select_');
    $hasError = !empty($error);

    // Button classes for the select trigger
    $buttonClasses = 'grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-neutral-900 outline-1 -outline-offset-1 focus-visible:outline-2 focus-visible:-outline-offset-2 sm:text-sm/6 dark:bg-white/5 dark:text-white';

    if ($hasError) {
        $buttonClasses .= ' outline-red-300 focus-visible:outline-red-600 dark:outline-red-500/50 dark:focus-visible:outline-red-400';
    } else {
        $buttonClasses .= ' outline-neutral-300 focus-visible:outline-primary-600 dark:outline-white/10 dark:focus-visible:outline-primary-500';
    }
@endphp

<div>
    {{-- Label with optional corner hint --}}
    @if($label || $cornerHint || isset($labelSlot))
        <div class="flex justify-between">
            @if($label || isset($labelSlot))
                <label for="{{ $selectId }}" class="block text-sm/6 font-medium text-neutral-900 dark:text-white">
                    {{ $labelSlot ?? $label }}
                </label>
            @endif

            @if($cornerHint || isset($cornerHintSlot))
                <span id="{{ $selectId }}-hint" class="text-sm/6 text-neutral-500 dark:text-neutral-400">
                    {{ $cornerHintSlot ?? $cornerHint }}
                </span>
            @endif
        </div>
    @endif

    {{-- Select component --}}
    <div class="{{ $label || $cornerHint ? 'mt-2' : '' }}">
        <el-select
            id="{{ $selectId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="block"
            @if($hasError)
                aria-invalid="true"
                aria-describedby="{{ $selectId }}-error"
            @elseif($description || $cornerHint)
                aria-describedby="{{ $description ? $selectId.'-description' : '' }} {{ $cornerHint ? $selectId.'-hint' : '' }}"
            @endif
        >
            <button type="button" class="{{ $buttonClasses }}">
                <el-selectedcontent class="col-start-1 row-start-1 truncate pr-6">
                    {{ $placeholder }}
                </el-selectedcontent>

                {{-- Chevron icon --}}
                <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="col-start-1 row-start-1 size-5 self-center justify-self-end text-neutral-500 sm:size-4 dark:text-neutral-400">
                    <path d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                </svg>
            </button>

            {{-- Options dropdown --}}
            <el-options
                anchor="bottom start"
                popover
                class="max-h-60 w-(--button-width) overflow-auto rounded-md bg-white py-1 text-base shadow-lg outline-1 outline-black/5 [--anchor-gap:--spacing(1)] data-leave:transition data-leave:transition-discrete data-leave:duration-100 data-leave:ease-in data-closed:data-leave:opacity-0 sm:text-sm dark:bg-neutral-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
            >
                {{ $slot }}
            </el-options>
        </el-select>
    </div>

    {{-- Error message --}}
    @if($hasError)
        <p id="{{ $selectId }}-error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ $error }}
        </p>
    @endif

    {{-- Description/help text --}}
    @if($description && !$hasError)
        <p id="{{ $selectId }}-description" class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            {{ $description }}
        </p>
    @endif
</div>
