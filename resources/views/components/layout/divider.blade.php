@props([
    'label' => null,
])

@if($label || $slot->isNotEmpty())
    <div class="flex items-center">
        <div aria-hidden="true" class="w-full border-t border-neutral-300 dark:border-white/15"></div>
        <div class="relative flex justify-center">
            <span class="bg-white px-2 text-sm text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                {{ $slot->isNotEmpty() ? $slot : $label }}
            </span>
        </div>
        <div aria-hidden="true" class="w-full border-t border-neutral-300 dark:border-white/15"></div>
    </div>
@else
    <div aria-hidden="true" class="border-t border-neutral-300 dark:border-white/15"></div>
@endif
