{{-- Task Detail Dialog (GitHub Projects Style) --}}
<el-dialog>
    <dialog id="task-detail-dialog"
            aria-labelledby="task-detail-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">

        <el-dialog-backdrop class="absolute inset-0 bg-neutral-500/75 dark:bg-neutral-950/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>

        <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
            <el-dialog-panel class="ml-auto block size-full max-w-2xl transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                <div class="flex h-full flex-col overflow-y-auto bg-white dark:bg-neutral-900 shadow-xl">

                    {{-- Header --}}
                    <div class="sticky top-0 z-10 border-b border-neutral-200 bg-white px-6 py-4 dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    {{-- Title Display/Edit Mode --}}
                                    <div class="flex-1" x-data="titleEditor">
                                        <div x-show="!editing" class="flex items-center gap-2">
                                            <h2 id="task-detail-title" class="text-lg font-semibold text-neutral-900 dark:text-white" x-text="title">Cargando...</h2>
                                            <button @click="startEdit()"
                                                    class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300"
                                                    title="Editar título">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="editing" class="flex items-center gap-2">
                                            <input type="text"
                                                   x-model="title"
                                                   @keydown.enter="save()"
                                                   @keydown.escape="cancel()"
                                                   class="flex-1 rounded-md border-neutral-300 text-lg font-semibold focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                                   placeholder="Título de la tarea">
                                            <button @click="save()"
                                                    class="rounded-md bg-primary-600 px-2 py-1 text-sm font-semibold text-white hover:bg-primary-500">
                                                Guardar
                                            </button>
                                            <button @click="cancel()"
                                                    class="rounded-md px-2 py-1 text-sm font-semibold text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div data-task-status-badge></div>
                                </div>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400" data-task-area>
                                    <!-- Area will be loaded here -->
                                </p>
                            </div>
                            <div class="ml-3 flex h-7 items-center">
                                <button type="button"
                                        command="close"
                                        commandfor="task-detail-dialog"
                                        class="relative -m-2 p-2 text-neutral-400 hover:text-neutral-500 dark:hover:text-neutral-300">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">Cerrar panel</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 overflow-y-auto px-6 py-6">
                        {{-- Priority and Due Date --}}
                        <div class="flex items-center gap-4 pb-6 border-b border-neutral-200 dark:border-neutral-700">
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Prioridad</label>
                                <div data-task-priority-badge></div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Fecha Límite</label>
                                <div class="text-sm text-neutral-900 dark:text-white" data-task-due-date>
                                    Sin fecha
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mt-6" x-data="descriptionEditor">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Descripción</h3>
                                <button @click="startEdit()"
                                        x-show="!editing"
                                        class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300"
                                        title="Editar descripción">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>

                            <div x-show="!editing">
                                <div id="task-description-display" class="prose prose-sm max-w-none dark:prose-invert text-neutral-700 dark:text-neutral-300" x-html="description || '<p class=\'text-neutral-500 dark:text-neutral-400\'>Sin descripción</p>'"></div>
                            </div>

                            <div x-show="editing">
                                <textarea x-model="description"
                                          rows="6"
                                          @keydown.escape="cancel()"
                                          class="w-full rounded-md border-neutral-300 focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                          placeholder="Descripción de la tarea"></textarea>
                                <div class="mt-2 flex items-center gap-2">
                                    <button @click="save()"
                                            class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">
                                        Guardar
                                    </button>
                                    <button @click="cancel()"
                                            class="rounded-md px-3 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Assigned Users --}}
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Asignados</h3>
                            <div class="space-y-2" data-task-assigned-users>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Sin asignaciones</p>
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Archivos Adjuntos</h3>
                            <div class="space-y-2" data-task-attachments>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Sin archivos</p>
                            </div>
                        </div>

                        {{-- Subtasks --}}
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Subtareas</h3>
                            <div class="space-y-2" data-task-subtasks>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Sin subtareas</p>
                            </div>
                        </div>

                        {{-- Child Tasks --}}
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Tareas Dependientes</h3>
                            <div class="space-y-2" data-task-child-tasks>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Sin tareas dependientes</p>
                            </div>
                        </div>

                        {{-- Metadata --}}
                        <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                            <dl class="space-y-3 text-sm">
                                <div>
                                    <dt class="font-medium text-neutral-500 dark:text-neutral-400">Creada</dt>
                                    <dd class="mt-1 text-neutral-900 dark:text-white" data-task-created-at>-</dd>
                                </div>
                                <div data-task-completed-at-container style="display: none;">
                                    <dt class="font-medium text-neutral-500 dark:text-neutral-400">Completada</dt>
                                    <dd class="mt-1 text-neutral-900 dark:text-white" data-task-completed-at>-</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-neutral-500 dark:text-neutral-400">Última Actualización</dt>
                                    <dd class="mt-1 text-neutral-900 dark:text-white" data-task-updated-at>-</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="sticky bottom-0 border-t border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800">
                        <div class="flex justify-between items-center">
                            <button type="button"
                                    command="close"
                                    commandfor="task-detail-dialog"
                                    class="rounded-md px-3 py-2 text-sm font-semibold text-neutral-900 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700">
                                Cerrar
                            </button>
                            <button type="button"
                                    onclick="openEditTaskDialog()"
                                    data-task-edit-button
                                    class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400">
                                Editar Tarea
                            </button>
                        </div>
                    </div>
                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

<script>
// Alpine.js components
document.addEventListener('alpine:init', () => {
    // Title editor component
    Alpine.data('titleEditor', () => ({
        title: 'Cargando...',
        originalTitle: '',
        editing: false,

        startEdit() {
            this.originalTitle = this.title;
            this.editing = true;
        },

        cancel() {
            this.title = this.originalTitle;
            this.editing = false;
        },

        save() {
            if (!currentTaskId) return;
            if (!this.title || this.title.trim() === '') {
                alert('El título no puede estar vacío');
                return;
            }

            saveTitle(this.title, () => {
                this.editing = false;
            });
        }
    }));

    // Description editor component
    Alpine.data('descriptionEditor', () => ({
        description: '',
        originalDescription: '',
        editing: false,

        startEdit() {
            this.originalDescription = this.description;
            this.editing = true;
        },

        cancel() {
            this.description = this.originalDescription;
            this.editing = false;
        },

        save() {
            if (!currentTaskId) return;

            saveDescription(this.description, () => {
                this.editing = false;
            });
        }
    }));
});

let currentTaskId = null;

function loadTaskDetails(taskId) {
    currentTaskId = taskId;

    // Fetch task details
    fetch(`/tasks/${taskId}/details`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(task => {
        // Update title using Alpine store/component
        updateAlpineData('titleEditor', 'title', task.title);

        // Update description
        updateAlpineData('descriptionEditor', 'description', task.description || '');

        // Update area
        document.querySelector('[data-task-area]').textContent = task.area ? task.area.name : 'Sin área';

        // Update status badge
        const statusBadge = document.querySelector('[data-task-status-badge]');
        statusBadge.innerHTML = getStatusBadgeHtml(task.status);

        // Update priority badge
        const priorityBadge = document.querySelector('[data-task-priority-badge]');
        priorityBadge.innerHTML = getPriorityBadgeHtml(task.priority);

        // Update due date
        const dueDateEl = document.querySelector('[data-task-due-date]');
        if (task.due_date) {
            const date = new Date(task.due_date);
            dueDateEl.textContent = date.toLocaleDateString('es-ES');
            if (task.is_overdue) {
                dueDateEl.classList.add('text-red-600', 'dark:text-red-400', 'font-semibold');
            } else {
                dueDateEl.classList.remove('text-red-600', 'dark:text-red-400', 'font-semibold');
            }
        } else {
            dueDateEl.textContent = 'Sin fecha';
        }

        // Update assigned users
        const assignedUsersEl = document.querySelector('[data-task-assigned-users]');
        if (task.assignments && task.assignments.length > 0) {
            assignedUsersEl.innerHTML = task.assignments.map(assignment => `
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-medium text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                        ${assignment.user.name.charAt(0)}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">${assignment.user.name}</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">${assignment.user.email}</p>
                    </div>
                </div>
            `).join('');
        } else {
            assignedUsersEl.innerHTML = '<p class="text-sm text-neutral-500 dark:text-neutral-400">Sin asignaciones</p>';
        }

        // Update attachments
        const attachmentsEl = document.querySelector('[data-task-attachments]');
        if (task.attachments && task.attachments.length > 0) {
            attachmentsEl.innerHTML = task.attachments.map(attachment => `
                <a href="${attachment.url}" target="_blank"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span class="text-sm text-neutral-900 dark:text-white">${attachment.name}</span>
                </a>
            `).join('');
        } else {
            attachmentsEl.innerHTML = '<p class="text-sm text-neutral-500 dark:text-neutral-400">Sin archivos</p>';
        }

        // Update subtasks
        const subtasksEl = document.querySelector('[data-task-subtasks]');
        if (task.subtasks && task.subtasks.length > 0) {
            subtasksEl.innerHTML = task.subtasks.map(subtask => `
                <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-sm text-neutral-900 dark:text-white">${subtask.title}</span>
                </div>
            `).join('');
        } else {
            subtasksEl.innerHTML = '<p class="text-sm text-neutral-500 dark:text-neutral-400">Sin subtareas</p>';
        }

        // Update child tasks
        const childTasksEl = document.querySelector('[data-task-child-tasks]');
        if (task.child_tasks && task.child_tasks.length > 0) {
            childTasksEl.innerHTML = task.child_tasks.map(childTask => `
                <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="text-sm text-neutral-900 dark:text-white">${childTask.title}</span>
                </div>
            `).join('');
        } else {
            childTasksEl.innerHTML = '<p class="text-sm text-neutral-500 dark:text-neutral-400">Sin tareas dependientes</p>';
        }

        // Update metadata
        document.querySelector('[data-task-created-at]').textContent = new Date(task.created_at).toLocaleString('es-ES');
        document.querySelector('[data-task-updated-at]').textContent = new Date(task.updated_at).toLocaleString('es-ES');

        if (task.completed_at) {
            document.querySelector('[data-task-completed-at-container]').style.display = 'block';
            document.querySelector('[data-task-completed-at]').textContent = new Date(task.completed_at).toLocaleString('es-ES');
        } else {
            document.querySelector('[data-task-completed-at-container]').style.display = 'none';
        }

        // Update edit button (no need to update href, we use onclick with currentTaskId)
    })
    .catch(error => {
        console.error('Error loading task details:', error);
        document.querySelector('[data-task-title]').textContent = 'Error al cargar la tarea';
    });
}

function getStatusBadgeHtml(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-700 ring-1 ring-inset ring-neutral-600/20 dark:bg-neutral-400/10 dark:text-neutral-400 dark:ring-neutral-400/20">Pendiente</span>',
        'in_progress': '<span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">En Progreso</span>',
        'completed': '<span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">Completada</span>',
        'cancelled': '<span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/20">Cancelada</span>'
    };
    return badges[status] || badges['pending'];
}

function getPriorityBadgeHtml(priority) {
    const badges = {
        'low': '<span class="inline-flex items-center gap-x-1.5 rounded-md bg-neutral-100 px-2 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-700 dark:text-neutral-300"><svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>Baja</span>',
        'medium': '<span class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300"><svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>Media</span>',
        'high': '<span class="inline-flex items-center gap-x-1.5 rounded-md bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900 dark:text-orange-300"><svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>Alta</span>',
        'critical': '<span class="inline-flex items-center gap-x-1.5 rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900 dark:text-red-300"><svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>Crítica</span>'
    };
    return badges[priority] || badges['medium'];
}

// Helper function to update Alpine.js component data
function updateAlpineData(componentName, property, value) {
    // Find all elements with the component
    const elements = document.querySelectorAll(`[x-data="${componentName}"]`);
    elements.forEach(el => {
        if (el._x_dataStack && el._x_dataStack[0]) {
            el._x_dataStack[0][property] = value;
        }
    });
}

function saveTitle(newTitle, callback) {
    if (!currentTaskId) return;

    // Send AJAX request to update title
    fetch(`/tasks/${currentTaskId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ title: newTitle })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('Título actualizado exitosamente', 'success');
            }
            if (callback) callback();
        } else {
            alert('Error al actualizar el título');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el título');
    });
}

function saveDescription(newDescription, callback) {
    if (!currentTaskId) return;

    // Send AJAX request to update description
    fetch(`/tasks/${currentTaskId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ description: newDescription })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('Descripción actualizada exitosamente', 'success');
            }
            if (callback) callback();
        } else {
            alert('Error al actualizar la descripción');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la descripción');
    });
}

// Function to open edit dialog
function openEditTaskDialog() {
    if (!currentTaskId) return;

    // Close the detail dialog
    window.DialogSystem.close('task-detail-dialog');

    // Call the global loadTaskForEdit function (defined in parent pages)
    if (typeof loadTaskForEdit === 'function') {
        loadTaskForEdit(currentTaskId);
    } else {
        console.error('loadTaskForEdit function not found');
    }
}
</script>