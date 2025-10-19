<x-auth-layout>
    <x-slot name="title">
        Confirmar contraseña
    </x-slot>

    <x-slot name="subtitle">
        Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.
    </x-slot>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm/6 font-medium text-neutral-900 dark:text-neutral-100">
                Contraseña
            </label>
            <div class="mt-2">
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-neutral-900 outline-1 -outline-offset-1 outline-neutral-300 placeholder:text-neutral-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-neutral-500 dark:focus:outline-primary-500"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-gradient-to-r from-primary-600 to-primary-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:from-primary-700 hover:to-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200"
            >
                Confirmar
            </button>
        </div>
    </form>
</x-auth-layout>