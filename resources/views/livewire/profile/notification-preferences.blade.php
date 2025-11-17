<div>
    {{-- Success Message --}}
    @if($showSuccess)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false; $wire.showSuccess = false; }, 3000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-6 rounded-md bg-green-50 p-4 dark:bg-green-900/20"
        >
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        Preferencias guardadas correctamente
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Info Box --}}
    <div class="mb-6 rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="size-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                    Canales de notificacion
                </h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <ul class="list-disc space-y-1 pl-5">
                        <li><strong>En la app:</strong> Notificaciones que aparecen en la campana de notificaciones</li>
                        <li><strong>Email:</strong> Notificaciones enviadas a tu correo electronico</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Preferences Table --}}
    <div class="space-y-8">
        @foreach($this->groupedPreferences as $category => $categoryPreferences)
            <div>
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">
                    {{ $this->categoryLabels[$category] ?? ucfirst($category) }}
                </h3>

                <div class="mt-4 overflow-hidden rounded-lg border border-neutral-200 dark:border-white/10">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-white/10">
                        <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                                    Tipo de notificacion
                                </th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                                    En la app
                                </th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                                    Email
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-white/10 dark:bg-neutral-900">
                            @foreach($categoryPreferences as $preference)
                                <tr wire:key="pref-{{ $preference->notification_type }}">
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                                {{ $preference->label }}
                                            </div>
                                            <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $preference->description }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-center">
                                        <button
                                            wire:click="togglePreference('{{ $preference->notification_type }}', 'database')"
                                            type="button"
                                            @class([
                                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-900',
                                                'bg-indigo-600' => $preferences[$preference->notification_type]['database_enabled'] ?? false,
                                                'bg-neutral-200 dark:bg-neutral-700' => !($preferences[$preference->notification_type]['database_enabled'] ?? false),
                                            ])
                                            role="switch"
                                            aria-checked="{{ ($preferences[$preference->notification_type]['database_enabled'] ?? false) ? 'true' : 'false' }}"
                                        >
                                            <span class="sr-only">Activar notificaciones en la app</span>
                                            <span
                                                @class([
                                                    'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                    'translate-x-5' => $preferences[$preference->notification_type]['database_enabled'] ?? false,
                                                    'translate-x-0' => !($preferences[$preference->notification_type]['database_enabled'] ?? false),
                                                ])
                                            ></span>
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-center">
                                        <button
                                            wire:click="togglePreference('{{ $preference->notification_type }}', 'email')"
                                            type="button"
                                            @class([
                                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-900',
                                                'bg-indigo-600' => $preferences[$preference->notification_type]['email_enabled'] ?? false,
                                                'bg-neutral-200 dark:bg-neutral-700' => !($preferences[$preference->notification_type]['email_enabled'] ?? false),
                                            ])
                                            role="switch"
                                            aria-checked="{{ ($preferences[$preference->notification_type]['email_enabled'] ?? false) ? 'true' : 'false' }}"
                                        >
                                            <span class="sr-only">Activar notificaciones por email</span>
                                            <span
                                                @class([
                                                    'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                    'translate-x-5' => $preferences[$preference->notification_type]['email_enabled'] ?? false,
                                                    'translate-x-0' => !($preferences[$preference->notification_type]['email_enabled'] ?? false),
                                                ])
                                            ></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
