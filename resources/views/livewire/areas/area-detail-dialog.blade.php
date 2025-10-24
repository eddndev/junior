<div>
    @if($area)
        {{-- Header --}}
        <x-dialog-header dialog-id="area-detail-dialog">
            <x-slot:title>
                <div class="flex items-center gap-2">
                    <span class="truncate">{{ $area->name }}</span>
                    @if($area->is_active)
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                            Activa
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                            Inactiva
                        </span>
                    @endif
                    @if($area->is_system)
                        <span class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                            </svg>
                            Sistema
                        </span>
                    @endif
                </div>
            </x-slot:title>

            <x-slot:description>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $area->slug }}
                </p>
            </x-slot:description>
        </x-dialog-header>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto">
            <div class="px-6 py-6 space-y-6">

                {{-- Description --}}
                @if($area->description)
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Descripción</h3>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        {{ $area->description }}
                    </p>
                </div>
                @endif

                {{-- Basic Information --}}
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Información General</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-neutral-200 dark:border-neutral-700">
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Slug</dt>
                            <dd class="text-sm text-neutral-900 dark:text-white">{{ $area->slug }}</dd>
                        </div>

                        <div class="flex justify-between py-2 border-b border-neutral-200 dark:border-neutral-700">
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Fecha de creación</dt>
                            <dd class="text-sm text-neutral-900 dark:text-white">
                                {{ $area->created_at->format('d/m/Y H:i') }}
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">({{ $area->created_at->diffForHumans() }})</span>
                            </dd>
                        </div>

                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Última actualización</dt>
                            <dd class="text-sm text-neutral-900 dark:text-white">
                                {{ $area->updated_at->diffForHumans() }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Users in this area --}}
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">
                        Usuarios Asignados
                        @if($area->users->count() > 0)
                            <span class="ml-2 text-xs font-normal text-neutral-500">({{ $area->users->count() }})</span>
                        @endif
                    </h3>
                    @if($area->users->count() > 0)
                        <div class="space-y-2">
                            @foreach($area->users->take(5) as $user)
                                <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                            @if($area->users->count() > 5)
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 text-center pt-2">
                                    Y {{ $area->users->count() - 5 }} usuario(s) más
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">Sin usuarios asignados</p>
                    @endif
                </div>

                {{-- Tasks in this area --}}
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">
                        Tareas Relacionadas
                        @if($area->tasks->count() > 0)
                            <span class="ml-2 text-xs font-normal text-neutral-500">({{ $area->tasks->count() }})</span>
                        @endif
                    </h3>
                    @if($area->tasks->count() > 0)
                        <div class="space-y-2">
                            @foreach($area->tasks->take(5) as $task)
                                <div class="p-2 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $task->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-0.5 rounded-md
                                            {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                            {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                            {{ $task->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                        @if($task->priority)
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                Prioridad: {{ ucfirst($task->priority) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($area->tasks->count() > 5)
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 text-center pt-2">
                                    Y {{ $area->tasks->count() - 5 }} tarea(s) más
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">Sin tareas asociadas</p>
                    @endif
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="area-detail-dialog">
            @can('update', $area)
                <button type="button"
                        onclick="window.DialogSystem.close('area-detail-dialog'); loadAreaForEdit({{ $area->id }})"
                        class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors">
                    Editar Área
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
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando área...</p>
            </div>
        </div>
    @endif
</div>
