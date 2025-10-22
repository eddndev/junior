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
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $area->is_active ?? true) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800">
            <label for="is_active" class="text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                Área activa
            </label>
        </div>
        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
            Las áreas inactivas no se podrán asignar a nuevos usuarios o tareas.
        </p>
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
