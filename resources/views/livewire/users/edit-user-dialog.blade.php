<div>
    {{-- Header --}}
    <x-dialog-header dialog-id="edit-user-dialog">
        <x-slot:title>Editar Usuario</x-slot:title>
        <x-slot:description>
            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                Actualice la información del usuario
            </p>
        </x-slot:description>
    </x-dialog-header>

    {{-- Form Content --}}
    <form wire:submit="save"
          wire:key="edit-user-{{ $userId ?? 'loading' }}"
          x-data="{
              selectedRoles: @js($selectedRoles ?? []),
              selectedAreas: @js($selectedAreas ?? []),
              roles: @js($roles ?? []),
              areas: @js($areas ?? [])
          }"
          @submit.prevent="
              $wire.set('selectedAreas', selectedAreas);
              $wire.set('selectedRoles', selectedRoles);
              $wire.call('save');
          "
          class="flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-6">

            {{-- LEFT COLUMN (2/3) - Main Information --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Information --}}
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4">Información Básica</h3>

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="edit_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nombre completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="edit_name"
                               wire:model="name"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('name') border-red-500 @enderror"
                               placeholder="Juan Pérez" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="edit_email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="edit_email"
                               wire:model="email"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('email') border-red-500 @enderror"
                               placeholder="juan.perez@example.com" />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password (Optional) --}}
                    <div class="mb-4">
                        <label for="edit_password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Nueva contraseña <span class="text-xs text-neutral-500">(opcional)</span>
                        </label>
                        <input type="password"
                               id="edit_password"
                               wire:model="password"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="••••••••" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Dejar en blanco para mantener la contraseña actual. Si se llena, debe tener mínimo 8 caracteres con mayúsculas, minúsculas, números y símbolos.
                        </p>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="mb-4">
                        <label for="edit_password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Confirmar nueva contraseña
                        </label>
                        <input type="password"
                               id="edit_password_confirmation"
                               wire:model="password_confirmation"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                               placeholder="••••••••" />
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Active Status --}}
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="edit_is_active"
                               wire:model="is_active"
                               class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800" />
                        <label for="edit_is_active" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                            Usuario activo
                        </label>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN (1/3) - Assignments --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Roles Multi-Select --}}
                <div x-data="{
                    open: false,
                    toggleRole(roleId) {
                        const index = selectedRoles.indexOf(roleId);
                        if (index > -1) {
                            selectedRoles.splice(index, 1);
                        } else {
                            selectedRoles.push(roleId);
                        }
                    },
                    isSelected(roleId) {
                        return selectedRoles.includes(roleId);
                    },
                    getSelectedRolesData() {
                        return roles.filter(r => selectedRoles.includes(r.id));
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Roles
                    </label>

                    {{-- Selected Roles Badges --}}
                    <div x-show="selectedRoles.length > 0" class="mb-2 flex flex-wrap gap-2">
                        <template x-for="role in getSelectedRolesData()" :key="role.id">
                            <span class="inline-flex items-center gap-x-1.5 rounded-md px-2.5 py-1.5 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                                <svg class="h-1.5 w-1.5 fill-blue-500" viewBox="0 0 6 6" aria-hidden="true">
                                    <circle cx="3" cy="3" r="3" />
                                </svg>
                                <span x-text="role.name"></span>
                            </span>
                        </template>
                    </div>

                    {{-- Dropdown Button --}}
                    <button type="button"
                            @click="open = !open"
                            @click.away="open = false"
                            class="relative w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left shadow-sm ring-1 ring-inset ring-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-neutral-800 dark:ring-neutral-700 sm:text-sm">
                        <span class="block truncate text-neutral-900 dark:text-white">
                            <span x-show="selectedRoles.length === 0">Seleccionar roles...</span>
                            <span x-show="selectedRoles.length > 0" x-text="`${selectedRoles.length} rol(es) seleccionado(s)`"></span>
                        </span>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <svg class="h-5 w-5 text-neutral-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                         x-transition
                         class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-neutral-800 sm:text-sm">
                        <template x-for="role in roles" :key="role.id">
                            <div @click="toggleRole(role.id)"
                                 class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                 :class="{ 'bg-primary-50 dark:bg-primary-900/20': isSelected(role.id) }">
                                <div class="flex items-center">
                                    <span class="block truncate font-normal text-neutral-900 dark:text-white"
                                          :class="{ 'font-semibold': isSelected(role.id) }"
                                          x-text="role.name"></span>
                                </div>
                                <span x-show="isSelected(role.id)"
                                      class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600 dark:text-primary-400">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Areas Multi-Select --}}
                <div x-data="{
                    open: false,
                    toggleArea(areaId) {
                        const index = selectedAreas.indexOf(areaId);
                        if (index > -1) {
                            selectedAreas.splice(index, 1);
                        } else {
                            selectedAreas.push(areaId);
                        }
                    },
                    isSelected(areaId) {
                        return selectedAreas.includes(areaId);
                    },
                    getSelectedAreasData() {
                        return areas.filter(a => selectedAreas.includes(a.id));
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Áreas
                    </label>

                    {{-- Selected Areas Badges --}}
                    <div x-show="selectedAreas.length > 0" class="mb-2 flex flex-wrap gap-2">
                        <template x-for="area in getSelectedAreasData()" :key="area.id">
                            <span class="inline-flex items-center gap-x-1.5 rounded-md px-2.5 py-1.5 text-xs font-medium bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/20 dark:text-purple-400 dark:ring-purple-600/30">
                                <svg class="h-1.5 w-1.5 fill-purple-500" viewBox="0 0 6 6" aria-hidden="true">
                                    <circle cx="3" cy="3" r="3" />
                                </svg>
                                <span x-text="area.name"></span>
                            </span>
                        </template>
                    </div>

                    {{-- Dropdown Button --}}
                    <button type="button"
                            @click="open = !open"
                            @click.away="open = false"
                            class="relative w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left shadow-sm ring-1 ring-inset ring-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-neutral-800 dark:ring-neutral-700 sm:text-sm">
                        <span class="block truncate text-neutral-900 dark:text-white">
                            <span x-show="selectedAreas.length === 0">Seleccionar áreas...</span>
                            <span x-show="selectedAreas.length > 0" x-text="`${selectedAreas.length} área(s) seleccionada(s)`"></span>
                        </span>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <svg class="h-5 w-5 text-neutral-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                         x-transition
                         class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-neutral-800 sm:text-sm">
                        <template x-for="area in areas" :key="area.id">
                            <div @click="toggleArea(area.id)"
                                 class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                 :class="{ 'bg-primary-50 dark:bg-primary-900/20': isSelected(area.id) }">
                                <div class="flex items-center">
                                    <span class="block truncate font-normal text-neutral-900 dark:text-white"
                                          :class="{ 'font-semibold': isSelected(area.id) }"
                                          x-text="area.name"></span>
                                </div>
                                <span x-show="isSelected(area.id)"
                                      class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600 dark:text-primary-400">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="edit-user-dialog">
            <button type="button"
                    wire:click="cancel"
                    class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700">
                Cancelar
            </button>
            <button type="submit"
                    class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400">
                Actualizar Usuario
            </button>
        </x-dialog-footer>
    </form>
</div>