<x-dashboard-layout title="Mensajes">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Mensajes
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Comunícate directamente con otros miembros del equipo
                    </p>
                </div>
            </div>

            {{-- Message Center Component --}}
            <livewire:messages.message-center :conversationId="$conversationId ?? null" />
        </div>
    </div>
</x-dashboard-layout>
