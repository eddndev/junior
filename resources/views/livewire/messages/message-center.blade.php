<div class="flex h-[calc(100vh-12rem)] bg-white dark:bg-gray-800 rounded-lg shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 overflow-hidden">
    {{-- Conversations List (Left Sidebar) --}}
    <div class="w-80 flex-shrink-0 border-r border-gray-200 dark:border-white/10 flex flex-col">
        {{-- Header --}}
        <div class="p-4 border-b border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mensajes</h2>
                <button wire:click="startNewConversation"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo
                </button>
            </div>

            @if($this->totalUnread > 0)
                <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">
                    {{ $this->totalUnread }} mensaje(s) sin leer
                </div>
            @endif
        </div>

        {{-- Conversations --}}
        <div class="flex-1 overflow-y-auto">
            @forelse($this->conversations as $conversation)
                @php
                    $otherUser = $conversation->getOtherUser(auth()->user());
                    $unreadCount = $conversation->unreadCountFor(auth()->user());
                    $isSelected = $selectedConversation && $selectedConversation->id === $conversation->id;
                @endphp
                <button wire:click="selectConversation({{ $conversation->id }})"
                    class="w-full text-left p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors border-b border-gray-100 dark:border-white/5 {{ $isSelected ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <x-data-display.avatar :user="$otherUser" size="md" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $otherUser->name }}
                                </p>
                                @if($unreadCount > 0)
                                    <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-indigo-600 text-xs font-medium text-white">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            @if($conversation->lastMessage)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ Str::limit($conversation->lastMessage->body, 40) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    {{ $conversation->lastMessage->created_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>
                </button>
            @empty
                <div class="p-4 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        No hay conversaciones
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        Inicia una nueva conversación
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Area (Right) --}}
    <div class="flex-1 flex flex-col">
        @if($selectedConversation)
            {{-- Chat Header --}}
            @php
                $chatUser = $selectedConversation->getOtherUser(auth()->user());
            @endphp
            <div class="p-4 border-b border-gray-200 dark:border-white/10 flex items-center gap-3">
                <x-data-display.avatar :user="$chatUser" size="md" />
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $chatUser->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $chatUser->email }}</p>
                </div>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container" wire:poll.5s>
                @foreach($selectedConversation->messages as $message)
                    @php
                        $isOwn = $message->isFrom(auth()->user());
                    @endphp
                    <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $isOwn ? 'order-2' : 'order-1' }}">
                            <div class="rounded-lg px-4 py-2 {{ $isOwn ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $message->body }}</p>
                            </div>
                            <p class="mt-1 text-xs {{ $isOwn ? 'text-right' : 'text-left' }} text-gray-400 dark:text-gray-500">
                                {{ $message->created_at->format('H:i') }}
                                @if($isOwn && $message->isRead())
                                    <svg class="inline-block h-3 w-3 ml-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Message Input --}}
            <div class="p-4 border-t border-gray-200 dark:border-white/10">
                <form wire:submit="sendMessage" class="flex gap-2">
                    <input type="text"
                        wire:model="newMessage"
                        placeholder="Escribe un mensaje..."
                        class="flex-1 rounded-md border-0 py-2 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 sm:text-sm bg-white dark:bg-white/5"
                        autocomplete="off">
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
                @error('newMessage')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

        @elseif($selectedUserId || !$selectedConversation)
            {{-- New Conversation --}}
            <div class="flex-1 flex flex-col">
                <div class="p-4 border-b border-gray-200 dark:border-white/10">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Nueva conversación</h3>
                    <div class="relative">
                        <input type="text"
                            wire:model.live.debounce.300ms="searchUsers"
                            placeholder="Buscar usuario..."
                            class="w-full rounded-md border-0 py-2 pl-10 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 sm:text-sm bg-white dark:bg-white/5">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Search Results --}}
                    @if($this->searchResults->count() > 0 && !$selectedUserId)
                        <div class="mt-2 rounded-md border border-gray-200 dark:border-white/10 divide-y divide-gray-200 dark:divide-white/10">
                            @foreach($this->searchResults as $user)
                                <button wire:click="selectUser({{ $user->id }})"
                                    class="w-full text-left p-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <x-data-display.avatar :user="$user" size="sm" />
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($selectedUserId)
                    {{-- Selected User Info --}}
                    @php
                        $selectedUser = App\Models\User::find($selectedUserId);
                    @endphp
                    <div class="flex-1 flex items-center justify-center overflow-y-auto">
                        <div class="text-center">
                            <x-data-display.avatar :user="$selectedUser" size="xl" class="mx-auto" />
                            <p class="mt-3 text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedUser->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedUser->email }}</p>
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                Escribe tu primer mensaje
                            </p>
                        </div>
                    </div>

                    {{-- Message Input for New Conversation --}}
                    <div class="p-4 border-t border-gray-200 dark:border-white/10">
                        <form wire:submit="sendMessage" class="flex gap-2">
                            <input type="text"
                                wire:model="newMessage"
                                placeholder="Escribe un mensaje..."
                                class="flex-1 rounded-md border-0 py-2 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 sm:text-sm bg-white dark:bg-white/5"
                                autocomplete="off">
                            <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </form>
                        @error('newMessage')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Busca un usuario para iniciar conversación
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- Empty State --}}
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Selecciona una conversación o inicia una nueva
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

@script
<script>
    // Scroll to bottom when messages update
    $wire.on('messageSent', () => {
        setTimeout(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 100);
    });

    // Initial scroll to bottom
    document.addEventListener('livewire:navigated', () => {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endscript
