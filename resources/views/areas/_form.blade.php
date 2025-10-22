<div class="space-y-6">
    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
            Nombre del Área <span class="text-red-500">*</span>
        </label>
        <div class="mt-2">
            <input type="text" name="name" id="name" value="{{ old('name', $area->name ?? '') }}" required
                class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('name') ring-red-500 dark:ring-red-500 @enderror"
                placeholder="Ej: Producción">
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
            Descripción
        </label>
        <div class="mt-2">
            <textarea name="description" id="description" rows="3"
                class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('description') ring-red-500 dark:ring-red-500 @enderror"
                placeholder="Propósito del área...">{{ old('description', $area->description ?? '') }}</textarea>
        </div>
        @error('description')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Active Status --}}
    <div>
        <div class="flex items-center gap-x-3">
            <input
                type="checkbox"
                name="is_active"
                id="is_active"
                value="1"
                {{ old('is_active', $area->is_active ?? true) ? 'checked' : '' }}
                @if(isset($area) && $area->is_system) disabled @endif
                class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed"
            >
            <label for="is_active" class="text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                Área activa
            </label>

            @if(isset($area) && $area->is_system)
                <x-data-display.badge color="primary">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                    </svg>
                    Sistema - Protegida
                </x-data-display.badge>
            @endif
        </div>

        @if(isset($area) && $area->is_system)
            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                <svg class="inline h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                Esta es un área del sistema y no puede ser desactivada. Es crítica para el funcionamiento de los módulos integrados.
            </p>
        @else
            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                Las áreas inactivas no se podrán asignar a nuevos usuarios o tareas.
            </p>
        @endif
    </div>
</div>

<x-layout.divider />

{{-- Form Actions --}}
<div class="flex items-center justify-end gap-x-4">
    <a href="{{ route('areas.index') }}"
        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
        Cancelar
    </a>
    <button type="submit"
        class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400">
        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ isset($area) ? 'Actualizar Área' : 'Crear Área' }}
    </button>
</div>
