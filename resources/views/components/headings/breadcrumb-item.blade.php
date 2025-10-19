@props([
    'href' => '#',
    'current' => false,
])

<li>
    <div class="flex items-center">
        {{-- Separator (except for first item) --}}
        @unless($attributes->has('first'))
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 shrink-0 text-neutral-400 dark:text-neutral-500">
                <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
        @endunless

        <a
            href="{{ $href }}"
            @if($current) aria-current="page" @endif
            @class([
                'text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300',
                'ml-4' => !$attributes->has('first'),
            ])
        >
            {{ $slot }}
        </a>
    </div>
</li>
