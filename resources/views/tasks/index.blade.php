<x-dashboard-layout title="Gestión de Tareas">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Gestión de Tareas</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Lista completa de tareas del área con su estado y asignaciones.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:gap-x-3">
            {{-- View Toggle --}}
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <a href="{{ route('tasks.index') }}"
                   class="rounded-l-md px-3 py-2 text-sm font-semibold bg-primary-600 text-white ring-1 ring-inset ring-primary-600 dark:bg-primary-500">
                    <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Lista
                </a>
                <a href="{{ route('tasks.kanban') }}"
                   class="rounded-r-md px-3 py-2 text-sm font-semibold text-neutral-900 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:text-neutral-300 dark:ring-neutral-700 dark:hover:bg-neutral-700">
                    <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    Kanban
                </a>
            </div>

            @can('create', App\Models\Task::class)
            <button type="button"
                    onclick="openDialog('create-task-dialog')"
                    class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Tarea
            </button>
            @endcan
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
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
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Total</dt>
                            <dd class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $metrics['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-neutral-800 shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Pendientes</dt>
                            <dd class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $metrics['pending'] }}</dd>
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
                            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Completadas</dt>
                            <dd class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $metrics['completed'] }}</dd>
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

    {{-- Filters Section --}}
    <div class="mt-8">
        <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
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
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
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

                {{-- Assigned To Filter --}}
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Asignado a
                    </label>
                    <select
                        name="assigned_to"
                        id="assigned_to"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todos</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
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
                        href="{{ route('tasks.index') }}"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                    >
                        Limpiar
                    </a>
                </div>

                <div class="flex items-center gap-x-4">
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

                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="parent_only"
                            id="parent_only"
                            value="true"
                            {{ request('parent_only') === 'true' ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                        />
                        <label for="parent_only" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                            Solo tareas principales
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Success/Error Messages --}}
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

    @if(session('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Tasks Table using Sprint 1 components --}}
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <x-layout.table id="tasks-table" :selectable="false">
                    <x-slot:header>
                        <x-layout.table-header>Tarea</x-layout.table-header>
                        <x-layout.table-header>Área</x-layout.table-header>
                        <x-layout.table-header>Asignado a</x-layout.table-header>
                        <x-layout.table-header>Estado</x-layout.table-header>
                        <x-layout.table-header>Prioridad</x-layout.table-header>
                        <x-layout.table-header>Fecha Límite</x-layout.table-header>
                        <x-layout.table-header>Acciones</x-layout.table-header>
                    </x-slot:header>

                    @forelse($tasks as $task)
                        <x-layout.table-row :selectable="false">
                            <x-layout.table-cell :primary="true">
                                <button type="button"
                                        onclick="loadTaskDetails({{ $task->id }})"
                                        class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-left">
                                    {{ $task->title }}
                                </button>
                                @if($task->parent_task_id)
                                    <span class="ml-2 text-xs text-neutral-500 dark:text-neutral-400">
                                        (Subtarea)
                                    </span>
                                @endif
                                @if($task->childTasks->count() > 0)
                                    <span class="ml-2 inline-flex items-center rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-600 ring-1 ring-inset ring-neutral-500/10 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30">
                                        {{ $task->childTasks->count() }} subtareas
                                    </span>
                                @endif
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                @if($task->area)
                                    <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/20 dark:text-purple-400 dark:ring-purple-600/30">
                                        {{ $task->area->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400">Sin área</span>
                                @endif
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($task->assignments->take(2) as $assignment)
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                                            {{ $assignment->user->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Sin asignar</span>
                                    @endforelse
                                    @if($task->assignments->count() > 2)
                                        <span class="inline-flex items-center rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-600 ring-1 ring-inset ring-neutral-500/10 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30">
                                            +{{ $task->assignments->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                <x-tasks.task-status-badge :status="$task->status" />
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                <x-tasks.priority-badge :priority="$task->priority" />
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                @if($task->due_date)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm {{ $task->is_overdue ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-neutral-900 dark:text-white' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                        @if($task->is_overdue)
                                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                                                Atrasada
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400">Sin fecha límite</span>
                                @endif
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

                                    <x-layout.dropdown-button type="button" onclick="loadTaskDetails({{ $task->id }})">
                                        <svg class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Ver detalle</span>
                                    </x-layout.dropdown-button>

                                    @can('update', $task)
                                        <x-layout.dropdown-link :href="route('tasks.edit', $task)">
                                            <svg class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Editar</span>
                                        </x-layout.dropdown-link>
                                    @endcan

                                    @can('complete', $task)
                                        @if($task->status !== 'completed')
                                            <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                                @csrf
                                                <x-layout.dropdown-button type="submit">
                                                    <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-green-700 dark:text-green-400">Completar</span>
                                                </x-layout.dropdown-button>
                                            </form>
                                        @endif
                                    @endcan

                                    @can('delete', $task)
                                        <x-layout.dropdown-divider />

                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('¿Está seguro de que desea eliminar esta tarea?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-layout.dropdown-button type="submit">
                                                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="text-red-700 dark:text-red-400">Eliminar</span>
                                            </x-layout.dropdown-button>
                                        </form>
                                    @endcan
                                </x-layout.dropdown>
                            </x-layout.table-cell>
                        </x-layout.table-row>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">No se encontraron tareas</p>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Intenta ajustar los filtros o crea una nueva tarea.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-layout.table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($tasks->hasPages())
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
</div>

{{-- Livewire Dialogs (OUTSIDE Livewire components) --}}
<x-dialog-wrapper id="task-detail-dialog" max-width="5xl">
    @livewire('tasks.task-detail-dialog')
</x-dialog-wrapper>

<x-dialog-wrapper id="create-task-dialog" max-width="5xl">
    @livewire('tasks.create-task-dialog')
</x-dialog-wrapper>

@push('scripts')
<script>
    // Livewire Dialog Integration
    document.addEventListener('livewire:init', () => {
        // Listen for task-created event to refresh the list
        Livewire.on('task-created', (event) => {
            const taskId = event.taskId || event[0]?.taskId;
            console.log('Task created:', taskId);

            // Reload the page to show the new task
            window.location.reload();
        });

        // Listen for show-toast events
        Livewire.on('show-toast', (event) => {
            const message = event.message || event[0]?.message || 'Operación completada';
            const type = event.type || event[0]?.type || 'success';
            showToast(message, type);
        });

        // Listen for dialog close to refresh list if task was updated
        document.addEventListener('dialog-closed', (event) => {
            if (event.detail && event.detail.dialogId === 'task-detail-dialog') {
                // Only reload if we were viewing a task (to update task list)
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            }
        });
    });

    // Function to open task detail dialog
    function loadTaskDetails(taskId) {
        // Load task data into Livewire component
        Livewire.dispatch('loadTask', { taskId: taskId });

        // Open the dialog only if it's not already open
        if (!window.DialogSystem.isOpen('task-detail-dialog')) {
            window.DialogSystem.open('task-detail-dialog');
        }
    }

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-50 flex items-center gap-3 rounded-lg px-4 py-3 shadow-lg transition-all duration-300 ${
            type === 'success'
                ? 'bg-green-50 text-green-800 ring-1 ring-green-600/20 dark:bg-green-900/30 dark:text-green-200'
                : 'bg-red-50 text-red-800 ring-1 ring-red-600/20 dark:bg-red-900/30 dark:text-red-200'
        }`;

        toast.innerHTML = `
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success'
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                }
            </svg>
            <span class="text-sm font-medium">${message}</span>
        `;

        document.body.appendChild(toast);

        // Slide in
        setTimeout(() => {
            toast.style.transform = 'translateY(0)';
        }, 10);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(1rem)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
</script>
@endpush

</x-dashboard-layout>
