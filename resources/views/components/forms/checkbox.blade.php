@props([
    'label' => null,
    'name' => null,
    'value' => '1',
    'checked' => false,
    'id' => null,
    'description' => null,
])

@php
    $checkboxId = $id ?? $name ?? uniqid('checkbox_');
    $isChecked = old($name, $checked);
@endphp

<div class="flex gap-3">
    <div class="flex h-6 shrink-0 items-center">
        <div class="group grid size-4 grid-cols-1">
            <input
                id="{{ $checkboxId }}"
                type="checkbox"
                name="{{ $name }}"
                value="{{ $value }}"
                @if($isChecked) checked @endif
                @if($description)
                    aria-describedby="{{ $checkboxId }}-description"
                @endif
                {{ $attributes->merge([
                    'class' => 'col-start-1 row-start-1 appearance-none rounded-sm border border-neutral-300 bg-white checked:border-primary-600 checked:bg-primary-600 indeterminate:border-primary-600 indeterminate:bg-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:border-neutral-300 disabled:bg-neutral-100 disabled:checked:bg-neutral-100 dark:border-white/10 dark:bg-white/5 dark:checked:border-primary-500 dark:checked:bg-primary-500 dark:indeterminate:border-primary-500 dark:indeterminate:bg-primary-500 dark:focus-visible:outline-primary-500 dark:disabled:border-white/5 dark:disabled:bg-white/10 dark:disabled:checked:bg-white/10 forced-colors:appearance-auto'
                ]) }}
            />
            <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-neutral-950/25 dark:group-has-disabled:stroke-white/25">
                <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
            </svg>
        </div>
    </div>

    @if($label || $description || isset($labelSlot) || isset($descriptionSlot))
        <div class="text-sm/6">
            @if($label || isset($labelSlot))
                <label for="{{ $checkboxId }}" class="font-medium text-neutral-900 dark:text-white">
                    {{ $labelSlot ?? $label }}
                </label>
            @endif

            @if($description || isset($descriptionSlot))
                <p id="{{ $checkboxId }}-description" class="text-neutral-500 dark:text-neutral-400">
                    {{ $descriptionSlot ?? $description }}
                </p>
            @endif
        </div>
    @endif
</div>
