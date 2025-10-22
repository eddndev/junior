<x-dashboard-layout title="Detalle de Usuario">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="mb-8">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('users.index') }}" class="ml-1 text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white md:ml-2">
                            Usuarios
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-neutral-500 dark:text-neutral-400 md:ml-2">{{ $user->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Page Title with Actions --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <div class="flex items-center gap-x-3">
                    <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $user->name }}</h1>
                    @if($user->is_active)
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                            Inactivo
                        </span>
                    @endif
                    @if($user->trashed())
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                            Eliminado
                        </span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                    Detalles completos del usuario, roles asignados y permisos efectivos.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-x-3">
                @if(!$user->trashed())
                    <a
                        href="{{ route('users.edit', $user) }}"
                        class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400"
                    >
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 dark:bg-green-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left Column - User Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information Card --}}
            <x-layout.card>
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
                        Información Personal
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-neutral-500 dark:text-neutral-400">
                        Datos básicos del usuario
                    </p>

                    <dl class="mt-6 space-y-4">
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-neutral-900 dark:text-white">Nombre completo</dt>
                            <dd class="mt-1 text-sm leading-6 text-neutral-700 dark:text-neutral-300 sm:col-span-2 sm:mt-0">
                                {{ $user->name }}
                            </dd>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-neutral-900 dark:text-white">Correo electrónico</dt>
                            <dd class="mt-1 text-sm leading-6 text-neutral-700 dark:text-neutral-300 sm:col-span-2 sm:mt-0">
                                {{ $user->email }}
                            </dd>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-neutral-900 dark:text-white">Fecha de registro</dt>
                            <dd class="mt-1 text-sm leading-6 text-neutral-700 dark:text-neutral-300 sm:col-span-2 sm:mt-0">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                                <span class="text-neutral-500 dark:text-neutral-400">({{ $user->created_at->diffForHumans() }})</span>
                            </dd>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-neutral-900 dark:text-white">Última actualización</dt>
                            <dd class="mt-1 text-sm leading-6 text-neutral-700 dark:text-neutral-300 sm:col-span-2 sm:mt-0">
                                {{ $user->updated_at->format('d/m/Y H:i') }}
                                <span class="text-neutral-500 dark:text-neutral-400">({{ $user->updated_at->diffForHumans() }})</span>
                            </dd>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-neutral-900 dark:text-white">Estado</dt>
                            <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0">
                                @if($user->is_active)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Inactivo
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </x-layout.card>

            {{-- Permissions Card --}}
            <x-layout.card>
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
                        Permisos Efectivos
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-neutral-500 dark:text-neutral-400">
                        Permisos acumulados de todos los roles asignados
                    </p>

                    @if($permissionsByModule->isEmpty())
                        <div class="mt-6 text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Este usuario no tiene permisos asignados</p>
                        </div>
                    @else
                        <div class="mt-6 space-y-6">
                            @foreach($permissionsByModule as $module => $permissions)
                                <div>
                                    <h4 class="text-sm font-medium text-neutral-900 dark:text-white mb-3">
                                        Módulo: {{ ucfirst($module) }}
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($permissions as $permission)
                                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-layout.card>
        </div>

        {{-- Right Column - Roles & Areas --}}
        <div class="space-y-6">
            {{-- Roles Card --}}
            <x-layout.card>
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
                        Roles Asignados
                    </h3>
                    <p class="mt-1 text-sm leading-6 text-neutral-500 dark:text-neutral-400">
                        {{ $user->roles->count() }} {{ $user->roles->count() === 1 ? 'rol' : 'roles' }}
                    </p>

                    @if($user->roles->isEmpty())
                        <div class="mt-6 text-center py-4">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Sin roles asignados</p>
                        </div>
                    @else
                        <ul role="list" class="mt-6 space-y-2">
                            @foreach($user->roles as $role)
                                <li class="flex items-center justify-between gap-x-3 rounded-md bg-neutral-50 px-3 py-2 dark:bg-neutral-900/50">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                            {{ $role->name }}
                                        </p>
                                        @if($role->description)
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                                {{ $role->description }}
                                            </p>
                                        @endif
                                        @if($role->pivot->area_id)
                                            <span class="mt-1 inline-flex items-center rounded-md bg-purple-50 px-1.5 py-0.5 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/20 dark:text-purple-400 dark:ring-purple-600/30">
                                                Área: {{ $role->pivot->area_id }}
                                            </span>
                                        @endif
                                    </div>
                                    <svg class="h-5 w-5 flex-shrink-0 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </x-layout.card>

            {{-- Areas Card --}}
            <x-layout.card>
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
                        Áreas de Trabajo
                    </h3>
                    <p class="mt-1 text-sm leading-6 text-neutral-500 dark:text-neutral-400">
                        {{ $user->areas->count() }} {{ $user->areas->count() === 1 ? 'área' : 'áreas' }}
                    </p>

                    @if($user->areas->isEmpty())
                        <div class="mt-6 text-center py-4">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Sin áreas asignadas</p>
                        </div>
                    @else
                        <ul role="list" class="mt-6 space-y-2">
                            @foreach($user->areas as $area)
                                <li class="flex items-center justify-between gap-x-3 rounded-md bg-neutral-50 px-3 py-2 dark:bg-neutral-900/50">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                            {{ $area->name }}
                                        </p>
                                        @if($area->description)
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                                {{ $area->description }}
                                            </p>
                                        @endif
                                    </div>
                                    <svg class="h-5 w-5 flex-shrink-0 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </x-layout.card>

            {{-- Actions Card --}}
            <x-layout.card>
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white mb-4">
                        Acciones
                    </h3>

                    <div class="space-y-3">
                        @if(!$user->trashed())
                            <a
                                href="{{ route('users.edit', $user) }}"
                                class="block w-full rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-700 dark:text-white dark:ring-neutral-600 dark:hover:bg-neutral-600"
                            >
                                Editar Usuario
                            </a>

                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('¿Está seguro de que desea desactivar este usuario? Perderá acceso al sistema inmediatamente.');">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="block w-full rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 dark:bg-red-500 dark:hover:bg-red-400"
                                >
                                    Desactivar Usuario
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.restore', $user->id) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="block w-full rounded-md bg-green-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-green-500 dark:bg-green-500 dark:hover:bg-green-400"
                                >
                                    Restaurar Usuario
                                </button>
                            </form>
                        @endif

                        <a
                            href="{{ route('users.index') }}"
                            class="block w-full rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                        >
                            Volver a la Lista
                        </a>
                    </div>
                </div>
            </x-layout.card>
        </div>
    </div>
</div>
</x-dashboard-layout>
