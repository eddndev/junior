@props([
    'title' => null,
])

<div class="border-b border-neutral-200 px-4 py-5 sm:px-6 dark:border-white/10">
    <div class="-mt-2 -ml-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
        <div class="mt-2 ml-4">
            <h3 class="text-base font-semibold text-neutral-900 dark:text-white">
                {{ $title ?? $slot }}
            </h3>
        </div>

        @if(isset($action))
            <div class="mt-2 ml-4 shrink-0">
                {{ $action }}
            </div>
        @endif
    </div>
</div>
