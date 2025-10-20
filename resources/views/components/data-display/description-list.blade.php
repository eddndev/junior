@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes }}>
    @if($title || $description || isset($titleSlot) || isset($descriptionSlot))
        <div class="px-4 sm:px-0">
            @if($title || isset($titleSlot))
                <h3 class="text-base/7 font-semibold text-neutral-900 dark:text-white">
                    {{ $titleSlot ?? $title }}
                </h3>
            @endif

            @if($description || isset($descriptionSlot))
                <p class="mt-1 max-w-2xl text-sm/6 text-neutral-500 dark:text-neutral-400">
                    {{ $descriptionSlot ?? $description }}
                </p>
            @endif
        </div>
    @endif

    <div class="{{ ($title || $description || isset($titleSlot) || isset($descriptionSlot)) ? 'mt-6' : '' }} border-t border-neutral-100 dark:border-white/10">
        <dl class="divide-y divide-neutral-100 dark:divide-white/10">
            {{ $slot }}
        </dl>
    </div>
</div>
