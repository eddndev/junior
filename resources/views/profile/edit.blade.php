<x-dashboard-layout :title="__('Perfil')">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <main>
        <header class="border-b border-gray-200 dark:border-white/5">
            <!-- Secondary navigation -->
            <nav class="flex overflow-x-auto py-4">
                <ul role="list" class="flex min-w-full flex-none gap-x-6 px-4 text-sm font-semibold leading-6 text-gray-400 sm:px-6 lg:px-8">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="text-primary-600 dark:text-primary-400">{{ __('Cuenta') }}</a>
                    </li>
                    {{-- You can add other profile-related links here if needed --}}
                </ul>
            </nav>
        </header>

        <!-- Settings forms -->
        <div class="divide-y divide-gray-200 dark:divide-white/10">

            {{-- Personal Information --}}
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{ __('Información Personal') }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Actualiza la información de tu perfil y tu dirección de correo electrónico.') }}</p>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="md:col-span-2">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
                        <div class="col-span-full flex items-center gap-x-8">
                            <x-data-display.avatar src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=256" alt="{{ Auth::user()->name }}" size="xl" />
                            <div>
                                <x-actions.button type="button" variant="secondary">{{ __('Cambiar avatar') }}</x-actions.button>
                                <p class="mt-2 text-xs leading-5 text-gray-400">{{ __('JPG, GIF o PNG. 1MB máximo.') }}</p>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <x-forms.input name="name" label="{{ __('Nombre') }}" :value="old('name', Auth::user()->name)" required />
                        </div>

                        <div class="col-span-full">
                            <x-forms.input name="email" type="email" label="{{ __('Dirección de correo electrónico') }}" :value="old('email', Auth::user()->email)" required />
                        </div>
                    </div>

                    <div class="mt-8 flex">
                        <x-actions.button type="submit" variant="primary">{{ __('Guardar') }}</x-actions.button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{ __('Cambiar contraseña') }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Actualiza la contraseña asociada a tu cuenta.') }}</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="md:col-span-2">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl">
                        <div class="col-span-full">
                            <x-forms.input name="current_password" type="password" label="{{ __('Contraseña actual') }}" autocomplete="current-password" error="{{ $errors->updatePassword->get('current_password')[0] ?? '' }}" />
                        </div>

                        <div class="col-span-full">
                            <x-forms.input name="password" type="password" label="{{ __('Nueva contraseña') }}" autocomplete="new-password" error="{{ $errors->updatePassword->get('password')[0] ?? '' }}" />
                        </div>

                        <div class="col-span-full">
                            <x-forms.input name="password_confirmation" type="password" label="{{ __('Confirmar contraseña') }}" autocomplete="new-password" error="{{ $errors->updatePassword->get('password_confirmation')[0] ?? '' }}" />
                        </div>
                    </div>

                    <div class="mt-8 flex">
                        <x-actions.button type="submit" variant="primary">{{ __('Guardar') }}</x-actions.button>
                    </div>
                </form>
            </div>

            {{-- Roles and Areas --}}
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{ __('Roles y Áreas') }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Tus roles y áreas asignadas dentro de la organización.') }}</p>
                </div>

                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 gap-y-8 sm:max-w-xl">
                        <div>
                            <h3 class="text-sm font-medium leading-6 text-gray-900 dark:text-white">{{ __('Roles') }}</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @forelse(Auth::user()->roles as $role)
                                    <x-data-display.badge>{{ $role->name }}</x-data-display.badge>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No hay roles asignados.') }}</p>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium leading-6 text-gray-900 dark:text-white">{{ __('Áreas') }}</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @forelse(Auth::user()->areas as $area)
                                    <x-data-display.badge>{{ $area->name }}</x-data-display.badge>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No hay áreas asignadas.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{ __('Eliminar cuenta') }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('¿Ya no quieres usar nuestro servicio? Puedes eliminar tu cuenta aquí. Esta acción no es reversible. Toda la información relacionada con esta cuenta se eliminará permanentemente.') }}</p>
                </div>

                <div class="flex items-start md:col-span-2">
                    <x-actions.button type="button" variant="danger" command="show-modal" commandfor="confirm-user-deletion">
                        {{ __('Sí, eliminar mi cuenta') }}
                    </x-actions.button>
                </div>
            </div>
        </div>
    </main>

    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')
        <x-layout.modal id="confirm-user-deletion" title="{{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}" :danger="true">
            <x-slot:content>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}
                </p>

                <div class="mt-6">
                    <x-forms.input name="password" type="password" label="{{ __('Contraseña') }}" srOnlyLabel placeholder="{{ __('Contraseña') }}" error="{{ $errors->userDeletion->get('password')[0] ?? '' }}" />
                </div>
            </x-slot:content>

            <x-slot:actions>
                <x-actions.button type="button" variant="secondary" command="close" commandfor="confirm-user-deletion">
                    {{ __('Cancelar') }}
                </x-actions.button>
                <x-actions.button type="submit" variant="danger">
                    {{ __('Eliminar Cuenta') }}
                </x-actions.button>
            </x-slot:actions>
        </x-layout.modal>
    </form>
</x-dashboard-layout>
