<x-dashboard-layout title="Gestión de Áreas">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Gestión de Áreas</h1>
                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                    Lista de todas las áreas de trabajo de la empresa.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('areas.create') }}"
                    class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Crear Área
                </a>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="mt-8">
            <form method="GET" action="{{ route('areas.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Search --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Buscar por nombre
                        </label>
                        <div class="relative mt-1">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Producción..."
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Estado
                        </label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activas</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivas
                            </option>
                        </select>
                    </div>

                    {{-- Filter Actions --}}
                    <div class="flex items-end gap-x-2">
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-neutral-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:bg-neutral-700 dark:hover:bg-neutral-600">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('areas.index') }}"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <x-layout.alerts />

        {{-- Areas Table --}}
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <x-layout.table>
                        <x-slot:header>
                            <x-layout.table-header>Nombre</x-layout.table-header>
                            <x-layout.table-header>Slug</x-layout.table-header>
                            <x-layout.table-header>Descripción</x-layout.table-header>
                            <x-layout.table-header>Estado</x-layout.table-header>
                            <x-layout.table-header>Acciones</x-layout.table-header>
                        </x-slot:header>

                        @forelse($areas as $area)
                            <x-layout.table-row>
                                <x-layout.table-cell :primary="true">
                                    <a href="{{ route('areas.edit', $area) }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ $area->name }}
                                    </a>
                                </x-layout.table-cell>

                                <x-layout.table-cell>{{ $area->slug }}</x-layout.table-cell>

                                <x-layout.table-cell>
                                    {{ Str::limit($area->description, 50) }}
                                </x-layout.table-cell>

                                <x-layout.table-cell>
                                    <div class="flex items-center gap-2">
                                        @if ($area->is_active)
                                            <x-data-display.badge-active>Activa</x-data-display.badge-active>
                                        @else
                                            <x-data-display.badge-inactive>Inactiva</x-data-display.badge-inactive>
                                        @endif

                                        @if ($area->is_system)
                                            <x-data-display.badge color="primary">
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                                </svg>
                                                Sistema
                                            </x-data-display.badge>
                                        @endif
                                    </div>
                                </x-layout.table-cell>

                                <x-layout.table-cell>
                                    <x-layout.dropdown anchor="bottom end" width="48">
                                        <x-slot:trigger>
                                            <button class="inline-flex items-center rounded-md bg-white px-2 py-1 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                                                Acciones
                                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </x-slot:trigger>

                                        <x-layout.dropdown-link :href="route('areas.edit', $area)">
                                            <svg class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Editar</span>
                                        </x-layout.dropdown-link>

                                        @if (!$area->is_system)
                                            <x-layout.dropdown-divider />

                                            <form method="POST" action="{{ route('areas.destroy', $area) }}" onsubmit="return confirm('¿Está seguro de que desea desactivar esta área?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-layout.dropdown-button type="submit">
                                                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span class="text-red-700 dark:text-red-400">Desactivar</span>
                                                </x-layout.dropdown-button>
                                            </form>
                                        @endif
                                    </x-layout.dropdown>
                                </x-layout.table-cell>
                            </x-layout.table-row>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">No se encontraron áreas</p>
                                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Intenta ajustar los filtros o crea una nueva área.</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-layout.table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($areas->hasPages())
            <div class="mt-6">
                {{ $areas->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
