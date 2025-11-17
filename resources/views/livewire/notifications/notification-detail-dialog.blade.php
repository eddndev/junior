<div>
    @if($notification)
        {{-- Header --}}
        <x-dialog-header :title="$notification->title" dialog-id="notification-detail-dialog">
            <x-slot:description>
                <div class="flex items-center gap-2 mt-2">
                    {{-- Type Badge --}}
                    @php
                        $typeBadgeClass = match($notification->type) {
                            'success' => 'bg-green-100 text-green-800 ring-green-600/20 dark:bg-green-900 dark:text-green-200',
                            'warning' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-900 dark:text-yellow-200',
                            'error' => 'bg-red-100 text-red-800 ring-red-600/20 dark:bg-red-900 dark:text-red-200',
                            default => 'bg-blue-100 text-blue-800 ring-blue-700/10 dark:bg-blue-900 dark:text-blue-200',
                        };
                        $typeLabel = match($notification->type) {
                            'success' => 'Exitoso',
                            'warning' => 'Advertencia',
                            'error' => 'Error',
                            default => 'Informacion',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $typeBadgeClass }}">
                        {{ $typeLabel }}
                    </span>

                    {{-- Priority Badge --}}
                    @if($notification->priority && $notification->priority !== 'normal')
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $this->getPriorityBadgeClass() }}">
                            Prioridad: {{ $this->getPriorityLabel() }}
                        </span>
                    @endif

                    {{-- Read Status --}}
                    @if($notification->is_read)
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">
                            Leida
                        </span>
                    @else
                        <span class="inline-flex h-2 w-2 rounded-full bg-primary-500" title="No leida"></span>
                    @endif
                </div>
            </x-slot:description>
        </x-dialog-header>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto">
            <div class="px-6 py-6 space-y-6">

                {{-- Icon and Message Section --}}
                <div class="flex gap-4">
                    {{-- Large Icon --}}
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $this->getIconBgClass() }}">
                            @if($notification->type === 'success')
                                <svg class="h-6 w-6 {{ $this->getIconColorClass() }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @elseif($notification->type === 'warning')
                                <svg class="h-6 w-6 {{ $this->getIconColorClass() }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            @elseif($notification->type === 'error')
                                <svg class="h-6 w-6 {{ $this->getIconColorClass() }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @else
                                <svg class="h-6 w-6 {{ $this->getIconColorClass() }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="flex-1">
                        <div class="prose prose-sm max-w-none dark:prose-invert text-neutral-700 dark:text-neutral-300">
                            {!! nl2br(e($notification->message)) !!}
                        </div>
                    </div>
                </div>

                {{-- Additional Data Section --}}
                @if($notification->data && count($notification->data) > 0)
                    <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Detalles Adicionales</h3>
                        <div class="rounded-lg bg-neutral-50 dark:bg-neutral-800/50 p-4">
                            <dl class="space-y-3">
                                @foreach($notification->data as $key => $value)
                                    <div class="flex flex-col gap-1">
                                        <dt class="text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wide">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </dt>
                                        <dd class="text-sm text-neutral-900 dark:text-neutral-100 font-mono bg-white dark:bg-neutral-900 rounded px-2 py-1.5 border border-neutral-200 dark:border-neutral-700 break-words">
                                            @if(is_array($value))
                                                {{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                            @elseif(is_bool($value))
                                                {{ $value ? 'Si' : 'No' }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                @endif

                {{-- Metadata Section --}}
                <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Informacion</h3>
                    <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="font-medium text-neutral-500 dark:text-neutral-400">Creada</dt>
                            <dd class="mt-1 text-neutral-900 dark:text-white">
                                {{ $notification->created_at->translatedFormat('d M Y, H:i') }}
                            </dd>
                        </div>
                        @if($notification->is_read && $notification->read_at)
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Leida</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">
                                    {{ $notification->read_at->translatedFormat('d M Y, H:i') }}
                                </dd>
                            </div>
                        @endif
                        @if($notification->notification_type)
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Tipo de Notificacion</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $notification->notification_type)) }}
                                </dd>
                            </div>
                        @endif
                        @if($notification->group)
                            <div>
                                <dt class="font-medium text-neutral-500 dark:text-neutral-400">Grupo</dt>
                                <dd class="mt-1 text-neutral-900 dark:text-white">
                                    {{ ucfirst($notification->group) }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Action Link Section --}}
                @if($notification->action_url)
                    <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Accion</h3>
                        <a href="{{ $notification->action_url }}"
                           class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors">
                            {{ $notification->action_text ?? 'Ir a la accion' }}
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                @endif

            </div>
        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="notification-detail-dialog">
            <button type="button"
                    wire:click="deleteNotification"
                    wire:loading.attr="disabled"
                    wire:confirm="¿Estas seguro de que deseas eliminar esta notificacion? Esta accion no se puede deshacer."
                    class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 dark:bg-red-500 dark:hover:bg-red-400 transition-colors disabled:opacity-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Eliminar
            </button>
            <button type="button"
                    command="close"
                    commandfor="notification-detail-dialog"
                    class="rounded-md px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-200 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors">
                Cerrar
            </button>
        </x-dialog-footer>

    @else
        {{-- Loading State --}}
        <div class="flex items-center justify-center h-full min-h-[400px] p-12">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-neutral-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando notificacion...</p>
            </div>
        </div>
    @endif
</div>
