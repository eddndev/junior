<div>
    {{-- Header --}}
    <x-dialog-header
        title="Editar Tarea"
        subtitle="Actualiza la información de la tarea"
        dialog-id="edit-task-dialog" />

    {{-- Content --}}
    <form wire:submit="save"
          wire:key="edit-task-{{ $taskId ?? 'loading' }}"
          x-data="{
              areaId: @js($areaId ?? 0),
              priority: @js($priority ?? 'medium'),
              status: @js($status ?? 'pending'),
              parentTaskId: @js($parentTaskId ?? null),
              assignedUsers: @js($assignedUsers ?? []),
              users: @js($users->toArray())
          }"
          @submit.prevent="
              $wire.set('areaId', areaId);
              $wire.set('priority', priority);
              $wire.set('status', status);
              $wire.set('parentTaskId', parentTaskId);
              $wire.set('assignedUsers', assignedUsers);
              $wire.call('save');
          "
          class="flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-6">

            {{-- LEFT COLUMN (2/3) - Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="title"
                           wire:model="title"
                           class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white sm:text-sm"
                           placeholder="Ej: Implementar nueva funcionalidad"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Descripción
                    </label>
                    <textarea id="description"
                              wire:model="description"
                              rows="4"
                              class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white sm:text-sm"
                              placeholder="Describe los detalles de la tarea..."></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Area --}}
                <div>
                    <x-forms.select
                        label="Área"
                        name="areaId"
                        x-model="areaId"
                        :error="$errors->first('areaId')"
                        placeholder="Selecciona un área">
                        @foreach($areas as $area)
                            <x-forms.select-option :value="$area->id">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-6 w-6 items-center justify-center rounded bg-primary-100 text-xs font-semibold text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                                        {{ substr($area->name, 0, 1) }}
                                    </div>
                                    <span>{{ $area->name }}</span>
                                </div>
                            </x-forms.select-option>
                        @endforeach
                    </x-forms.select>
                </div>

                {{-- Priority and Status (Grid) --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- Priority --}}
                    <div>
                        <x-forms.select
                            label="Prioridad"
                            name="priority"
                            x-model="priority"
                            :error="$errors->first('priority')"
                            placeholder="Selecciona prioridad">
                            <x-forms.select-option value="low">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-neutral-400"></span>
                                    <span>Baja</span>
                                </div>
                            </x-forms.select-option>
                            <x-forms.select-option value="medium">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                                    <span>Media</span>
                                </div>
                            </x-forms.select-option>
                            <x-forms.select-option value="high">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-orange-500"></span>
                                    <span>Alta</span>
                                </div>
                            </x-forms.select-option>
                            <x-forms.select-option value="critical">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                                    <span>Crítica</span>
                                </div>
                            </x-forms.select-option>
                        </x-forms.select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-forms.select
                            label="Estado"
                            name="status"
                            x-model="status"
                            :error="$errors->first('status')"
                            placeholder="Selecciona estado">
                            <x-forms.select-option value="pending">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-neutral-400"></span>
                                    <span>Pendiente</span>
                                </div>
                            </x-forms.select-option>
                            <x-forms.select-option value="in_progress">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                                    <span>En Progreso</span>
                                </div>
                            </x-forms.select-option>
                            <x-forms.select-option value="completed">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                                    <span>Completada</span>
                                </div>
                            </x-forms.select-option>
                            <x-forms.select-option value="cancelled">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                                    <span>Cancelada</span>
                                </div>
                            </x-forms.select-option>
                        </x-forms.select>
                    </div>
                </div>

                {{-- Due Date and Parent Task (Grid) --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- Due Date --}}
                    <div>
                        <label for="dueDate" class="block text-sm font-medium text-neutral-900 dark:text-white">
                            Fecha Límite
                        </label>
                        <input type="date"
                               id="dueDate"
                               wire:model="dueDate"
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white sm:text-sm">
                        @error('dueDate')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Parent Task (Optional) --}}
                    <div>
                        <x-forms.select
                            label="Tarea Padre"
                            name="parentTaskId"
                            x-model="parentTaskId"
                            :error="$errors->first('parentTaskId')"
                            corner-hint="Opcional"
                            description="Selecciona si esta tarea depende de otra"
                            placeholder="Sin tarea padre">
                            @foreach($parentTasks as $parentTask)
                                <x-forms.select-option :value="$parentTask->id">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="truncate">{{ $parentTask->title }}</span>
                                    </div>
                                </x-forms.select-option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>

                {{-- Subtasks (Checklist) --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-neutral-900 dark:text-white">
                            Subtareas (Checklist)
                        </label>
                        <button type="button"
                                wire:click="addSubtask"
                                class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar Subtarea
                        </button>
                    </div>

                    @if(count($subtasks) > 0)
                        <div class="space-y-3">
                            @foreach($subtasks as $index => $subtask)
                                <div wire:key="subtask-{{ $subtask['temp_id'] }}"
                                     class="flex items-start gap-2 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800">
                                    <div class="flex-1 space-y-2">
                                        <input type="text"
                                               wire:model="subtasks.{{ $index }}.title"
                                               placeholder="Título de la subtarea"
                                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white text-sm">
                                        @error("subtasks.{$index}.title")
                                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror

                                        <textarea wire:model="subtasks.{{ $index }}.description"
                                                  rows="2"
                                                  placeholder="Descripción (opcional)"
                                                  class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white text-sm"></textarea>
                                        @error("subtasks.{$index}.description")
                                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="button"
                                            wire:click="removeSubtask('{{ $subtask['temp_id'] }}')"
                                            class="flex-shrink-0 p-1 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                            title="Eliminar subtarea">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">
                            No hay subtareas. Haz clic en "Agregar Subtarea" para crear una.
                        </p>
                    @endif
                </div>

            </div>

            {{-- RIGHT COLUMN (1/3) - Sidebar --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Assigned Users (Multi-select with checkboxes) --}}
                <div x-data="{
                    open: false,
                    toggleUser(userId) {
                        const index = assignedUsers.indexOf(userId);
                        if (index > -1) {
                            assignedUsers.splice(index, 1);
                        } else {
                            assignedUsers.push(userId);
                        }
                    },
                    isSelected(userId) {
                        return assignedUsers.indexOf(userId) > -1;
                    },
                    getSelectedUsersData() {
                        return users.filter(user => assignedUsers.indexOf(user.id) > -1);
                    }
                }">
                    <label class="block text-sm font-medium text-neutral-900 dark:text-white mb-3">
                        Usuarios Asignados
                    </label>

                    {{-- Selected Users Display --}}
                    <template x-if="assignedUsers.length > 0">
                        <div class="mb-3 flex flex-wrap gap-2">
                            <template x-for="user in getSelectedUsersData()" :key="user.id">
                                <span class="inline-flex items-center gap-1.5 rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-700/10 dark:bg-primary-900/30 dark:text-primary-400 dark:ring-primary-400/30">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-xs font-semibold text-white dark:bg-primary-500" x-text="user.name.charAt(0)"></span>
                                    <span x-text="user.name"></span>
                                </span>
                            </template>
                        </div>
                    </template>

                    <div class="relative">
                        {{-- Dropdown Button --}}
                        <button type="button"
                                @click="open = !open"
                                class="w-full cursor-default rounded-md bg-white py-2 pl-3 pr-10 text-left text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 sm:text-sm">
                            <span class="block truncate" x-text="assignedUsers.length > 0 ? assignedUsers.length + ' usuario(s) seleccionado(s)' : 'Selecciona usuarios'"></span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <svg class="h-5 w-5 text-neutral-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>

                        {{-- Dropdown List --}}
                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-neutral-800 dark:ring-white/10 sm:text-sm"
                             style="display: none;">
                        <template x-for="user in users" :key="user.id">
                            <label class="group relative flex cursor-pointer select-none items-center gap-3 py-2 pl-3 pr-9 text-neutral-900 hover:bg-primary-50 dark:text-white dark:hover:bg-primary-900/30">
                                <input type="checkbox"
                                       :checked="isSelected(user.id)"
                                       @change="toggleUser(user.id)"
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-600 dark:bg-neutral-700">
                                <div class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-semibold text-primary-700 dark:bg-primary-900 dark:text-primary-300" x-text="user.name.charAt(0)"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate" x-text="user.name"></div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate" x-text="user.email"></div>
                                </div>
                            </label>
                        </template>
                        </div>
                    </div>

                    @error('assignedUsers')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File Attachments --}}
                <div>
                    <label for="attachments" class="block text-sm font-medium text-neutral-900 dark:text-white mb-3">
                        Archivos Adjuntos
                    </label>
                    <input type="file"
                           id="attachments"
                           wire:model="attachments"
                           multiple
                           class="block w-full text-sm text-neutral-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900 dark:file:text-primary-300">
                    <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                        Máximo 10MB por archivo. Puedes seleccionar múltiples archivos.
                    </p>
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- Upload Progress --}}
                    <div wire:loading wire:target="attachments" class="mt-2">
                        <div class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Subiendo archivos...</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <x-dialog-footer
            dialog-id="edit-task-dialog"
            align="end">

            <button type="button"
                    wire:click="cancel"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="rounded-md px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-200 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Cancelar
            </button>

            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex items-center gap-2 rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">Actualizar Tarea</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Actualizando...
                </span>
            </button>
        </x-dialog-footer>
    </form>
</div>