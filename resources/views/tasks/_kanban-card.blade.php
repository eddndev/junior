{{-- Kanban Task Card --}}
<div class="group relative cursor-pointer rounded-lg bg-white p-4 shadow-sm ring-1 ring-neutral-900/5 hover:shadow-md transition-shadow dark:bg-neutral-800 dark:ring-white/10"
     draggable="true"
     data-task-id="{{ $task->id }}"
     ondragstart="handleDragStart(event)"
     ondragend="handleDragEnd(event)">

    {{-- Open Dialog Button --}}
    <button type="button"
            command="show-modal"
            commandfor="task-detail-dialog"
            onclick="loadTaskDetails({{ $task->id }})"
            class="absolute inset-0 w-full h-full z-10">
        <span class="sr-only">Ver detalles de {{ $task->title }}</span>
    </button>

    {{-- Priority Badge --}}
    <div class="relative z-20 pointer-events-none mb-2">
        <x-tasks.priority-badge :priority="$task->priority" />
    </div>

    {{-- Task Title --}}
    <h4 class="relative z-20 pointer-events-none text-sm font-semibold text-neutral-900 dark:text-white line-clamp-2">
        {{ $task->title }}
    </h4>

    {{-- Task Meta Info --}}
    <div class="relative z-20 pointer-events-none mt-3 flex items-center gap-3 text-xs text-neutral-500 dark:text-neutral-400">
        {{-- Due Date --}}
        @if($task->due_date)
        <div class="flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="{{ $task->is_overdue ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                {{ $task->due_date->format('d/m/Y') }}
            </span>
        </div>
        @endif

        {{-- Subtasks Count --}}
        @if($task->subtasks->count() > 0)
        <div class="flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>{{ $task->subtasks->count() }}</span>
        </div>
        @endif

        {{-- Attachments Count --}}
        @if($task->getMedia('attachments')->count() > 0)
        <div class="flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
            </svg>
            <span>{{ $task->getMedia('attachments')->count() }}</span>
        </div>
        @endif
    </div>

    {{-- Assigned Users Avatars --}}
    @if($task->assignments->count() > 0)
    <div class="relative z-20 pointer-events-none mt-3 flex -space-x-2">
        @foreach($task->assignments->take(3) as $assignment)
        <div class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700 ring-2 ring-white dark:bg-primary-900 dark:text-primary-300 dark:ring-neutral-800"
             title="{{ $assignment->user->name }}">
            {{ substr($assignment->user->name, 0, 1) }}
        </div>
        @endforeach
        @if($task->assignments->count() > 3)
        <div class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-neutral-100 text-xs font-medium text-neutral-600 ring-2 ring-white dark:bg-neutral-700 dark:text-neutral-300 dark:ring-neutral-800">
            +{{ $task->assignments->count() - 3 }}
        </div>
        @endif
    </div>
    @endif

    {{-- Area Badge --}}
    @if($task->area)
    <div class="relative z-20 pointer-events-none mt-3">
        <span class="inline-flex items-center rounded-md bg-neutral-100 px-2 py-1 text-xs font-medium text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
            {{ $task->area->name }}
        </span>
    </div>
    @endif
</div>