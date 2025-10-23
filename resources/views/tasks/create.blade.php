<x-dashboard-layout title="Nueva Tarea">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header with back button --}}
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-white">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a Tareas
        </a>
    </div>

    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Crear Nueva Tarea</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Completa los detalles de la nueva tarea y asígnala a los usuarios responsables.
            </p>
        </div>
    </div>

    {{-- Display validation errors summary --}}
    @if($errors->any())
        <div class="mt-6 rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Hay {{ $errors->count() }} error(es) en el formulario:
                    </h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data" class="mt-8">
        @csrf

        <div class="bg-white dark:bg-neutral-800 shadow-sm ring-1 ring-neutral-900/5 dark:ring-white/10 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                @include('tasks._form', [
                    'assignedUserIds' => []
                ])
            </div>

            <div class="flex items-center justify-end gap-x-4 border-t border-neutral-900/10 dark:border-white/10 px-4 py-4 sm:px-8">
                <a
                    href="{{ route('tasks.index') }}"
                    class="rounded-md px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                >
                    Cancelar
                </a>
                <button
                    type="submit"
                    class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:bg-primary-500 dark:hover:bg-primary-400"
                >
                    Crear Tarea
                </button>
            </div>
        </div>
    </form>
</div>
</x-dashboard-layout>