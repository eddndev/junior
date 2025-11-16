<div>
    @if($user)
        {{-- Header --}}
        <x-dialog-header dialog-id="user-detail-dialog">
            <x-slot:title>
                <div class="flex items-center gap-2">
                    <span class="truncate">{{ $user->name }}</span>
                    @if($user->is_active)
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-600/30">
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                            Inactivo
                        </span>
                    @endif
                    @if($user->trashed())
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-600/30">
                            Eliminado
                        </span>
                    @endif
                </div>
            </x-slot:title>

            <x-slot:description>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $user->email }}
                </p>
            </x-slot:description>
        </x-dialog-header>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-6">

                {{-- LEFT COLUMN (2/3) - Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Basic Information --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Información Personal</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-neutral-200 dark:border-neutral-700">
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre completo</dt>
                                <dd class="text-sm text-neutral-900 dark:text-white">{{ $user->name }}</dd>
                            </div>

                            <div class="flex justify-between py-2 border-b border-neutral-200 dark:border-neutral-700">
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Correo electrónico</dt>
                                <dd class="text-sm text-neutral-900 dark:text-white">{{ $user->email }}</dd>
                            </div>

                            <div class="flex justify-between py-2 border-b border-neutral-200 dark:border-neutral-700">
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Fecha de registro</dt>
                                <dd class="text-sm text-neutral-900 dark:text-white">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400">({{ $user->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>

                            <div class="flex justify-between py-2">
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Última actualización</dt>
                                <dd class="text-sm text-neutral-900 dark:text-white">
                                    {{ $user->updated_at->diffForHumans() }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Roles por Área --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">
                            Roles por Área
                            @if($user->areas->count() > 0)
                                <span class="ml-2 text-xs font-normal text-neutral-500">({{ $user->areas->count() }} áreas)</span>
                            @endif
                        </h3>
                        @php
                            // Group roles by area
                            $rolesByArea = [];
                            foreach ($user->roles as $role) {
                                $areaId = $role->pivot->area_id;
                                if ($areaId) {
                                    if (!isset($rolesByArea[$areaId])) {
                                        $rolesByArea[$areaId] = [];
                                    }
                                    $rolesByArea[$areaId][] = $role;
                                }
                            }
                        @endphp

                        @if(count($rolesByArea) > 0)
                            <div class="space-y-4">
                                @foreach($user->areas as $area)
                                    @if(isset($rolesByArea[$area->id]))
                                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded bg-purple-100 text-xs font-semibold text-purple-700 dark:bg-purple-900 dark:text-purple-300">
                                                    {{ substr($area->name, 0, 1) }}
                                                </div>
                                                <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ $area->name }}</span>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($rolesByArea[$area->id] as $role)
                                                    <span class="inline-flex items-center gap-x-1 rounded px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-600/30">
                                                        <svg class="h-1.5 w-1.5 fill-blue-500" viewBox="0 0 6 6" aria-hidden="true">
                                                            <circle cx="3" cy="3" r="3" />
                                                        </svg>
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">Sin roles asignados</p>
                        @endif
                    </div>

                </div>

                {{-- RIGHT COLUMN (1/3) - Sidebar --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Quick Stats --}}
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Resumen</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-2 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">Total de Áreas</span>
                                <span class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $user->areas->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">Total de Roles</span>
                                <span class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $user->roles->count() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- OAuth Connections --}}
                    @if($user->google_id || $user->github_id)
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Cuentas Conectadas</h3>
                        <div class="space-y-2">
                            @if($user->google_id)
                                <div class="flex items-center gap-2 p-2 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Google</span>
                                </div>
                            @endif
                            @if($user->github_id)
                                <div class="flex items-center gap-2 p-2 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    <span class="text-sm text-neutral-700 dark:text-neutral-300">GitHub</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="user-detail-dialog">
            @can('update', $user)
                @if(!$user->trashed())
                    <button type="button"
                            onclick="window.DialogSystem.close('user-detail-dialog'); loadUserForEdit({{ $user->id }})"
                            class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400 transition-colors">
                        Editar Usuario
                    </button>
                @endif
            @endcan
        </x-dialog-footer>

    @else
        {{-- Loading State --}}
        <div class="flex items-center justify-center h-full min-h-[400px] p-12">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-neutral-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">Cargando usuario...</p>
            </div>
        </div>
    @endif
</div>