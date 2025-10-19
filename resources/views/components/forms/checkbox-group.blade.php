@props([
    'legend' => null,
    'srOnly' => true,
])

<fieldset {{ $attributes }}>
    @if($legend || isset($legendSlot))
        <legend class="{{ $srOnly ? 'sr-only' : 'text-sm/6 font-semibold text-neutral-900 dark:text-white' }}">
            {{ $legendSlot ?? $legend }}
        </legend>
    @endif

    <div class="space-y-5">
        {{ $slot }}
    </div>
</fieldset>