<div
    x-data="{
        isDragging: false,
        showLinkForm: false,
        fileCount: 0,
        files: [],
        handleFiles(fileList) {
            this.files = Array.from(fileList);
            this.fileCount = this.files.length;
            setTimeout(() => $dispatch('attachments-updated'), 100);
        },
        removeFile(index) {
            // Crear un nuevo DataTransfer para modificar el FileList
            const dt = new DataTransfer();
            this.files.forEach((file, i) => {
                if (i !== index) dt.items.add(file);
            });
            this.$refs.fileInput.files = dt.files;
            this.handleFiles(dt.files);
        }
    }"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="
        isDragging = false;
        handleFiles($event.dataTransfer.files);
    "
>
    {{-- Action Buttons (Similar to Google Classroom) --}}
    <div class="flex items-center gap-2">
        {{-- File Upload Button --}}
        <button
            type="button"
            @click="$refs.fileInput.click()"
            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors"
            title="Adjuntar archivo"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
            </svg>
            <span class="hidden sm:inline">Archivo</span>
        </button>

        {{-- Link Button --}}
        <button
            type="button"
            @click="showLinkForm = !showLinkForm; setTimeout(() => $dispatch('attachments-updated'), 250)"
            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors"
            :class="showLinkForm ? 'bg-neutral-100 dark:bg-neutral-700 text-primary-600 dark:text-primary-400' : ''"
            title="Añadir enlace"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
            <span class="hidden sm:inline">Enlace</span>
        </button>

        {{-- Counter Badge --}}
        <span
            x-show="fileCount > 0 || {{ count($links) }} > 0"
            x-text="`${fileCount + {{ count($links) }}} adjunto(s)`"
            class="inline-flex items-center rounded-full bg-primary-100 dark:bg-primary-900/30 px-2 py-0.5 text-xs font-medium text-primary-700 dark:text-primary-400"
        ></span>

        {{-- File input for form submission --}}
        <input
            type="file"
            name="attachments[]"
            multiple
            x-ref="fileInput"
            @change="handleFiles($event.target.files)"
            class="sr-only"
            accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,audio/*,.txt,.json,.xml,.html,.css,.js,.zip,.rar,.7z"
        >
    </div>

    @error('attachments.*')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror

    {{-- Compact Preview of Uploaded Files (Alpine.js) --}}
    <div x-show="fileCount > 0" class="mt-2 transition-all duration-200 ease-in-out">
        <ul class="flex flex-wrap gap-2">
            <template x-for="(file, index) in files" :key="index">
                <li class="relative group">
                    <div class="relative flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 pl-2 pr-8 py-1.5">
                        {{-- File icon or thumbnail --}}
                        <template x-if="file.type.startsWith('image/')">
                            <div class="h-8 w-8 rounded overflow-hidden bg-neutral-100 dark:bg-neutral-700 flex-shrink-0">
                                <img :src="URL.createObjectURL(file)" alt="Preview" class="h-full w-full object-cover">
                            </div>
                        </template>
                        <template x-if="!file.type.startsWith('image/')">
                            <svg class="h-5 w-5 text-neutral-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </template>

                        <span x-text="file.name" class="text-xs font-medium text-neutral-700 dark:text-neutral-300 truncate max-w-[120px]"></span>

                        {{-- Remove button --}}
                        <button
                            type="button"
                            @click="removeFile(index)"
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-red-500 dark:hover:text-red-400"
                            title="Eliminar"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </li>
            </template>
        </ul>
    </div>

    {{-- Compact Link Form (Collapsible) --}}
    <div
        x-show="showLinkForm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mt-2 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg border border-neutral-200 dark:border-neutral-700"
    >
        <div class="space-y-2">
            <div class="flex gap-2 items-start">
                <input
                    type="url"
                    wire:model="newLinkUrl"
                    placeholder="https://ejemplo.com/video"
                    class="block flex-1 rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                >

                <select
                    wire:model.live="newLinkType"
                    class="block w-32 rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm py-2 px-3"
                >
                    <option value="external">Enlace</option>
                    <option value="video">Video</option>
                    <option value="image">Imagen</option>
                </select>

                <button
                    type="button"
                    wire:click="addLink"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none flex-shrink-0"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>
            @error('newLinkUrl')
                <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Compact Links List --}}
    @if (count($links) > 0)
        <div class="mt-2 transition-all duration-200 ease-in-out">
            <ul class="flex flex-wrap gap-2">
                @foreach ($links as $link)
                    <li class="relative group">
                        <div class="relative flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 pl-2 pr-8 py-1.5">
                            {{-- Icon based on link type --}}
                            @if ($link['type'] === 'video')
                                <svg class="h-4 w-4 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                </svg>
                            @elseif ($link['type'] === 'image')
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                </svg>
                            @endif

                            <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 truncate max-w-[150px]" title="{{ $link['url'] }}">
                                {{ $link['url'] }}
                            </span>

                            {{-- Remove button --}}
                            <button
                                type="button"
                                wire:click="removeLink('{{ $link['id'] }}')"
                                class="absolute right-1.5 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-red-500 dark:hover:text-red-400"
                                title="Eliminar"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hidden inputs for form submission --}}
    @foreach ($links as $index => $link)
        <input type="hidden" name="links[{{ $index }}][url]" value="{{ $link['url'] }}">
        <input type="hidden" name="links[{{ $index }}][type]" value="{{ $link['type'] }}">
    @endforeach
</div>
