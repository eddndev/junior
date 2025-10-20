@props([
    'title' => null,
    'columns' => '3',
])

<div {{ $attributes }}>
    @if($title || isset($titleSlot))
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white">
            {{ $titleSlot ?? $title }}
        </h3>
    @endif

    <dl class="{{ ($title || isset($titleSlot)) ? 'mt-5' : '' }} grid grid-cols-1 gap-5 sm:grid-cols-{{ $columns }}">
        {{ $slot }}
    </dl>
</div>