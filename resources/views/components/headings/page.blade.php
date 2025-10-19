@props([
    'title' => null,
    'backUrl' => null,
    'backLabel' => 'Atrás',
])

<div>
    {{-- Breadcrumb/Back Navigation --}}
    @if(isset($breadcrumb) || $backUrl)
        <div>
            {{-- Mobile Back Button --}}
            @if($backUrl)
                <nav aria-label="Back" class="sm:hidden">
                    <a href="{{ $backUrl }}" class="flex items-center text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mr-1 -ml-1 size-5 shrink-0 text-neutral-400 dark:text-neutral-500">
                            <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                        {{ $backLabel }}
                    </a>
                </nav>
            @endif

            {{-- Desktop Breadcrumb --}}
            @if(isset($breadcrumb))
                <nav aria-label="Breadcrumb" class="hidden sm:flex">
                    <ol role="list" class="flex items-center space-x-4">
                        {{ $breadcrumb }}
                    </ol>
                </nav>
            @endif
        </div>
    @endif

    {{-- Title & Actions --}}
    <div @class([
        'md:flex md:items-center md:justify-between',
        'mt-2' => isset($breadcrumb) || $backUrl,
    ])>
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl/7 font-bold text-neutral-900 sm:truncate sm:text-3xl sm:tracking-tight dark:text-white">
                {{ $title ?? $slot }}
            </h2>
        </div>

        @if(isset($actions))
            <div class="mt-4 flex shrink-0 md:mt-0 md:ml-4">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
