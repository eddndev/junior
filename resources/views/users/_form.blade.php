{{-- Reusable form partial for create/edit user --}}

<div class="space-y-8">
    {{-- Basic Information Section --}}
    <div>
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Información Básica
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Datos personales y credenciales del usuario.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
            {{-- Name --}}
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Nombre completo <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $user->name ?? '') }}"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('name') ring-red-500 dark:ring-red-500 @enderror"
                        placeholder="Ej: Juan Pérez"
                    />
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="sm:col-span-3">
                <label for="email" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Correo electrónico <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $user->email ?? '') }}"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('email') ring-red-500 dark:ring-red-500 @enderror"
                        placeholder="ejemplo@empresa.com"
                    />
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="sm:col-span-3">
                <label for="password" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Contraseña @if(!isset($user)) <span class="text-red-500">*</span> @endif
                </label>
                <div class="mt-2">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        @if(!isset($user)) required @endif
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6 @error('password') ring-red-500 dark:ring-red-500 @enderror"
                        placeholder="••••••••"
                    />
                </div>
                @if(isset($user))
                    <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                        Dejar en blanco para mantener la contraseña actual
                    </p>
                @endif
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="sm:col-span-3">
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                    Confirmar contraseña @if(!isset($user)) <span class="text-red-500">*</span> @endif
                </label>
                <div class="mt-2">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        @if(!isset($user)) required @endif
                        class="block w-full rounded-md border-0 py-1.5 text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 sm:text-sm sm:leading-6"
                        placeholder="••••••••"
                    />
                </div>
            </div>

            {{-- Active Status --}}
            <div class="sm:col-span-6">
                <div class="flex items-center gap-x-3">
                    <input
                        type="checkbox"
                        name="is_active"
                        id="is_active"
                        value="1"
                        {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                    />
                    <label for="is_active" class="text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                        Usuario activo
                    </label>
                </div>
                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                    Los usuarios inactivos no podrán iniciar sesión en el sistema
                </p>
            </div>
        </div>
    </div>

    <x-layout.divider />

    {{-- Areas Section --}}
    <div>
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Áreas de Trabajo
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Selecciona las áreas a las que pertenece el usuario.
        </p>

        <div class="mt-6">
            <fieldset>
                <legend class="sr-only">Áreas</legend>
                <div class="space-y-3">
                    @forelse($areas as $area)
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                name="areas[]"
                                id="area_{{ $area->id }}"
                                value="{{ $area->id }}"
                                {{ in_array($area->id, old('areas', isset($user) ? $user->areas->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                            />
                            <label for="area_{{ $area->id }}" class="ml-3 block text-sm font-medium leading-6 text-neutral-900 dark:text-white">
                                {{ $area->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No hay áreas disponibles.</p>
                    @endforelse
                </div>
            </fieldset>
            @error('areas')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <x-layout.divider />

    {{-- Roles Section --}}
    <div>
        <h3 class="text-base font-semibold leading-7 text-neutral-900 dark:text-white">
            Roles y Permisos
        </h3>
        <p class="mt-1 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
            Asigna roles al usuario. Los permisos se acumulan de todos los roles asignados.
        </p>

        <div class="mt-6">
            <fieldset>
                <legend class="sr-only">Roles</legend>
                <div class="space-y-3">
                    @forelse($roles as $role)
                        <div class="flex items-start">
                            <div class="flex h-6 items-center">
                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    id="role_{{ $role->id }}"
                                    value="{{ $role->id }}"
                                    {{ in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-600 dark:border-neutral-700 dark:bg-neutral-800"
                                />
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="role_{{ $role->id }}" class="font-medium text-neutral-900 dark:text-white">
                                    {{ $role->name }}
                                </label>
                                @if($role->description)
                                    <p class="text-neutral-500 dark:text-neutral-400">{{ $role->description }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No hay roles disponibles.</p>
                    @endforelse
                </div>
            </fieldset>
            @error('roles')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <div class="mt-4 rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>Nota sobre roles contextuales:</strong> En el MVP actual, los roles se asignan de forma global.
                            En una versión futura, podrás asignar roles específicos por área (ej: "Director" en Producción pero "Miembro" en Marketing).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-layout.divider />

    {{-- Form Actions --}}
    <div class="flex items-center justify-end gap-x-4">
        <a
            href="{{ route('users.index') }}"
            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
        >
            Cancelar
        </a>
        <button
            type="submit"
            class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400"
        >
            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ isset($user) ? 'Actualizar Usuario' : 'Crear Usuario' }}
        </button>
    </div>
</div>

{{-- Password Strength Indicator (optional enhancement) --}}
@push('scripts')
<script>
    // Simple password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;

            // Character variety checks
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            // Visual feedback could be added here
            console.log('Password strength:', strength);
        });
    }
</script>
@endpush