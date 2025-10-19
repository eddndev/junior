@props([
    'label' => null,
    'name' => null,
    'rows' => 4,
    'error' => null,
    'description' => null,
    'value' => null,
    'placeholder' => null,
    'cornerHint' => null,
    'id' => null,
])

@php
    $textareaId = $id ?? $name ?? uniqid('textarea_');
    $hasError = !empty($error);

    // Textarea base classes
    $textareaClasses = 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-neutral-900 placeholder:text-neutral-400 outline-1 -outline-offset-1 focus:outline-2 focus:-outline-offset-2 sm:text-sm/6 dark:text-white dark:placeholder:text-neutral-500 dark:bg-white/5';

    if ($hasError) {
        $textareaClasses .= ' outline-red-300 focus:outline-red-600 dark:outline-red-500/50 dark:focus:outline-red-400 text-red-900 dark:text-red-400';
    } else {
        $textareaClasses .= ' outline-neutral-300 focus:outline-primary-600 dark:outline-white/10 dark:focus:outline-primary-500';
    }
@endphp

<div>
    {{-- Label with optional corner hint --}}
    @if($label || $cornerHint || isset($labelSlot))
        <div class="flex justify-between">
            @if($label || isset($labelSlot))
                <label for="{{ $textareaId }}" class="block text-sm/6 font-medium text-neutral-900 dark:text-white">
                    {{ $labelSlot ?? $label }}
                </label>
            @endif

            @if($cornerHint || isset($cornerHintSlot))
                <span id="{{ $textareaId }}-hint" class="text-sm/6 text-neutral-500 dark:text-neutral-400">
                    {{ $cornerHintSlot ?? $cornerHint }}
                </span>
            @endif
        </div>
    @endif

    {{-- Textarea --}}
    <div class="mt-2">
        <textarea
            id="{{ $textareaId }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @if($hasError)
                aria-invalid="true"
                aria-describedby="{{ $textareaId }}-error"
            @elseif($description || $cornerHint)
                aria-describedby="{{ $description ? $textareaId.'-description' : '' }} {{ $cornerHint ? $textareaId.'-hint' : '' }}"
            @endif
            {{ $attributes->merge(['class' => $textareaClasses]) }}
        >{{ old($name, $value) }}</textarea>
    </div>

    {{-- Error message --}}
    @if($hasError)
        <p id="{{ $textareaId }}-error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ $error }}
        </p>
    @endif

    {{-- Description/help text --}}
    @if($description && !$hasError)
        <p id="{{ $textareaId }}-description" class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            {{ $description }}
        </p>
    @endif
</div>
