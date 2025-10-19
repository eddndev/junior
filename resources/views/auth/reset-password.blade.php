<x-auth-layout>
    <x-slot name="title">
        Restablecer contraseña
    </x-slot>

    <x-slot name="subtitle">
        Ingresa una nueva contraseña segura para tu cuenta.
    </x-slot>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-neutral-900 outline-1 -outline-offset-1 outline-neutral-300 placeholder:text-neutral-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-neutral-500 dark:focus:outline-primary-500"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm/6 font-medium text-neutral-900 dark:text-neutral-100">
                Nueva contraseña
            </label>
            <div class="mt-2">
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-neutral-900 outline-1 -outline-offset-1 outline-neutral-300 placeholder:text-neutral-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-neutral-500 dark:focus:outline-primary-500"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm/6 font-medium text-neutral-900 dark:text-neutral-100">
                Confirmar contraseña
            </label>
            <div class="mt-2">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-neutral-900 outline-1 -outline-offset-1 outline-neutral-300 placeholder:text-neutral-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-neutral-500 dark:focus:outline-primary-500"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-gradient-to-r from-primary-600 to-primary-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:from-primary-700 hover:to-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200"
            >
                Restablecer contraseña
            </button>
        </div>
    </form>
</x-auth-layout>