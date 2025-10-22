<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-x-1.5 rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-600 ring-1 ring-inset ring-neutral-500/10 dark:bg-neutral-400/10 dark:text-neutral-400 dark:ring-neutral-400/20']) }}>
    <svg viewBox="0 0 6 6" aria-hidden="true" class="size-1.5 fill-neutral-500 dark:fill-neutral-400">
        <circle r="3" cx="3" cy="3" />
    </svg>
    {{ $slot }}
</span>
