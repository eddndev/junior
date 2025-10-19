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

            {{-- Tareas --}}
            @can('ver-tareas')
            <li>
                <a
                    href="#"
                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 shrink-0 text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white">
                        <path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Tareas
                </a>
            </li>
            @endcan

            {{-- Calendario --}}
            <li>
                <a
                    href="#"
                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 shrink-0 text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white">
                        <path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Calendario
                </a>
            </li>

            {{-- Bitácora --}}
            @can('ver-bitacora')
            <li>
                <a
                    href="#"
                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 shrink-0 text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-white">
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
            @can('ver-usuarios')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">U</span>
                    <span class="truncate">Usuarios</span>
                </a>
            </li>
            @endcan

            @can('ver-trazabilidad')
            <li>
                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-[0.625rem] font-medium text-neutral-400 group-hover:border-primary-600 group-hover:text-primary-600 dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white">T</span>
                    <span class="truncate">Trazabilidad</span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    {{-- User Profile (at bottom) --}}
    <li class="-mx-6 mt-auto">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-neutral-900 hover:bg-neutral-50 dark:text-white dark:hover:bg-white/5">
            <img
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=128"
                alt="{{ Auth::user()->name }}"
                class="size-8 rounded-full bg-neutral-50 outline -outline-offset-1 outline-black/5 dark:bg-neutral-800 dark:outline-white/10"
            />
            <span class="sr-only">Tu perfil</span>
            <span aria-hidden="true">{{ Auth::user()->name }}</span>
        </a>
    </li>
</ul>
