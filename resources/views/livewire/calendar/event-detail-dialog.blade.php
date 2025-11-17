<div>
    @if($event)
        {{-- Header --}}
        <x-dialog-header dialog-id="event-detail-dialog">
            <x-slot:title>
                <div class="flex items-center gap-3">
                    <span class="truncate">{{ $event->title }}</span>
                </div>
            </x-slot:title>

            <x-slot:description>
                <div class="flex items-center gap-2 mt-2">
                    {{-- Type Badge --}}
                    @php
                        $typeColors = [
                            'blue' => 'bg-blue-100 text-blue-700 ring-blue-700/10 dark:bg-blue-900 dark:text-blue-300',
                            'green' => 'bg-green-100 text-green-700 ring-green-700/10 dark:bg-green-900 dark:text-green-300',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $typeColors[$this->getTypeColor()] }}">
                        @if($event->isMeeting())
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                        {{ $this->getTypeLabel() }}
                    </span>

                    {{-- Status Badge --}}
                    @php
                        $statusColors = [
                            'blue' => 'bg-blue-100 text-blue-700 ring-blue-700/10 dark:bg-blue-900 dark:text-blue-300',
                            'amber' => 'bg-amber-100 text-amber-700 ring-amber-700/10 dark:bg-amber-900 dark:text-amber-300',
                            'green' => 'bg-green-100 text-green-700 ring-green-700/10 dark:bg-green-900 dark:text-green-300',
                            'red' => 'bg-red-100 text-red-700 ring-red-600/10 dark:bg-red-900 dark:text-red-300',
                            'neutral' => 'bg-neutral-100 text-neutral-700 ring-neutral-600/20 dark:bg-neutral-700 dark:text-neutral-300',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusColors[$this->getStatusColor()] }}">
                        {{ $this->getStatusLabel() }}
                    </span>

                    {{-- Color Indicator --}}
                    <div class="w-4 h-4 rounded-full border border-neutral-300 dark:border-neutral-600" style="background-color: {{ $event->color }}"></div>
                </div>

                {{-- Area --}}
                @if($event->area)
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ $event->area->name }}
                    </p>
                @endif
            </x-slot:description>
        </x-dialog-header>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-6">

                {{-- LEFT COLUMN (2/3) - Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Description --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Descripción</h3>
                        <div class="prose prose-sm max-w-none dark:prose-invert text-neutral-700 dark:text-neutral-300">
                            @if($event->description)
                                {!! nl2br(e($event->description)) !!}
                            @else
                                <p class="text-neutral-500 dark:text-neutral-400 italic">Sin descripción</p>
                            @endif
                        </div>
                    </div>

                    {{-- Date and Time Information --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Fecha y Hora</h3>
                        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4">
                            <dl class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <svg class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-900 dark:text-white">Fecha</dt>
                                        <dd class="text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $event->formatted_date_range }}
                                        </dd>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <dt class="text-sm font-medium text-neutral-900 dark:text-white">Hora</dt>
                                        <dd class="text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $event->formatted_time_range }}
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Location and Virtual Link --}}
                    @if($event->location || $event->virtual_link)
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Ubicación</h3>
                            <div class="space-y-3">
                                @if($event->location)
                                    <div class="flex items-start gap-3">
                                        <svg class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white">Ubicación física</p>
                                            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $event->location }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($event->virtual_link)
                                    <div class="flex items-start gap-3">
                                        <svg class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white">Enlace virtual</p>
                                            <a href="{{ $event->virtual_link }}"
                                               target="_blank"
                                               class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 break-all">
                                                {{ $event->virtual_link }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Participants (for meetings) --}}
                    @if($event->isMeeting() && $event->participants->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">
                                Participantes
                                <span class="ml-2 text-xs font-normal text-neutral-500">
                                    ({{ $event->participants->count() }})
                                </span>
                            </h3>
                            <div class="space-y-2">
                                @foreach($event->participants as $participant)
                                    <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <x-data-display.avatar :user="$participant->user" size="sm" />
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                                    {{ $participant->user->name }}
                                                </p>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                                    {{ $participant->user->email }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            {{-- Attendance Status Badge --}}
                                            @php
                                                $attendanceColors = [
                                                    'pending' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-700 dark:text-neutral-300',
                                                    'confirmed' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                    'declined' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                    'tentative' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $attendanceColors[$participant->attendance_status] ?? $attendanceColors['pending'] }}">
                                                {{ $participant->attendance_status_label }}
                                            </span>

                                            {{-- Actual Attendance Badge (if recorded) --}}
                                            @if($participant->actual_attendance)
                                                @php
                                                    $actualColors = [
                                                        'present' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                        'absent' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                        'late' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                                                        'excused' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $actualColors[$participant->actual_attendance] ?? 'bg-neutral-100 text-neutral-700' }}">
                                                    {{ $participant->actual_attendance_label }}
                                                </span>
                                            @endif

                                            {{-- Required Badge --}}
                                            @if($participant->is_required)
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">(Requerido)</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Attachments --}}
                    @if($event->getMedia('attachments')->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Archivos Adjuntos</h3>
                            <div class="space-y-2">
                                @foreach($event->getMedia('attachments') as $media)
                                    <a href="{{ $media->getUrl() }}"
                                       target="_blank"
                                       class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors group">
                                        <svg class="h-5 w-5 text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-neutral-900 dark:text-white truncate">{{ $media->file_name }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $media->human_readable_size }}</p>
                                        </div>
                                        <svg class="h-4 w-4 text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- RIGHT COLUMN (1/3) - Sidebar Metadata --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Creator Info --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Creado por</h3>
                        @if($event->creator)
                            <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                <x-data-display.avatar :user="$event->creator" size="md" />
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                        {{ $event->creator->name }}
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                        {{ $event->creator->email }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">Creador no disponible</p>
                        @endif
                    </div>

                    {{-- Visibility --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Visibilidad</h3>
                        <div class="flex items-center gap-2">
                            @if($event->is_public)
                                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Público</span>
                            @else
                                <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Privado</span>
                            @endif
                        </div>
                    </div>

                    {{-- Timestamps --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Información</h3>
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Creado</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">
                                    {{ $event->created_at->translatedFormat('d M Y, H:i') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Última actualización</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">
                                    {{ $event->updated_at->diffForHumans() }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                </div>

            </div>
        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="event-detail-dialog">
            @can('recordAttendance', $event)
                <button type="button"
                        wire:click="openAttendanceDialog"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                    Registrar Asistencia
                </button>
            @endcan
            @can('update', $event)
                <button type="button"
                        wire:click="openEditDialog"
                        class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors">
                    Editar {{ $event->isMeeting() ? 'Reunión' : 'Evento' }}
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
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando evento...</p>
            </div>
        </div>
    @endif
</div>
