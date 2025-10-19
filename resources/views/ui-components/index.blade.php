<x-dashboard-layout title="UI Components">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-8 border-b border-neutral-200 pb-5 dark:border-white/10">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">UI Components</h1>
        <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-300">
            Biblioteca de componentes reutilizables del sistema Junior
        </p>
    </div>

    {{-- Navigation Tabs --}}
    <div class="mb-8">
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Selecciona una categoría</label>
            <select id="tabs" name="tabs" class="block w-full rounded-md border-neutral-300 focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                <option selected>Headings</option>
                <option>Forms</option>
                <option>Data Display</option>
                <option>Feedback</option>
                <option>Actions</option>
            </select>
        </div>
        <div class="hidden sm:block">
            <nav class="flex space-x-4" aria-label="Tabs">
                <a href="#headings" class="rounded-md bg-primary-100 px-3 py-2 text-sm font-medium text-primary-700 dark:bg-primary-900/20 dark:text-primary-400">Headings</a>
                <a href="#forms" class="rounded-md px-3 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-white">Forms</a>
                <a href="#data-display" class="rounded-md px-3 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-white">Data Display</a>
                <a href="#feedback" class="rounded-md px-3 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-white">Feedback</a>
                <a href="#actions" class="rounded-md px-3 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-white">Actions</a>
            </nav>
        </div>
    </div>

    {{-- Content Sections --}}
    <div class="space-y-12">
        {{-- Headings Section --}}
        <section id="headings">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Headings</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Jerarquía de títulos para estructurar páginas y secciones.
            </p>

            <div class="mt-6 space-y-8">
                {{-- Page Heading Simple --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Page Heading - Simple</h3>

                    <x-headings.page title="Back End Developer" />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <code class="text-xs text-neutral-800 dark:text-neutral-200">&lt;x-headings.page title="Back End Developer" /&gt;</code>
                    </div>
                </div>

                {{-- Page Heading con Breadcrumb y Acciones --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Page Heading - Con Breadcrumb y Acciones</h3>

                    <x-headings.page title="Back End Developer">
                        <x-slot name="breadcrumb">
                            <x-headings.breadcrumb-item href="#" first>Jobs</x-headings.breadcrumb-item>
                            <x-headings.breadcrumb-item href="#">Engineering</x-headings.breadcrumb-item>
                            <x-headings.breadcrumb-item href="#" :current="true">Back End Developer</x-headings.breadcrumb-item>
                        </x-slot>

                        <x-slot name="actions">
                            <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-xs inset-ring inset-ring-neutral-300 hover:bg-neutral-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">
                                Editar
                            </button>
                            <button type="button" class="ml-3 inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-primary-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:bg-primary-500 dark:shadow-none dark:hover:bg-primary-400 dark:focus-visible:outline-primary-500">
                                Publicar
                            </button>
                        </x-slot>
                    </x-headings.page>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-headings.page title="Back End Developer"&gt;
    &lt;x-slot name="breadcrumb"&gt;
        &lt;x-headings.breadcrumb-item href="#" first&gt;Jobs&lt;/x-headings.breadcrumb-item&gt;
        &lt;x-headings.breadcrumb-item href="#"&gt;Engineering&lt;/x-headings.breadcrumb-item&gt;
        &lt;x-headings.breadcrumb-item :current="true"&gt;Back End Developer&lt;/x-headings.breadcrumb-item&gt;
    &lt;/x-slot&gt;

    &lt;x-slot name="actions"&gt;
        &lt;button&gt;Editar&lt;/button&gt;
        &lt;button&gt;Publicar&lt;/button&gt;
    &lt;/x-slot&gt;
&lt;/x-headings.page&gt;</code></pre>
                    </div>
                </div>

                {{-- Card Heading --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Card Heading - Con Acción</h3>

                    <x-headings.card title="Job Postings">
                        <x-slot name="action">
                            <button type="button" class="relative inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-primary-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:bg-primary-500 dark:shadow-none dark:hover:bg-primary-400 dark:focus-visible:outline-primary-500">
                                Crear nuevo trabajo
                            </button>
                        </x-slot>
                    </x-headings.card>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-headings.card title="Job Postings"&gt;
    &lt;x-slot name="action"&gt;
        &lt;button&gt;Crear nuevo trabajo&lt;/button&gt;
    &lt;/x-slot&gt;
&lt;/x-headings.card&gt;</code></pre>
                    </div>
                </div>

                {{-- Section Heading --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Section Heading - Con Descripción</h3>

                    <x-headings.section
                        title="Job Postings"
                        description="Workcation es un sitio web de alquiler de propiedades. Gestiona las publicaciones de trabajo y las aplicaciones de los candidatos."
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-headings.section
    title="Job Postings"
    description="Descripción de la sección..."
/&gt;</code></pre>
                    </div>
                </div>
            </div>
        </section>
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Headings</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Jerarquía de títulos para estructurar páginas y secciones.
            </p>

            <div class="mt-6 space-y-6">
                {{-- Page Heading Example --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Page Heading</h3>
                    <div class="space-y-4">
                        <h1 class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">Page Title</h1>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Descripción de la página</p>
                    </div>
                    <div class="mt-4 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <code class="text-xs text-neutral-800 dark:text-neutral-200">&lt;h1 class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-white"&gt;Page Title&lt;/h1&gt;</code>
                    </div>
                </div>

                {{-- Card Heading Example --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Card Heading</h3>
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Card Title</h2>
                    <div class="mt-4 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <code class="text-xs text-neutral-800 dark:text-neutral-200">&lt;h2 class="text-xl font-semibold text-neutral-900 dark:text-white"&gt;Card Title&lt;/h2&gt;</code>
                    </div>
                </div>

                {{-- Section Heading Example --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Section Heading</h3>
                    <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Section Title</h3>
                    <div class="mt-4 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <code class="text-xs text-neutral-800 dark:text-neutral-200">&lt;h3 class="text-base font-semibold text-neutral-900 dark:text-white"&gt;Section Title&lt;/h3&gt;</code>
                    </div>
                </div>
            </div>
        </section>

        {{-- Forms Section --}}
        <section id="forms">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Forms</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Componentes de formulario: inputs, selects, textareas, etc.
            </p>

            <div class="mt-6 space-y-8">
                {{-- Input Básico --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Input - Básico</h3>

                    <x-forms.input
                        label="Email"
                        name="email"
                        type="email"
                        placeholder="tu@ejemplo.com"
                        description="Usaremos este email para enviarte notificaciones."
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.input
    label="Email"
    name="email"
    type="email"
    placeholder="tu@ejemplo.com"
    description="Descripción del campo..."
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Input con Corner Hint --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Input - Con Corner Hint</h3>

                    <x-forms.input
                        label="Email"
                        name="email-optional"
                        type="email"
                        placeholder="tu@ejemplo.com"
                        cornerHint="Opcional"
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.input
    label="Email"
    name="email"
    cornerHint="Opcional"
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Input con Error --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Input - Con Error</h3>

                    <x-forms.input
                        label="Email"
                        name="email-error"
                        type="email"
                        value="adamwathan"
                        placeholder="tu@ejemplo.com"
                        error="No es una dirección de email válida."
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.input
    label="Email"
    name="email"
    error="Mensaje de error..."
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Input con Leading Addon --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Input - Con Leading Addon</h3>

                    <x-forms.input
                        label="Sitio web de la empresa"
                        name="company-website"
                        type="text"
                        placeholder="www.ejemplo.com"
                    >
                        <x-slot name="leadingAddon">https://</x-slot>
                    </x-forms.input>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.input label="Sitio web" name="website"&gt;
    &lt;x-slot name="leadingAddon"&gt;https://&lt;/x-slot&gt;
&lt;/x-forms.input&gt;</code></pre>
                    </div>
                </div>

                {{-- Input con Inline Addons --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Input - Con Inline Addons</h3>

                    <x-forms.input
                        label="Precio"
                        name="price"
                        type="text"
                        placeholder="0.00"
                    >
                        <x-slot name="leadingInline">$</x-slot>
                        <x-slot name="trailingInline">USD</x-slot>
                    </x-forms.input>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.input label="Precio" name="price"&gt;
    &lt;x-slot name="leadingInline"&gt;$&lt;/x-slot&gt;
    &lt;x-slot name="trailingInline"&gt;USD&lt;/x-slot&gt;
&lt;/x-forms.input&gt;</code></pre>
                    </div>
                </div>
            </div>
        </section>

        {{-- Data Display Section (Placeholder) --}}
        <section id="data-display">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Data Display</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Stats, tablas, feeds, description lists, etc.
            </p>
            <div class="mt-6 rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Componentes en desarrollo...</p>
            </div>
        </section>

        {{-- Feedback Section (Placeholder) --}}
        <section id="feedback">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Feedback</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Alertas, empty states, loading states, etc.
            </p>
            <div class="mt-6 rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Componentes en desarrollo...</p>
            </div>
        </section>

        {{-- Actions Section --}}
        <section id="actions">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Actions</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Botones, dropdowns, action panels, etc.
            </p>

            <div class="mt-6 space-y-8">
                {{-- Button Variants --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Button - Variantes</h3>

                    <div class="flex flex-wrap gap-3">
                        <x-actions.button variant="primary">Primary</x-actions.button>
                        <x-actions.button variant="secondary">Secondary</x-actions.button>
                        <x-actions.button variant="danger">Danger</x-actions.button>
                        <x-actions.button variant="ghost">Ghost</x-actions.button>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-actions.button variant="primary"&gt;Primary&lt;/x-actions.button&gt;
&lt;x-actions.button variant="secondary"&gt;Secondary&lt;/x-actions.button&gt;
&lt;x-actions.button variant="danger"&gt;Danger&lt;/x-actions.button&gt;
&lt;x-actions.button variant="ghost"&gt;Ghost&lt;/x-actions.button&gt;</code></pre>
                    </div>
                </div>

                {{-- Button Sizes --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Button - Tamaños</h3>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-actions.button size="sm">Small</x-actions.button>
                        <x-actions.button size="md">Medium</x-actions.button>
                        <x-actions.button size="lg">Large</x-actions.button>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-actions.button size="sm"&gt;Small&lt;/x-actions.button&gt;
&lt;x-actions.button size="md"&gt;Medium&lt;/x-actions.button&gt;
&lt;x-actions.button size="lg"&gt;Large&lt;/x-actions.button&gt;</code></pre>
                    </div>
                </div>

                {{-- Button States --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Button - Estados</h3>

                    <div class="flex flex-wrap gap-3">
                        <x-actions.button>Normal</x-actions.button>
                        <x-actions.button :disabled="true">Disabled</x-actions.button>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-actions.button&gt;Normal&lt;/x-actions.button&gt;
&lt;x-actions.button :disabled="true"&gt;Disabled&lt;/x-actions.button&gt;</code></pre>
                    </div>
                </div>

                {{-- Button con Iconos --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Button - Con Iconos</h3>

                    <div class="flex flex-wrap gap-3">
                        <x-actions.button>
                            <svg viewBox="0 0 20 20" fill="currentColor" class="-ml-0.5 mr-1.5 size-5">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                            </svg>
                            Crear nuevo
                        </x-actions.button>

                        <x-actions.button variant="secondary">
                            Descargar
                            <svg viewBox="0 0 20 20" fill="currentColor" class="-mr-0.5 ml-1.5 size-5">
                                <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                                <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                            </svg>
                        </x-actions.button>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-actions.button&gt;
    &lt;svg&gt;...&lt;/svg&gt;
    Crear nuevo
&lt;/x-actions.button&gt;</code></pre>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-dashboard-layout>
