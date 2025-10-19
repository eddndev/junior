@php
    // Frases inspiracionales para el panel derecho
    $quotes = [
        [
            'text' => 'La innovación distingue entre un líder y un seguidor.',
            'author' => 'Steve Jobs',
        ],
        [
            'text' => 'La mejor manera de predecir el futuro es crearlo.',
            'author' => 'Peter Drucker',
        ],
        [
            'text' => 'El liderazgo consiste en empoderar a otros para alcanzar su máximo potencial.',
            'author' => 'Bill Gates',
        ],
        [
            'text' => 'El éxito no es la clave de la felicidad. La felicidad es la clave del éxito.',
            'author' => 'Albert Schweitzer',
        ],
        [
            'text' => 'La gente no compra lo que haces, compra el porqué lo haces.',
            'author' => 'Simon Sinek',
        ],
        [
            'text' => 'No te preocupes por el fracaso, preocúpate por las oportunidades que pierdes cuando ni siquiera lo intentas.',
            'author' => 'Jack Canfield',
        ],
        [
            'text' => 'El cliente es el activo más importante de cualquier empresa.',
            'author' => 'Michael LeBoeuf',
        ],
        [
            'text' => 'La calidad no es un acto, es un hábito.',
            'author' => 'Aristóteles',
        ],
        [
            'text' => 'El trabajo en equipo es la capacidad de trabajar juntos hacia una visión común.',
            'author' => 'Andrew Carnegie',
        ],
        [
            'text' => 'La productividad nunca es un accidente. Es siempre el resultado de un compromiso con la excelencia.',
            'author' => 'Paul J. Meyer',
        ],
        [
            'text' => 'La única forma de hacer un gran trabajo es amar lo que haces.',
            'author' => 'Steve Jobs',
        ],
        [
            'text' => 'En medio de la dificultad reside la oportunidad.',
            'author' => 'Albert Einstein',
        ],
    ];

    // Imágenes corporativas para el panel derecho
    $images = [
        [
            'url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Equipo de trabajo colaborando en oficina moderna',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Profesionales en reunión de estrategia',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Espacio de trabajo colaborativo',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Equipo diverso trabajando en proyecto',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Liderazgo y gestión empresarial',
        ],
    ];

    // Seleccionar aleatoriamente una frase y una imagen
    $selectedQuote = $quotes[array_rand($quotes)];
    $selectedImage = $images[array_rand($images)];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-neutral-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full">
        <div class="flex min-h-full">
            <!-- Left Panel - Auth Form -->
            <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <!-- Logo & Header -->
                    <div>
                        <x-image.logo
                            src="resources/images/brand/logo.png"
                            alt="{{ config('app.name', 'Junior') }}"
                            size="sm"
                            class="h-10 w-auto"
                            :priority="true"
                        />

                        <h2 class="mt-8 text-2xl/9 font-bold tracking-tight text-neutral-900 dark:text-white">
                            {{ $title ?? 'Bienvenido a Junior' }}
                        </h2>

                        @if(isset($subtitle))
                            <p class="mt-2 text-sm/6 text-neutral-500 dark:text-neutral-400">
                                {{ $subtitle }}
                            </p>
                        @endif
                    </div>

                    <!-- Main Content -->
                    <div class="mt-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Panel - Image -->
            <div class="relative hidden w-0 flex-1 lg:block">
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-accent-600/20 mix-blend-multiply"></div>

                <!-- Background Image -->
                <img
                    src="{{ $selectedImage['url'] }}"
                    alt="{{ $selectedImage['alt'] }}"
                    class="absolute inset-0 size-full object-cover"
                />

                <!-- Bottom Gradient -->
                <div class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-neutral-900/60 to-transparent"></div>

                <!-- Quote Section -->
                <div class="absolute bottom-12 left-12 right-12 text-white">
                    <p class="text-lg font-medium leading-relaxed">
                        "{{ $selectedQuote['text'] }}"
                    </p>
                    <p class="mt-2 text-sm text-white/80">
                        — {{ $selectedQuote['author'] }}
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
