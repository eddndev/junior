<div>
    @if($event)
        {{-- Header --}}
        <x-dialog-header dialog-id="edit-event-dialog">
            <x-slot:title>
                {{ $type === 'meeting' ? 'Editar Reunión' : 'Editar Evento' }}
            </x-slot:title>
            <x-slot:description>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    Modifique los campos necesarios para actualizar el evento
                </p>
            </x-slot:description>
        </x-dialog-header>

        {{-- Form Content --}}
        <form wire:submit="save" class="flex-1 overflow-y-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-6">

                {{-- LEFT COLUMN (2/3) - Main Information --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Event Type Selector --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">
                            Tipo <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio"
                                       wire:model.live="type"
                                       value="event"
                                       class="h-4 w-4 border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-600 dark:bg-neutral-700" />
                                <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Evento</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio"
                                       wire:model.live="type"
                                       value="meeting"
                                       class="h-4 w-4 border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-600 dark:bg-neutral-700" />
                                <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Reunión</span>
                            </label>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="edit_title" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="edit_title"
                               wire:model="title"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('title') border-red-500 @enderror" />
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Descripción
                        </label>
                        <textarea id="edit_description"
                                  wire:model="description"
                                  rows="4"
                                  class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('description') border-red-500 @enderror"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_status"
                                wire:model="status"
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('status') border-red-500 @enderror">
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date and Time --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Start Date --}}
                        <div>
                            <label for="edit_startDate" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Fecha de inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="edit_startDate"
                                   wire:model="startDate"
                                   class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('startDate') border-red-500 @enderror" />
                            @error('startDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- End Date --}}
                        <div>
                            <label for="edit_endDate" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Fecha de fin <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="edit_endDate"
                                   wire:model="endDate"
                                   class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('endDate') border-red-500 @enderror" />
                            @error('endDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- All Day Checkbox --}}
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="edit_isAllDay"
                               wire:model.live="isAllDay"
                               class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800" />
                        <label for="edit_isAllDay" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                            Evento de todo el día
                        </label>
                    </div>

                    {{-- Time Fields (hidden if all day) --}}
                    @if(!$isAllDay)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Start Time --}}
                            <div>
                                <label for="edit_startTime" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Hora de inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                       id="edit_startTime"
                                       wire:model="startTime"
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('startTime') border-red-500 @enderror" />
                                @error('startTime')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End Time --}}
                            <div>
                                <label for="edit_endTime" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Hora de fin <span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                       id="edit_endTime"
                                       wire:model="endTime"
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('endTime') border-red-500 @enderror" />
                                @error('endTime')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Location and Virtual Link --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Location --}}
                        <div>
                            <label for="edit_location" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Ubicación
                            </label>
                            <input type="text"
                                   id="edit_location"
                                   wire:model="location"
                                   class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('location') border-red-500 @enderror" />
                            @error('location')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Virtual Link --}}
                        <div>
                            <label for="edit_virtualLink" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Enlace virtual
                            </label>
                            <input type="url"
                                   id="edit_virtualLink"
                                   wire:model="virtualLink"
                                   class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('virtualLink') border-red-500 @enderror" />
                            @error('virtualLink')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Color and Area --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Color Selector --}}
                        <div>
                            <label for="edit_color" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <select id="edit_color"
                                        wire:model="color"
                                        class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('color') border-red-500 @enderror">
                                    @foreach($colorOptions as $hex => $name)
                                        <option value="{{ $hex }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <div class="w-8 h-8 rounded-md border border-neutral-300 dark:border-neutral-600" style="background-color: {{ $color }}"></div>
                            </div>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Area Selector --}}
                        <div>
                            <label for="edit_areaId" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Área
                            </label>
                            <select id="edit_areaId"
                                    wire:model="areaId"
                                    class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('areaId') border-red-500 @enderror">
                                <option value="">-- General (sin área) --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                @endforeach
                            </select>
                            @error('areaId')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Public Checkbox --}}
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="edit_isPublic"
                               wire:model="isPublic"
                               class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800" />
                        <label for="edit_isPublic" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                            Evento público (visible para todos los usuarios)
                        </label>
                    </div>

                    {{-- Existing Attachments --}}
                    @if($event->getMedia('attachments')->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Archivos adjuntos existentes
                            </label>
                            <div class="space-y-2">
                                @foreach($event->getMedia('attachments') as $media)
                                    <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-800 rounded-md">
                                        <div class="flex items-center gap-2 truncate">
                                            <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                            <span class="text-sm text-neutral-700 dark:text-neutral-300 truncate">
                                                {{ $media->file_name }}
                                            </span>
                                            <span class="text-xs text-neutral-500">
                                                ({{ $media->human_readable_size }})
                                            </span>
                                        </div>
                                        <button type="button"
                                                wire:click="removeExistingAttachment({{ $media->id }})"
                                                wire:confirm="¿Está seguro de eliminar este archivo?"
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- New File Attachments --}}
                    <div>
                        <label for="edit_attachments" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Agregar nuevos archivos
                        </label>
                        <input type="file"
                               id="edit_attachments"
                               wire:model="newAttachments"
                               multiple
                               class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:text-neutral-400 dark:file:bg-primary-900/20 dark:file:text-primary-400" />
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Máximo 10MB por archivo
                        </p>
                        @error('newAttachments.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        {{-- New Attachment Preview --}}
                        @if(count($newAttachments) > 0)
                            <div class="mt-3 space-y-2">
                                @foreach($newAttachments as $index => $attachment)
                                    <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/20 rounded-md">
                                        <div class="flex items-center gap-2 truncate">
                                            <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span class="text-sm text-neutral-700 dark:text-neutral-300 truncate">
                                                {{ $attachment->getClientOriginalName() }}
                                            </span>
                                            <span class="text-xs text-green-600 dark:text-green-400">(nuevo)</span>
                                        </div>
                                        <button type="button"
                                                wire:click="removeNewAttachment({{ $index }})"
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                {{-- RIGHT COLUMN (1/3) - Participants for Meetings --}}
                <div class="lg:col-span-1 space-y-6">
                    @if($type === 'meeting')
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-2">
                                Participantes <span class="text-red-500">*</span>
                            </h3>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-4">
                                Selecciona los usuarios que participarán en la reunión.
                            </p>

                            {{-- Selected Count Badge --}}
                            @if(count($selectedParticipants) > 0)
                                <div class="mb-4">
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                        {{ count($selectedParticipants) }} participante(s) seleccionado(s)
                                    </span>
                                </div>
                            @endif

                            @error('selectedParticipants')
                                <p class="mb-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror

                            {{-- Participants List --}}
                            <div class="space-y-2 max-h-96 overflow-y-auto pr-2">
                                @foreach($users as $user)
                                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 cursor-pointer transition-colors">
                                        <input type="checkbox"
                                               wire:click="toggleParticipant({{ $user['id'] }})"
                                               {{ in_array($user['id'], $selectedParticipants) ? 'checked' : '' }}
                                               class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-600 dark:bg-neutral-700" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                                {{ $user['name'] }}
                                            </p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                                {{ $user['email'] }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Info box for events --}}
                        <div class="rounded-lg bg-amber-50 dark:bg-amber-900/20 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                        Nota
                                    </h3>
                                    <div class="mt-2 text-sm text-amber-700 dark:text-amber-400">
                                        <p>
                                            Al cambiar el tipo a "Evento", se eliminarán todos los participantes asociados.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Footer --}}
            <x-dialog-footer dialog-id="edit-event-dialog">
                <button type="button"
                        wire:click="cancel"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                    Cancelar
                </button>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="inline-flex items-center gap-2 rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">
                        Guardar Cambios
                    </span>
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
            </x-dialog-footer>
        </form>
    @else
        {{-- Loading State --}}
        <div class="flex items-center justify-center h-full min-h-[400px] p-12">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-neutral-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando evento...</p>
            </div>
        </div>
    @endif
</div>
