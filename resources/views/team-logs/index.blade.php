<x-dashboard-layout title="Bitácora de Equipo">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Bitácora de Equipo</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Un registro de las decisiones, eventos y notas importantes de tus áreas.
            </p>
        </div>

        <x-layout.alerts />

        {{-- Composer Form --}}
        <div class="mb-8">
            <form action="{{ route('team-logs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-forms.composer
                    title-placeholder="¿Qué sucedió hoy?"
                    description-placeholder="Describe la decisión, evento o nota importante..."
                    title-name="title"
                    description-name="content"
                    description-rows="3"
                >
                    {{-- Attachment Actions Toolbar --}}
                    <x-slot name="toolbar">
                        @livewire('team-log-attachments')
                    </x-slot>

                    <x-slot name="leftActions">
                        {{-- Area Selector --}}
                        <x-forms.select
                            id="area_id"
                            name="area_id"
                            placeholder="Selecciona un área"
                            :value="old('area_id', $userAreas->first()->id ?? null)"
                        >
                            @foreach($userAreas as $area)
                                <x-forms.select-option :value="$area->id">
                                    {{ $area->name }}
                                </x-forms.select-option>
                            @endforeach
                        </x-forms.select>

                        {{-- Type Selector --}}
                        <x-forms.select
                            id="type"
                            name="type"
                            placeholder="Tipo de entrada"
                            value="note"
                        >
                            <x-forms.select-option value="note">Nota</x-forms.select-option>
                            <x-forms.select-option value="decision">Decisión</x-forms.select-option>
                            <x-forms.select-option value="event">Evento</x-forms.select-option>
                            <x-forms.select-option value="meeting">Reunión</x-forms.select-option>
                        </x-forms.select>
                    </x-slot>

                    <x-slot name="rightActions">
                        <button type="submit" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:bg-primary-500 dark:hover:bg-primary-400">
                            Publicar
                        </button>
                    </x-slot>
                </x-forms.composer>
            </form>
        </div>

        <x-layout.divider />

        {{-- Filters Section --}}
        <div class="mt-8 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
            <form method="GET" action="{{ route('team-logs.index') }}" class="flex flex-col sm:flex-row gap-4">
                {{-- Search Input --}}
                <div class="flex-1">
                    <label for="search" class="sr-only">Buscar</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar en título o contenido..."
                        class="block w-full rounded-md border-0 py-2 px-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700"
                    >
                </div>

                {{-- Area Filter --}}
                <div class="w-full sm:w-48">
                    <x-forms.select
                        id="filter_area"
                        name="area_id"
                        placeholder="Todas las áreas"
                        :value="request('area_id')"
                    >
                        <x-forms.select-option value="">Todas las áreas</x-forms.select-option>
                        @foreach($userAreas as $area)
                            <x-forms.select-option :value="$area->id">
                                {{ $area->name }}
                            </x-forms.select-option>
                        @endforeach
                    </x-forms.select>
                </div>

                {{-- Type Filter --}}
                <div class="w-full sm:w-48">
                    <x-forms.select
                        id="filter_type"
                        name="type"
                        placeholder="Todos los tipos"
                        :value="request('type')"
                    >
                        <x-forms.select-option value="">Todos los tipos</x-forms.select-option>
                        <x-forms.select-option value="note">Nota</x-forms.select-option>
                        <x-forms.select-option value="decision">Decisión</x-forms.select-option>
                        <x-forms.select-option value="event">Evento</x-forms.select-option>
                        <x-forms.select-option value="meeting">Reunión</x-forms.select-option>
                    </x-forms.select>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400"
                    >
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'area_id', 'type']))
                        <a
                            href="{{ route('team-logs.index') }}"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                        >
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Log Feed --}}
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Actividad Reciente</h2>

            <ul class="space-y-6">
                @forelse($logs as $log)
                    <li class="relative flex gap-x-4">
                        <div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
                            <div class="w-px bg-neutral-200 dark:bg-neutral-700"></div>
                        </div>
                        <x-data-display.avatar :user="$log->user" size="xs" class="relative mt-3 flex-none" />
                        <div class="flex-auto rounded-md p-3 ring-1 ring-inset ring-neutral-200 dark:ring-neutral-700">
                            <div class="flex justify-between gap-x-4">
                                <div class="py-0.5 text-xs leading-5 text-neutral-500 dark:text-neutral-400">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $log->user->name }}</span> en el área <span class="font-medium text-neutral-900 dark:text-white">{{ $log->area->name }}</span>
                                </div>
                                <div class="flex items-center gap-x-2">
                                    <time datetime="{{ $log->created_at->toIso8601String() }}" class="flex-none py-0.5 text-xs leading-5 text-neutral-500 dark:text-neutral-400">
                                        {{ $log->created_at->diffForHumans() }}
                                    </time>
                                    {{-- Edit and Delete buttons - Only shown to the author --}}
                                    @if($log->user_id === auth()->id())
                                        <a href="{{ route('team-logs.edit', $log) }}" class="text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400" title="Editar entrada">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('team-logs.destroy', $log) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta entrada?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-neutral-400 hover:text-red-600 dark:hover:text-red-400" title="Eliminar entrada">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <h3 class="text-md font-semibold text-neutral-800 dark:text-neutral-200 mt-2">{{ $log->title }}</h3>
                            <p class="text-sm leading-6 text-neutral-600 dark:text-neutral-300 mt-1">{{ $log->content }}</p>

                            {{-- Display attachments and links --}}
                            <x-team-log.attachments-display :teamLog="$log" />

                            <div class="mt-2">
                                @switch($log->type)
                                    @case('decision')
                                        <x-data-display.badge color="blue">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Decisión
                                        </x-data-display.badge>
                                        @break
                                    @case('event')
                                        <x-data-display.badge color="purple">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            Evento
                                        </x-data-display.badge>
                                        @break
                                    @case('meeting')
                                        <x-data-display.badge color="green">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                            </svg>
                                            Reunión
                                        </x-data-display.badge>
                                        @break
                                    @default
                                        <x-data-display.badge color="neutral">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                            Nota
                                        </x-data-display.badge>
                                @endswitch
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">No hay entradas en la bitácora</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Sé el primero en registrar una decisión, evento o nota.</p>
                    </li>
                @endforelse
            </ul>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="mt-8">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
