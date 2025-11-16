<x-dashboard-layout title="Disponibilidad del Equipo">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Disponibilidad del Equipo
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Consulta los horarios disponibles de los miembros de tu equipo
                    </p>
                </div>
            </div>

            @if($userAreas->isEmpty())
                {{-- No areas message --}}
                <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/20 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Sin áreas asignadas
                            </h3>
                            <p class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                No perteneces a ningún área. Contacta a tu administrador para ser asignado a un equipo.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Area Selector Tabs --}}
                <div class="border-b border-gray-200 dark:border-white/10 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        @foreach($userAreas as $area)
                            <a href="{{ route('team-availability.index', ['area_id' => $area->id]) }}"
                               class="{{ $selectedArea && $selectedArea->id === $area->id
                                   ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400'
                                   : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-300' }}
                                   whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors">
                                {{ $area->name }}
                                <span class="ml-2 rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-300">
                                    {{ $area->users->count() }}
                                </span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                @if($selectedArea)
                    {{-- Week Table --}}
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                            {{-- Table Header --}}
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    @foreach($weekData as $dayIndex => $dayData)
                                        <th scope="col" class="px-3 py-3 text-center text-sm font-semibold text-gray-900 dark:text-white {{ $dayIndex < 6 ? 'border-r border-gray-200 dark:border-white/10' : '' }}">
                                            {{ $dayData['dayName'] }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            {{-- Table Body --}}
                            <tbody>
                                <tr>
                                    @foreach($weekData as $dayIndex => $dayData)
                                        <td class="align-top p-3 {{ $dayIndex < 6 ? 'border-r border-gray-200 dark:border-white/10' : '' }}">
                                            @if(count($dayData['availability']) > 0)
                                                <ol class="space-y-2">
                                                    @foreach($dayData['availability'] as $slot)
                                                        <li class="group rounded-md bg-green-50 dark:bg-green-900/20 p-2 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                                            <div class="flex items-start gap-2">
                                                                {{-- User Avatar --}}
                                                                <div class="flex-shrink-0">
                                                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-medium text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">
                                                                        {{ substr($slot['user']->name, 0, 1) }}
                                                                    </div>
                                                                </div>

                                                                {{-- User Info --}}
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                                                        {{ $slot['user']->name }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                                        {{ $slot['start_time_formatted'] }} - {{ $slot['end_time_formatted'] }}
                                                                    </p>
                                                                    @if($slot['notes'])
                                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500 italic truncate">
                                                                            {{ Str::limit($slot['notes'], 30) }}
                                                                        </p>
                                                                    @endif
                                                                </div>

                                                                {{-- Action Menu --}}
                                                                <el-dropdown class="relative opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                                                    <button class="relative flex items-center rounded-full text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-white">
                                                                        <span class="absolute -inset-1"></span>
                                                                        <span class="sr-only">Opciones</span>
                                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-4">
                                                                            <path d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" stroke-linecap="round" stroke-linejoin="round" />
                                                                        </svg>
                                                                    </button>

                                                                    <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10 z-50">
                                                                        <div class="py-1">
                                                                            <a href="mailto:{{ $slot['user']->email }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                                                                                Enviar correo
                                                                            </a>
                                                                            <button type="button" onclick="navigator.clipboard.writeText('{{ $slot['user']->email }}')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                                                                                Copiar email
                                                                            </button>
                                                                        </div>
                                                                    </el-menu>
                                                                </el-dropdown>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @else
                                                <div class="text-center py-4">
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                                        Sin disponibilidad
                                                    </p>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Legend --}}
                    <div class="mt-4 flex items-center justify-end gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-2">
                            <span class="inline-block h-3 w-3 rounded-md bg-green-50 dark:bg-green-900/20 ring-1 ring-green-200 dark:ring-green-800"></span>
                            Disponible
                        </span>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-dashboard-layout>
