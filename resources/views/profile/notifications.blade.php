<x-dashboard-layout :title="__('Notificaciones')">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <main class="flex flex-col h-full">
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

        <div class="flex-grow px-4 py-6 sm:px-6 lg:px-8">
            <header class="mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">
                    {{ __('Preferencias de Notificaciones') }}
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                    {{ __('Configura como y cuando deseas recibir notificaciones.') }}
                </p>
            </header>

            <livewire:profile.notification-preferences />
        </div>
    </main>
</x-dashboard-layout>
