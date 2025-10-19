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

                {{-- Textarea Básico --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Textarea - Básico</h3>

                    <x-forms.textarea
                        label="Comentario"
                        name="comment"
                        rows="4"
                        placeholder="Escribe tu comentario aquí..."
                        description="Tu comentario será visible para todo el equipo."
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.textarea
    label="Comentario"
    name="comment"
    rows="4"
    placeholder="Escribe tu comentario..."
    description="Descripción del campo..."
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Textarea con Error --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Textarea - Con Error</h3>

                    <x-forms.textarea
                        label="Descripción"
                        name="description-error"
                        rows="3"
                        value="Texto muy corto"
                        error="La descripción debe tener al menos 50 caracteres."
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.textarea
    label="Descripción"
    name="description"
    error="Mensaje de error..."
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Textarea con Corner Hint --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Textarea - Con Corner Hint</h3>

                    <x-forms.textarea
                        label="Notas adicionales"
                        name="notes"
                        rows="3"
                        placeholder="Agrega notas si es necesario..."
                        cornerHint="Opcional"
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.textarea
    label="Notas adicionales"
    name="notes"
    cornerHint="Opcional"
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Composer - Entrada de Bitácora --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Composer - Entrada de Bitácora</h3>
                    <p class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
                        Componente compuesto ideal para bitácoras, notas, notificaciones y cualquier contenido con título + descripción.
                    </p>

                    <form action="#" method="POST">
                        <x-forms.composer
                            titlePlaceholder="Título de la entrada"
                            descriptionPlaceholder="Describe lo que sucedió en tu equipo..."
                            titleName="log_title"
                            descriptionName="log_description"
                            :descriptionRows="3"
                        >
                            <x-slot name="rightActions">
                                <x-actions.button type="submit" variant="primary">
                                    Publicar en bitácora
                                </x-actions.button>
                            </x-slot>
                        </x-forms.composer>
                    </form>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.composer
    titlePlaceholder="Título"
    descriptionPlaceholder="Descripción..."
&gt;
    &lt;x-slot name="rightActions"&gt;
        &lt;x-actions.button type="submit"&gt;Publicar&lt;/x-actions.button&gt;
    &lt;/x-slot&gt;
&lt;/x-forms.composer&gt;</code></pre>
                    </div>
                </div>

                {{-- Composer - Con Acciones Adicionales --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Composer - Con Acciones Adicionales</h3>

                    <form action="#" method="POST">
                        <x-forms.composer
                            titlePlaceholder="Asunto de la notificación"
                            descriptionPlaceholder="Mensaje para el equipo..."
                            titleName="notification_title"
                            descriptionName="notification_message"
                        >
                            <x-slot name="leftActions">
                                <button type="button" class="group -my-2 -ml-2 inline-flex items-center rounded-full px-3 py-2 text-left text-neutral-400 dark:text-neutral-500">
                                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="mr-2 -ml-1 size-5 group-hover:text-neutral-500 dark:group-hover:text-white">
                                        <path d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z" clip-rule="evenodd" fill-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-neutral-500 italic group-hover:text-neutral-600 dark:text-neutral-400 dark:group-hover:text-white">Adjuntar archivo</span>
                                </button>
                            </x-slot>

                            <x-slot name="rightActions">
                                <x-actions.button type="submit" variant="primary">
                                    Enviar notificación
                                </x-actions.button>
                            </x-slot>
                        </x-forms.composer>
                    </form>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.composer&gt;
    &lt;x-slot name="leftActions"&gt;
        &lt;button&gt;Adjuntar archivo&lt;/button&gt;
    &lt;/x-slot&gt;

    &lt;x-slot name="rightActions"&gt;
        &lt;x-actions.button&gt;Enviar&lt;/x-actions.button&gt;
    &lt;/x-slot&gt;
&lt;/x-forms.composer&gt;</code></pre>
                    </div>
                </div>

                {{-- Select Básico --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Select - Básico</h3>

                    <x-forms.select
                        label="País"
                        name="country"
                        value="mx"
                        description="Selecciona tu país de residencia."
                    >
                        <x-forms.select-option value="us">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">Estados Unidos</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="mx">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">México</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="ca">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">Canadá</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="es">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">España</span>
                        </x-forms.select-option>
                    </x-forms.select>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.select label="País" name="country" value="mx"&gt;
    &lt;x-forms.select-option value="us"&gt;
        &lt;span&gt;Estados Unidos&lt;/span&gt;
    &lt;/x-forms.select-option&gt;
    &lt;x-forms.select-option value="mx"&gt;
        &lt;span&gt;México&lt;/span&gt;
    &lt;/x-forms.select-option&gt;
&lt;/x-forms.select&gt;</code></pre>
                    </div>
                </div>

                {{-- Select con Error --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Select - Con Error</h3>

                    <x-forms.select
                        label="Categoría"
                        name="category"
                        error="Debes seleccionar una categoría válida."
                    >
                        <x-forms.select-option value="1">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">Marketing</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="2">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">Desarrollo</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="3">
                            <span class="block truncate font-normal group-aria-selected/option:font-semibold">Diseño</span>
                        </x-forms.select-option>
                    </x-forms.select>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.select
    label="Categoría"
    name="category"
    error="Mensaje de error..."
&gt;
    ...opciones...
&lt;/x-forms.select&gt;</code></pre>
                    </div>
                </div>

                {{-- Select con Avatares --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Select - Con Avatares</h3>
                    <p class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
                        Componente versátil que permite contenido complejo dentro de las opciones.
                    </p>

                    <x-forms.select
                        label="Asignado a"
                        name="assigned_to"
                        value="4"
                    >
                        <x-forms.select-option value="1">
                            <img src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full" />
                            <span class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold">Wade Cooper</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="2">
                            <img src="https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full" />
                            <span class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold">Arlene Mccoy</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="3">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.25&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full" />
                            <span class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold">Devon Webb</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="4">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full" />
                            <span class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold">Tom Cook</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="5">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full" />
                            <span class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold">Tanya Fox</span>
                        </x-forms.select-option>
                    </x-forms.select>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.select label="Asignado a" name="user_id"&gt;
    &lt;x-forms.select-option value="1"&gt;
        &lt;img src="..." class="size-5 rounded-full" /&gt;
        &lt;span class="ml-3"&gt;Wade Cooper&lt;/span&gt;
    &lt;/x-forms.select-option&gt;
&lt;/x-forms.select&gt;</code></pre>
                    </div>
                </div>

                {{-- Select con Metadata --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Select - Con Metadata</h3>

                    <x-forms.select
                        label="Miembro del equipo"
                        name="team_member"
                        value="4"
                        cornerHint="Opcional"
                    >
                        <x-forms.select-option value="1">
                            <span class="truncate font-normal group-aria-selected/option:font-semibold">Wade Cooper</span>
                            <span class="ml-2 truncate text-neutral-500 group-focus/option:text-primary-200 dark:text-neutral-400 dark:group-focus/option:text-primary-100">@wadecooper</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="2">
                            <span class="truncate font-normal group-aria-selected/option:font-semibold">Arlene Mccoy</span>
                            <span class="ml-2 truncate text-neutral-500 group-focus/option:text-primary-200 dark:text-neutral-400 dark:group-focus/option:text-primary-100">@arlenemccoy</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="3">
                            <span class="truncate font-normal group-aria-selected/option:font-semibold">Devon Webb</span>
                            <span class="ml-2 truncate text-neutral-500 group-focus/option:text-primary-200 dark:text-neutral-400 dark:group-focus/option:text-primary-100">@devonwebb</span>
                        </x-forms.select-option>
                        <x-forms.select-option value="4">
                            <span class="truncate font-normal group-aria-selected/option:font-semibold">Tom Cook</span>
                            <span class="ml-2 truncate text-neutral-500 group-focus/option:text-primary-200 dark:text-neutral-400 dark:group-focus/option:text-primary-100">@tomcook</span>
                        </x-forms.select-option>
                    </x-forms.select>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.select label="Miembro del equipo"&gt;
    &lt;x-forms.select-option value="1"&gt;
        &lt;span&gt;Wade Cooper&lt;/span&gt;
        &lt;span class="text-neutral-500"&gt;@wadecooper&lt;/span&gt;
    &lt;/x-forms.select-option&gt;
&lt;/x-forms.select&gt;</code></pre>
                    </div>
                </div>

                {{-- Checkbox Individual --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Checkbox - Individual</h3>

                    <x-forms.checkbox
                        name="terms"
                        label="Acepto los términos y condiciones"
                        description="Al aceptar, confirmas que has leído nuestra política de privacidad."
                        :checked="true"
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.checkbox
    name="terms"
    label="Acepto los términos y condiciones"
    description="Descripción opcional..."
    :checked="true"
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Checkbox Simple sin Descripción --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Checkbox - Simple</h3>

                    <x-forms.checkbox
                        name="newsletter"
                        label="Suscribirse al newsletter"
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.checkbox
    name="newsletter"
    label="Suscribirse al newsletter"
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Checkbox Group --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Checkbox - Grupo</h3>
                    <p class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
                        Agrupa múltiples checkboxes relacionados con fieldset y legend.
                    </p>

                    <x-forms.checkbox-group legend="Notificaciones">
                        <x-forms.checkbox
                            name="comments"
                            label="Comentarios"
                            description="Recibe una notificación cuando alguien publique un comentario."
                            :checked="true"
                        />

                        <x-forms.checkbox
                            name="candidates"
                            label="Candidatos"
                            description="Recibe una notificación cuando un candidato solicite un trabajo."
                        />

                        <x-forms.checkbox
                            name="offers"
                            label="Ofertas"
                            description="Recibe una notificación cuando un candidato acepte o rechace una oferta."
                        />
                    </x-forms.checkbox-group>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.checkbox-group legend="Notificaciones"&gt;
    &lt;x-forms.checkbox
        name="comments"
        label="Comentarios"
        description="Descripción..."
        :checked="true"
    /&gt;
    &lt;x-forms.checkbox name="candidates" label="Candidatos" /&gt;
&lt;/x-forms.checkbox-group&gt;</code></pre>
                    </div>
                </div>

                {{-- Checkbox Disabled --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Checkbox - Disabled</h3>

                    <div class="space-y-5">
                        <x-forms.checkbox
                            name="disabled-unchecked"
                            label="Opción deshabilitada"
                            description="Esta opción no puede ser modificada."
                            disabled
                        />

                        <x-forms.checkbox
                            name="disabled-checked"
                            label="Opción deshabilitada y marcada"
                            description="Esta opción está marcada y no puede ser modificada."
                            :checked="true"
                            disabled
                        />
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.checkbox
    name="option"
    label="Opción deshabilitada"
    disabled
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Radio Básico --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Radio - Grupo Simple</h3>

                    <x-forms.radio-group
                        legend="Método de notificación"
                        description="¿Cómo prefieres recibir notificaciones?"
                    >
                        <x-forms.radio
                            name="notification_method"
                            value="email"
                            label="Email"
                            :checked="true"
                        />

                        <x-forms.radio
                            name="notification_method"
                            value="sms"
                            label="Phone (SMS)"
                        />

                        <x-forms.radio
                            name="notification_method"
                            value="push"
                            label="Push notification"
                        />
                    </x-forms.radio-group>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.radio-group legend="Método de notificación"&gt;
    &lt;x-forms.radio name="method" value="email" label="Email" /&gt;
    &lt;x-forms.radio name="method" value="sms" label="SMS" /&gt;
&lt;/x-forms.radio-group&gt;</code></pre>
                    </div>
                </div>

                {{-- Radio con Descripción --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Radio - Con Descripción</h3>

                    <x-forms.radio-group legend="Plan" srOnly>
                        <x-forms.radio
                            name="plan"
                            value="small"
                            label="Small"
                            description="4 GB RAM / 2 CPUS / 80 GB SSD Storage"
                            :checked="true"
                        />

                        <x-forms.radio
                            name="plan"
                            value="medium"
                            label="Medium"
                            description="8 GB RAM / 4 CPUS / 160 GB SSD Storage"
                        />

                        <x-forms.radio
                            name="plan"
                            value="large"
                            label="Large"
                            description="16 GB RAM / 8 CPUS / 320 GB SSD Storage"
                        />
                    </x-forms.radio-group>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.radio-group legend="Plan"&gt;
    &lt;x-forms.radio
        name="plan"
        value="small"
        label="Small"
        description="4 GB RAM / 2 CPUS..."
    /&gt;
&lt;/x-forms.radio-group&gt;</code></pre>
                    </div>
                </div>

                {{-- Toggle Básico --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Toggle - Básico</h3>

                    <x-forms.toggle
                        name="available"
                        label="Available to hire"
                        description="Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia."
                    />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.toggle
    name="available"
    label="Available to hire"
    description="Descripción opcional..."
/&gt;</code></pre>
                    </div>
                </div>

                {{-- Toggle Activado --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Toggle - Activado</h3>

                    <div class="space-y-4">
                        <x-forms.toggle
                            name="notifications"
                            label="Notificaciones push"
                            description="Recibe notificaciones en tiempo real."
                            :checked="true"
                        />

                        <x-forms.toggle
                            name="newsletter"
                            label="Newsletter semanal"
                        />
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-forms.toggle
    name="notifications"
    label="Notificaciones"
    :checked="true"
/&gt;</code></pre>
                    </div>
                </div>
            </div>
        </section>

        {{-- Data Display Section --}}
        <section id="data-display">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Data Display</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Badges, stats, tablas, feeds, description lists, etc.
            </p>

            <div class="mt-6 space-y-8">
                {{-- Badge Simple --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Badge - Simple</h3>

                    <div class="flex flex-wrap gap-2">
                        <x-data-display.badge>Badge</x-data-display.badge>
                        <x-data-display.badge>Badge 2</x-data-display.badge>
                        <x-data-display.badge>Badge 3</x-data-display.badge>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-data-display.badge&gt;Badge&lt;/x-data-display.badge&gt;</code></pre>
                    </div>
                </div>

                {{-- Badge con Dot --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Badge - Con Dot (Indicador de Color)</h3>

                    <div class="flex flex-wrap gap-2">
                        <x-data-display.badge color="red" :dot="true">Cancelado</x-data-display.badge>
                        <x-data-display.badge color="yellow" :dot="true">Pendiente</x-data-display.badge>
                        <x-data-display.badge color="green" :dot="true">Activo</x-data-display.badge>
                        <x-data-display.badge color="blue" :dot="true">Info</x-data-display.badge>
                        <x-data-display.badge color="primary" :dot="true">Destacado</x-data-display.badge>
                        <x-data-display.badge color="purple" :dot="true">Nuevo</x-data-display.badge>
                        <x-data-display.badge color="pink" :dot="true">Hot</x-data-display.badge>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-data-display.badge color="green" :dot="true"&gt;
    Activo
&lt;/x-data-display.badge&gt;</code></pre>
                    </div>
                </div>

                {{-- Badge Estados --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Badge - Casos de Uso Comunes</h3>
                    <p class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
                        Ejemplos de badges para diferentes estados y categorías.
                    </p>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-neutral-700 mb-2 dark:text-neutral-300">Estados de tarea:</p>
                            <div class="flex flex-wrap gap-2">
                                <x-data-display.badge color="neutral" :dot="true">Draft</x-data-display.badge>
                                <x-data-display.badge color="yellow" :dot="true">En progreso</x-data-display.badge>
                                <x-data-display.badge color="blue" :dot="true">En revisión</x-data-display.badge>
                                <x-data-display.badge color="green" :dot="true">Completado</x-data-display.badge>
                                <x-data-display.badge color="red" :dot="true">Rechazado</x-data-display.badge>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-neutral-700 mb-2 dark:text-neutral-300">Categorías:</p>
                            <div class="flex flex-wrap gap-2">
                                <x-data-display.badge>Marketing</x-data-display.badge>
                                <x-data-display.badge>Desarrollo</x-data-display.badge>
                                <x-data-display.badge>Diseño</x-data-display.badge>
                                <x-data-display.badge>Ventas</x-data-display.badge>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>{{-- Estado de tarea --}}
&lt;x-data-display.badge color="green" :dot="true"&gt;
    Completado
&lt;/x-data-display.badge&gt;

{{-- Categoría --}}
&lt;x-data-display.badge&gt;Marketing&lt;/x-data-display.badge&gt;</code></pre>
                    </div>
                </div>
            </div>
        </section>

        {{-- Feedback Section --}}
        <section id="feedback">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Feedback</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Alertas, notificaciones, empty states, loading states, etc.
            </p>

            <div class="mt-6 space-y-8">
                {{-- Alert Success - Simple --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Alert - Success Simple</h3>

                    <x-feedback.alert type="success" title="Successfully uploaded" />

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-feedback.alert type="success" title="Successfully uploaded" /&gt;</code></pre>
                    </div>
                </div>

                {{-- Alert Warning - Con Descripción --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Alert - Warning con Descripción</h3>

                    <x-feedback.alert type="warning" title="Attention needed">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid pariatur, ipsum similique veniam quo totam eius aperiam dolorum.</p>
                    </x-feedback.alert>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-feedback.alert type="warning" title="Attention needed"&gt;
    &lt;p&gt;Descripción del problema...&lt;/p&gt;
&lt;/x-feedback.alert&gt;</code></pre>
                    </div>
                </div>

                {{-- Alert Error - Con Lista --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Alert - Error con Lista</h3>

                    <x-feedback.alert type="error" title="There were 2 errors with your submission">
                        <ul role="list" class="list-disc space-y-1 pl-5">
                            <li>Your password must be at least 8 characters</li>
                            <li>Your password must include at least one pro wrestling finishing move</li>
                        </ul>
                    </x-feedback.alert>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-feedback.alert type="error" title="There were 2 errors..."&gt;
    &lt;ul role="list" class="list-disc space-y-1 pl-5"&gt;
        &lt;li&gt;Error 1&lt;/li&gt;
        &lt;li&gt;Error 2&lt;/li&gt;
    &lt;/ul&gt;
&lt;/x-feedback.alert&gt;</code></pre>
                    </div>
                </div>

                {{-- Alert Info --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Alert - Info</h3>

                    <x-feedback.alert type="info" title="Nueva actualización disponible">
                        <p>Una nueva versión del sistema está disponible. Por favor actualiza para obtener las últimas mejoras.</p>
                    </x-feedback.alert>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-feedback.alert type="info" title="Nueva actualización..."&gt;
    &lt;p&gt;Descripción...&lt;/p&gt;
&lt;/x-feedback.alert&gt;</code></pre>
                    </div>
                </div>

                {{-- Alert No Dismissible --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Alert - Sin Botón Dismiss</h3>

                    <x-feedback.alert type="success" title="Cambios guardados permanentemente" :dismissible="false">
                        <p>Esta notificación no puede ser cerrada por el usuario.</p>
                    </x-feedback.alert>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>&lt;x-feedback.alert type="success" title="..." :dismissible="false"&gt;
    &lt;p&gt;Contenido...&lt;/p&gt;
&lt;/x-feedback.alert&gt;</code></pre>
                    </div>
                </div>

                {{-- Toast Notifications --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Toast - Notificaciones Temporales</h3>
                    <p class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
                        Los toasts aparecen automáticamente en la esquina superior derecha y se ocultan después de unos segundos.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <x-actions.button variant="primary" onclick="showToast({ type: 'success', title: 'Operación exitosa', description: 'Los cambios se han guardado correctamente.' })">
                            Success Toast
                        </x-actions.button>

                        <x-actions.button variant="danger" onclick="showToast({ type: 'error', title: 'Error al procesar', description: 'No se pudo completar la operación. Por favor intenta de nuevo.', autoHide: false })">
                            Error Toast
                        </x-actions.button>

                        <x-actions.button variant="secondary" onclick="showToast({ type: 'warning', title: 'Atención requerida', description: 'Por favor revisa los campos marcados.', duration: 7000 })">
                            Warning Toast
                        </x-actions.button>

                        <x-actions.button variant="ghost" onclick="showToast({ type: 'info', title: 'Nueva versión disponible' })">
                            Info Toast
                        </x-actions.button>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>// JavaScript - Mostrar toast
window.showToast({
    type: 'success',
    title: 'Operación exitosa',
    description: 'Los cambios se han guardado.',
    autoHide: true,
    duration: 5000
});

// PHP - Flash message (se convierte automáticamente en toast)
return redirect()-&gt;route('dashboard')
    -&gt;with('success', 'Tarea creada exitosamente');</code></pre>
                    </div>
                </div>

                {{-- Toast con Acciones --}}
                <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h3 class="mb-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">Toast - Con Acciones</h3>

                    <div class="flex flex-wrap gap-3">
                        <x-actions.button variant="primary" onclick="showToast({
                            type: 'info',
                            title: 'Discusión movida',
                            description: 'La discusión se ha movido a otro proyecto.',
                            autoHide: false,
                            actions: [
                                {
                                    label: 'Deshacer',
                                    primary: true,
                                    onClick: () => { console.log('Undo clicked'); },
                                    dismiss: true
                                },
                                {
                                    label: 'Cerrar',
                                    primary: false,
                                    onClick: () => {},
                                    dismiss: true
                                }
                            ]
                        })">
                            Toast con Acciones
                        </x-actions.button>

                        <x-actions.button variant="secondary" onclick="showToast({
                            type: 'success',
                            title: 'Archivo subido',
                            showIcon: false,
                            description: 'El archivo se ha procesado correctamente.'
                        })">
                            Toast sin Ícono
                        </x-actions.button>
                    </div>

                    <div class="mt-6 rounded-md bg-neutral-50 p-4 dark:bg-neutral-900">
                        <pre class="text-xs text-neutral-800 dark:text-neutral-200"><code>window.showToast({
    type: 'info',
    title: 'Discusión movida',
    description: 'La discusión se ha movido...',
    autoHide: false,
    actions: [
        {
            label: 'Deshacer',
            primary: true,
            onClick: () => { /* acción */ },
            dismiss: true
        }
    ]
});</code></pre>
                    </div>
                </div>
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
