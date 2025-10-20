@props([
    'divided' => true,
])

<div {{ $attributes->merge([
    'class' => ($divided ? 'divide-y divide-neutral-200 dark:divide-white/10 ' : '') . 'overflow-hidden rounded-lg bg-white shadow-sm dark:bg-neutral-800/50 dark:shadow-none dark:outline dark:-outline-offset-1 dark:outline-white/10'
]) }}>
    @if(isset($header))
        <div class="px-4 py-5 sm:px-6">
            {{ $header }}
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="px-4 py-5 sm:p-6">
            {{ $slot }}
        </div>
    @endif

    @if(isset($footer))
        <div class="px-4 py-4 sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
