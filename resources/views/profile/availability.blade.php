@use('Carbon\Carbon');
@use('Carbon\CarbonPeriod');

<x-dashboard-layout :title="__('Disponibilidad')">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <main class="flex flex-col h-full">
        <x-layout.tabs class="px-4 sm:px-6 lg:px-8">
            <x-layout.tab-item :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                {{ __('Cuenta') }}
            </x-layout.tab-item>
            <x-layout.tab-item href="#" :active="false">
                {{ __('Notificaciones') }}
            </x-layout.tab-item>
            <x-layout.tab-item :href="route('profile.availability.show')" :active="request()->routeIs('profile.availability.show')">
                {{ __('Disponibilidad') }}
            </x-layout.tab-item>
            <x-layout.tab-item href="#" :active="false">
                {{ __('Cuentas Conectadas') }}
            </x-layout.tab-item>
        </x-layout.tabs>

        <form method="post" action="{{ route('profile.availability.update') }}" class="flex-grow flex flex-col">
            @csrf
            @method('patch')

            <header class="flex flex-none items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-white/15">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{ __('Disponibilidad Semanal') }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Selecciona o arrastra en el calendario para definir tus horas de trabajo.') }}</p>
                </div>
                <x-actions.button type="submit" variant="primary">{{ __('Guardar Cambios') }}</x-actions.button>
            </header>

            @php
                $availabilityValue = [];
                $userAvailability = $user->availability()->where('status', 'available')->get();

                foreach ($userAvailability as $record) {
                    $date = Carbon::parse($record->date)->format('Y-m-d');
                    if (!isset($availabilityValue[$date])) {
                        $availabilityValue[$date] = [];
                    }

                    $period = CarbonPeriod::create(
                        $record->start_time,
                        '30 minutes',
                        Carbon::parse($record->end_time)->subMinute()
                    );

                    foreach ($period as $datetime) {
                        $availabilityValue[$date][] = $datetime->format('H:i');
                    }
                }
            @endphp

            <x-schedule.weekly
                class="flex-grow"
                name="availability"
                :value="$availabilityValue"
                :start-hour="7"
                :end-hour="22"
            />
        </form>
    </main>
</x-dashboard-layout>
