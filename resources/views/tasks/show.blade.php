<x-dashboard-layout title="{{ $task->title }}">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header with back button --}}
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-white">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a Tareas
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 dark:bg-green-900/20">
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

    {{-- Header Section --}}
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-neutral-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $task->title }}
            </h1>
            <div class="mt-2 flex flex-col sm:flex-row sm:flex-wrap sm:gap-x-6">
                <div class="mt-2 flex items-center text-sm text-neutral-500 dark:text-neutral-400">
                    <svg class="mr-1.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ $task->area->name ?? 'Sin área' }}
                </div>
                @if($task->due_date)
                    <div class="mt-2 flex items-center text-sm {{ $task->is_overdue ? 'text-red-600 dark:text-red-400' : 'text-neutral-500 dark:text-neutral-400' }}">
                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $task->due_date->format('d/m/Y') }}
                        @if($task->is_overdue)
                            <span class="ml-2 font-semibold">(Atrasada)</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="mt-5 flex gap-x-3 lg:ml-4 lg:mt-0">
            @can('update', $task)
                <a
                    href="{{ route('tasks.edit', $task) }}"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
            @endcan
            @can('complete', $task)
                @if($task->status !== 'completed')
                    <form method="POST" action="{{ route('tasks.complete', $task) }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 dark:bg-green-700 dark:hover:bg-green-600"
                        >
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Marcar como Completada
                        </button>
                    </form>
                @endif
            @endcan
        </div>
    </div>

    {{-- Main Content --}}
    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Left Column: Main Details --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Status & Priority Card --}}
            <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</h3>
                            <div class="mt-2">
                                <x-tasks.task-status-badge :status="$task->status" class="text-base" />
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Prioridad</h3>
                            <div class="mt-2">
                                <x-tasks.priority-badge :priority="$task->priority" class="text-base" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description Card --}}
            <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-6 text-neutral-900 dark:text-white">
                        Descripción
                    </h3>
                    <div class="mt-3 text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">
                        {{ $task->description ?: 'Sin descripción' }}
                    </div>
                </div>
            </div>

            {{-- File Attachments Card --}}
            @if($task->media->count() > 0)
                <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-base font-semibold leading-6 text-neutral-900 dark:text-white mb-4">
                            Archivos Adjuntos ({{ $task->media->count() }})
                        </h3>
                        <ul class="divide-y divide-neutral-200 dark:divide-neutral-700 rounded-md border border-neutral-200 dark:border-neutral-700">
                            @foreach($task->media as $media)
                                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                                    <div class="flex w-0 flex-1 items-center">
                                        <svg class="h-5 w-5 flex-shrink-0 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <span class="ml-2 w-0 flex-1 truncate text-neutral-900 dark:text-white">
                                            {{ $media->file_name }}
                                        </span>
                                        <span class="ml-2 flex-shrink-0 text-neutral-400">
                                            {{ number_format($media->size / 1024, 2) }} KB
                                        </span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a
                                            href="{{ $media->getUrl() }}"
                                            target="_blank"
                                            download
                                            class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400"
                                        >
                                            Descargar
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Child Tasks (Subtareas) --}}
            @if($task->childTasks->count() > 0)
                <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-base font-semibold leading-6 text-neutral-900 dark:text-white mb-4">
                            Subtareas Dependientes ({{ $task->childTasks->count() }})
                        </h3>
                        <ul class="space-y-3">
                            @foreach($task->childTasks as $childTask)
                                <li class="flex items-start gap-3 rounded-lg border border-neutral-200 dark:border-neutral-700 p-3 hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                    <div class="flex-1">
                                        <a href="{{ route('tasks.show', $childTask) }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">
                                            {{ $childTask->title }}
                                        </a>
                                        <div class="mt-1 flex items-center gap-2">
                                            <x-tasks.task-status-badge :status="$childTask->status" />
                                            <x-tasks.priority-badge :priority="$childTask->priority" />
                                            @if($childTask->assignments->count() > 0)
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    Asignado a: {{ $childTask->assignments->first()->user->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Subtasks (from subtasks table) --}}
            @if($task->subtasks->count() > 0)
                <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-base font-semibold leading-6 text-neutral-900 dark:text-white mb-4">
                            Lista de Subtareas ({{ $task->subtasks->count() }})
                        </h3>
                        <ul class="space-y-2">
                            @foreach($task->subtasks->sortBy('order') as $subtask)
                                <li class="flex items-center gap-3 rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                                    <input
                                        type="checkbox"
                                        {{ $subtask->status === 'completed' ? 'checked' : '' }}
                                        disabled
                                        class="h-4 w-4 rounded border-neutral-300 text-primary-600 dark:border-neutral-700"
                                    />
                                    <div class="flex-1">
                                        <span class="{{ $subtask->status === 'completed' ? 'line-through text-neutral-500 dark:text-neutral-400' : 'text-neutral-900 dark:text-white' }}">
                                            {{ $subtask->title }}
                                        </span>
                                        @if($subtask->description)
                                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $subtask->description }}
                                            </p>
                                        @endif
                                    </div>
                                    <x-tasks.task-status-badge :status="$subtask->status" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Task Submission/Deliverable --}}
            @if($task->assignments->count() > 0)
                <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <livewire:tasks.task-submission-manager :taskId="$task->id" />
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Sidebar Info --}}
        <div class="space-y-6">
            {{-- Assigned Users Card --}}
            <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-3">
                        Asignado a
                    </h3>
                    @if($task->assignments->count() > 0)
                        <ul class="space-y-2">
                            @foreach($task->assignments as $assignment)
                                <li class="flex items-center gap-2">
                                    <div class="flex-shrink-0">
                                        <x-data-display.avatar :user="$assignment->user" size="sm" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            {{ $assignment->user->name }}
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ $assignment->user->email }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            No hay usuarios asignados
                        </p>
                    @endif
                </div>
            </div>

            {{-- Metadata Card --}}
            <div class="bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-4">
                        Información
                    </h3>
                    <dl class="space-y-3 text-sm">
                        @if($task->parentTask)
                            <div>
                                <dt class="font-medium text-neutral-900 dark:text-white">Tarea Padre</dt>
                                <dd class="mt-1">
                                    <a href="{{ route('tasks.show', $task->parentTask) }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">
                                        {{ $task->parentTask->title }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="font-medium text-neutral-900 dark:text-white">Fecha de Creación</dt>
                            <dd class="mt-1 text-neutral-500 dark:text-neutral-400">
                                {{ $task->created_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                        @if($task->completed_at)
                            <div>
                                <dt class="font-medium text-neutral-900 dark:text-white">Completada el</dt>
                                <dd class="mt-1 text-neutral-500 dark:text-neutral-400">
                                    {{ $task->completed_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="font-medium text-neutral-900 dark:text-white">Última Actualización</dt>
                            <dd class="mt-1 text-neutral-500 dark:text-neutral-400">
                                {{ $task->updated_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
</x-dashboard-layout>
