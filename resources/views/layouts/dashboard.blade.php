<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-neutral-900 scheme-light dark:scheme-dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Junior') }} - {{ $title ?? 'Dashboard' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="h-full">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
            <!-- Mobile sidebar -->
            <div
                x-show="sidebarOpen"
                class="relative z-50 lg:hidden"
                role="dialog"
                aria-modal="true"
                style="display: none;"
            >
                <!-- Backdrop -->
                <div
                    x-show="sidebarOpen"
                    x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-neutral-900/80"
                    @click="sidebarOpen = false"
                ></div>

                <div class="fixed inset-0 flex" tabindex="0">
                    <!-- Sidebar panel -->
                    <div
                        x-show="sidebarOpen"
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        class="relative mr-16 flex w-full max-w-xs flex-1"
                        @click.away="sidebarOpen = false"
                    >
                        <!-- Close button -->
                        <div
                            x-show="sidebarOpen"
                            x-transition:enter="ease-in-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in-out duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute top-0 left-full flex w-16 justify-center pt-5"
                        >
                            <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                                <span class="sr-only">Cerrar sidebar</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 text-white">
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sidebar content (mobile) -->
                        <div class="relative flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2 dark:bg-neutral-900 dark:ring dark:ring-white/10 dark:before:pointer-events-none dark:before:absolute dark:before:inset-0 dark:before:bg-black/10">
                            <div class="relative flex h-16 shrink-0 items-center">
                                <x-image.logo
                                    src="resources/images/brand/logo.png"
                                    alt="{{ config('app.name', 'Junior') }}"
                                    size="sm"
                                    class="h-8 w-auto"
                                />
                            </div>
                            <nav class="relative flex flex-1 flex-col">
                                {{ $sidebarMobile ?? $sidebar ?? '' }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col dark:bg-neutral-900">
                <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-neutral-200 bg-white px-6 dark:border-white/10 dark:bg-black/10">
                    <div class="flex h-16 shrink-0 items-center">
                        <x-image.logo
                            src="resources/images/brand/logo.png"
                            alt="{{ config('app.name', 'Junior') }}"
                            size="sm"
                            class="h-8 w-auto"
                        />
                    </div>
                    <nav class="flex flex-1 flex-col">
                        {{ $sidebar }}
                    </nav>
                </div>
            </div>

            <!-- Mobile top bar -->
            <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-xs sm:px-6 lg:hidden dark:bg-neutral-900 dark:shadow-none dark:after:pointer-events-none dark:after:absolute dark:after:inset-0 dark:after:border-b dark:after:border-white/10 dark:after:bg-black/10">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-neutral-700 hover:text-neutral-900 lg:hidden dark:text-neutral-400 dark:hover:text-white">
                    <span class="sr-only">Abrir sidebar</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="flex-1 text-sm/6 font-semibold text-neutral-900 dark:text-white">
                    {{ $mobileTitle ?? $title ?? 'Dashboard' }}
                </div>

                {{-- User Menu Dropdown --}}
                <x-layout.dropdown anchor="bottom end" width="56">
                    <x-slot:trigger>
                        <button type="button" class="-m-1.5 flex items-center p-1.5">
                            <span class="sr-only">Abrir menú de usuario</span>
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=128"
                                alt="{{ Auth::user()->name }}"
                                class="size-8 rounded-full bg-neutral-50 outline -outline-offset-1 outline-black/5 dark:bg-neutral-800 dark:outline-white/10"
                            />
                        </button>
                    </x-slot:trigger>

                    <x-layout.dropdown-link href="{{ route('profile.edit') }}">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-neutral-400">
                            <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                        </svg>
                        <span>Mi Perfil</span>
                    </x-layout.dropdown-link>

                    <x-layout.dropdown-link href="{{ route('dashboard') }}">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-neutral-400">
                            <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                        </svg>
                        <span>Configuración</span>
                    </x-layout.dropdown-link>

                    <x-layout.dropdown-divider />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-layout.dropdown-button type="submit">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-neutral-400">
                                <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd" />
                            </svg>
                            <span>Cerrar Sesión</span>
                        </x-layout.dropdown-button>
                    </form>
                </x-layout.dropdown>
            </div>

            <!-- Main content -->
            <main class="py-10 lg:pl-72">
                <div class="px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        {{-- Toast Container --}}
        <x-feedback.toast-container />

        {{-- Flash Messages Handler --}}
        @if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
            <script>
                // Wait for Alpine.js to be fully initialized
                document.addEventListener('alpine:initialized', function() {
                    // Small delay to ensure toast container is ready
                    setTimeout(function() {
                        @if(session('success'))
                            window.showToast({
                                type: 'success',
                                title: {!! json_encode(session('success')) !!},
                                autoHide: true,
                                duration: 5000
                            });
                        @endif

                        @if(session('error'))
                            window.showToast({
                                type: 'error',
                                title: {!! json_encode(session('error')) !!},
                                autoHide: false
                            });
                        @endif

                        @if(session('warning'))
                            window.showToast({
                                type: 'warning',
                                title: {!! json_encode(session('warning')) !!},
                                autoHide: true,
                                duration: 7000
                            });
                        @endif

                        @if(session('info'))
                            window.showToast({
                                type: 'info',
                                title: {!! json_encode(session('info')) !!},
                                autoHide: true,
                                duration: 5000
                            });
                        @endif
                    }, 100);
                });
            </script>
        @endif

        @livewireScripts
        @stack('scripts')
    </body>
</html>
