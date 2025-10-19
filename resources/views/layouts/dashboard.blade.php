<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-neutral-900">
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
                <a href="{{ route('profile.edit') }}">
                    <span class="sr-only">Tu perfil</span>
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=128"
                        alt="{{ Auth::user()->name }}"
                        class="size-8 rounded-full bg-neutral-50 outline -outline-offset-1 outline-black/5 dark:bg-neutral-800 dark:outline-white/10"
                    />
                </a>
            </div>

            <!-- Main content -->
            <main class="py-10 lg:pl-72">
                <div class="px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
    </body>
</html>
