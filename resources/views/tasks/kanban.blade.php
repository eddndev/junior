<x-dashboard-layout title="Tablero Kanban - Tareas">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Tablero Kanban</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Vista de tareas organizadas por estado.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:gap-x-3">
            {{-- View Toggle --}}
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <a href="{{ route('tasks.index') }}"
                   class="rounded-l-md px-3 py-2 text-sm font-semibold text-neutral-900 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:text-neutral-300 dark:ring-neutral-700 dark:hover:bg-neutral-700">
                    <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Lista
                </a>
                <a href="{{ route('tasks.kanban') }}"
                   class="rounded-r-md px-3 py-2 text-sm font-semibold bg-primary-600 text-white ring-1 ring-inset ring-primary-600 dark:bg-primary-500">
                    <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    Kanban
                </a>
            </div>

            @can('crear-tareas')
            <button type="button"
                    onclick="openDialog('create-task-dialog')"
                    class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400">
                <svg class="inline-block h-5 w-5 -ml-0.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Tarea
            </button>
            @endcan
        </div>
    </div>

    {{-- Filters --}}
    <div class="mt-6" x-data="kanbanFilters">
        <div class="flex flex-wrap gap-4">
            {{-- Area Filter --}}
            <div>
                <select x-model="filters.area_id" @change="applyFilters()"
                        class="block rounded-md border-0 py-1.5 pl-3 pr-10 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm">
                    <option value="">Todas las Áreas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Priority Filter --}}
            <div>
                <select x-model="filters.priority" @change="applyFilters()"
                        class="block rounded-md border-0 py-1.5 pl-3 pr-10 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm">
                    <option value="">Todas las Prioridades</option>
                    <option value="low">Baja</option>
                    <option value="medium">Media</option>
                    <option value="high">Alta</option>
                    <option value="critical">Crítica</option>
                </select>
            </div>

            {{-- Clear Filters Button --}}
            <button type="button"
                    x-show="filters.area_id !== '' || filters.priority !== ''"
                    @click="clearFilters()"
                    class="inline-flex items-center rounded-md px-3 py-1.5 text-sm font-semibold text-neutral-700 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-white"
                    x-transition>
                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Limpiar Filtros
            </button>

            {{-- Loading Indicator --}}
            <div x-show="loading"
                 class="inline-flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400"
                 x-transition>
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Cargando...</span>
            </div>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="mt-8 overflow-x-auto pb-4">
        <div id="kanban-board" class="inline-flex min-w-full gap-4">
            {{-- Pending Column --}}
            <div class="w-80 shrink-0">
                <div class="flex items-center justify-between rounded-t-lg bg-neutral-100 px-4 py-3 dark:bg-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                        <span class="inline-flex h-2 w-2 rounded-full bg-neutral-500 mr-2"></span>
                        Pendiente
                    </h3>
                    <span class="inline-flex items-center rounded-full bg-neutral-200 px-2 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-600 dark:text-neutral-200">
                        {{ $tasksByStatus['pending']->count() }}
                    </span>
                </div>
                <div class="space-y-3 rounded-b-lg bg-neutral-50 p-4 dark:bg-neutral-800/50 min-h-[500px]"
                     data-status="pending"
                     ondrop="handleDrop(event)"
                     ondragover="handleDragOver(event)">
                    @foreach($tasksByStatus['pending'] as $task)
                        @include('tasks._kanban-card', ['task' => $task])
                    @endforeach
                </div>
            </div>

            {{-- In Progress Column --}}
            <div class="w-80 shrink-0">
                <div class="flex items-center justify-between rounded-t-lg bg-blue-100 px-4 py-3 dark:bg-blue-900/30">
                    <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200">
                        <span class="inline-flex h-2 w-2 rounded-full bg-blue-500 mr-2"></span>
                        En Progreso
                    </h3>
                    <span class="inline-flex items-center rounded-full bg-blue-200 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-800 dark:text-blue-200">
                        {{ $tasksByStatus['in_progress']->count() }}
                    </span>
                </div>
                <div class="space-y-3 rounded-b-lg bg-blue-50 p-4 dark:bg-blue-900/10 min-h-[500px]"
                     data-status="in_progress"
                     ondrop="handleDrop(event)"
                     ondragover="handleDragOver(event)">
                    @foreach($tasksByStatus['in_progress'] as $task)
                        @include('tasks._kanban-card', ['task' => $task])
                    @endforeach
                </div>
            </div>

            {{-- Completed Column --}}
            <div class="w-80 shrink-0">
                <div class="flex items-center justify-between rounded-t-lg bg-green-100 px-4 py-3 dark:bg-green-900/30">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-200">
                        <span class="inline-flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                        Completada
                    </h3>
                    <span class="inline-flex items-center rounded-full bg-green-200 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-800 dark:text-green-200">
                        {{ $tasksByStatus['completed']->count() }}
                    </span>
                </div>
                <div class="space-y-3 rounded-b-lg bg-green-50 p-4 dark:bg-green-900/10 min-h-[500px]"
                     data-status="completed"
                     ondrop="handleDrop(event)"
                     ondragover="handleDragOver(event)">
                    @foreach($tasksByStatus['completed'] as $task)
                        @include('tasks._kanban-card', ['task' => $task])
                    @endforeach
                </div>
            </div>

            {{-- Cancelled Column --}}
            <div class="w-80 shrink-0">
                <div class="flex items-center justify-between rounded-t-lg bg-red-100 px-4 py-3 dark:bg-red-900/30">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-200">
                        <span class="inline-flex h-2 w-2 rounded-full bg-red-500 mr-2"></span>
                        Cancelada
                    </h3>
                    <span class="inline-flex items-center rounded-full bg-red-200 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-800 dark:text-red-200">
                        {{ $tasksByStatus['cancelled']->count() }}
                    </span>
                </div>
                <div class="space-y-3 rounded-b-lg bg-red-50 p-4 dark:bg-red-900/10 min-h-[500px]"
                     data-status="cancelled"
                     ondrop="handleDrop(event)"
                     ondragover="handleDragOver(event)">
                    @foreach($tasksByStatus['cancelled'] as $task)
                        @include('tasks._kanban-card', ['task' => $task])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Livewire Dialogs (OUTSIDE Livewire components) --}}
<x-dialog-wrapper id="task-detail-dialog" max-width="5xl">
    @livewire('tasks.task-detail-dialog')
</x-dialog-wrapper>

<x-dialog-wrapper id="create-task-dialog" max-width="5xl">
    @livewire('tasks.create-task-dialog')
</x-dialog-wrapper>

<x-dialog-wrapper id="edit-task-dialog" max-width="5xl">
    @livewire('tasks.edit-task-dialog')
</x-dialog-wrapper>

@push('scripts')
<script>
    // Livewire Dialog Integration
    document.addEventListener('livewire:init', () => {
        // Listen for task-created event to refresh the board
        Livewire.on('task-created', (event) => {
            const taskId = event.taskId || event[0]?.taskId;
            console.log('Task created:', taskId);

            // Reload the page to show the new task in Kanban
            window.location.reload();
        });

        // Listen for task-updated event to refresh the board
        Livewire.on('task-updated', (event) => {
            const taskId = event.taskId || event[0]?.taskId;
            console.log('Task updated:', taskId);

            // Reload the page to show the updated task in Kanban
            window.location.reload();
        });

        // Listen for show-toast events
        Livewire.on('show-toast', (event) => {
            const message = event.message || event[0]?.message || 'Operación completada';
            const type = event.type || event[0]?.type || 'success';
            showToast(message, type);
        });

        // Listen for dialog close events
        document.addEventListener('dialog-closed', (event) => {
            if (!event.detail) return;

            // Reset form when create-task-dialog closes
            if (event.detail.dialogId === 'create-task-dialog') {
                setTimeout(() => {
                    Livewire.dispatch('resetTaskForm');
                }, 100);
            }
        });
    });

    // Function to open dialog for creating a new task
    function openDialog(dialogId) {
        // Reset form to create mode
        Livewire.dispatch('resetTaskForm');

        if (!window.DialogSystem.isOpen(dialogId)) {
            window.DialogSystem.open(dialogId);
        }
    }

    // Function to open task detail dialog
    function loadTaskDetails(taskId) {
        // Load task data into Livewire component
        Livewire.dispatch('loadTask', { taskId: taskId });

        // Open the dialog only if it's not already open
        if (!window.DialogSystem.isOpen('task-detail-dialog')) {
            window.DialogSystem.open('task-detail-dialog');
        }
    }

    // Function to load task for editing
    function loadTaskForEdit(taskId) {
        // Dispatch event to Livewire component
        Livewire.dispatch('loadTaskForEdit', { taskId: taskId });

        // Wait a bit for Livewire to process the data before opening dialog
        setTimeout(() => {
            if (!window.DialogSystem.isOpen('edit-task-dialog')) {
                window.DialogSystem.open('edit-task-dialog');
            }
        }, 150);
    }
</script>
@endpush

<script>
    // Alpine.js component for Kanban filters
    document.addEventListener('alpine:init', () => {
        Alpine.data('kanbanFilters', () => ({
            filters: {
                area_id: '{{ request('area_id') }}',
                priority: '{{ request('priority') }}'
            },
            loading: false,

            applyFilters() {
                this.loading = true;

                // Build query string
                const params = new URLSearchParams();
                if (this.filters.area_id) params.append('area_id', this.filters.area_id);
                if (this.filters.priority) params.append('priority', this.filters.priority);

                // Fetch filtered tasks
                fetch(`{{ route('tasks.kanban') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the response HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Extract the kanban board content
                    const newBoard = doc.querySelector('#kanban-board');

                    if (newBoard) {
                        // Replace the board content
                        const currentBoard = document.querySelector('#kanban-board');
                        currentBoard.innerHTML = newBoard.innerHTML;

                        // Re-attach drag event listeners
                        reattachDragListeners();
                    }

                    this.loading = false;

                    // Update URL without reload
                    const newUrl = params.toString()
                        ? `{{ route('tasks.kanban') }}?${params.toString()}`
                        : '{{ route('tasks.kanban') }}';
                    window.history.pushState({}, '', newUrl);
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.loading = false;
                    showToast('Error al filtrar tareas', 'error');
                });
            },

            clearFilters() {
                this.filters.area_id = '';
                this.filters.priority = '';
                this.applyFilters();
            }
        }));
    });

    // Drag and Drop functionality
    let draggedTaskId = null;
    let draggedElement = null;
    let sourceColumn = null;

    function handleDragStart(event) {
        draggedTaskId = event.target.dataset.taskId;
        draggedElement = event.target;
        sourceColumn = event.target.closest('[data-status]');

        // Add dragging styles with animation
        event.target.style.transition = 'all 0.2s ease-out';
        event.target.classList.add('opacity-50');
        event.target.style.transform = 'scale(0.95)';
    }

    function handleDragEnd(event) {
        event.target.classList.remove('opacity-50');
        event.target.style.transform = '';

        // Reset after animation
        setTimeout(() => {
            event.target.style.transition = '';
        }, 200);
    }

    function handleDragOver(event) {
        event.preventDefault();
        const column = event.currentTarget;

        // Add highlight with smooth animation
        if (!column.classList.contains('ring-2')) {
            column.style.transition = 'all 0.2s ease-in-out';
            column.classList.add('ring-2', 'ring-primary-500', 'bg-opacity-75');
            column.style.transform = 'scale(1.02)';
        }
    }

    function handleDragLeave(event) {
        const column = event.currentTarget;
        column.classList.remove('ring-2', 'ring-primary-500', 'bg-opacity-75');
        column.style.transform = '';

        setTimeout(() => {
            column.style.transition = '';
        }, 200);
    }

    function handleDrop(event) {
        event.preventDefault();
        const targetColumn = event.currentTarget;

        // Remove highlight from target column
        targetColumn.classList.remove('ring-2', 'ring-primary-500', 'bg-opacity-75');
        targetColumn.style.transform = '';

        const newStatus = targetColumn.dataset.status;

        // Don't do anything if dropped in the same column
        if (sourceColumn === targetColumn) {
            return;
        }

        if (draggedTaskId && newStatus) {
            // Store original position
            const rect = draggedElement.getBoundingClientRect();

            // Update task status via AJAX
            fetch(`/tasks/${draggedTaskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animate card movement
                    animateCardMove(draggedElement, targetColumn);

                    // Update counters with animation
                    updateColumnCounters(true);

                    // Show success toast
                    showToast('Tarea movida exitosamente', 'success');
                } else {
                    showToast('Error al actualizar el estado de la tarea', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error al actualizar el estado de la tarea', 'error');
            });
        }
    }

    function animateCardMove(card, targetColumn) {
        // Fade out and scale down
        card.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9) translateY(-10px)';

        setTimeout(() => {
            // Move to new column
            const firstCard = targetColumn.querySelector('[data-task-id]');
            if (firstCard) {
                targetColumn.insertBefore(card, firstCard);
            } else {
                targetColumn.appendChild(card);
            }

            // Reset position for animation
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9) translateY(10px)';

            // Trigger reflow
            card.offsetHeight;

            // Fade in and scale up
            card.style.opacity = '1';
            card.style.transform = 'scale(1) translateY(0)';

            // Clean up
            setTimeout(() => {
                card.style.transition = '';
                card.style.transform = '';
            }, 300);
        }, 300);
    }

    function updateColumnCounters(animate = false) {
        document.querySelectorAll('[data-status]').forEach(column => {
            const count = column.querySelectorAll('[data-task-id]').length;
            const counter = column.parentElement.querySelector('.rounded-full.px-2');

            if (counter && counter.textContent != count) {
                if (animate) {
                    // Animate counter change
                    counter.style.transition = 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1)';
                    counter.style.transform = 'scale(1.3)';

                    setTimeout(() => {
                        counter.textContent = count;
                        counter.style.transform = 'scale(1)';

                        setTimeout(() => {
                            counter.style.transition = '';
                        }, 200);
                    }, 100);
                } else {
                    counter.textContent = count;
                }
            }
        });
    }

    function showToast(message, type = 'success') {
        // Create toast element
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

    function reattachDragListeners() {
        // Re-attach drag leave event to all columns
        document.querySelectorAll('[data-status]').forEach(column => {
            column.addEventListener('dragleave', handleDragLeave);
        });
    }

    // Add drag leave event to all columns on page load
    document.addEventListener('DOMContentLoaded', () => {
        reattachDragListeners();
    });
</script>
</x-dashboard-layout>
