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
                    {{-- Heatmap Grid --}}
                    <div x-data="heatmapApp()" class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                {{-- Table Header - Days --}}
                                <thead class="bg-gray-50 dark:bg-gray-900/50">
                                    <tr>
                                        <th class="w-20 px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Hora
                                        </th>
                                        @php
                                            $dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                        @endphp
                                        @foreach($dayNames as $dayName)
                                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                                                {{ $dayName }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                {{-- Table Body - Hours --}}
                                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                    @for($hour = 8; $hour <= 20; $hour++)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/2.5">
                                            {{-- Time Label --}}
                                            <td class="whitespace-nowrap px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400">
                                                {{ $hour > 12 ? ($hour - 12) : $hour }}:00 {{ $hour >= 12 ? 'PM' : 'AM' }}
                                            </td>

                                            {{-- Day Cells --}}
                                            @for($dayIndex = 0; $dayIndex < 7; $dayIndex++)
                                                @php
                                                    $key = $hour . '-' . $dayIndex;
                                                    $slotData = $heatmapData[$key] ?? ['count' => 0, 'users' => []];
                                                    $count = $slotData['count'];
                                                    $users = $slotData['users'];

                                                    // Color based on density
                                                    $bgClass = match(true) {
                                                        $count === 0 => 'bg-gray-50 dark:bg-gray-900/30',
                                                        $count === 1 => 'bg-green-100 dark:bg-green-900/30',
                                                        $count === 2 => 'bg-green-200 dark:bg-green-900/50',
                                                        $count === 3 => 'bg-green-300 dark:bg-green-800/60',
                                                        $count >= 4 => 'bg-green-400 dark:bg-green-700/70',
                                                        default => 'bg-gray-50 dark:bg-gray-900/30',
                                                    };

                                                    $textClass = $count >= 3 ? 'text-green-900 dark:text-green-100' : 'text-gray-700 dark:text-gray-300';
                                                @endphp

                                                <td class="px-1 py-1">
                                                    @if($count > 0)
                                                        <div x-data="{ showPopover: false }"
                                                             class="relative">
                                                            <button type="button"
                                                                    @mouseenter="showPopover = true"
                                                                    @mouseleave="showPopover = false"
                                                                    @click="openModal(@js($users), '{{ $hour }}:00', '{{ $dayNames[$dayIndex] }}')"
                                                                    class="w-full h-10 rounded-md {{ $bgClass }} flex items-center justify-center transition-all hover:ring-2 hover:ring-indigo-400 dark:hover:ring-indigo-500 cursor-pointer">
                                                                <span class="text-sm font-semibold {{ $textClass }}">
                                                                    {{ $count }}
                                                                </span>
                                                            </button>

                                                            {{-- Hover Popover with Avatars --}}
                                                            <div x-show="showPopover"
                                                                 x-transition:enter="transition ease-out duration-150"
                                                                 x-transition:enter-start="opacity-0 scale-95"
                                                                 x-transition:enter-end="opacity-100 scale-100"
                                                                 x-transition:leave="transition ease-in duration-100"
                                                                 x-transition:leave-start="opacity-100 scale-100"
                                                                 x-transition:leave-end="opacity-0 scale-95"
                                                                 class="absolute z-50 bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 origin-bottom"
                                                                 @click.outside="showPopover = false">
                                                                <div class="rounded-lg bg-white dark:bg-gray-900 shadow-lg ring-1 ring-black/5 dark:ring-white/10 p-3">
                                                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                                        {{ $count }} {{ $count === 1 ? 'disponible' : 'disponibles' }}
                                                                    </p>
                                                                    <div class="space-y-2">
                                                                        @foreach($users as $user)
                                                                            <div class="flex items-center gap-2">
                                                                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                                                                    <span class="text-xs font-medium text-indigo-700 dark:text-indigo-300">
                                                                                        {{ $user['initials'] }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="min-w-0 flex-1">
                                                                                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                                                                        {{ $user['name'] }}
                                                                                    </p>
                                                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                                        {{ $user['start_time'] }} - {{ $user['end_time'] }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <p class="mt-2 text-xs text-gray-400 dark:text-gray-500 text-center">
                                                                        Click para más detalles
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="w-full h-10 rounded-md {{ $bgClass }}"></div>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        {{-- Detail Modal --}}
                        <template x-teleport="body">
                            <div x-show="modalOpen"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-[100] overflow-y-auto"
                                 @keydown.escape.window="modalOpen = false">
                                {{-- Backdrop --}}
                                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"
                                     @click="modalOpen = false"></div>

                                {{-- Modal Content --}}
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div x-show="modalOpen"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                        {{-- Close button --}}
                                        <div class="absolute right-0 top-0 pr-4 pt-4">
                                            <button type="button"
                                                    @click="modalOpen = false"
                                                    class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                                <span class="sr-only">Cerrar</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Header --}}
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" x-text="modalTitle">
                                                    Disponibilidad
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="modalSubtitle">
                                                </p>
                                            </div>
                                        </div>

                                        {{-- User List --}}
                                        <div class="mt-5">
                                            <ul role="list" class="divide-y divide-gray-100 dark:divide-white/10">
                                                <template x-for="user in modalUsers" :key="user.id">
                                                    <li class="py-4">
                                                        <div class="flex items-start gap-x-3">
                                                            {{-- Avatar --}}
                                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                                                <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300" x-text="user.initials"></span>
                                                            </div>

                                                            {{-- Info --}}
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="user.name"></p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="user.email"></p>
                                                                <div class="mt-1 flex items-center gap-2">
                                                                    <span class="inline-flex items-center rounded-md bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30">
                                                                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <span x-text="user.start_time + ' - ' + user.end_time"></span>
                                                                    </span>
                                                                </div>
                                                                <template x-if="user.notes">
                                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic" x-text="user.notes"></p>
                                                                </template>
                                                            </div>

                                                            {{-- Actions --}}
                                                            <div class="flex-shrink-0">
                                                                <a :href="'mailto:' + user.email"
                                                                   class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-2.5 py-1.5 text-xs font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                                    </svg>
                                                                    Email
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                            <button type="button"
                                                    @click="modalOpen = false"
                                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Legend --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium">Densidad:</span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block h-4 w-4 rounded bg-gray-50 dark:bg-gray-900/30 ring-1 ring-gray-200 dark:ring-gray-700"></span>
                                0
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block h-4 w-4 rounded bg-green-100 dark:bg-green-900/30 ring-1 ring-green-200 dark:ring-green-800"></span>
                                1
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block h-4 w-4 rounded bg-green-200 dark:bg-green-900/50 ring-1 ring-green-300 dark:ring-green-700"></span>
                                2
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block h-4 w-4 rounded bg-green-300 dark:bg-green-800/60 ring-1 ring-green-400 dark:ring-green-600"></span>
                                3
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="inline-block h-4 w-4 rounded bg-green-400 dark:bg-green-700/70 ring-1 ring-green-500 dark:ring-green-500"></span>
                                4+
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            Pasa el cursor sobre una celda para ver quién está disponible
                        </p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function heatmapApp() {
            return {
                modalOpen: false,
                modalUsers: [],
                modalTitle: '',
                modalSubtitle: '',

                openModal(users, time, day) {
                    this.modalUsers = users;
                    this.modalTitle = `${users.length} ${users.length === 1 ? 'persona disponible' : 'personas disponibles'}`;
                    this.modalSubtitle = `${day} a las ${time}`;
                    this.modalOpen = true;
                },
            }
        }
    </script>
    @endpush
</x-dashboard-layout>
