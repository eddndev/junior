@props([
    'term' => null,
    'description' => null,
])

<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
    @if($term || isset($termSlot))
        <dt class="text-sm/6 font-medium text-neutral-900 dark:text-neutral-100">
            {{ $termSlot ?? $term }}
        </dt>
    @endif

    @if($description || isset($descriptionSlot) || $slot->isNotEmpty())
        <dd class="mt-1 text-sm/6 text-neutral-700 sm:col-span-2 sm:mt-0 dark:text-neutral-400">
            {{ $descriptionSlot ?? $description ?? $slot }}
        </dd>
    @endif
</div>
