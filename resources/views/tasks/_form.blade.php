{{-- Reusable form partial for create/edit task --}}

<div class="space-y-8">
    {{-- Basic Information Section --}}
    <div>
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Información de la Tarea
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Detalles básicos y configuración de la tarea.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
            {{-- Title --}}
            <div class="sm:col-span-6">
                <label for="title" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Título <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $task->title ?? '') }}"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('title') ring-red-500 dark:ring-red-500 @enderror"
                        placeholder="Ej: Revisar propuesta de diseño"
                    />
                </div>
                @error('title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="sm:col-span-6">
                <label for="description" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Descripción
                </label>
                <div class="mt-2">
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('description') ring-red-500 dark:ring-red-500 @enderror"
                        placeholder="Describe los detalles de la tarea..."
                    >{{ old('description', $task->description ?? '') }}</textarea>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Area --}}
            <div class="sm:col-span-3">
                <label for="area_id" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Área <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <select
                        name="area_id"
                        id="area_id"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('area_id') ring-red-500 dark:ring-red-500 @enderror"
                    >
                        <option value="">Selecciona un área</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $task->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('area_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Parent Task --}}
            <div class="sm:col-span-3">
                <label for="parent_task_id" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Tarea Padre (opcional)
                </label>
                <div class="mt-2">
                    <select
                        name="parent_task_id"
                        id="parent_task_id"
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6"
                    >
                        <option value="">Ninguna (tarea principal)</option>
                        @foreach($parentTasks as $parentTask)
                            <option value="{{ $parentTask->id }}" {{ old('parent_task_id', $task->parent_task_id ?? '') == $parentTask->id ? 'selected' : '' }}>
                                {{ $parentTask->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                    Si esta tarea depende de otra, selecciónala aquí
                </p>
                @error('parent_task_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Priority --}}
            <div class="sm:col-span-2">
                <label for="priority" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Prioridad <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <select
                        name="priority"
                        id="priority"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('priority') ring-red-500 dark:ring-red-500 @enderror"
                    >
                        <option value="low" {{ old('priority', $task->priority ?? 'medium') === 'low' ? 'selected' : '' }}>Baja</option>
                        <option value="medium" {{ old('priority', $task->priority ?? 'medium') === 'medium' ? 'selected' : '' }}>Media</option>
                        <option value="high" {{ old('priority', $task->priority ?? 'medium') === 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="critical" {{ old('priority', $task->priority ?? 'medium') === 'critical' ? 'selected' : '' }}>Crítica</option>
                    </select>
                </div>
                @error('priority')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="sm:col-span-2">
                <label for="status" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Estado @if(isset($task)) <span class="text-red-500">*</span> @endif
                </label>
                <div class="mt-2">
                    <select
                        name="status"
                        id="status"
                        @if(isset($task)) required @endif
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6"
                    >
                        <option value="pending" {{ old('status', $task->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_progress" {{ old('status', $task->status ?? 'pending') === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                        <option value="completed" {{ old('status', $task->status ?? 'pending') === 'completed' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelled" {{ old('status', $task->status ?? 'pending') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                @if(!isset($task))
                    <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                        Por defecto se creará como "Pendiente"
                    </p>
                @endif
                @error('status')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Due Date --}}
            <div class="sm:col-span-2">
                <label for="due_date" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Fecha Límite
                </label>
                <div class="mt-2">
                    <input
                        type="date"
                        name="due_date"
                        id="due_date"
                        value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                        min="{{ date('Y-m-d') }}"
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6"
                    />
                </div>
                @error('due_date')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Assignment Section --}}
    <div class="border-t border-neutral-200 dark:border-neutral-700 pt-8">
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Asignación
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Selecciona los usuarios responsables de esta tarea.
        </p>

        <div class="mt-6">
            <label class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                Usuarios asignados
            </label>
            <div class="mt-4 space-y-2 max-h-60 overflow-y-auto rounded-md border border-neutral-300 dark:border-neutral-700 p-4">
                @foreach($users as $user)
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="assigned_users[]"
                            id="user_{{ $user->id }}"
                            value="{{ $user->id }}"
                            {{ in_array($user->id, old('assigned_users', $assignedUserIds ?? [])) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                        />
                        <label for="user_{{ $user->id }}" class="ml-3 text-sm text-neutral-900 dark:text-white">
                            {{ $user->name }}
                            <span class="text-xs text-neutral-500 dark:text-neutral-400">({{ $user->email }})</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                Puedes asignar la tarea a múltiples usuarios si es colaborativa
            </p>
            @error('assigned_users')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            @error('assigned_users.*')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Subtasks Section (Checklist) --}}
    <div class="border-t border-neutral-200 dark:border-neutral-700 pt-8"
         x-data="{
             subtasks: {{ json_encode(old('subtasks', isset($task) ? $task->subtasks->map(fn($s) => ['id' => $s->id, 'title' => $s->title, 'description' => $s->description, 'status' => $s->status, 'order' => $s->order])->toArray() : [])) }},
             addSubtask() {
                 this.subtasks.push({
                     id: null,
                     title: '',
                     description: '',
                     status: 'pending',
                     order: this.subtasks.length
                 });
             },
             removeSubtask(index) {
                 this.subtasks.splice(index, 1);
                 // Reorder remaining subtasks
                 this.subtasks.forEach((subtask, i) => {
                     subtask.order = i;
                 });
             },
             moveUp(index) {
                 if (index > 0) {
                     const temp = this.subtasks[index];
                     this.subtasks[index] = this.subtasks[index - 1];
                     this.subtasks[index - 1] = temp;
                     // Update order values
                     this.subtasks.forEach((subtask, i) => {
                         subtask.order = i;
                     });
                 }
             },
             moveDown(index) {
                 if (index < this.subtasks.length - 1) {
                     const temp = this.subtasks[index];
                     this.subtasks[index] = this.subtasks[index + 1];
                     this.subtasks[index + 1] = temp;
                     // Update order values
                     this.subtasks.forEach((subtask, i) => {
                         subtask.order = i;
                     });
                 }
             }
         }">
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Subtareas (Checklist Interno)
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Pasos específicos para completar esta tarea. Útil para trackear progreso granular.
        </p>

        <div class="mt-6">
            {{-- Subtasks list --}}
            <template x-if="subtasks.length > 0">
                <div class="space-y-4">
                    <template x-for="(subtask, index) in subtasks" :key="index">
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-neutral-50 dark:bg-neutral-800/50">
                            <div class="flex items-start gap-4">
                                {{-- Order controls --}}
                                <div class="flex flex-col gap-1">
                                    <button type="button"
                                            @click="moveUp(index)"
                                            :disabled="index === 0"
                                            class="p-1 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 disabled:opacity-30 disabled:cursor-not-allowed">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <button type="button"
                                            @click="moveDown(index)"
                                            :disabled="index === subtasks.length - 1"
                                            class="p-1 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 disabled:opacity-30 disabled:cursor-not-allowed">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Subtask fields --}}
                                <div class="flex-1 space-y-3">
                                    {{-- Hidden ID field for existing subtasks --}}
                                    <input type="hidden" :name="`subtasks[${index}][id]`" x-model="subtask.id">
                                    <input type="hidden" :name="`subtasks[${index}][order]`" x-model="subtask.order">

                                    {{-- Title --}}
                                    <div>
                                        <label :for="`subtask_title_${index}`" class="block text-sm font-medium text-neutral-900 dark:text-white">
                                            Título <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               :id="`subtask_title_${index}`"
                                               :name="`subtasks[${index}][title]`"
                                               x-model="subtask.title"
                                               required
                                               placeholder="Ej: Diseñar mockups"
                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm">
                                    </div>

                                    {{-- Description --}}
                                    <div>
                                        <label :for="`subtask_description_${index}`" class="block text-sm font-medium text-neutral-900 dark:text-white">
                                            Descripción (opcional)
                                        </label>
                                        <textarea :id="`subtask_description_${index}`"
                                                  :name="`subtasks[${index}][description]`"
                                                  x-model="subtask.description"
                                                  rows="2"
                                                  placeholder="Detalles adicionales..."
                                                  class="mt-1 block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm"></textarea>
                                    </div>

                                    {{-- Status (only in edit mode) --}}
                                    <template x-if="subtask.id">
                                        <div>
                                            <label :for="`subtask_status_${index}`" class="block text-sm font-medium text-neutral-900 dark:text-white">
                                                Estado
                                            </label>
                                            <select :id="`subtask_status_${index}`"
                                                    :name="`subtasks[${index}][status]`"
                                                    x-model="subtask.status"
                                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm">
                                                <option value="pending">Pendiente</option>
                                                <option value="in_progress">En Progreso</option>
                                                <option value="completed">Completada</option>
                                            </select>
                                        </div>
                                    </template>
                                </div>

                                {{-- Remove button --}}
                                <button type="button"
                                        @click="removeSubtask(index)"
                                        class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Add subtask button --}}
            <button type="button"
                    @click="addSubtask()"
                    class="mt-4 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Subtarea
            </button>

            <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">
                Las subtareas son pasos internos para completar esta tarea. Usa "Tarea Padre" arriba si quieres crear una tarea dependiente completa.
            </p>
        </div>
    </div>

    {{-- File Attachments Section --}}
    <div class="border-t border-neutral-200 dark:border-neutral-700 pt-8">
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Archivos Adjuntos
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Sube archivos relacionados con la tarea (documentos, imágenes, etc.).
        </p>

        <div class="mt-6">
            <label for="attachments" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                Seleccionar archivos
            </label>
            <div class="mt-2">
                <input
                    type="file"
                    name="attachments[]"
                    id="attachments"
                    multiple
                    accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.json,.zip,.rar,.7z"
                    class="block w-full text-sm text-neutral-900 dark:text-white
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-primary-50 file:text-primary-700
                        hover:file:bg-primary-100
                        dark:file:bg-primary-900/20 dark:file:text-primary-400
                        dark:hover:file:bg-primary-900/30"
                />
            </div>
            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                Máximo 10MB por archivo. Formatos: imágenes, documentos, PDFs, archivos comprimidos
            </p>
            @error('attachments')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            @error('attachments.*')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Show existing attachments if editing --}}
        @if(isset($task) && $task->media->count() > 0)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-neutral-900 dark:text-white">Archivos actuales</h4>
                <ul class="mt-3 divide-y divide-neutral-200 dark:divide-neutral-700 rounded-md border border-neutral-200 dark:border-neutral-700">
                    @foreach($task->media as $media)
                        <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                            <div class="flex w-0 flex-1 items-center">
                                <svg class="h-5 w-5 flex-shrink-0 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="ml-2 w-0 flex-1 truncate text-neutral-900 dark:text-white">{{ $media->file_name }}</span>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <a href="{{ $media->getUrl() }}" target="_blank" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">
                                    Descargar
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
