<div x-data="{ open: false }" @click.away="open = false" class="relative">
    {{-- Notification Bell Button --}}
    <button
        @click="open = !open"
        type="button"
        class="relative rounded-full p-1 text-neutral-400 hover:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-neutral-300 dark:hover:text-white dark:focus:ring-offset-neutral-900"
    >
        <span class="sr-only">Ver notificaciones</span>
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>

        {{-- Unread Badge --}}
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex size-5 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none dark:bg-neutral-800 dark:ring-white/10 sm:w-96"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-neutral-200 px-4 py-3 dark:border-white/10">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                Notificaciones
            </h3>
            @if($unreadCount > 0)
                <button
                    wire:click="markAllAsRead"
                    type="button"
                    class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                >
                    Marcar todas como leidas
                </button>
            @endif
        </div>

        {{-- Notification List --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse($this->recentNotifications as $notification)
                <div
                    wire:key="notification-{{ $notification->id }}"
                    @class([
                        'flex gap-3 px-4 py-3 hover:bg-neutral-50 dark:hover:bg-neutral-700/50',
                        'bg-indigo-50/50 dark:bg-indigo-900/20' => !$notification->is_read,
                    ])
                >
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        <div @class([
                            'flex size-8 items-center justify-center rounded-full',
                            'bg-blue-100 dark:bg-blue-900/50' => $notification->type === 'info',
                            'bg-green-100 dark:bg-green-900/50' => $notification->type === 'success',
                            'bg-yellow-100 dark:bg-yellow-900/50' => $notification->type === 'warning',
                            'bg-red-100 dark:bg-red-900/50' => $notification->type === 'error',
                        ])>
                            @if($notification->type === 'success')
                                <svg class="size-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @elseif($notification->type === 'warning')
                                <svg class="size-4 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            @elseif($notification->type === 'error')
                                <svg class="size-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @else
                                <svg class="size-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $notification->title }}
                            </p>
                            @if(!$notification->is_read)
                                <button
                                    wire:click="markAsRead({{ $notification->id }})"
                                    type="button"
                                    class="flex-shrink-0 text-xs text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200"
                                    title="Marcar como leida"
                                >
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">
                            {{ Str::limit($notification->message, 100) }}
                        </p>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-xs text-neutral-500 dark:text-neutral-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if($notification->action_url)
                                <a
                                    href="{{ $notification->action_url }}"
                                    class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                >
                                    {{ $notification->action_text ?? 'Ver' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto size-12 text-neutral-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                        No tienes notificaciones
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="border-t border-neutral-200 px-4 py-3 dark:border-white/10">
            <a
                href="{{ route('notifications.index') }}"
                class="block text-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
            >
                Ver todas las notificaciones
            </a>
        </div>
    </div>
</div>
