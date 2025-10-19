@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'checked' => false,
    'id' => null,
    'description' => null,
])

@php
    $radioId = $id ?? $name . '_' . $value ?? uniqid('radio_');
    $isChecked = old($name) == $value || ($checked && !old($name));
@endphp

<div class="{{ $description ? 'relative flex items-start' : 'flex items-center' }}">
    <div class="flex h-6 items-center">
        <input
            id="{{ $radioId }}"
            type="radio"
            name="{{ $name }}"
            value="{{ $value }}"
            @if($isChecked) checked @endif
            @if($description)
                aria-describedby="{{ $radioId }}-description"
            @endif
            {{ $attributes->merge([
                'class' => 'relative size-4 appearance-none rounded-full border border-neutral-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-primary-600 checked:bg-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:border-neutral-300 disabled:bg-neutral-100 disabled:before:bg-neutral-400 dark:border-white/10 dark:bg-white/5 dark:checked:border-primary-500 dark:checked:bg-primary-500 dark:focus-visible:outline-primary-500 dark:disabled:border-white/5 dark:disabled:bg-white/10 dark:disabled:before:bg-white/20 forced-colors:appearance-auto forced-colors:before:hidden'
            ]) }}
        />
    </div>

    @if($label || $description || isset($labelSlot) || isset($descriptionSlot))
        <div class="ml-3 text-sm/6">
            @if($label || isset($labelSlot))
                <label for="{{ $radioId }}" class="font-medium text-neutral-900 dark:text-white">
                    {{ $labelSlot ?? $label }}
                </label>
            @endif

            @if($description || isset($descriptionSlot))
                <p id="{{ $radioId }}-description" class="text-neutral-500 dark:text-neutral-400">
                    {{ $descriptionSlot ?? $description }}
                </p>
            @endif
        </div>
    @endif
</div>