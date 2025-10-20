@props([
    'label' => null,
    'value' => null,
])

<div {{ $attributes->merge([
    'class' => 'overflow-hidden rounded-lg bg-white px-4 py-5 shadow-sm sm:p-6 dark:bg-neutral-800/75 dark:inset-ring dark:inset-ring-white/10'
]) }}>
    @if($label || isset($labelSlot))
        <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">
            {{ $labelSlot ?? $label }}
        </dt>
    @endif

    @if($value || isset($valueSlot) || $slot->isNotEmpty())
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-neutral-900 dark:text-white">
            {{ $valueSlot ?? $value ?? $slot }}
        </dd>
    @endif
</div>