<x-dashboard-layout title="Notificaciones">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Notificaciones</h1>
                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-400">
                    Historial completo de todas tus notificaciones.
                    @if($unreadCount > 0)
                        <span class="font-medium text-primary-600 dark:text-primary-400">
                            Tienes {{ $unreadCount }} {{ $unreadCount === 1 ? 'notificacion sin leer' : 'notificaciones sin leer' }}.
                        </span>
                    @endif
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Marcar todas como leidas
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mt-4 rounded-md bg-green-50 p-4 dark:bg-green-900/20">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filters Section --}}
        <div class="mt-6 border-b border-neutral-200 dark:border-neutral-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('notifications.index') }}"
                   @class([
                       'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors',
                       'border-primary-500 text-primary-600 dark:text-primary-400' => !request('filter'),
                       'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' => request('filter'),
                   ])>
                    Todas
                    <span class="ml-2 rounded-full bg-neutral-100 px-2.5 py-0.5 text-xs font-medium text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                        {{ $notifications->total() }}
                    </span>
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                   @class([
                       'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors',
                       'border-primary-500 text-primary-600 dark:text-primary-400' => request('filter') === 'unread',
                       'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' => request('filter') !== 'unread',
                   ])>
                    No leidas
                    @if($unreadCount > 0)
                        <span class="ml-2 rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-600 dark:bg-primary-900 dark:text-primary-300">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
                   @class([
                       'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors',
                       'border-primary-500 text-primary-600 dark:text-primary-400' => request('filter') === 'read',
                       'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' => request('filter') !== 'read',
                   ])>
                    Leidas
                </a>
            </nav>
        </div>

        {{-- Type Filter (if there are multiple types) --}}
        @if(count($typeCounts) > 1)
            <div class="mt-4">
                <label for="type-filter" class="sr-only">Filtrar por tipo</label>
                <select id="type-filter"
                        onchange="window.location.href = this.value"
                        class="block w-full rounded-md border-neutral-300 py-2 pl-3 pr-10 text-base focus:border-primary-500 focus:outline-none focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:text-sm">
                    <option value="{{ route('notifications.index', array_merge(request()->except('type'), ['type' => 'all'])) }}"
                            {{ !request('type') || request('type') === 'all' ? 'selected' : '' }}>
                        Todos los tipos
                    </option>
                    @foreach($typeCounts as $type => $count)
                        <option value="{{ route('notifications.index', array_merge(request()->except('type'), ['type' => $type])) }}"
                                {{ request('type') === $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }} ({{ $count }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Notifications List --}}
        <div class="mt-6 space-y-4">
            @forelse($notifications as $notification)
                <div @class([
                    'relative rounded-lg border p-4 transition-colors',
                    'bg-primary-50/50 border-primary-200 dark:bg-primary-900/10 dark:border-primary-800' => !$notification->is_read,
                    'bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700' => $notification->is_read,
                ])>
                    <div class="flex gap-4">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            @php
                                $iconBgClass = match($notification->type) {
                                    'success' => 'bg-green-100 dark:bg-green-900/50',
                                    'warning' => 'bg-yellow-100 dark:bg-yellow-900/50',
                                    'error' => 'bg-red-100 dark:bg-red-900/50',
                                    default => 'bg-blue-100 dark:bg-blue-900/50',
                                };
                                $iconColorClass = match($notification->type) {
                                    'success' => 'text-green-600 dark:text-green-400',
                                    'warning' => 'text-yellow-600 dark:text-yellow-400',
                                    'error' => 'text-red-600 dark:text-red-400',
                                    default => 'text-blue-600 dark:text-blue-400',
                                };
                            @endphp
                            <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $iconBgClass }}">
                                @if($notification->type === 'success')
                                    <svg class="h-5 w-5 {{ $iconColorClass }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @elseif($notification->type === 'warning')
                                    <svg class="h-5 w-5 {{ $iconColorClass }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                @elseif($notification->type === 'error')
                                    <svg class="h-5 w-5 {{ $iconColorClass }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 {{ $iconColorClass }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                            {{ $notification->title }}
                                        </h3>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex h-2 w-2 rounded-full bg-primary-500"></span>
                                        @endif
                                        @if($notification->priority === 'high')
                                            <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900 dark:text-red-300 dark:ring-red-500/20">
                                                Alta prioridad
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $notification->message }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-4 text-xs text-neutral-500 dark:text-neutral-500">
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        @if($notification->notification_type)
                                            <span class="inline-flex items-center rounded bg-neutral-100 px-1.5 py-0.5 text-xs font-medium text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                                                {{ ucfirst(str_replace('_', ' ', $notification->notification_type)) }}
                                            </span>
                                        @endif
                                        @if($notification->is_read && $notification->read_at)
                                            <span>Leida {{ $notification->read_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2">
                                    {{-- View Details Button --}}
                                    <button type="button"
                                            @click="Livewire.dispatch('openNotificationDetail', { notificationId: {{ $notification->id }} })"
                                            class="rounded-md p-2 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-500 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors"
                                            title="Ver detalles">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    @if(!$notification->is_read)
                                        {{-- Mark as Read --}}
                                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="rounded-md p-2 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-500 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors"
                                                    title="Marcar como leida">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete --}}
                                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Estas seguro de que deseas eliminar esta notificacion?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-md p-2 text-neutral-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-colors"
                                                title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Action Link --}}
                            @if($notification->action_url)
                                <div class="mt-3">
                                    <a href="{{ $notification->action_url }}"
                                       class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:bg-neutral-700 dark:text-white dark:ring-neutral-600 dark:hover:bg-neutral-600 transition-colors">
                                        {{ $notification->action_text ?? 'Ver mas' }}
                                        <svg class="ml-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-neutral-200 bg-white p-12 text-center dark:border-neutral-700 dark:bg-neutral-800">
                    <svg class="mx-auto h-12 w-12 text-neutral-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-neutral-900 dark:text-white">No hay notificaciones</h3>
                    <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                        @if(request('filter') === 'unread')
                            No tienes notificaciones sin leer.
                        @elseif(request('filter') === 'read')
                            No tienes notificaciones leidas.
                        @else
                            No tienes ninguna notificacion en este momento.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif

        {{-- Info Box --}}
        <div class="mt-8 rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Acerca de las notificaciones
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Las notificaciones se generan automaticamente cuando ocurren eventos importantes</li>
                            <li>Puedes configurar tus preferencias de notificacion en <a href="{{ route('profile.notifications') }}" class="font-medium underline hover:no-underline">ajustes de perfil</a></li>
                            <li>Las notificaciones eliminadas no se pueden recuperar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Detail Dialog --}}
    <x-dialog-wrapper id="notification-detail-dialog" max-width="xl" slide-from="right">
        @livewire('notifications.notification-detail-dialog')
    </x-dialog-wrapper>

    @push('scripts')
    <script>
        /**
         * Toast notification system
         */
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 rounded-md p-4 shadow-lg transition-all transform ${
                type === 'success'
                    ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-200'
                    : 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-200'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);

            // Fade out and remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            // Toast notifications
            Livewire.on('show-toast', (event) => {
                showToast(event.message, event.type || 'success');
            });

            // Dialog close events
            Livewire.on('close-dialog', (event) => {
                if (event.dialogId && window.DialogSystem.isOpen(event.dialogId)) {
                    window.DialogSystem.close(event.dialogId);
                }
            });

            // Notification deleted event - reload page to reflect changes
            Livewire.on('notificationDeleted', () => {
                window.location.reload();
            });

            // Notification read event - update UI
            Livewire.on('notificationRead', () => {
                // Could update the UI without reload, but for simplicity we'll just reload
                window.location.reload();
            });
        });
    </script>
    @endpush
</x-dashboard-layout>
