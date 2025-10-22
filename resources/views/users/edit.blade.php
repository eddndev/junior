@extends('layouts.dashboard')

@section('content')
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
                        <a href="{{ route('users.index') }}" class="ml-1 text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white md:ml-2">
                            Usuarios
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('users.show', $user) }}" class="ml-1 text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white md:ml-2">
                            {{ $user->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-neutral-500 dark:text-neutral-400 md:ml-2">Editar</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Page Title --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Editar Usuario</h1>
                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                    Actualiza la información de <strong>{{ $user->name }}</strong>.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a
                    href="{{ route('users.show', $user) }}"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-white dark:ring-neutral-700 dark:hover:bg-neutral-700"
                >
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Ver Detalle
                </a>
            </div>
        </div>
    </div>

    {{-- Validation Errors Summary --}}
    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Hay {{ $errors->count() }} {{ $errors->count() === 1 ? 'error' : 'errores' }} en el formulario:
                    </h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white shadow-sm ring-1 ring-neutral-900/5 dark:bg-neutral-800 dark:ring-white/10 sm:rounded-xl">
        <form method="POST" action="{{ route('users.update', $user) }}" class="px-4 py-6 sm:p-8">
            @csrf
            @method('PUT')

            {{-- Include reusable form partial with $user variable --}}
            @include('users._form')
        </form>
    </div>

    {{-- Warning for password change --}}
    <div class="mt-6 rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    Advertencia sobre cambios
                </h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Si cambias la contraseña, el usuario deberá usar la nueva contraseña en su próximo inicio de sesión</li>
                        <li>Los cambios de roles y áreas afectarán inmediatamente los permisos del usuario</li>
                        <li>Si desactivas el usuario, perderá acceso al sistema de inmediato</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
