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
              areaRoleAssignments: @js($areaRoleAssignments ?? []),
              areas: @js($areas ?? []),
              roles: @js($roles ?? []),
              toggleAreaRole(areaId, roleId) {
                  if (!this.areaRoleAssignments[areaId]) {
                      this.areaRoleAssignments[areaId] = [];
                  }
                  const index = this.areaRoleAssignments[areaId].indexOf(roleId);
                  if (index > -1) {
                      this.areaRoleAssignments[areaId].splice(index, 1);
                      if (this.areaRoleAssignments[areaId].length === 0) {
                          delete this.areaRoleAssignments[areaId];
                      }
                  } else {
                      this.areaRoleAssignments[areaId].push(roleId);
                  }
              },
              hasRole(areaId, roleId) {
                  return this.areaRoleAssignments[areaId] && this.areaRoleAssignments[areaId].includes(roleId);
              },
              getAreaRoles(areaId) {
                  if (!this.areaRoleAssignments[areaId]) return [];
                  return this.roles.filter(r => this.areaRoleAssignments[areaId].includes(r.id));
              },
              getAssignedAreasCount() {
                  return Object.keys(this.areaRoleAssignments).length;
              }
          }"
          @submit.prevent="$wire.set('areaRoleAssignments', areaRoleAssignments); $wire.call('save')"
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

            {{-- RIGHT COLUMN (1/3) - Role Assignments per Area --}}
            <div class="lg:col-span-1 space-y-6">

                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-2">
                        Asignación de Roles por Área
                    </h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-4">
                        Selecciona los roles que tendrá el usuario en cada área.
                    </p>

                    {{-- Summary Badge --}}
                    <div x-show="getAssignedAreasCount() > 0" class="mb-4">
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="`${getAssignedAreasCount()} área(s) configurada(s)`"></span>
                        </span>
                    </div>

                    {{-- Areas with Role Selection --}}
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        <template x-for="area in areas" :key="area.id">
                            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                                {{-- Area Header --}}
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-neutral-900 dark:text-white" x-text="area.name"></span>
                                    <span x-show="getAreaRoles(area.id).length > 0"
                                          class="text-xs text-primary-600 dark:text-primary-400"
                                          x-text="`${getAreaRoles(area.id).length} rol(es)`"></span>
                                </div>

                                {{-- Selected Roles for this Area --}}
                                <div x-show="getAreaRoles(area.id).length > 0" class="mb-2 flex flex-wrap gap-1">
                                    <template x-for="role in getAreaRoles(area.id)" :key="role.id">
                                        <span class="inline-flex items-center gap-x-1 rounded px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                                            <span x-text="role.name"></span>
                                            <button type="button"
                                                    @click="toggleAreaRole(area.id, role.id)"
                                                    class="ml-0.5 hover:text-blue-900 dark:hover:text-blue-200">
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </div>

                                {{-- Role Checkboxes --}}
                                <div class="space-y-1">
                                    <template x-for="role in roles" :key="role.id">
                                        <label class="flex items-center gap-2 text-xs cursor-pointer hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded p-1 -m-1">
                                            <input type="checkbox"
                                                   :checked="hasRole(area.id, role.id)"
                                                   @change="toggleAreaRole(area.id, role.id)"
                                                   class="h-3.5 w-3.5 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-600 dark:bg-neutral-700" />
                                            <span class="text-neutral-700 dark:text-neutral-300" x-text="role.name"></span>
                                        </label>
                                    </template>
                                </div>
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