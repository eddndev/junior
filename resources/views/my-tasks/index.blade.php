<x-dashboard-layout title="Mis Tareas">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Mis Tareas</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Vista personal de todas tus tareas asignadas.
            </p>
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="overflow-hidden rounded-lg bg-white dark:bg-neutral-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Asignadas</dt>
                            <dd class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $metrics['total_assigned'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-neutral-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">En Progreso</dt>
                            <dd class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $metrics['in_progress'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-neutral-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Completadas Hoy</dt>
                            <dd class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $metrics['completed_today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-neutral-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Atrasadas</dt>
                            <dd class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $metrics['overdue'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Completion Rate Progress Bar --}}
    <div class="mt-6 bg-white dark:bg-neutral-800 shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-neutral-900 dark:text-white">Tasa de Completación</h3>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $metrics['completion_rate'] }}% completadas de todas tus tareas
                </p>
            </div>
            <div class="text-2xl font-semibold text-primary-600 dark:text-primary-400">
                {{ $metrics['completion_rate'] }}%
            </div>
        </div>
        <div class="mt-4">
            <div class="overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                <div class="h-2 rounded-full bg-primary-600 dark:bg-primary-500" style="width: {{ $metrics['completion_rate'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="mt-8">
        <form method="GET" action="{{ route('my-tasks.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Buscar
                    </label>
                    <div class="relative mt-1">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Título o descripción..."
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                        />
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Estado
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>

                {{-- Priority Filter --}}
                <div>
                    <label for="priority" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Prioridad
                    </label>
                    <select
                        name="priority"
                        id="priority"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todas</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Baja</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Media</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Crítica</option>
                    </select>
                </div>

                {{-- Area Filter --}}
                <div>
                    <label for="area_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Área
                    </label>
                    <select
                        name="area_id"
                        id="area_id"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filter Actions --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-x-2">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-neutral-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:bg-neutral-700 dark:hover:bg-neutral-600"
                    >
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrar
                    </button>
                    <a
                        href="{{ route('my-tasks.index') }}"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                    >
                        Limpiar
                    </a>
                </div>

                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="overdue"
                        id="overdue"
                        value="true"
                        {{ request('overdue') === 'true' ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                    />
                    <label for="overdue" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                        Solo atrasadas
                    </label>
                </div>
            </div>
        </form>
    </div>

    {{-- Success Messages --}}
    @if(session('success'))
        <div class="mt-4 rounded-md bg-green-50 p-4 dark:bg-green-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Tasks List --}}
    <div class="mt-8">
        @if($groupedTasks)
            {{-- Grouped by due date --}}
            @foreach($groupedTasks as $group => $groupTasks)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 capitalize">
                        {{ str_replace('_', ' ', $group) }}
                        <span class="text-sm font-normal text-neutral-500 dark:text-neutral-400">({{ $groupTasks->count() }})</span>
                    </h3>
                    @include('my-tasks.partials.task-list', ['tasks' => $groupTasks])
                </div>
            @endforeach
        @else
            {{-- Simple list --}}
            @forelse($tasks as $task)
                <div class="mb-4 bg-white dark:bg-neutral-800 shadow rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    {{-- Complete Checkbox --}}
                                    @if($task->status !== 'completed')
                                        <form method="POST" action="{{ route('my-tasks.complete', $task) }}" class="flex-shrink-0">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="h-5 w-5 rounded border-2 border-neutral-300 hover:border-primary-600 dark:border-neutral-600 dark:hover:border-primary-500 transition-colors"
                                            ></button>
                                        </form>
                                    @else
                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif

                                    <div class="flex-1">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-base font-medium text-neutral-900 hover:text-primary-600 dark:text-white dark:hover:text-primary-400">
                                            {{ $task->title }}
                                        </a>
                                        @if($task->description)
                                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 line-clamp-2">
                                                {{ $task->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <x-tasks.task-status-badge :status="$task->status" />
                                    <x-tasks.priority-badge :priority="$task->priority" />

                                    @if($task->area)
                                        <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/20 dark:text-purple-400 dark:ring-purple-600/30">
                                            {{ $task->area->name }}
                                        </span>
                                    @endif

                                    @if($task->due_date)
                                        <span class="inline-flex items-center text-xs {{ $task->is_overdue ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-neutral-500 dark:text-neutral-400' }}">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif

                                    @if($task->subtasks->count() > 0)
                                        <span class="inline-flex items-center text-xs text-neutral-500 dark:text-neutral-400">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            {{ $task->subtasks->count() }} subtareas
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="ml-4 flex-shrink-0">
                                <a
                                    href="{{ route('tasks.show', $task) }}"
                                    class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-700 dark:text-white dark:ring-neutral-600 dark:hover:bg-neutral-600"
                                >
                                    Ver detalle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">No hay tareas asignadas</h3>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        No tienes tareas asignadas en este momento.
                    </p>
                </div>
            @endforelse
        @endif
    </div>
</div>
</x-dashboard-layout>
