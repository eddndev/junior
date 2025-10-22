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

<div
    class="relative"
    x-data="{
        toolbarHeight: 0,
        updatePadding() {
            if (this.$refs.toolbar) {
                this.toolbarHeight = this.$refs.toolbar.offsetHeight;
                this.$refs.content.style.paddingBottom = this.toolbarHeight + 'px';
            }
        }
    }"
    x-init="
        updatePadding();
        // Observar cambios en la toolbar
        const observer = new MutationObserver(() => {
            setTimeout(() => updatePadding(), 50);
        });
        if ($refs.toolbar) {
            observer.observe($refs.toolbar, {
                childList: true,
                subtree: true,
                attributes: true
            });
        }
        // También actualizar en resize de ventana
        window.addEventListener('resize', () => updatePadding());
    "
>
    <div
        x-ref="content"
        class="rounded-lg bg-white outline-1 -outline-offset-1 outline-neutral-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-primary-600 dark:bg-neutral-800/50 dark:outline-white/10 dark:focus-within:outline-primary-500 transition-[padding] duration-200"
        style="padding-bottom: 0;"
    >
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

        {{-- Description textarea with auto-resize --}}
        <label for="{{ $descriptionName }}" class="sr-only">{{ $descriptionPlaceholder }}</label>
        <textarea
            id="{{ $descriptionName }}"
            name="{{ $descriptionName }}"
            rows="{{ $descriptionRows }}"
            placeholder="{{ $descriptionPlaceholder }}"
            class="block w-full resize-none border-0 bg-transparent px-3 py-1.5 text-base text-neutral-900 placeholder:text-neutral-400 focus:ring-0 focus:outline-none sm:text-sm/6 dark:text-white dark:placeholder:text-neutral-500"
            x-data="{
                resize() {
                    $el.style.height = 'auto';
                    $el.style.height = $el.scrollHeight + 'px';
                }
            }"
            x-init="resize()"
            @input="resize()"
            {{ $attributes->whereStartsWith('description') }}
        >{{ old($descriptionName, $descriptionValue) }}</textarea>
    </div>

    @if($showToolbar)
        <div x-ref="toolbar" class="absolute inset-x-px bottom-0">
            {{-- Toolbar actions slot --}}
            @if(isset($toolbar))
                <div
                    class="px-3 py-2 border-t border-neutral-200 dark:border-white/10"
                    @attachments-updated.window="updatePadding()"
                    @link-added.window="updatePadding()"
                >
                    {{ $toolbar }}
                </div>
            @endif

            {{-- Bottom actions bar --}}
            <div class="flex items-center justify-between space-x-3 border-t border-neutral-200 px-2 py-2 sm:px-3 dark:border-white/10">
                {{-- Left actions slot --}}
                <div class="flex gap-2">
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
