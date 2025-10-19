@props([
    'title' => null,
    'description' => null,
])

<div class="border-b border-neutral-200 pb-5 dark:border-white/10">
    <h3 class="text-base font-semibold text-neutral-900 dark:text-white">
        {{ $title ?? $slot }}
    </h3>

    @if($description || isset($content))
        <p class="mt-2 max-w-4xl text-sm text-neutral-500 dark:text-neutral-400">
            {{ $description ?? $content }}
        </p>
    @endif
</div>