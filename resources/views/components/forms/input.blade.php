@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'error' => null,
    'description' => null,
    'value' => null,
    'placeholder' => null,
    'cornerHint' => null,
    'id' => null,
])

@php
    $inputId = $id ?? $name ?? uniqid('input_');
    $hasError = !empty($error);
    $hasLeadingAddon = isset($leadingAddon);
    $hasTrailingAddon = isset($trailingAddon);
    $hasInlineAddons = isset($leadingInline) || isset($trailingInline);

    // Input base classes
    $inputBaseClasses = 'block w-full text-base text-neutral-900 placeholder:text-neutral-400 sm:text-sm/6 dark:text-white dark:placeholder:text-neutral-500';

    // Input border classes (depending on context)
    if ($hasInlineAddons) {
        // Inline addons: no border on input, border on container
        // Adjust padding based on which inline addons are present
        $paddingLeft = isset($leadingInline) ? 'pl-1.5' : 'pl-3';
        $paddingRight = isset($trailingInline) ? 'pr-1.5' : 'pr-3';
        $inputClasses = $inputBaseClasses . ' py-1.5 ' . $paddingRight . ' ' . $paddingLeft . ' bg-transparent border-0 focus:ring-0 focus:outline-none';
    } else {
        // Normal input with borders
        $inputClasses = $inputBaseClasses . ' bg-white rounded-md px-3 py-1.5 outline-1 -outline-offset-1 focus:outline-2 focus:-outline-offset-2 dark:bg-white/5';

        if ($hasError) {
            $inputClasses .= ' outline-red-300 focus:outline-red-600 dark:outline-red-500/50 dark:focus:outline-red-400 text-red-900 dark:text-red-400';
        } else {
            $inputClasses .= ' outline-neutral-300 focus:outline-primary-600 dark:outline-white/10 dark:focus:outline-primary-500';
        }

        // Adjust border radius for external addons
        if ($hasLeadingAddon && !$hasTrailingAddon) {
            $inputClasses = str_replace('rounded-md', 'rounded-r-md -ml-px', $inputClasses);
        } elseif ($hasTrailingAddon && !$hasLeadingAddon) {
            $inputClasses = str_replace('rounded-md', 'rounded-l-md -mr-px', $inputClasses);
        } elseif ($hasLeadingAddon && $hasTrailingAddon) {
            $inputClasses = str_replace('rounded-md', '-mx-px', $inputClasses);
        }
    }

    // Container classes for inline addons
    $containerClasses = 'flex items-center rounded-md bg-white px-3 outline-1 -outline-offset-1 focus-within:outline-2 focus-within:-outline-offset-2 dark:bg-white/5';

    if ($hasError) {
        $containerClasses .= ' outline-red-300 focus-within:outline-red-600 dark:outline-red-500/50 dark:focus-within:outline-red-400';
    } else {
        $containerClasses .= ' outline-neutral-300 focus-within:outline-primary-600 dark:outline-white/10 dark:focus-within:outline-primary-500';
    }

    // Addon classes
    $externalAddonClasses = 'flex shrink-0 items-center bg-white px-3 text-base text-neutral-500 outline-1 -outline-offset-1 outline-neutral-300 sm:text-sm/6 dark:bg-white/5 dark:text-neutral-400 dark:outline-neutral-700';
    $inlineAddonClasses = 'shrink-0 text-base text-neutral-500 select-none sm:text-sm/6 dark:text-neutral-400';
@endphp

<div>
    {{-- Label with optional corner hint --}}
    @if($label || $cornerHint || isset($labelSlot))
        <div class="flex justify-between">
            @if($label || isset($labelSlot))
                <label for="{{ $inputId }}" class="block text-sm/6 font-medium text-neutral-900 dark:text-white">
                    {{ $labelSlot ?? $label }}
                </label>
            @endif

            @if($cornerHint || isset($cornerHintSlot))
                <span id="{{ $inputId }}-hint" class="text-sm/6 text-neutral-500 dark:text-neutral-400">
                    {{ $cornerHintSlot ?? $cornerHint }}
                </span>
            @endif
        </div>
    @endif

    {{-- Input wrapper --}}
    <div class="mt-2">
        @if($hasLeadingAddon || $hasTrailingAddon || $hasInlineAddons)
            <div class="flex{{ $hasInlineAddons ? '' : ' gap-x-0' }}">
                {{-- Leading addon (external) --}}
                @if($hasLeadingAddon)
                    <div class="{{ $externalAddonClasses }} rounded-l-md">
                        {{ $leadingAddon }}
                    </div>
                @endif

                {{-- Inline addons container --}}
                @if($hasInlineAddons)
                    <div class="{{ $containerClasses }} grow">
                        {{-- Leading addon (inline) --}}
                        @if(isset($leadingInline))
                            <div class="{{ $inlineAddonClasses }}">
                                {{ $leadingInline }}
                            </div>
                        @endif

                        {{-- Input --}}
                        <input
                            id="{{ $inputId }}"
                            type="{{ $type }}"
                            name="{{ $name }}"
                            value="{{ old($name, $value) }}"
                            placeholder="{{ $placeholder }}"
                            @if($hasError)
                                aria-invalid="true"
                                aria-describedby="{{ $inputId }}-error"
                            @elseif($description || $cornerHint)
                                aria-describedby="{{ $description ? $inputId.'-description' : '' }} {{ $cornerHint ? $inputId.'-hint' : '' }}"
                            @endif
                            {{ $attributes->merge(['class' => $inputClasses]) }}
                        />

                        {{-- Trailing addon (inline) --}}
                        @if(isset($trailingInline))
                            <div id="{{ $inputId }}-currency" class="{{ $inlineAddonClasses }}">
                                {{ $trailingInline }}
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Regular input --}}
                    <input
                        id="{{ $inputId }}"
                        type="{{ $type }}"
                        name="{{ $name }}"
                        value="{{ old($name, $value) }}"
                        placeholder="{{ $placeholder }}"
                        @if($hasError)
                            aria-invalid="true"
                            aria-describedby="{{ $inputId }}-error"
                        @elseif($description || $cornerHint)
                            aria-describedby="{{ $description ? $inputId.'-description' : '' }} {{ $cornerHint ? $inputId.'-hint' : '' }}"
                        @endif
                        {{ $attributes->merge(['class' => $inputClasses]) }}
                    />
                @endif

                {{-- Trailing addon (external) --}}
                @if($hasTrailingAddon)
                    <div class="{{ $externalAddonClasses }} rounded-r-md">
                        {{ $trailingAddon }}
                    </div>
                @endif
            </div>
        @else
            {{-- Simple input without addons --}}
            <div class="grid grid-cols-1">
                <input
                    id="{{ $inputId }}"
                    type="{{ $type }}"
                    name="{{ $name }}"
                    value="{{ old($name, $value) }}"
                    placeholder="{{ $placeholder }}"
                    @if($hasError)
                        aria-invalid="true"
                        aria-describedby="{{ $inputId }}-error"
                        class="col-start-1 row-start-1 {{ $inputClasses }} pr-10"
                    @else
                        aria-describedby="{{ $description ? $inputId.'-description' : '' }} {{ $cornerHint ? $inputId.'-hint' : '' }}"
                        class="{{ $inputClasses }}"
                    @endif
                    {{ $attributes }}
                />

                {{-- Error icon --}}
                @if($hasError)
                    <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4 dark:text-red-400">
                        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                @endif
            </div>
        @endif
    </div>

    {{-- Error message --}}
    @if($hasError)
        <p id="{{ $inputId }}-error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ $error }}
        </p>
    @endif

    {{-- Description/help text --}}
    @if($description && !$hasError)
        <p id="{{ $inputId }}-description" class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            {{ $description }}
        </p>
    @endif
</div>