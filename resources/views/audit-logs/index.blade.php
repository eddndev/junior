<x-dashboard-layout title="Trazabilidad del Sistema">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Trazabilidad del Sistema</h1>
                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                    Registro completo de todas las acciones realizadas en el sistema para fines de auditoría y cumplimiento.
                </p>
            </div>
        </div>

        {{-- Advanced Filters Section --}}
        <div class="mt-8">
            <form method="GET" action="{{ route('audit-logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- User Filter --}}
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Usuario
                        </label>
                        <select name="user_id" id="user_id"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                            <option value="">Todos los usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Filter --}}
                    <div>
                        <label for="action" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Acción
                        </label>
                        <select name="action" id="action"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                            <option value="">Todas las acciones</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Model Type Filter --}}
                    <div>
                        <label for="auditable_type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Tipo de Registro
                        </label>
                        <select name="auditable_type" id="auditable_type"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                            <option value="">Todos los tipos</option>
                            @foreach($auditableTypes as $type)
                                <option value="{{ $type['value'] }}" {{ request('auditable_type') === $type['value'] ? 'selected' : '' }}>
                                    {{ $type['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Buscar en cambios
                        </label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Buscar valores..."
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                    </div>
                </div>

                {{-- Date Range Filters --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Fecha desde
                        </label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                    </div>

                    <div>
                        <label for="date_to" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Fecha hasta
                        </label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
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
                        <a href="{{ route('audit-logs.index') }}"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Results Count --}}
        <div class="mt-6">
            <p class="text-sm text-neutral-700 dark:text-neutral-400">
                Mostrando <span class="font-semibold">{{ $logs->firstItem() ?? 0 }}</span> a
                <span class="font-semibold">{{ $logs->lastItem() ?? 0 }}</span> de
                <span class="font-semibold">{{ $logs->total() }}</span> registros
            </p>
        </div>

        {{-- Audit Logs Table --}}
        <div class="mt-4 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <x-layout.table>
                        <x-slot:header>
                            <x-layout.table-header>Fecha/Hora</x-layout.table-header>
                            <x-layout.table-header>Usuario</x-layout.table-header>
                            <x-layout.table-header>Acción</x-layout.table-header>
                            <x-layout.table-header>Tipo</x-layout.table-header>
                            <x-layout.table-header>Registro</x-layout.table-header>
                            <x-layout.table-header>Cambios</x-layout.table-header>
                            <x-layout.table-header>IP Address</x-layout.table-header>
                        </x-slot:header>

                        @forelse($logs as $log)
                            <x-layout.table-row>
                                {{-- Date/Time --}}
                                <x-layout.table-cell :primary="true">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $log->created_at->format('d/m/Y') }}</span>
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $log->created_at->format('H:i:s') }}</span>
                                    </div>
                                </x-layout.table-cell>

                                {{-- User --}}
                                <x-layout.table-cell>
                                    {{ $log->user->name ?? 'Sistema' }}
                                </x-layout.table-cell>

                                {{-- Action --}}
                                <x-layout.table-cell>
                                    @php
                                        $actionColors = [
                                            'created' => 'green',
                                            'updated' => 'blue',
                                            'deleted' => 'red',
                                            'restored' => 'yellow',
                                            'force_deleted' => 'red',
                                        ];
                                        $color = $actionColors[$log->action] ?? 'neutral';
                                    @endphp
                                    <x-data-display.badge :color="$color" :dot="true">
                                        {{ ucfirst($log->action) }}
                                    </x-data-display.badge>
                                </x-layout.table-cell>

                                {{-- Auditable Type --}}
                                <x-layout.table-cell>
                                    {{ class_basename($log->auditable_type) }}
                                </x-layout.table-cell>

                                {{-- Auditable Record --}}
                                <x-layout.table-cell>
                                    ID: {{ $log->auditable_id }}
                                </x-layout.table-cell>

                                {{-- Changes Summary --}}
                                <x-layout.table-cell>
                                    @if($log->old_values || $log->new_values)
                                        <button
                                            type="button"
                                            command="show-modal"
                                            commandfor="audit-log-details-modal"
                                            data-old-values="{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}"
                                            data-new-values="{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}"
                                            @click="window.dispatchEvent(new CustomEvent('load-audit-data', {
                                                detail: {
                                                    oldValues: {{ json_encode($log->old_values) }},
                                                    newValues: {{ json_encode($log->new_values) }}
                                                }
                                            }))"
                                            class="text-xs cursor-pointer text-primary-600 hover:text-primary-500 dark:text-primary-400"
                                        >
                                            Ver cambios
                                        </button>
                                    @else
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Sin cambios</span>
                                    @endif
                                </x-layout.table-cell>

                                {{-- IP Address --}}
                                <x-layout.table-cell>
                                    <code class="text-xs">{{ $log->ip_address }}</code>
                                </x-layout.table-cell>
                            </x-layout.table-row>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">No se encontraron registros de auditoría</p>
                                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Intenta ajustar los filtros o realiza algunas acciones en el sistema.</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-layout.table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif

        {{-- Info Box --}}
        <div class="mt-8 rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Acerca de la trazabilidad
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Todos los cambios en usuarios y áreas se registran automáticamente</li>
                            <li>Los registros incluyen información del usuario, IP address y user agent</li>
                            <li>Los campos sensibles (contraseñas, tokens) nunca se registran</li>
                            <li>Los logs son inmutables y no pueden ser editados o eliminados</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Audit Log Details Modal --}}
    <x-layout.modal id="audit-log-details-modal" title="Detalles del Registro de Auditoría" maxWidth="3xl">
        <x-slot:content>
            <div x-data="{
                oldValues: {},
                newValues: {},
                hasValues(obj) {
                    return obj && typeof obj === 'object' && Object.keys(obj).length > 0;
                },
                formatValue(value) {
                    if (value === null) return 'null';
                    if (value === undefined) return 'undefined';
                    if (typeof value === 'object') return JSON.stringify(value, null, 2);
                    return String(value);
                }
            }"
            @load-audit-data.window="
                oldValues = $event.detail.oldValues || {};
                newValues = $event.detail.newValues || {};
            "
            >
                <div class="w-full space-y-6">
                    {{-- Valores Anteriores --}}
                    <div class="w-full">
                        <h4 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Valores Anteriores</h4>
                        <div class="w-full rounded-lg bg-neutral-50 dark:bg-neutral-800/50 p-4 overflow-auto max-h-60">
                            <template x-if="!hasValues(oldValues)">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">No hay datos anteriores.</p>
                            </template>
                            <template x-if="hasValues(oldValues)">
                                <dl class="w-full space-y-3">
                                    <template x-for="[key, value] in Object.entries(oldValues)" :key="key">
                                        <div class="w-full flex flex-col gap-1">
                                            <dt class="text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wide" x-text="key"></dt>
                                            <dd class="w-full text-sm text-neutral-900 dark:text-neutral-100 font-mono bg-white dark:bg-neutral-900 rounded px-2 py-1.5 border border-neutral-200 dark:border-neutral-700 break-words" x-text="formatValue(value)"></dd>
                                        </div>
                                    </template>
                                </dl>
                            </template>
                        </div>
                    </div>

                    {{-- Valores Nuevos --}}
                    <div class="w-full">
                        <h4 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Valores Nuevos</h4>
                        <div class="w-full rounded-lg bg-neutral-50 dark:bg-neutral-800/50 p-4 overflow-auto max-h-60">
                            <template x-if="!hasValues(newValues)">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">No hay datos nuevos.</p>
                            </template>
                            <template x-if="hasValues(newValues)">
                                <dl class="w-full space-y-3">
                                    <template x-for="[key, value] in Object.entries(newValues)" :key="key">
                                        <div class="w-full flex flex-col gap-1">
                                            <dt class="text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wide" x-text="key"></dt>
                                            <dd class="w-full text-sm text-neutral-900 dark:text-neutral-100 font-mono bg-white dark:bg-neutral-900 rounded px-2 py-1.5 border border-neutral-200 dark:border-neutral-700 break-words" x-text="formatValue(value)"></dd>
                                        </div>
                                    </template>
                                </dl>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot:content>
    </x-layout.modal>

    @push('scripts')
    <script>
        // No need for DOMContentLoaded listener here, Alpine.js handles initialization
        // The Alpine.js component itself will listen for the modal show event
    </script>
    @endpush
</x-dashboard-layout>
