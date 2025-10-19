# Sistema de Diseño: Junior

**Versión:** 1.0
**Fecha:** 2025-10-19
**Filosofía:** Profesionalismo moderno con toques de innovación. Diseño empresarial que inspira confianza y productividad.

---

## 1. Paleta de Colores

El sistema utiliza una paleta dual de **azules corporativos** y **rosas vibrantes** que se complementan para crear una identidad visual moderna y profesional. Los gradientes son fundamentales en la identidad visual.

### Colores Primarios (Azul Corporativo - "Slate Blue")

Usados para navegación, headers, elementos principales de UI y acciones primarias.

```css
/* Azul Corporativo */
--primary-50: #f0f4ff
--primary-100: #e0eaff
--primary-200: #c7d7fe
--primary-300: #a5b9fc
--primary-400: #8191f8
--primary-500: #6366f1  /* Color principal */
--primary-600: #4f46e5
--primary-700: #4338ca
--primary-800: #3730a3
--primary-900: #312e81
--primary-950: #1e1b4b
```

![primary-500](https://img.shields.io/badge/Primary%20500-6366f1-6366f1?style=for-the-badge&logoColor=white)

### Colores de Acento (Rosa Empresarial - "Rose")

Usados para CTAs destacados, notificaciones importantes y elementos que requieren atención.

```css
/* Rosa Empresarial */
--accent-50: #fff1f2
--accent-100: #ffe4e6
--accent-200: #fecdd3
--accent-300: #fda4af
--accent-400: #fb7185
--accent-500: #f43f5e  /* Color de acento principal */
--accent-600: #e11d48
--accent-700: #be123c
--accent-800: #9f1239
--accent-900: #881337
--accent-950: #4c0519
```

![accent-500](https://img.shields.io/badge/Accent%20500-f43f5e-f43f5e?style=for-the-badge&logoColor=white)

### Colores Neutrales (Slate Gray)

Base para fondos, textos y elementos de soporte.

```css
/* Neutrales */
--neutral-50: #f8fafc
--neutral-100: #f1f5f9
--neutral-200: #e2e8f0
--neutral-300: #cbd5e1
--neutral-400: #94a3b8
--neutral-500: #64748b
--neutral-600: #475569
--neutral-700: #334155
--neutral-800: #1e293b
--neutral-900: #0f172a
--neutral-950: #020617
```

### Colores Semánticos

Mensajes de sistema, estados y feedback.

* **Éxito (`success`):** ![Success](https://img.shields.io/badge/Success-10b981-10b981?style=for-the-badge&logoColor=white) `#10b981` (Emerald 500)
* **Peligro (`danger`):** ![Danger](https://img.shields.io/badge/Danger-ef4444-ef4444?style=for-the-badge&logoColor=white) `#ef4444` (Red 500)
* **Advertencia (`warning`):** ![Warning](https://img.shields.io/badge/Warning-f59e0b-f59e0b?style=for-the-badge&logoColor=white) `#f59e0b` (Amber 500)
* **Información (`info`):** ![Info](https://img.shields.io/badge/Info-3b82f6-3b82f6?style=for-the-badge&logoColor=white) `#3b82f6` (Blue 500)

### Gradientes Empresariales

Los gradientes son parte fundamental de la identidad visual de Junior.

#### Gradiente Principal (Azul a Rosa)
```css
background: linear-gradient(135deg, #6366f1 0%, #f43f5e 100%);
/* Tailwind: bg-gradient-to-br from-primary-500 to-accent-500 */
```

#### Gradiente Sutil (Azul Claro)
```css
background: linear-gradient(135deg, #e0eaff 0%, #f0f4ff 100%);
/* Para fondos de secciones */
```

#### Gradiente de Header
```css
background: linear-gradient(90deg, #312e81 0%, #4338ca 50%, #881337 100%);
/* Para headers principales y hero sections */
```

#### Gradiente de Botón Primario
```css
background: linear-gradient(135deg, #6366f1 0%, #8191f8 100%);
/* Hover: background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); */
```

### Uso en Tema Claro vs. Oscuro

| Uso Semántico            | Modo Claro (`light`)                    | Modo Oscuro (`dark`)                       |
| ------------------------ | --------------------------------------- | ------------------------------------------ |
| **Fondo Principal**      | `bg-neutral-50`                         | `bg-neutral-900`                           |
| **Fondo Tarjetas**       | `bg-white`                              | `bg-neutral-800`                           |
| **Texto Principal**      | `text-neutral-900`                      | `text-neutral-50`                          |
| **Texto Secundario**     | `text-neutral-600`                      | `text-neutral-400`                         |
| **Botón Primario**       | `bg-primary-600 text-white`             | `bg-primary-500 text-white`                |
| **Botón Acento**         | `bg-accent-600 text-white`              | `bg-accent-500 text-white`                 |
| **Bordes**               | `border-neutral-200`                    | `border-neutral-700`                       |
| **Hover Elementos**      | `hover:bg-neutral-100`                  | `hover:bg-neutral-800`                     |
| **Sidebar**              | `bg-white border-r border-neutral-200` | `bg-neutral-900 border-r border-neutral-800` |

---

## 2. Tipografía

Fuentes profesionales y legibles que transmiten confianza y modernidad.

* **Fuente Principal:** **Inter** (Sans-serif moderna y altamente legible, ideal para UIs empresariales)
* **Fuente Monoespaciada:** **JetBrains Mono** (Para códigos, datos numéricos y tablas)

### Importación de Fuentes

```html
<!-- En el head de la aplicación -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
```

### Escala Tipográfica

* **h1:** `text-4xl font-bold` (36px - 900 weight) - Títulos de páginas principales
* **h2:** `text-3xl font-bold` (30px - 700 weight) - Títulos de secciones
* **h3:** `text-2xl font-semibold` (24px - 600 weight) - Sub-secciones
* **h4:** `text-xl font-semibold` (20px - 600 weight) - Tarjetas y componentes
* **h5:** `text-lg font-medium` (18px - 500 weight) - Labels importantes
* **p (body):** `text-base font-normal` (16px - 400 weight) - Texto general
* **small:** `text-sm font-normal` (14px - 400 weight) - Texto secundario
* **caption:** `text-xs font-medium` (12px - 500 weight) - Metadata, badges

### Pesos de Fuente

* **Light (300):** Textos decorativos
* **Regular (400):** Texto de párrafos
* **Medium (500):** Labels, navegación
* **Semibold (600):** Subtítulos
* **Bold (700):** Títulos importantes
* **Extrabold (800):** Headers principales
* **Black (900):** Hero text, números destacados

---

## 3. Espaciado y Rejilla (Grid)

Basado en múltiplos de **4px** para consistencia visual.

### Escala de Espaciado

* `p-1`, `m-1`: 4px
* `p-2`, `m-2`: 8px
* `p-3`, `m-3`: 12px
* `p-4`, `m-4`: 16px
* `p-5`, `m-5`: 20px
* `p-6`, `m-6`: 24px
* `p-8`, `m-8`: 32px
* `p-10`, `m-10`: 40px
* `p-12`, `m-12`: 48px
* `p-16`, `m-16`: 64px
* `p-20`, `m-20`: 80px

### Sistema de Grid

* **Columnas:** 12 columnas (sistema Tailwind estándar)
* **Gutters:** 16px - 24px (variable según breakpoint)
* **Container Max Width:**
  - Mobile: 100%
  - Tablet: 768px
  - Desktop: 1024px
  - Large: 1280px
  - XL: 1536px

### Breakpoints

```css
/* Mobile first approach */
sm: 640px    /* Teléfonos grandes */
md: 768px    /* Tablets */
lg: 1024px   /* Desktop pequeño */
xl: 1280px   /* Desktop estándar */
2xl: 1536px  /* Pantallas grandes */
```

---

## 4. Componentes Clave

### 4.1 Botones

#### Primario (Acciones principales)
**Uso:** Guardar, Crear, Enviar, Confirmar

```html
<button class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium rounded-lg shadow-md hover:from-primary-700 hover:to-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
    Guardar Cambios
</button>
```

#### Acento (CTAs destacados)
**Uso:** Acciones importantes que requieren atención inmediata

```html
<button class="px-6 py-2.5 bg-gradient-to-r from-accent-600 to-accent-500 text-white font-medium rounded-lg shadow-md hover:from-accent-700 hover:to-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 transition-all duration-200">
    Crear Nueva Campaña
</button>
```

#### Secundario (Acciones alternativas)
**Uso:** Cancelar, Volver, Ver más

```html
<button class="px-6 py-2.5 bg-white border-2 border-neutral-300 text-neutral-700 font-medium rounded-lg hover:bg-neutral-50 hover:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700">
    Cancelar
</button>
```

#### Texto/Ghost (Acciones terciarias)
**Uso:** Links, acciones secundarias sin borde

```html
<button class="px-4 py-2 text-primary-600 font-medium hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-all duration-200 dark:text-primary-400 dark:hover:bg-neutral-800">
    Ver Detalles
</button>
```

#### Destructivo (Acciones peligrosas)
**Uso:** Eliminar, Desactivar

```html
<button class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
    Eliminar Usuario
</button>
```

#### Tamaños de Botones

```html
<!-- Pequeño -->
<button class="px-3 py-1.5 text-sm ...">Pequeño</button>

<!-- Normal (por defecto) -->
<button class="px-6 py-2.5 text-base ...">Normal</button>

<!-- Grande -->
<button class="px-8 py-3 text-lg ...">Grande</button>
```

---

### 4.2 Inputs de Formulario

#### Input General

```html
<div>
    <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
        Correo Electrónico
    </label>
    <input
        type="email"
        id="email"
        class="w-full px-4 py-2.5 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-900 dark:text-neutral-100 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
        placeholder="usuario@empresa.com"
    >
</div>
```

#### Estado de Enfoque (Focus)

```css
focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none
```

#### Estado de Error

```html
<div>
    <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
        Contraseña
    </label>
    <input
        type="password"
        id="password"
        class="w-full px-4 py-2.5 bg-white dark:bg-neutral-800 border-2 border-red-500 text-neutral-900 dark:text-neutral-100 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
    >
    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">
        La contraseña debe tener al menos 8 caracteres
    </p>
</div>
```

#### Estado Deshabilitado

```css
disabled:bg-neutral-100 disabled:text-neutral-400 disabled:cursor-not-allowed disabled:border-neutral-200
dark:disabled:bg-neutral-900 dark:disabled:border-neutral-800
```

#### Select / Dropdown

```html
<select class="w-full px-4 py-2.5 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-900 dark:text-neutral-100 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
    <option>Seleccionar área...</option>
    <option>Producción</option>
    <option>Marketing</option>
    <option>Finanzas</option>
</select>
```

---

### 4.3 Tarjetas (Cards)

#### Tarjeta Estándar

```html
<div class="bg-white dark:bg-neutral-800 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-neutral-200 dark:border-neutral-700">
    <h3 class="text-xl font-semibold text-neutral-900 dark:text-neutral-100 mb-2">
        Título de la Tarjeta
    </h3>
    <p class="text-neutral-600 dark:text-neutral-400">
        Contenido de la tarjeta con información relevante para el usuario.
    </p>
</div>
```

#### Tarjeta con Gradiente (Destacada)

```html
<div class="bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl shadow-lg p-6 text-white">
    <h3 class="text-2xl font-bold mb-2">
        Panel de Control
    </h3>
    <p class="text-primary-50">
        Acceso rápido a las métricas más importantes
    </p>
</div>
```

#### Tarjeta con Borde de Acento

```html
<div class="bg-white dark:bg-neutral-800 rounded-xl shadow-md p-6 border-l-4 border-accent-500">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <!-- Icon SVG -->
        </div>
        <div class="ml-4">
            <h4 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Notificación Importante
            </h4>
            <p class="mt-1 text-neutral-600 dark:text-neutral-400">
                Contenido del mensaje
            </p>
        </div>
    </div>
</div>
```

#### Tarjeta Interactiva (Clickeable)

```html
<a href="#" class="block bg-white dark:bg-neutral-800 rounded-xl shadow-md hover:shadow-xl p-6 border border-neutral-200 dark:border-neutral-700 hover:border-primary-500 dark:hover:border-primary-500 transition-all duration-300 group">
    <h4 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
        Ver Detalles →
    </h4>
</a>
```

---

### 4.4 Badges/Etiquetas

#### Badge Primario

```html
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300">
    Activo
</span>
```

#### Badge de Estado

```html
<!-- Éxito -->
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
    Completado
</span>

<!-- Advertencia -->
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
    Pendiente
</span>

<!-- Peligro -->
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
    Cancelado
</span>

<!-- Info -->
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
    En Revisión
</span>
```

#### Badge con Gradiente

```html
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-primary-500 to-accent-500 text-white">
    Premium
</span>
```

---

### 4.5 Modales/Diálogos

#### Estructura Completa de Modal

```html
<!-- Overlay -->
<div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm z-40 transition-opacity"></div>

<!-- Modal Container -->
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <!-- Modal Content -->
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-neutral-800 shadow-2xl transition-all w-full max-w-lg">

            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-white">
                        Título del Modal
                    </h3>
                    <button class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-4">
                <p class="text-neutral-700 dark:text-neutral-300">
                    Contenido del modal con información relevante.
                </p>
            </div>

            <!-- Footer -->
            <div class="bg-neutral-50 dark:bg-neutral-900 px-6 py-4 flex justify-end gap-3">
                <button class="px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    Cancelar
                </button>
                <button class="px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-lg hover:from-primary-700 hover:to-primary-600 transition-all">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>
```

---

### 4.6 Notificaciones/Alertas

#### Alerta de Éxito

```html
<div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-emerald-800 dark:text-emerald-200">
                Operación exitosa
            </h3>
            <p class="mt-1 text-sm text-emerald-700 dark:text-emerald-300">
                Los cambios se han guardado correctamente.
            </p>
        </div>
    </div>
</div>
```

#### Alerta de Error

```html
<div class="rounded-lg bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                Error al procesar
            </h3>
            <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                Por favor verifica los datos ingresados.
            </p>
        </div>
    </div>
</div>
```

#### Notificación Toast (Flotante)

```html
<div class="fixed top-4 right-4 z-50 transform transition-all duration-300">
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl border border-neutral-200 dark:border-neutral-700 p-4 max-w-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                    Tarea completada
                </p>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    La tarea ha sido marcada como finalizada.
                </p>
            </div>
            <button class="ml-4 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
```

---

### 4.7 Tablas

#### Tabla Empresarial Moderna

```html
<div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700 shadow-md">
    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
        <!-- Header -->
        <thead class="bg-neutral-50 dark:bg-neutral-900">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                    Nombre
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                    Rol
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                    Estado
                </th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </thead>
        <!-- Body -->
        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-semibold">
                                CM
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                Carlos Mendoza
                            </div>
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                director@junior.com
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-neutral-900 dark:text-neutral-100">Dirección General</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                        Activo
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">
                        Editar
                    </button>
                    <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                        Eliminar
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 5. Iconografía

* **Librería de Iconos:** **Heroicons** (diseñados por el equipo de Tailwind CSS)
* **Tamaño por defecto:** 24px (`w-6 h-6`)
* **Estilo:** Outline para UI general, Solid para estados activos
* **Color:** Hereda del texto padre o usa colores específicos

### Iconos Comunes

| Acción/Contexto  | Icono                | Uso                                      |
| ---------------- | -------------------- | ---------------------------------------- |
| Cerrar           | `XMarkIcon`          | Cerrar modales, notificaciones           |
| Búsqueda         | `MagnifyingGlassIcon`| Campo de búsqueda                        |
| Usuario          | `UserIcon`           | Perfil de usuario, gestión RRHH          |
| Configuración    | `Cog6ToothIcon`      | Acceso a ajustes                         |
| Notificación     | `BellIcon`           | Centro de notificaciones                 |
| Calendario       | `CalendarDaysIcon`   | Disponibilidad, eventos                  |
| Dashboard        | `ChartBarIcon`       | Panel de control, métricas               |
| Tareas           | `CheckCircleIcon`    | Lista de tareas, completado              |
| Documentos       | `DocumentTextIcon`   | Cotizaciones, presupuestos               |
| Finanzas         | `BanknotesIcon`      | Módulo financiero                        |
| Marketing        | `MegaphoneIcon`      | Campañas, leads                          |
| Producción       | `WrenchScrewdriverIcon` | Área de producción                   |
| Añadir           | `PlusIcon`           | Crear nuevo elemento                     |
| Editar           | `PencilSquareIcon`   | Modificar registros                      |
| Eliminar         | `TrashIcon`          | Borrar elementos                         |
| Exportar         | `ArrowDownTrayIcon`  | Descargar reportes                       |

### Uso de Iconos

```html
<!-- Heroicons via CDN o instalación -->
<svg class="w-6 h-6 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
</svg>
```

---

## 6. Sombras (Shadows)

Las sombras crean profundidad y jerarquía visual.

* **Ninguna:** `shadow-none` - Elementos integrados al fondo
* **Sutil:** `shadow-sm` - Inputs, elementos ligeramente elevados
* **Normal:** `shadow-md` - Tarjetas estándar
* **Media:** `shadow-lg` - Tarjetas destacadas, dropdowns
* **Alta:** `shadow-xl` - Modales, elementos flotantes
* **Extrema:** `shadow-2xl` - Hero sections, overlays importantes

### Sombras con Color (Efecto Glow)

```css
/* Sombra azul primaria */
box-shadow: 0 10px 40px -10px rgba(99, 102, 241, 0.4);

/* Sombra rosa acento */
box-shadow: 0 10px 40px -10px rgba(244, 63, 94, 0.4);

/* Tailwind classes personalizadas (agregar en tailwind.config.js) */
.shadow-primary { box-shadow: 0 10px 40px -10px rgba(99, 102, 241, 0.4); }
.shadow-accent { box-shadow: 0 10px 40px -10px rgba(244, 63, 94, 0.4); }
```

---

## 7. Bordes y Esquinas Redondeadas

* **Grosor de Borde:**
  * `border` (1px) - Por defecto
  * `border-2` (2px) - Énfasis
  * `border-4` (4px) - Bordes laterales de acento

* **Radio de Esquinas:**
  * `rounded-none` (0px) - Sin redondeo
  * `rounded-sm` (2px) - Inputs internos
  * `rounded` o `rounded-md` (6px) - Inputs, botones pequeños
  * `rounded-lg` (8px) - Botones, badges
  * `rounded-xl` (12px) - Tarjetas estándar
  * `rounded-2xl` (16px) - Tarjetas destacadas, modales
  * `rounded-full` (999px) - Avatares, badges circulares

---

## 8. Animaciones y Transiciones

Las animaciones deben ser suaves y no distraer.

### Duraciones

* **Rápida:** `duration-150` (150ms) - Hover de botones
* **Normal:** `duration-200` o `duration-300` (200-300ms) - Transiciones generales
* **Media:** `duration-500` (500ms) - Modales, slides
* **Lenta:** `duration-700` (700ms) - Animaciones complejas

### Ease Functions

* `ease-in` - Inicio lento
* `ease-out` - Final lento (recomendado para UI)
* `ease-in-out` - Inicio y final lento
* `linear` - Velocidad constante

### Propiedades Comunes a Animar

```css
/* Colores (botones, links) */
transition-colors duration-200

/* Opacidad (overlays, fades) */
transition-opacity duration-300

/* Transform (hover effects, modales) */
transition-transform duration-300

/* Shadow (elevación de tarjetas) */
transition-shadow duration-300

/* All (usar con precaución) */
transition-all duration-200
```

### Animaciones Específicas

#### Fade In (Aparecer)

```css
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out;
}
```

#### Slide In from Right (Sidebar, Panels)

```css
@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}

.animate-slide-in-right {
  animation: slideInRight 0.3s ease-out;
}
```

#### Pulse (Notificaciones)

```css
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.animate-pulse-subtle {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
```

---

## 9. Accesibilidad (A11y)

### Principios Fundamentales

* **Contraste de Colores:** Todos los textos cumplen con WCAG 2.1 AA mínimo (ratio 4.5:1 para texto normal, 3:1 para textos grandes)
* **Focus States:** Todos los elementos interactivos tienen indicador de foco visible con `focus:ring-2`
* **Etiquetas ARIA:** Usar `aria-label`, `aria-describedby`, `aria-expanded` cuando sea necesario
* **Navegación por Teclado:** Todos los elementos interactivos deben ser accesibles con Tab
* **Tamaños de Toque:** Elementos interactivos móviles mínimo 44x44px
* **Texto Alternativo:** Todas las imágenes funcionales tienen `alt` descriptivo

### Clases de Focus Ring

```css
/* Focus ring primario (por defecto) */
focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2

/* Focus ring en fondos oscuros */
focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-neutral-900

/* Focus ring para elementos de error */
focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
```

### Skip Links (Saltar al contenido)

```html
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded-lg">
    Saltar al contenido principal
</a>
```

---

## 10. Responsive Design

### Estrategia: Mobile First

Diseñamos primero para móvil y expandimos hacia pantallas más grandes.

### Breakpoints en Uso

| Breakpoint | Tamaño  | Dispositivo             | Grid Columns |
| ---------- | ------- | ----------------------- | ------------ |
| Default    | < 640px | Móviles                 | 1-2          |
| `sm`       | 640px+  | Móviles grandes         | 2-4          |
| `md`       | 768px+  | Tablets                 | 4-8          |
| `lg`       | 1024px+ | Laptops/Desktop pequeño | 8-12         |
| `xl`       | 1280px+ | Desktop estándar        | 12           |
| `2xl`      | 1536px+ | Pantallas grandes       | 12           |

### Consideraciones Especiales

* **Sidebar:** Drawer en móvil, fija en desktop (a partir de `lg`)
* **Tablas:** Scroll horizontal en móvil, vista completa en desktop
* **Modales:** Fullscreen en móvil, centrado con max-width en desktop
* **Forms:** 1 columna en móvil, 2 columnas en tablet+
* **Navegación:** Hamburger menu hasta `lg`, horizontal después

### Ejemplos de Uso

```html
<!-- Card responsive: 1 col móvil, 2 col tablet, 3 col desktop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cards aquí -->
</div>

<!-- Texto responsive -->
<h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold">
    Título Responsive
</h1>

<!-- Padding responsive -->
<div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-12">
    Contenido con espaciado adaptativo
</div>
```

---

## 11. Layout Principal

### Estructura de la Aplicación

```
┌─────────────────────────────────────────┐
│           Header/Navbar                 │
│  [Logo] [Nav Links] [User Menu]        │
├─────────────┬───────────────────────────┤
│             │                           │
│   Sidebar   │     Main Content         │
│   (Areas)   │                           │
│             │                           │
│   - Inicio  │   [Breadcrumbs]          │
│   - Tareas  │                           │
│   - RRHH    │   <Page Content>         │
│   - Finanzas│                           │
│   - Mkt     │                           │
│             │                           │
└─────────────┴───────────────────────────┘
```

### Header/Navbar

```html
<header class="bg-gradient-to-r from-primary-900 via-primary-800 to-accent-900 border-b border-primary-700 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-white">Junior</h1>
            </div>

            <!-- Navigation Links (desktop) -->
            <nav class="hidden lg:flex space-x-8">
                <a href="#" class="text-white/90 hover:text-white font-medium transition-colors">
                    Dashboard
                </a>
                <!-- Más links -->
            </nav>

            <!-- User Menu -->
            <div class="flex items-center gap-4">
                <!-- Notificaciones -->
                <button class="relative p-2 text-white/90 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1 right-1 h-2 w-2 bg-accent-500 rounded-full"></span>
                </button>

                <!-- Avatar -->
                <button class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-white font-semibold text-sm">
                        CM
                    </div>
                </button>
            </div>
        </div>
    </div>
</header>
```

### Sidebar

```html
<aside class="hidden lg:flex flex-col w-64 bg-white dark:bg-neutral-900 border-r border-neutral-200 dark:border-neutral-800 min-h-screen">
    <nav class="flex-1 px-4 py-6 space-y-1">
        <!-- Item activo -->
        <a href="#" class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-900/20 dark:to-accent-900/20 text-primary-700 dark:text-primary-400 rounded-lg font-medium border-l-4 border-primary-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <!-- Item normal -->
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded-lg font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Tareas
        </a>

        <!-- Más items -->
    </nav>
</aside>
```

---

## 12. Tokens de Diseño (Design Tokens)

Valores reutilizables para mantener consistencia.

```javascript
// tailwind.config.js
export default {
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f4ff',
          100: '#e0eaff',
          200: '#c7d7fe',
          300: '#a5b9fc',
          400: '#8191f8',
          500: '#6366f1',
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
          950: '#1e1b4b',
        },
        accent: {
          50: '#fff1f2',
          100: '#ffe4e6',
          200: '#fecdd3',
          300: '#fda4af',
          400: '#fb7185',
          500: '#f43f5e',
          600: '#e11d48',
          700: '#be123c',
          800: '#9f1239',
          900: '#881337',
          950: '#4c0519',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      boxShadow: {
        'primary': '0 10px 40px -10px rgba(99, 102, 241, 0.4)',
        'accent': '0 10px 40px -10px rgba(244, 63, 94, 0.4)',
      },
    },
  },
}
```

---

## 13. Recursos y Referencias

* **Figma:** [Pendiente de crear]
* **Tailwind CSS:** https://tailwindcss.com
* **Heroicons:** https://heroicons.com
* **Google Fonts:** https://fonts.google.com
* **Paleta de Colores Interactiva:** https://uicolors.app

---

## 14. Guía de Implementación

### Paso 1: Configurar Tailwind

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### Paso 2: Configurar tailwind.config.js

Copiar la configuración de tokens de diseño del punto 12.

### Paso 3: Importar Fuentes

Agregar las fuentes Inter y JetBrains Mono en el `<head>` de la aplicación.

### Paso 4: Crear Componentes Blade

Crear componentes reutilizables en `resources/views/components/`:
- `button.blade.php`
- `card.blade.php`
- `badge.blade.php`
- `input.blade.php`
- `modal.blade.php`

### Paso 5: Implementar Dark Mode

Configurar en `tailwind.config.js`:

```javascript
export default {
  darkMode: 'class', // o 'media' para auto
  // ...
}
```

Agregar toggle en la UI para cambiar entre temas.

---

**Notas Finales:**

Este sistema de diseño está optimizado para aplicaciones empresariales que requieren:
- **Profesionalismo:** Colores sobrios con toques de innovación
- **Accesibilidad:** Cumple con estándares WCAG 2.1 AA
- **Flexibilidad:** Sistema modular y extensible
- **Modernidad:** Gradientes y animaciones sutiles
- **Consistencia:** Tokens de diseño reutilizables

El uso de gradientes azul-rosa crea una identidad visual única sin sacrificar la seriedad empresarial. La paleta dual permite distinguir acciones primarias (azul) de CTAs destacados (rosa), mejorando la jerarquía visual.
