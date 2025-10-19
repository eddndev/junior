@props([
    'titlePlaceholder' => 'Título',
    'descriptionPlaceholder' => 'Escribe una descripción...',
    'titleName' => 'title',
    'descriptionName' => 'description',
    'titleValue' => null,
    'descriptionValue' => null,
    'descriptionRows' => 2,
    'showToolbar' => true,
])

<div class="relative">
    <div class="rounded-lg bg-white outline-1 -outline-offset-1 outline-neutral-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-primary-600 dark:bg-neutral-800/50 dark:outline-white/10 dark:focus-within:outline-primary-500">
        {{-- Title input --}}
        <label for="{{ $titleName }}" class="sr-only">{{ $titlePlaceholder }}</label>
        <input
            id="{{ $titleName }}"
            type="text"
            name="{{ $titleName }}"
            value="{{ old($titleName, $titleValue) }}"
            placeholder="{{ $titlePlaceholder }}"
            class="block w-full border-0 bg-transparent px-3 pt-2.5 text-lg font-medium text-neutral-900 placeholder:text-neutral-400 focus:ring-0 focus:outline-none dark:text-white dark:placeholder:text-neutral-500"
            {{ $attributes->whereStartsWith('title') }}
        />

        {{-- Description textarea --}}
        <label for="{{ $descriptionName }}" class="sr-only">{{ $descriptionPlaceholder }}</label>
        <textarea
            id="{{ $descriptionName }}"
            name="{{ $descriptionName }}"
            rows="{{ $descriptionRows }}"
            placeholder="{{ $descriptionPlaceholder }}"
            class="block w-full resize-none border-0 bg-transparent px-3 py-1.5 text-base text-neutral-900 placeholder:text-neutral-400 focus:ring-0 focus:outline-none sm:text-sm/6 dark:text-white dark:placeholder:text-neutral-500"
            {{ $attributes->whereStartsWith('description') }}
        >{{ old($descriptionName, $descriptionValue) }}</textarea>

        @if($showToolbar)
            {{-- Spacer element to match the height of the toolbar --}}
            <div aria-hidden="true">
                <div class="py-2">
                    <div class="h-9"></div>
                </div>
                <div class="h-px"></div>
                <div class="py-2">
                    <div class="py-px">
                        <div class="h-9"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($showToolbar)
        <div class="absolute inset-x-px bottom-0">
            {{-- Toolbar actions slot --}}
            @if(isset($toolbar))
                <div class="flex flex-nowrap justify-end space-x-2 px-2 py-2 sm:px-3">
                    {{ $toolbar }}
                </div>
            @endif

            {{-- Bottom actions bar --}}
            <div class="flex items-center justify-between space-x-3 border-t border-neutral-200 px-2 py-2 sm:px-3 dark:border-white/10">
                {{-- Left actions slot --}}
                <div class="flex">
                    {{ $leftActions ?? '' }}
                </div>

                {{-- Right actions slot (typically submit button) --}}
                <div class="shrink-0">
                    {{ $rightActions ?? '' }}
                </div>
            </div>
        </div>
    @endif
</div>
