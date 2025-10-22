<x-dashboard-layout title="Asignar Roles">
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('users.show', $user) }}" class="ml-1 text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white md:ml-2">
                            {{ $user->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-neutral-500 dark:text-neutral-400 md:ml-2">Asignar Roles</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Page Title --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Asignar Roles</h1>
                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                    Gestiona los roles de <strong>{{ $user->name }}</strong> ({{ $user->email }}).
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a
                    href="{{ route('users.show', $user) }}"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al Usuario
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 dark:bg-green-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Hay {{ $errors->count() }} {{ $errors->count() === 1 ? 'error' : 'errores' }}:
                    </h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Left Column: Assign New Role --}}
        <div class="bg-white shadow-sm ring-1 ring-neutral-900/5 dark:bg-neutral-800 dark:ring-white/10 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
                    Asignar Nuevo Rol
                </h3>
                <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
                    Selecciona un rol y opcionalmente un área para asignarlo al usuario.
                </p>

                <form method="POST" action="{{ route('users.roles.store', $user) }}" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    {{-- Role Selection --}}
                    <div>
                        <label for="role_id" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                            Rol <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-900 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('role_id') ring-red-500 dark:ring-red-500 @enderror"
                        >
                            <option value="">Selecciona un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                    @if($role->description) - {{ $role->description }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Area Selection (Optional) --}}
                    <div>
                        <label for="area_id" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                            Área (opcional)
                        </label>
                        <select
                            id="area_id"
                            name="area_id"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-900 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('area_id') ring-red-500 dark:ring-red-500 @enderror"
                        >
                            <option value="">Global (sin área específica)</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                            Si no seleccionas un área, el rol se asignará de forma global
                        </p>
                    </div>

                    {{-- Info about contextual roles --}}
                    <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <strong>Roles Contextuales:</strong> Puedes asignar el mismo rol en diferentes áreas. Por ejemplo, un usuario puede ser "Director" en Producción pero "Miembro" en Marketing.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Asignar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Current Role Assignments --}}
        <div class="bg-white shadow-sm ring-1 ring-neutral-900/5 dark:bg-neutral-800 dark:ring-white/10 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
                    Roles Actuales
                </h3>
                <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
                    Roles asignados a {{ $user->name }} con sus áreas correspondientes.
                </p>

                <div class="mt-6 space-y-4">
                    @php
                        $roleAssignments = collect();
                        foreach($user->roles as $role) {
                            $roleAssignments->push([
                                'role' => $role,
                                'area_id' => $role->pivot->area_id,
                                'area' => $role->pivot->area_id ? $areas->find($role->pivot->area_id) : null,
                            ]);
                        }
                    @endphp

                    @forelse($roleAssignments as $assignment)
                        <div class="flex items-center justify-between p-4 border border-neutral-200 rounded-lg dark:border-neutral-700">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    {{ $assignment['role']->name }}
                                </h4>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    @if($assignment['area'])
                                        Área: <span class="font-medium">{{ $assignment['area']->name }}</span>
                                    @else
                                        <span class="font-medium">Global (sin área específica)</span>
                                    @endif
                                </p>
                                @if($assignment['role']->description)
                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $assignment['role']->description }}
                                    </p>
                                @endif
                            </div>

                            {{-- Remove Role Button --}}
                            <form
                                method="POST"
                                action="{{ route('users.roles.destroy', [$user, $assignment['role']->id]) }}"
                                onsubmit="return confirm('¿Estás seguro de que deseas remover este rol?')"
                                class="ml-4"
                            >
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="area_id" value="{{ $assignment['area_id'] }}">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-400"
                                >
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remover
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">Sin roles asignados</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                                Este usuario no tiene ningún rol asignado todavía.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Warning Note --}}
    <div class="mt-6 rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    Advertencia
                </h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <p>Los cambios en los roles afectan inmediatamente los permisos del usuario. Asegúrate de revisar los permisos asignados después de hacer cambios.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-dashboard-layout>
