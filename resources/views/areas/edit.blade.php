<x-dashboard-layout title="Editar Área">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            {{-- Breadcrumb --}}
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('areas.index') }}" class="ml-1 text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white md:ml-2">
                                Áreas
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-neutral-500 dark:text-neutral-400 md:ml-2">Editar {{ $area->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Page Title --}}
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Editar Área</h1>
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                Actualiza la información del área <strong>{{ $area->name }}</strong>.
            </p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white shadow-sm ring-1 ring-neutral-900/5 dark:bg-neutral-800 dark:ring-white/10 sm:rounded-xl">
            <form method="POST" action="{{ route('areas.update', $area) }}" class="px-4 py-6 sm:p-8">
                @csrf
                @method('PUT')
                @include('areas._form', ['area' => $area])
            </form>
        </div>
    </div>
</x-dashboard-layout>
