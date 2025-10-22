<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-x-1.5 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20']) }}>
    <svg viewBox="0 0 6 6" aria-hidden="true" class="size-1.5 fill-green-500 dark:fill-green-400">
        <circle r="3" cx="3" cy="3" />
    </svg>
    {{ $slot }}
</span>
