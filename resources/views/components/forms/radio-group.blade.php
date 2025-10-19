@props([
    'legend' => null,
    'description' => null,
    'srOnly' => false,
])

<fieldset {{ $attributes }}>
    @if($legend || isset($legendSlot))
        <legend class="{{ $srOnly ? 'sr-only' : 'text-sm/6 font-semibold text-neutral-900 dark:text-white' }}">
            {{ $legendSlot ?? $legend }}
        </legend>
    @endif

    @if($description || isset($descriptionSlot))
        <p class="mt-1 text-sm/6 text-neutral-600 dark:text-neutral-400">
            {{ $descriptionSlot ?? $description }}
        </p>
    @endif

    <div class="{{ ($legend || $description) && !$srOnly ? 'mt-6' : '' }} space-y-{{ $attributes->get('spacing') ?? '6' }}">
        {{ $slot }}
    </div>
</fieldset>