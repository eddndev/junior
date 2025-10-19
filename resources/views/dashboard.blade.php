<x-dashboard-layout title="Dashboard">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Dashboard</h1>
        <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-300">
            Bienvenido de vuelta, {{ Auth::user()->name }}
        </p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-neutral-800">
            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Tareas Pendientes</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-neutral-900 dark:text-white">0</dd>
        </div>

        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-neutral-800">
            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Notificaciones</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-neutral-900 dark:text-white">0</dd>
        </div>

        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-neutral-800">
            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Eventos Hoy</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-neutral-900 dark:text-white">0</dd>
        </div>

        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-neutral-800">
            <dt class="truncate text-sm font-medium text-neutral-500 dark:text-neutral-400">Mensajes</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-neutral-900 dark:text-white">0</dd>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="mt-8">
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-neutral-800">
            <div class="p-6">
                <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Actividad Reciente</h2>
                <div class="mt-6 flow-root">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">No hay actividad reciente para mostrar.</p>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
