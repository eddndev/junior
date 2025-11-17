<div>
    @if($task)
        {{-- Header --}}
        <x-dialog-header dialog-id="task-detail-dialog">
            <x-slot:title>
                {{-- Title Editor --}}
                @if($editingTitle)
                    <div class="flex items-center gap-2">
                        <input type="text"
                               wire:model="title"
                               wire:keydown.enter="saveTitle"
                               wire:keydown.escape="cancelEditTitle"
                               class="flex-1 rounded-md border-neutral-300 text-lg font-semibold focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                               placeholder="Título de la tarea"
                               autofocus>
                        <button wire:click="saveTitle"
                                wire:loading.attr="disabled"
                                class="rounded-md bg-primary-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-primary-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="saveTitle">Guardar</span>
                            <span wire:loading wire:target="saveTitle">...</span>
                        </button>
                        <button wire:click="cancelEditTitle"
                                class="rounded-md px-3 py-1.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-700">
                            Cancelar
                        </button>
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <div class="flex items-center gap-2">
                        <span class="truncate">{{ $title }}</span>
                        <button wire:click="startEditTitle"
                                class="flex-shrink-0 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors"
                                title="Editar título">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>
                @endif
            </x-slot:title>

            <x-slot:description>
                <div class="flex items-center gap-2 mt-2">
                    {{-- Status Badge --}}
                    <x-tasks.task-status-badge :status="$task->status" />

                    {{-- Priority Badge --}}
                    @php
                        $priorityColors = [
                            'low' => 'bg-neutral-100 text-neutral-700 ring-neutral-600/20 dark:bg-neutral-700 dark:text-neutral-300',
                            'medium' => 'bg-blue-100 text-blue-700 ring-blue-700/10 dark:bg-blue-900 dark:text-blue-300',
                            'high' => 'bg-orange-100 text-orange-700 ring-orange-700/10 dark:bg-orange-900 dark:text-orange-300',
                            'critical' => 'bg-red-100 text-red-700 ring-red-600/10 dark:bg-red-900 dark:text-red-300',
                        ];
                        $priorityIcons = [
                            'low' => 'M19 14l-7 7m0 0l-7-7m7 7V3',
                            'medium' => 'M20 12H4',
                            'high' => 'M5 10l7-7m0 0l7 7m-7-7v18',
                            'critical' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $priorityColors[$priority] }}">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $priorityIcons[$priority] }}" />
                        </svg>
                        {{ $this->getPriorityLabel() }}
                    </span>

                    {{-- Due Date --}}
                    @if($dueDate)
                        <span class="text-sm {{ $this->isOverdue() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-neutral-500 dark:text-neutral-400' }}">
                            {{ $this->formatDate($dueDate) }}
                            @if($this->isOverdue())
                                <span class="ml-1">(Atrasada)</span>
                            @endif
                        </span>
                    @endif
                </div>

                {{-- Area --}}
                @if($task->area)
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ $task->area->name }}
                    </p>
                @endif
            </x-slot:description>
        </x-dialog-header>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-6">

                {{-- LEFT COLUMN (2/3) - Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Description Editor --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Descripción</h3>
                            @if(!$editingDescription)
                                <button wire:click="startEditDescription"
                                        class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors"
                                        title="Editar descripción">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        @if($editingDescription)
                            <div>
                                <textarea wire:model="description"
                                          wire:keydown.escape="cancelEditDescription"
                                          rows="6"
                                          class="w-full rounded-md border-neutral-300 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                          placeholder="Descripción de la tarea"></textarea>
                                <div class="mt-2 flex items-center gap-2">
                                    <button wire:click="saveDescription"
                                            wire:loading.attr="disabled"
                                            class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="saveDescription">Guardar</span>
                                        <span wire:loading wire:target="saveDescription">Guardando...</span>
                                    </button>
                                    <button wire:click="cancelEditDescription"
                                            class="rounded-md px-3 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                        Cancelar
                                    </button>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="prose prose-sm max-w-none dark:prose-invert text-neutral-700 dark:text-neutral-300">
                                @if($description)
                                    {!! nl2br(e($description)) !!}
                                @else
                                    <p class="text-neutral-500 dark:text-neutral-400 italic">Sin descripción</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Subtasks --}}
                    @if($task->subtasks->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">
                                Subtareas
                                <span class="ml-2 text-xs font-normal text-neutral-500">
                                    ({{ $task->subtasks->where('status', 'completed')->count() }}/{{ $task->subtasks->count() }})
                                </span>
                            </h3>
                            <div class="space-y-2">
                                @foreach($task->subtasks->sortBy('order') as $subtask)
                                    <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                        <input type="checkbox"
                                               wire:click="toggleSubtask({{ $subtask->id }})"
                                               {{ $subtask->status === 'completed' ? 'checked' : '' }}
                                               class="mt-1 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                                        <div class="flex-1">
                                            <span class="text-sm {{ $subtask->status === 'completed' ? 'line-through text-neutral-500 dark:text-neutral-400' : 'text-neutral-900 dark:text-white' }}">
                                                {{ $subtask->title }}
                                            </span>
                                            @if($subtask->description)
                                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ $subtask->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Child Tasks (Dependent Tasks) --}}
                    @if($task->childTasks->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Tareas Dependientes</h3>
                            <div class="space-y-2">
                                @foreach($task->childTasks as $childTask)
                                    <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        <span class="text-sm text-neutral-900 dark:text-white">{{ $childTask->title }}</span>
                                        <x-tasks.task-status-badge :status="$childTask->status" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Task Submission/Deliverable --}}
                    @if($task->assignments->count() > 0)
                        <div class="pt-6 border-t border-neutral-200 dark:border-neutral-700">
                            <livewire:tasks.task-submission-manager :taskId="$task->id" :key="'submission-'.$task->id" />
                        </div>
                    @endif

                    {{-- Metadata --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-700">
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Información</h3>
                        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Creada</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">{{ $task->created_at->translatedFormat('d M Y, H:i') }}</dd>
                            </div>
                            @if($task->completed_at)
                                <div>
                                    <dt class="font-medium text-neutral-500 dark:text-neutral-400">Completada</dt>
                                    <dd class="mt-1 text-neutral-900 dark:text-white">{{ $task->completed_at->translatedFormat('d M Y, H:i') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Última Actualización</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">{{ $task->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>

                </div>

                {{-- RIGHT COLUMN (1/3) - Sidebar --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Assigned Users --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Asignados</h3>
                        @if($task->assignments->count() > 0)
                            <div class="space-y-2">
                                @foreach($task->assignments as $assignment)
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                        <x-data-display.avatar :user="$assignment->user" size="sm" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $assignment->user->name }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $assignment->user->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">Sin asignaciones</p>
                        @endif
                    </div>

                    {{-- Attachments --}}
                    @if($task->getMedia('attachments')->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Archivos Adjuntos</h3>
                            <div class="space-y-2">
                                @foreach($task->getMedia('attachments') as $media)
                                    <a href="{{ $media->getUrl() }}"
                                       target="_blank"
                                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors group">
                                        <svg class="h-5 w-5 text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-neutral-900 dark:text-white truncate">{{ $media->file_name }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $media->human_readable_size }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="task-detail-dialog">
            @can('update', $task)
                <button type="button"
                        onclick="window.DialogSystem.close('task-detail-dialog'); loadTaskForEdit({{ $task->id }})"
                        class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors">
                    Editar Tarea
                </button>
            @endcan
        </x-dialog-footer>

    @else
        {{-- Loading State --}}
        <div class="flex items-center justify-center h-full min-h-[400px] p-12">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-neutral-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando tarea...</p>
            </div>
        </div>
    @endif
</div>