@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'description' => null,
    'showIcon' => true,
    'autoHide' => true,
    'duration' => 5000, // milliseconds
])

@php
    // Icon color classes based on type
    $iconClasses = match($type) {
        'success' => 'text-green-400',
        'error' => 'text-red-400',
        'warning' => 'text-yellow-400 dark:text-yellow-300',
        'info' => 'text-neutral-400',
        default => 'text-neutral-400',
    };

    // Action button classes based on type
    $primaryActionClasses = match($type) {
        'success' => 'text-green-600 hover:text-green-500 focus:outline-green-500 dark:text-green-400 dark:hover:text-green-300 dark:focus:outline-green-400',
        'error' => 'text-red-600 hover:text-red-500 focus:outline-red-500 dark:text-red-400 dark:hover:text-red-300 dark:focus:outline-red-400',
        'warning' => 'text-yellow-600 hover:text-yellow-500 focus:outline-yellow-500 dark:text-yellow-400 dark:hover:text-yellow-300 dark:focus:outline-yellow-400',
        'info' => 'text-primary-600 hover:text-primary-500 focus:outline-primary-500 dark:text-primary-400 dark:hover:text-primary-300 dark:focus:outline-primary-400',
        default => 'text-primary-600 hover:text-primary-500 focus:outline-primary-500 dark:text-primary-400 dark:hover:text-primary-300 dark:focus:outline-primary-400',
    };

    // Icons SVG paths
    $icons = [
        'success' => [
            'viewBox' => '0 0 24 24',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'path' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
        'error' => [
            'viewBox' => '0 0 24 24',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'path' => 'm9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
        'warning' => [
            'viewBox' => '0 0 24 24',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'path' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z',
        ],
        'info' => [
            'viewBox' => '0 0 24 24',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'path' => 'M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z',
        ],
    ];

    $icon = $icons[$type];
    $hasActions = isset($actions);
@endphp

<div
    x-data="{
        show: true,
        init() {
            @if($autoHide)
            setTimeout(() => {
                this.show = false;
            }, {{ $duration }});
            @endif
        }
    }"
    x-show="show"
    x-transition:enter="transform transition duration-300 ease-out"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-x-2 sm:translate-y-0"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition duration-100 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="pointer-events-auto w-full max-w-sm rounded-lg bg-white shadow-lg outline-1 outline-black/5 dark:bg-neutral-800 dark:-outline-offset-1 dark:outline-white/10"
>
    <div class="p-4">
        <div class="flex items-start">
            @if($showIcon)
                <div class="shrink-0">
                    <svg
                        viewBox="{{ $icon['viewBox'] }}"
                        fill="{{ $icon['fill'] }}"
                        stroke="{{ $icon['stroke'] }}"
                        stroke-width="1.5"
                        data-slot="icon"
                        aria-hidden="true"
                        class="size-6 {{ $iconClasses }}"
                    >
                        <path d="{{ $icon['path'] }}" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            @endif

            <div class="{{ $showIcon ? 'ml-3' : '' }} w-0 flex-1 pt-0.5">
                @if($title)
                    <p class="text-sm font-medium text-neutral-900 dark:text-white">
                        {{ $title }}
                    </p>
                @endif

                @if($description || isset($content))
                    <p class="{{ $title ? 'mt-1' : '' }} text-sm text-neutral-500 dark:text-neutral-400">
                        {{ $content ?? $description }}
                    </p>
                @endif

                @if($hasActions)
                    <div class="mt-3 flex space-x-7">
                        {{ $actions }}
                    </div>
                @endif
            </div>

            <div class="ml-4 flex shrink-0">
                <button
                    type="button"
                    @click="show = false"
                    class="inline-flex rounded-md text-neutral-400 hover:text-neutral-500 focus:outline-2 focus:outline-offset-2 focus:outline-primary-600 dark:hover:text-white dark:focus:outline-primary-500"
                >
                    <span class="sr-only">Close</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
