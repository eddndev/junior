<div>
    {{-- Header --}}
    <x-dialog-header dialog-id="create-area-dialog">
        <x-slot:title>{{ $areaId ? 'Editar Área' : 'Crear Nueva Área' }}</x-slot:title>
        <x-slot:description>
            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                {{ $areaId ? 'Actualice la información del área' : 'Complete el formulario para crear una nueva área de trabajo' }}
            </p>
        </x-slot:description>
    </x-dialog-header>

    {{-- Form Content --}}
    <form wire:submit="save" class="flex-1 overflow-y-auto">
        <div class="px-6 py-6 space-y-6">

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Nombre del área <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       wire:model="name"
                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('name') border-red-500 @enderror"
                       placeholder="Ej: Producción, Marketing, Ventas..." />
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Descripción
                </label>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="4"
                    class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('description') border-red-500 @enderror"
                    placeholder="Descripción breve del área y sus responsabilidades..."></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="flex items-center">
                <input type="checkbox"
                       id="is_active"
                       wire:model="is_active"
                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800" />
                <label for="is_active" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                    Área activa
                </label>
            </div>

        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="create-area-dialog">
            <button type="button"
                    wire:click="cancel"
                    class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                Cancelar
            </button>
            <button type="submit"
                    class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400">
                {{ $areaId ? 'Actualizar Área' : 'Crear Área' }}
            </button>
        </x-dialog-footer>
    </form>
</div>
