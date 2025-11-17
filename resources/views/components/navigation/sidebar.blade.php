@props(['currentRoute' => null])

<ul role="list" class="flex flex-1 flex-col gap-y-7">
    <li>
        <ul role="list" class="-mx-2 space-y-1">
            {{-- Dashboard --}}
            <li>
                <a
                    href="{{ route('dashboard') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('dashboard'),
                        'text-neutral-700 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-white hover:bg-neutral-50 dark:hover:bg-white/5' => !request()->routeIs('dashboard'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('dashboard'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('dashboard'),
                    ])>
                        <path d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Dashboard
                </a>
            </li>

            {{-- Mis Tareas (Dashboard Personal) - Todos los empleados --}}
            <li>
                <a
                    href="{{ route('my-tasks.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('my-tasks.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('my-tasks.*'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('my-tasks.*'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('my-tasks.*'),
                    ])>
                        <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Mis Tareas
                </a>
            </li>

            {{-- Gestión de Tareas (Solo Directores) --}}
            @can('crear-tareas')
            <li>
                <a
                    href="{{ route('tasks.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('tasks.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('tasks.*'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('tasks.*'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('tasks.*'),
                    ])>
                        <path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Gestión de Tareas
                </a>
            </li>
            @endcan

            {{-- Calendario --}}
            @can('viewAny', App\Models\CalendarEvent::class)
            <li>
                <a
                    href="{{ route('calendar.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('calendar.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('calendar.*'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('calendar.*'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('calendar.*'),
                    ])>
                        <path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Calendario
                </a>
            </li>
            @endcan

            {{-- Disponibilidad del Equipo --}}
            @if(auth()->user()->areas()->count() > 0)
            <li>
                <a
                    href="{{ route('team-availability.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('team-availability.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('team-availability.*'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('team-availability.*'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('team-availability.*'),
                    ])>
                        <path d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Equipo
                </a>
            </li>
            @endif

            {{-- Bitácora --}}
            @can('ver-bitacora')
            <li>
                <a
                    href="{{ route('team-logs.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('team-logs.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('team-logs.*'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('team-logs.*'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('team-logs.*'),
                    ])>
                        <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Bitácora
                </a>
            </li>
            @endcan

            {{-- Notificaciones --}}
            <li>
                <a
                    href="#"
                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 shrink-0 text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white">
                        <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Notificaciones
                </a>
            </li>

            {{-- Mensajes Directos --}}
            <li>
                <a
                    href="{{ route('messages.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('messages.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('messages.*'),
                    ])
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" @class([
                        'size-6 shrink-0',
                        'text-primary-600 dark:text-white' => request()->routeIs('messages.*'),
                        'text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white' => !request()->routeIs('messages.*'),
                    ])>
                        <path d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Mensajes
                </a>
            </li>
        </ul>
    </li>

    {{-- Módulos por Rol --}}
    @if(auth()->user()->hasRole('gestor-financiero') || auth()->user()->hasRole('direccion-general'))
    <li>
        <div class="text-xs/6 font-semibold text-neutral-400">Finanzas</div>
        <ul role="list" class="-mx-2 mt-2 space-y-1">
            @can('ver-clientes')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">C</span>
                    <span class="truncate">Clientes</span>
                </a>
            </li>
            @endcan

            @can('ver-presupuestos')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">P</span>
                    <span class="truncate">Presupuestos</span>
                </a>
            </li>
            @endcan

            @can('ver-cotizaciones')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">Q</span>
                    <span class="truncate">Cotizaciones</span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    @if(auth()->user()->hasRole('gestor-marketing') || auth()->user()->hasRole('direccion-general'))
    <li>
        <div class="text-xs/6 font-semibold text-neutral-400">Marketing</div>
        <ul role="list" class="-mx-2 mt-2 space-y-1">
            @can('ver-campanas')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">C</span>
                    <span class="truncate">Campañas</span>
                </a>
            </li>
            @endcan

            @can('ver-leads')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">L</span>
                    <span class="truncate">Leads</span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    @if(auth()->user()->hasRole('admin-rrhh') || auth()->user()->hasRole('direccion-general'))
    <li>
        <div class="text-xs/6 font-semibold text-neutral-400">Administración</div>
        <ul role="list" class="-mx-2 mt-2 space-y-1">
            @can('gestionar-usuarios')
            <li>
                <a
                    href="{{ route('users.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('users.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('users.*'),
                    ])
                >
                    <span @class([
                        'flex size-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium',
                        'border-primary-600 bg-primary-50 text-primary-600 dark:border-white/20 dark:bg-white/10 dark:text-white' => request()->routeIs('users.*'),
                        'border-neutral-200 bg-white text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white' => !request()->routeIs('users.*'),
                    ])>U</span>
                    <span class="truncate">Usuarios</span>
                </a>
            </li>
            @endcan

            @can('viewAny', App\Models\Area::class)
            <li>
                <a
                    href="{{ route('areas.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('areas.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('areas.*'),
                    ])
                >
                    <span @class([
                        'flex size-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium',
                        'border-primary-600 bg-primary-50 text-primary-600 dark:border-white/20 dark:bg-white/10 dark:text-white' => request()->routeIs('areas.*'),
                        'border-neutral-200 bg-white text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white' => !request()->routeIs('areas.*'),
                    ])>A</span>
                    <span class="truncate">Áreas</span>
                </a>
            </li>
            @endcan

            @can('ver-trazabilidad')
            <li>
                <a
                    href="{{ route('audit-logs.index') }}"
                    @class([
                        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold',
                        'bg-neutral-50 dark:bg-white/5 text-primary-600 dark:text-white' => request()->routeIs('audit-logs.*'),
                        'text-neutral-700 dark:text-neutral-400 hover:bg-neutral-50 hover:text-primary-600 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('audit-logs.*'),
                    ])
                >
                    <span @class([
                        'flex size-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium',
                        'border-primary-600 bg-primary-50 text-primary-600 dark:border-white/20 dark:bg-white/10 dark:text-white' => request()->routeIs('audit-logs.*'),
                        'border-neutral-200 bg-white text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white' => !request()->routeIs('audit-logs.*'),
                    ])>T</span>
                    <span class="truncate">Trazabilidad</span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    {{-- User Profile (at bottom) --}}
    <li class="mt-auto">
        <x-layout.dropdown anchor="top end" width="72" :block="true">
            <x-slot:trigger>
                <button type="button" class="flex w-full items-center gap-x-4 p-2 text-sm/6 font-semibold text-neutral-900 hover:bg-neutral-50 rounded-md dark:text-white dark:hover:bg-white/5 -mx-2">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=128"
                        alt="{{ Auth::user()->name }}"
                        class="size-8 rounded-full bg-neutral-50 outline -outline-offset-1 outline-black/5 dark:bg-neutral-800 dark:outline-white/10"
                    />
                    <span class="sr-only">Tu perfil</span>
                    <span aria-hidden="true">{{ Auth::user()->name }}</span>
                </button>
            </x-slot:trigger>

            <x-layout.dropdown-link href="{{ route('profile.edit') }}">
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-neutral-400">
                    <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                </svg>
                <span>Mi Perfil</span>
            </x-layout.dropdown-link>

            <x-layout.dropdown-link href="{{ route('dashboard') }}">
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-neutral-400">
                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                </svg>
                <span>Configuración</span>
            </x-layout.dropdown-link>

            <x-layout.dropdown-divider />

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-layout.dropdown-button type="submit">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-neutral-400">
                        <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd" />
                    </svg>
                    <span>Cerrar Sesión</span>
                </x-layout.dropdown-button>
            </form>
        </x-layout.dropdown>
    </li>
</ul>
