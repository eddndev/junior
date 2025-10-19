<x-auth-layout>
    <x-slot name="title">
        Recuperar contraseña
    </x-slot>

    <x-slot name="subtitle">
        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm/6 font-medium text-neutral-900 dark:text-neutral-100">
                Correo electrónico
            </label>
            <div class="mt-2">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-neutral-900 outline-1 -outline-offset-1 outline-neutral-300 placeholder:text-neutral-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-neutral-500 dark:focus:outline-primary-500"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100">
                ← Volver al inicio de sesión
            </a>

            <button
                type="submit"
                class="rounded-md bg-gradient-to-r from-primary-600 to-primary-500 px-6 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:from-primary-700 hover:to-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200"
            >
                Enviar enlace
            </button>
        </div>
    </form>
</x-auth-layout>
