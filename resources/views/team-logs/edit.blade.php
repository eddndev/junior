<x-dashboard-layout title="Editar Entrada de Bitácora">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('team-logs.index') }}" class="text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Editar Entrada de Bitácora</h1>
            </div>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Modifica el título, contenido o tipo de tu entrada en el área <span class="font-medium">{{ $teamLog->area->name }}</span>.
            </p>
        </div>

        <x-layout.alerts />

        {{-- Edit Form using Composer Component --}}
        <div class="mb-8">
            <form action="{{ route('team-logs.update', $teamLog) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-forms.composer
                    title-placeholder="¿Qué sucedió hoy?"
                    description-placeholder="Describe la decisión, evento o nota importante..."
                    title-name="title"
                    description-name="content"
                    description-rows="3"
                    :title-value="old('title', $teamLog->title)"
                    :description-value="old('content', $teamLog->content)"
                >
                    {{-- Attachment Actions Toolbar --}}
                    <x-slot name="toolbar">
                        @livewire('team-log-attachments')
                    </x-slot>

                    <x-slot name="leftActions">
                        {{-- Area Display (read-only) --}}
                        <div class="inline-flex items-center rounded-md bg-neutral-100 px-3 py-2 text-sm font-medium text-neutral-700 dark:bg-neutral-700 dark:text-neutral-300">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $teamLog->area->name }}
                        </div>

                        {{-- Type Selector --}}
                        <x-forms.select
                            id="type"
                            name="type"
                            placeholder="Tipo de entrada"
                            :value="old('type', $teamLog->type)"
                        >
                            <x-forms.select-option value="note">Nota</x-forms.select-option>
                            <x-forms.select-option value="decision">Decisión</x-forms.select-option>
                            <x-forms.select-option value="event">Evento</x-forms.select-option>
                            <x-forms.select-option value="meeting">Reunión</x-forms.select-option>
                        </x-forms.select>
                    </x-slot>

                    <x-slot name="rightActions">
                        <a
                            href="{{ route('team-logs.index') }}"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                        >
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:bg-primary-500 dark:hover:bg-primary-400">
                            Guardar Cambios
                        </button>
                    </x-slot>
                </x-forms.composer>
            </form>
        </div>

        {{-- Current Attachments --}}
        @if($teamLog->media->count() > 0)
            <div class="mt-8">
                <h3 class="text-sm font-medium text-neutral-900 dark:text-white mb-4">Adjuntos Actuales</h3>
                <x-team-log.attachments-display :teamLog="$teamLog" />
                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                    Los adjuntos existentes se mantendrán. Los nuevos adjuntos se agregarán a la entrada.
                </p>
            </div>
        @endif
    </div>
</x-dashboard-layout>
