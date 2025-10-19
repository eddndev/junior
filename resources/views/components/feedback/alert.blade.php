@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'dismissible' => true,
])

@php
    // Container classes based on type
    $containerClasses = 'rounded-md p-4';

    $typeClasses = match($type) {
        'success' => 'bg-green-50 dark:bg-green-500/10 dark:outline dark:outline-green-500/20',
        'error' => 'bg-red-50 dark:bg-red-500/15 dark:outline dark:outline-red-500/25',
        'warning' => 'bg-yellow-50 dark:bg-yellow-500/10 dark:outline dark:outline-yellow-500/15',
        'info' => 'bg-blue-50 dark:bg-blue-500/10 dark:outline dark:outline-blue-500/20',
        default => 'bg-blue-50 dark:bg-blue-500/10 dark:outline dark:outline-blue-500/20',
    };

    // Icon color classes
    $iconClasses = match($type) {
        'success' => 'text-green-400',
        'error' => 'text-red-400',
        'warning' => 'text-yellow-400 dark:text-yellow-300',
        'info' => 'text-blue-400',
        default => 'text-blue-400',
    };

    // Title color classes
    $titleClasses = match($type) {
        'success' => 'text-green-800 dark:text-green-300',
        'error' => 'text-red-800 dark:text-red-200',
        'warning' => 'text-yellow-800 dark:text-yellow-100',
        'info' => 'text-blue-800 dark:text-blue-200',
        default => 'text-blue-800 dark:text-blue-200',
    };

    // Content color classes
    $contentClasses = match($type) {
        'success' => 'text-green-700 dark:text-green-200/80',
        'error' => 'text-red-700 dark:text-red-200/80',
        'warning' => 'text-yellow-700 dark:text-yellow-100/80',
        'info' => 'text-blue-700 dark:text-blue-200/80',
        default => 'text-blue-700 dark:text-blue-200/80',
    };

    // Dismiss button classes
    $dismissClasses = match($type) {
        'success' => 'bg-green-50 text-green-500 hover:bg-green-100 focus-visible:ring-green-600 focus-visible:ring-offset-green-50 dark:bg-transparent dark:text-green-400 dark:hover:bg-green-500/10 dark:focus-visible:ring-green-500 dark:focus-visible:ring-offset-green-900',
        'error' => 'bg-red-50 text-red-500 hover:bg-red-100 focus-visible:ring-red-600 focus-visible:ring-offset-red-50 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-500/10 dark:focus-visible:ring-red-500 dark:focus-visible:ring-offset-red-900',
        'warning' => 'bg-yellow-50 text-yellow-500 hover:bg-yellow-100 focus-visible:ring-yellow-600 focus-visible:ring-offset-yellow-50 dark:bg-transparent dark:text-yellow-400 dark:hover:bg-yellow-500/10 dark:focus-visible:ring-yellow-500 dark:focus-visible:ring-offset-yellow-900',
        'info' => 'bg-blue-50 text-blue-500 hover:bg-blue-100 focus-visible:ring-blue-600 focus-visible:ring-offset-blue-50 dark:bg-transparent dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus-visible:ring-blue-500 dark:focus-visible:ring-offset-blue-900',
        default => 'bg-blue-50 text-blue-500 hover:bg-blue-100 focus-visible:ring-blue-600 focus-visible:ring-offset-blue-50 dark:bg-transparent dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus-visible:ring-blue-500 dark:focus-visible:ring-offset-blue-900',
    };

    // Icon SVG paths
    $icons = [
        'success' => 'M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z',
        'error' => 'M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z',
        'warning' => 'M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z',
        'info' => 'M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z',
    ];

    $hasContent = $slot->isNotEmpty();
@endphp

<div x-data="{ show: true }" x-show="show" x-transition class="{{ $containerClasses }} {{ $typeClasses }}">
    <div class="flex">
        <div class="shrink-0">
            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 {{ $iconClasses }}">
                <path d="{{ $icons[$type] }}" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3{{ $dismissible ? ' flex-1' : '' }}">
            @if($title || isset($titleSlot))
                <h3 class="text-sm font-medium {{ $titleClasses }}">
                    {{ $titleSlot ?? $title }}
                </h3>
            @endif

            @if($hasContent)
                <div class="{{ $title ? 'mt-2' : '' }} text-sm {{ $contentClasses }}">
                    {{ $slot }}
                </div>
            @endif
        </div>

        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button
                        type="button"
                        @click="show = false"
                        class="inline-flex rounded-md p-1.5 focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-1 focus-visible:outline-hidden {{ $dismissClasses }}"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
