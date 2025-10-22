<div {{ $attributes }}>
    <div class="hidden sm:block">
        <div class="border-b border-gray-200 dark:border-white/10">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                {{ $slot }}
            </nav>
        </div>
    </div>
    <div class="sm:hidden">
        <p class="text-sm text-gray-500 dark:text-gray-400 p-4">{{ __('Tab navigation is available on larger screens.') }}</p>
    </div>
</div>
