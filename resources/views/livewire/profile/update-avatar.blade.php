<div>
    <div class="space-y-6">
        {{-- Avatar Preview with Overlay --}}
        <div class="flex flex-col items-center sm:flex-row sm:items-start gap-6">
            <div class="relative group">
                {{-- Avatar Display --}}
                <div class="relative">
                    @if ($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="size-32 rounded-full object-cover ring-4 ring-primary-100 dark:ring-primary-900/50 shadow-lg" />
                        <div class="absolute -bottom-1 -right-1 rounded-full bg-green-500 p-1.5 ring-4 ring-white dark:ring-neutral-900">
                            <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    @else
                        <x-data-display.avatar :user="auth()->user()" size="2xl" class="ring-4 ring-neutral-200 dark:ring-neutral-700 shadow-lg" />
                    @endif

                    {{-- Hover Overlay --}}
                    <label for="avatar-upload" class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                        <div class="text-center">
                            <svg class="mx-auto size-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                            <span class="mt-2 block text-sm font-medium text-white">{{ __('Cambiar') }}</span>
                        </div>
                    </label>
                </div>

                <input
                    wire:model="avatar"
                    type="file"
                    id="avatar-upload"
                    class="sr-only"
                    accept="image/jpeg,image/png,image/gif"
                />
            </div>

            {{-- Info and Actions --}}
            <div class="flex-1 text-center sm:text-left">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    {{ __('Foto de perfil') }}
                </h3>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ __('Tu avatar se mostrará en tu perfil, mensajes y tareas asignadas.') }}
                </p>

                {{-- File Requirements --}}
                <div class="mt-4 rounded-lg bg-neutral-50 dark:bg-neutral-800 p-3">
                    <p class="text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('Requisitos:') }}</p>
                    <ul class="mt-1 space-y-1 text-xs text-neutral-500 dark:text-neutral-400">
                        <li class="flex items-center gap-1.5">
                            <svg class="size-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Formato: JPG, PNG o GIF') }}
                        </li>
                        <li class="flex items-center gap-1.5">
                            <svg class="size-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Tamaño máximo: 1MB') }}
                        </li>
                        <li class="flex items-center gap-1.5">
                            <svg class="size-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Se recomienda imagen cuadrada') }}
                        </li>
                    </ul>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    @if ($avatar)
                        <x-actions.button type="button" wire:click="save" variant="primary" wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="-ml-0.5 mr-1.5 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span wire:loading.remove wire:target="save">{{ __('Guardar cambios') }}</span>
                            <span wire:loading wire:target="save">{{ __('Guardando...') }}</span>
                        </x-actions.button>
                        <x-actions.button type="button" wire:click="$set('avatar', null)" variant="secondary">
                            <svg class="-ml-0.5 mr-1.5 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('Cancelar') }}
                        </x-actions.button>
                    @else
                        <label for="avatar-upload" class="cursor-pointer inline-flex items-center justify-center gap-2 rounded-md bg-white px-3.5 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-white/10 dark:text-white dark:ring-white/10 dark:hover:bg-white/20 transition-colors">
                            <svg class="-ml-0.5 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            {{ __('Subir imagen') }}
                        </label>
                        @if (auth()->user()->avatar_path)
                            <x-actions.button type="button" wire:click="removeAvatar" variant="danger" wire:loading.attr="disabled" wire:confirm="{{ __('¿Estás seguro de eliminar tu avatar?') }}">
                                <svg class="-ml-0.5 mr-1.5 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                {{ __('Eliminar avatar') }}
                            </x-actions.button>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Messages --}}
        @error('avatar')
            <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4">
                <div class="flex">
                    <svg class="size-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
                </div>
            </div>
        @enderror

        @if (session()->has('avatar-updated'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                <div class="flex">
                    <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm text-green-700 dark:text-green-400">{{ session('avatar-updated') }}</p>
                </div>
            </div>
        @endif

        <div wire:loading wire:target="avatar" class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-4">
            <div class="flex items-center">
                <svg class="size-5 text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="ml-3 text-sm text-blue-700 dark:text-blue-400">{{ __('Cargando imagen...') }}</p>
            </div>
        </div>
    </div>
</div>
