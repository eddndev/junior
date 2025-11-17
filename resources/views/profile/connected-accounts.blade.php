<x-dashboard-layout :title="__('Cuentas Conectadas')">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <main>
        <x-layout.tabs class="px-4 sm:px-6 lg:px-8">
            <x-layout.tab-item :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                {{ __('Cuenta') }}
            </x-layout.tab-item>
            <x-layout.tab-item :href="route('profile.notifications')" :active="request()->routeIs('profile.notifications')">
                {{ __('Notificaciones') }}
            </x-layout.tab-item>
            <x-layout.tab-item :href="route('profile.availability.show')" :active="request()->routeIs('profile.availability.show')">
                {{ __('Disponibilidad') }}
            </x-layout.tab-item>
            <x-layout.tab-item :href="route('profile.connected-accounts.show')" :active="request()->routeIs('profile.connected-accounts.show')">
                {{ __('Cuentas Conectadas') }}
            </x-layout.tab-item>
        </x-layout.tabs>

        <!-- Connected Accounts -->
        <div class="divide-y divide-gray-200 dark:divide-white/10">
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{ __('Cuentas Conectadas') }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        {{ __('Gestiona las cuentas de terceros conectadas a tu perfil. Puedes vincular tu cuenta con Google o GitHub para iniciar sesión más fácilmente.') }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <div class="space-y-4">
                        @if(session('status'))
                            <div class="rounded-md bg-green-50 p-4 dark:bg-green-900/20">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                            {{ session('status') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="rounded-md bg-red-50 p-4 dark:bg-red-900/20">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                            {{ session('error') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Google Account --}}
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-white/10">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white dark:bg-gray-800">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Google</h3>
                                    @if($connections['google']['connected'])
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Conectado') }} • {{ $connections['google']['email'] }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('No conectado') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($connections['google']['connected'])
                                    <form method="post" action="{{ route('auth.google.disconnect') }}">
                                        @csrf
                                        @method('delete')
                                        <x-actions.button type="submit" variant="secondary">
                                            {{ __('Desvincular') }}
                                        </x-actions.button>
                                    </form>
                                @else
                                    <a href="{{ route('auth.google.redirect') }}">
                                        <x-actions.button type="button" variant="primary">
                                            {{ __('Conectar') }}
                                        </x-actions.button>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- GitHub Account --}}
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-white/10">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-900 dark:bg-white">
                                    <svg class="h-6 w-6 text-white dark:text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">GitHub</h3>
                                    @if($connections['github']['connected'])
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Conectado') }} • {{ $connections['github']['email'] }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('No conectado') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($connections['github']['connected'])
                                    <form method="post" action="{{ route('auth.github.disconnect') }}">
                                        @csrf
                                        @method('delete')
                                        <x-actions.button type="submit" variant="secondary">
                                            {{ __('Desvincular') }}
                                        </x-actions.button>
                                    </form>
                                @else
                                    <a href="{{ route('auth.github.redirect') }}">
                                        <x-actions.button type="button" variant="primary">
                                            {{ __('Conectar') }}
                                        </x-actions.button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="mt-6 rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    {{ __('Acerca de las cuentas conectadas') }}
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>{{ __('Puedes vincular tu cuenta con servicios de terceros para iniciar sesión más rápidamente.') }}</li>
                                        <li>{{ __('Solo puedes conectar cuentas que usen el mismo correo electrónico registrado en el sistema.') }}</li>
                                        <li>{{ __('Debes tener una contraseña configurada antes de poder desvincular cualquier cuenta.') }}</li>
                                        <li>{{ __('La vinculación de cuentas no comparte información adicional con terceros.') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-dashboard-layout>
