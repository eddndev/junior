<x-dashboard-layout title="Gestión de Usuarios">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Gestión de Usuarios</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Lista completa de usuarios del sistema con sus roles y áreas asignadas.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button
                type="button"
                onclick="openDialog('create-user-dialog')"
                class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400"
            >
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Usuario
            </button>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="mt-8">
        <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Buscar
                    </label>
                    <div class="relative mt-1">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Nombre o email..."
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                        />
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Estado
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>

                {{-- Area Filter --}}
                <div>
                    <label for="area_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Área
                    </label>
                    <select
                        name="area_id"
                        id="area_id"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Role Filter --}}
                <div>
                    <label for="role_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Rol
                    </label>
                    <select
                        name="role_id"
                        id="role_id"
                        class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm"
                    >
                        <option value="">Todos los roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filter Actions --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-x-2">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-neutral-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:bg-neutral-700 dark:hover:bg-neutral-600"
                    >
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrar
                    </button>
                    <a
                        href="{{ route('users.index') }}"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                    >
                        Limpiar
                    </a>
                </div>

                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="show_deleted"
                        id="show_deleted"
                        value="true"
                        {{ request('show_deleted') === 'true' ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                    />
                    <label for="show_deleted" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                        Mostrar eliminados
                    </label>
                </div>
            </div>
        </form>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mt-4 rounded-md bg-green-50 p-4 dark:bg-green-900/20">
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

    @if(session('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Users Table using Sprint 1 components --}}
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <x-layout.table id="users-table" :selectable="true">
                    <x-slot:header>
                        <x-layout.table-header>Nombre</x-layout.table-header>
                        <x-layout.table-header>Email</x-layout.table-header>
                        <x-layout.table-header>Roles</x-layout.table-header>
                        <x-layout.table-header>Áreas</x-layout.table-header>
                        <x-layout.table-header>Estado</x-layout.table-header>
                        <x-layout.table-header>Acciones</x-layout.table-header>
                    </x-slot:header>

                    @forelse($users as $user)
                        <x-layout.table-row :selectable="true">
                            <x-layout.table-cell :primary="true">
                                <button type="button"
                                        onclick="loadUserDetails({{ $user->id }})"
                                        class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-left">
                                    {{ $user->name }}
                                </button>
                                @if($user->trashed())
                                    <span class="ml-2 inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                                        Eliminado
                                    </span>
                                @endif
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                {{ $user->email }}
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles->take(2) as $role)
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Sin roles</span>
                                    @endforelse
                                    @if($user->roles->count() > 2)
                                        <span class="inline-flex items-center rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-600 ring-1 ring-inset ring-neutral-500/10 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30">
                                            +{{ $user->roles->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->areas->take(2) as $area)
                                        <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/20 dark:text-purple-400 dark:ring-purple-600/30">
                                            {{ $area->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Sin áreas</span>
                                    @endforelse
                                    @if($user->areas->count() > 2)
                                        <span class="inline-flex items-center rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-600 ring-1 ring-inset ring-neutral-500/10 dark:bg-neutral-900/20 dark:text-neutral-400 dark:ring-neutral-600/30">
                                            +{{ $user->areas->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                @if($user->is_active)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                                        Inactivo
                                    </span>
                                @endif
                            </x-layout.table-cell>

                            <x-layout.table-cell>
                                <x-layout.dropdown anchor="bottom end" width="48">
                                    <x-slot:trigger>
                                        <button class="inline-flex items-center rounded-md bg-white px-2 py-1 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                                            Acciones
                                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </x-slot:trigger>

                                    <x-layout.dropdown-button type="button" onclick="loadUserDetails({{ $user->id }})">
                                        <svg class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Ver detalle</span>
                                    </x-layout.dropdown-button>

                                    @if(!$user->trashed())
                                        <x-layout.dropdown-button type="button" onclick="loadUserForEdit({{ $user->id }})">
                                            <svg class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Editar</span>
                                        </x-layout.dropdown-button>

                                        <x-layout.dropdown-divider />

                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('¿Está seguro de que desea desactivar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-layout.dropdown-button type="submit">
                                                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="text-red-700 dark:text-red-400">Desactivar</span>
                                            </x-layout.dropdown-button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('users.restore', $user->id) }}">
                                            @csrf
                                            <x-layout.dropdown-button type="submit">
                                                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                <span class="text-green-700 dark:text-green-400">Restaurar</span>
                                            </x-layout.dropdown-button>
                                        </form>
                                    @endif
                                </x-layout.dropdown>
                            </x-layout.table-cell>
                        </x-layout.table-row>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">No se encontraron usuarios</p>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Intenta ajustar los filtros o crea un nuevo usuario.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-layout.table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>

{{-- User Detail Dialog (outside Livewire component to preserve state) --}}
<x-dialog-wrapper id="user-detail-dialog" max-width="5xl">
    @livewire('users.user-detail-dialog')
</x-dialog-wrapper>

{{-- Create User Dialog (outside Livewire component to preserve state) --}}
<x-dialog-wrapper id="create-user-dialog" max-width="5xl">
    @livewire('users.create-user-dialog')
</x-dialog-wrapper>

{{-- Edit User Dialog (outside Livewire component to preserve state) --}}
<x-dialog-wrapper id="edit-user-dialog" max-width="5xl">
    @livewire('users.edit-user-dialog')
</x-dialog-wrapper>

@push('scripts')
<script>
    /**
     * Open dialog for creating a new user
     */
    function openDialog(dialogId) {
        // Reset form to create mode
        Livewire.dispatch('resetUserForm');

        if (!window.DialogSystem.isOpen(dialogId)) {
            window.DialogSystem.open(dialogId);
        }
    }

    /**
     * Load user details and open dialog
     */
    function loadUserDetails(userId) {
        // Dispatch event to Livewire component
        Livewire.dispatch('loadUser', { userId: userId });

        // Open dialog (with check to prevent duplicate opens)
        if (!window.DialogSystem.isOpen('user-detail-dialog')) {
            window.DialogSystem.open('user-detail-dialog');
        }
    }

    /**
     * Load user for editing and open dialog
     */
    function loadUserForEdit(userId) {
        // Dispatch event to Livewire component
        Livewire.dispatch('loadUserForEdit', { userId: userId });

        // Open dialog (with check to prevent duplicate opens)
        setTimeout(() => {
            if (!window.DialogSystem.isOpen('edit-user-dialog')) {
                window.DialogSystem.open('edit-user-dialog');
            }
        }, 150);
    }

    /**
     * Toast notification system
     */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 rounded-md p-4 shadow-lg transition-all transform ${
            type === 'success'
                ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-200'
                : 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-200'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        // Fade out and remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Listen for toast events from Livewire
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-toast', (event) => {
            showToast(event.message, event.type || 'success');
        });

        // Listen for dialog close events
        Livewire.on('close-dialog', (event) => {
            if (event.dialogId && window.DialogSystem.isOpen(event.dialogId)) {
                window.DialogSystem.close(event.dialogId);
            }
        });

        // Listen for user created event - reload page to show new user
        Livewire.on('user-created', () => {
            window.location.reload();
        });

        // Listen for user updated event - reload page to show changes
        Livewire.on('user-updated', () => {
            window.location.reload();
        });
    });
</script>
@endpush
</x-dashboard-layout>
