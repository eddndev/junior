<x-auth-layout>
    <x-slot name="title">
        Verifica tu correo electrónico
    </x-slot>

    <x-slot name="subtitle">
        Gracias por registrarte. Antes de comenzar, verifica tu dirección de correo haciendo clic en el enlace que te enviamos.
    </x-slot>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-emerald-700 dark:text-emerald-300">
                        Se envió un nuevo enlace de verificación a tu correo electrónico.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-gradient-to-r from-primary-600 to-primary-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:from-primary-700 hover:to-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200"
            >
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full text-center text-sm/6 text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100 underline transition-colors"
            >
                Cerrar sesión
            </button>
        </form>
    </div>
</x-auth-layout>