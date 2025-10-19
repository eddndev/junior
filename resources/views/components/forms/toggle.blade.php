@props([
    'label' => null,
    'name' => null,
    'value' => '1',
    'checked' => false,
    'id' => null,
    'description' => null,
])

@php
    $toggleId = $id ?? $name ?? uniqid('toggle_');
    $isChecked = old($name, $checked);
@endphp

<div class="flex items-center justify-between">
    @if($label || $description || isset($labelSlot) || isset($descriptionSlot))
        <span class="flex grow flex-col">
            @if($label || isset($labelSlot))
                <label id="{{ $toggleId }}-label" class="text-sm/6 font-medium text-neutral-900 dark:text-white">
                    {{ $labelSlot ?? $label }}
                </label>
            @endif

            @if($description || isset($descriptionSlot))
                <span id="{{ $toggleId }}-description" class="text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $descriptionSlot ?? $description }}
                </span>
            @endif
        </span>
    @endif

    <label class="group relative inline-flex w-11 shrink-0 cursor-pointer rounded-full bg-neutral-200 p-0.5 inset-ring inset-ring-neutral-900/5 outline-offset-2 outline-primary-600 transition-colors duration-200 ease-in-out has-[:checked]:bg-primary-600 has-[:focus-visible]:outline-2 dark:bg-white/5 dark:inset-ring-white/10 dark:outline-primary-500 dark:has-[:checked]:bg-primary-500">
        <input
            id="{{ $toggleId }}"
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            @if($isChecked) checked @endif
            @if($label)
                aria-labelledby="{{ $toggleId }}-label"
            @endif
            @if($description)
                aria-describedby="{{ $toggleId }}-description"
            @endif
            {{ $attributes->merge(['class' => 'sr-only']) }}
        />
        <span class="pointer-events-none size-5 rounded-full bg-white shadow-xs ring-1 ring-neutral-900/5 transition-transform duration-200 ease-in-out group-has-[:checked]:translate-x-5"></span>
    </label>
</div>