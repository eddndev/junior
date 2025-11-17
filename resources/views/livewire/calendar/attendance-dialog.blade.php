<div>
    @if($event)
        {{-- Header --}}
        <x-dialog-header dialog-id="attendance-dialog">
            <x-slot:title>Registrar Asistencia</x-slot:title>
            <x-slot:description>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $event->title }}
                </p>
                <p class="mt-1 text-xs text-neutral-400 dark:text-neutral-500">
                    {{ $event->formatted_date_range }} - {{ $event->formatted_time_range }}
                </p>
            </x-slot:description>
        </x-dialog-header>

        {{-- Form Content --}}
        <form wire:submit="saveAttendance" class="flex-1 overflow-y-auto">
            <div class="px-6 py-6">

                {{-- Quick Actions --}}
                <div class="mb-6 flex items-center gap-3">
                    <button type="button"
                            wire:click="markAllPresent"
                            class="inline-flex items-center gap-2 rounded-md bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marcar todos presentes
                    </button>
                    <button type="button"
                            wire:click="clearAll"
                            class="inline-flex items-center gap-2 rounded-md bg-neutral-50 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar todo
                    </button>
                </div>

                {{-- Summary --}}
                <div class="mb-6 p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Total de participantes:
                        </span>
                        <span class="text-sm text-neutral-900 dark:text-white font-semibold">
                            {{ $event->participants->count() }}
                        </span>
                    </div>
                    @php
                        $recorded = collect($attendanceData)->filter(fn($data) => !empty($data['status']))->count();
                    @endphp
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Asistencia registrada:
                        </span>
                        <span class="text-sm text-neutral-900 dark:text-white font-semibold">
                            {{ $recorded }} / {{ $event->participants->count() }}
                        </span>
                    </div>
                </div>

                {{-- Participants List --}}
                <div class="space-y-4">
                    @foreach($event->participants as $participant)
                        <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                            {{-- Participant Header --}}
                            <div class="flex items-center gap-3 mb-4">
                                <x-data-display.avatar :user="$participant->user" size="md" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                        {{ $participant->user->name }}
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                        {{ $participant->user->email }}
                                    </p>
                                </div>
                                @if($participant->is_required)
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400">
                                        Requerido
                                    </span>
                                @endif
                            </div>

                            {{-- Attendance Form --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                {{-- Status --}}
                                <div>
                                    <label for="status_{{ $participant->id }}" class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Estado
                                    </label>
                                    <select id="status_{{ $participant->id }}"
                                            wire:model="attendanceData.{{ $participant->id }}.status"
                                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($attendanceOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error("attendanceData.{$participant->id}.status")
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Arrival Time --}}
                                <div>
                                    <label for="arrival_{{ $participant->id }}" class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Hora de llegada
                                    </label>
                                    <input type="time"
                                           id="arrival_{{ $participant->id }}"
                                           wire:model="attendanceData.{{ $participant->id }}.arrival_time"
                                           class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm"
                                           {{ empty($attendanceData[$participant->id]['status']) || $attendanceData[$participant->id]['status'] === 'absent' ? 'disabled' : '' }} />
                                    @error("attendanceData.{$participant->id}.arrival_time")
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Notes --}}
                                <div>
                                    <label for="notes_{{ $participant->id }}" class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Notas
                                    </label>
                                    <input type="text"
                                           id="notes_{{ $participant->id }}"
                                           wire:model="attendanceData.{{ $participant->id }}.notes"
                                           class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm"
                                           placeholder="Opcional..." />
                                    @error("attendanceData.{$participant->id}.notes")
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($event->participants->count() === 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">
                            No hay participantes en esta reunión
                        </p>
                    </div>
                @endif

            </div>

            {{-- Footer --}}
            <x-dialog-footer dialog-id="attendance-dialog">
                <button type="button"
                        wire:click="cancel"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                    Cancelar
                </button>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="saveAttendance"
                        class="inline-flex items-center gap-2 rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveAttendance">
                        Guardar Asistencia
                    </span>
                    <span wire:loading wire:target="saveAttendance">
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
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando datos de asistencia...</p>
            </div>
        </div>
    @endif
</div>
