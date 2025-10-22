@props(['teamLog'])

@php
    $attachments = $teamLog->getMedia('attachments');
    $links = $teamLog->getMedia('links');
    $hasAttachments = $attachments->isNotEmpty();
    $hasLinks = $links->isNotEmpty();
@endphp

@if ($hasAttachments || $hasLinks)
    <div class="mt-4 border-t border-neutral-200 dark:border-neutral-700 pt-4">
        {{-- Archivos Adjuntos --}}
        @if ($hasAttachments)
            <div class="space-y-2">
                <h5 class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wide">
                    Archivos adjuntos ({{ $attachments->count() }})
                </h5>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach ($attachments as $media)
                        @php
                            $isImage = str_starts_with($media->mime_type, 'image/');
                            $isAudio = str_starts_with($media->mime_type, 'audio/');
                            $isPdf = $media->mime_type === 'application/pdf';
                        @endphp

                        @if ($isImage)
                            {{-- Imagen con lightbox --}}
                            <a
                                href="{{ $media->getUrl() }}"
                                target="_blank"
                                class="group relative block aspect-square rounded-lg overflow-hidden bg-neutral-100 dark:bg-neutral-800 hover:opacity-90 transition-opacity"
                            >
                                @if ($media->hasGeneratedConversion('thumb'))
                                    <img
                                        src="{{ $media->getUrl('thumb') }}"
                                        alt="{{ $media->name }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <img
                                        src="{{ $media->getUrl() }}"
                                        alt="{{ $media->name }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                @endif

                                {{-- Overlay con nombre del archivo --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                                    <p class="text-white text-xs truncate">{{ $media->file_name }}</p>
                                </div>
                            </a>

                        @elseif ($isAudio)
                            {{-- Reproductor de audio --}}
                            <div class="col-span-2 sm:col-span-3 md:col-span-4 rounded-lg bg-neutral-50 dark:bg-neutral-800/50 p-3">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="h-10 w-10 rounded bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $media->file_name }}</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $media->human_readable_size }}</p>
                                    </div>
                                </div>
                                <audio controls class="w-full">
                                    <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                    Tu navegador no soporta el elemento de audio.
                                </audio>
                            </div>

                        @else
                            {{-- Otros archivos (PDF, documentos, etc.) --}}
                            <a
                                href="{{ $media->getUrl() }}"
                                target="_blank"
                                download
                                class="flex flex-col items-center justify-center aspect-square rounded-lg bg-neutral-50 dark:bg-neutral-800/50 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors p-3"
                            >
                                {{-- Icono según tipo de archivo --}}
                                @if ($isPdf)
                                    <svg class="h-12 w-12 text-red-500 dark:text-red-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="h-12 w-12 text-neutral-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                @endif

                                <p class="text-xs text-center text-neutral-700 dark:text-neutral-300 font-medium line-clamp-2 mb-1">
                                    {{ $media->file_name }}
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $media->human_readable_size }}
                                </p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Enlaces Externos --}}
        @if ($hasLinks)
            <div class="space-y-2 @if($hasAttachments) mt-4 @endif">
                <h5 class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wide">
                    Enlaces ({{ $links->count() }})
                </h5>
                <div class="space-y-2">
                    @foreach ($links as $link)
                        @php
                            $linkType = $link->getCustomProperty('link_type', 'external');
                            // Obtener URL desde custom_properties
                            $linkUrl = $link->getCustomProperty('url');
                        @endphp

                        @if ($linkType === 'video')
                            {{-- Video embebido (YouTube/Vimeo) --}}
                            <div class="rounded-lg overflow-hidden bg-neutral-100 dark:bg-neutral-800">
                                @php
                                    $embedUrl = null;
                                    // Parse YouTube URL
                                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $linkUrl, $matches)) {
                                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                    // Parse Vimeo URL
                                    elseif (preg_match('/vimeo\.com\/(\d+)/', $linkUrl, $matches)) {
                                        $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                                    }
                                @endphp

                                @if ($embedUrl)
                                    <div class="relative w-full" style="padding-bottom: 56.25%;"> {{-- 16:9 aspect ratio --}}
                                        <iframe
                                            src="{{ $embedUrl }}"
                                            class="absolute top-0 left-0 w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    </div>
                                @else
                                    {{-- Fallback para videos que no se pueden incrustar --}}
                                    <a
                                        href="{{ $linkUrl }}"
                                        target="_blank"
                                        class="flex items-center gap-3 p-3 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors"
                                    >
                                        <div class="h-10 w-10 rounded bg-red-100 dark:bg-red-900/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $linkUrl }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Video externo</p>
                                        </div>
                                        <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                            </div>

                        @elseif ($linkType === 'image')
                            {{-- Imagen externa --}}
                            <a
                                href="{{ $linkUrl }}"
                                target="_blank"
                                class="block rounded-lg overflow-hidden bg-neutral-100 dark:bg-neutral-800 hover:opacity-90 transition-opacity"
                            >
                                <img
                                    src="{{ $linkUrl }}"
                                    alt="Imagen externa"
                                    class="w-full h-auto"
                                    loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'p-4 text-center text-neutral-500\'>No se pudo cargar la imagen</div>'"
                                >
                            </a>

                        @else
                            {{-- Enlace genérico --}}
                            <a
                                href="{{ $linkUrl }}"
                                target="_blank"
                                class="flex items-center gap-3 p-3 rounded-lg bg-neutral-50 dark:bg-neutral-800/50 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors"
                            >
                                <div class="h-10 w-10 rounded bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ $linkUrl }}</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Enlace externo</p>
                                </div>
                                <svg class="h-5 w-5 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif
