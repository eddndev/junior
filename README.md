# 🚀 Laravel Starter Template

Plantilla base de Laravel 12 preconfigurada con Breeze, Tailwind CSS v4, y metodología AGENTS.md para gestión de proyectos.

---

## ⚡ Inicio Rápido

```bash
# Instalar dependencias frontend
npm install

# Compilar assets en modo desarrollo
npm run dev

# En otra terminal: Levantar servidor
php artisan serve
```

Visita: **http://localhost:8000**

---

## 📦 Stack Tecnológico Incluido

Este template viene preconfigurado con:

### Backend
- **Laravel 12** - Framework PHP moderno
- **Laravel Breeze** - Sistema de autenticación completo
- **MySQL** - Base de datos relacional

### Frontend
- **Tailwind CSS v4** - Framework CSS moderno (sin PostCSS)
- **Vite** - Bundler ultra-rápido con plugin `@tailwindcss/vite`
- **Blade/Livewire/Vue/React** - Stack según selección en CLI

### Paquetes Preinstalados
- **Laravel Socialite** - Autenticación OAuth (Google, GitHub, Facebook, etc.)
- **Livewire** - Componentes dinámicos reactivos
- **GSAP** - Librería de animaciones JavaScript
- **Laravel Lang** - Traducciones ES/EN preconfiguradas

### Paquetes Opcionales
- **Laravel Nova 5** - Panel de administración (si fue seleccionado)
- **Spatie Media Library** - Gestión de archivos multimedia (si fue seleccionado)

---

## 🛠️ Configuración Inicial Completada por la CLI

Cuando creaste este proyecto, la CLI ya configuró:

✅ Archivo `.env` con nombre del proyecto y credenciales de base de datos
✅ `APP_KEY` generada automáticamente
✅ Dependencias de Composer instaladas
✅ Tailwind CSS v4 configurado con Vite (sin PostCSS)
✅ Migraciones de base de datos ejecutadas
✅ Tablas de autenticación creadas (users, password_resets, etc.)
✅ [Si aplica] Laravel Nova instalado y configurado

---

## 📚 Documentación del Proyecto

Este template incluye una metodología completa de gestión de proyectos en `/docs/`.

### Archivos Principales

| Archivo | Propósito | ¿Debes editarlo? |
|---------|-----------|------------------|
| **`docs/AGENTS.md`** | Metodología completa de gestión (INQUEBRANTABLE) | ❌ No, es la metodología |
| **`docs/01-manifest.md`** | Visión, objetivos y alcance del proyecto | ✅ SÍ - Completa con tu proyecto |
| **`docs/02-design-system.md`** | Sistema de diseño, colores, tipografía, componentes | ✅ SÍ - Define tu UI/UX |
| **`docs/03-database-schema.md`** | Esquema de base de datos con diagrama Mermaid | ✅ SÍ - Documenta tu BD |
| **`docs/04-user-stories.md`** | Backlog de funcionalidades e historias de usuario | ✅ SÍ - Define features |

### Guías de Workflow

| Archivo | Propósito |
|---------|-----------|
| **`docs/workflow/01-team-workflow.md`** | Flujo de trabajo en equipo |
| **`docs/workflow/02-branch-protection.md`** | Configuración de protección de ramas |
| **`docs/workflow/03-github-projects-setup.md`** | Setup de GitHub Projects y automatización |

### ⚠️ IMPORTANTE: Lee primero `docs/AGENTS.md`

Este documento define **REGLAS INQUEBRANTABLES** sobre cómo gestionar:
- Issues y Épicas en GitHub
- Sistema de labels (Type, Module, Priority, Sprint)
- Sprints y planificación
- GitHub Projects (Kanban)
- Documentación viva

**Si vas a trabajar en este proyecto, este documento es tu biblia.**

---

## 🔧 Configuración de GitHub Workflow (Opcional pero Recomendado)

Si quieres aplicar la metodología AGENTS.md con automatización completa:

### Paso 1: Activar Workflows de GitHub

```bash
# Renombrar carpeta de ejemplo
mv .github.example .github
```

### Paso 2: Crear Personal Access Token (PAT)

Los workflows de GitHub Actions necesitan permisos especiales para mover issues en Projects.

1. Ve a: https://github.com/settings/tokens
2. Click **"Generate new token (classic)"**
3. Nombre: `PROJECT_AUTOMATION`
4. Seleccionar scopes:
   - ✅ `repo` (Full control of private repositories)
   - ✅ `project` (Full control of projects)
5. Click **"Generate token"**
6. **COPIAR EL TOKEN** (solo se muestra una vez)

### Paso 3: Agregar PAT como Secret

1. Ve a: `https://github.com/[TU-USERNAME]/[ESTE-REPO]/settings/secrets/actions`
2. Click **"New repository secret"**
3. Name: `PROJECT_PAT`
4. Secret: pega el token copiado
5. Click **"Add secret"**

### Paso 4: Crear GitHub Project

```bash
# Crear proyecto
gh project create --owner [TU-USERNAME] --title "[NOMBRE-PROYECTO] - Development"

# Listar proyectos (anota el PROJECT_NUMBER)
gh project list --owner [TU-USERNAME]

# Ver campos del proyecto (anota STATUS_FIELD_ID y OPTION_IDs)
gh project field-list [PROJECT_NUMBER] --owner [TU-USERNAME]
```

### Paso 5: Configurar IDs en el Workflow

Edita `.github/workflows/project-board-automation.yml`:

```yaml
env:
  PROJECT_ID: [PEGAR_PROJECT_ID_AQUI]           # Ej: PVT_kwHOABCDEF
  STATUS_FIELD_ID: [PEGAR_STATUS_FIELD_ID]      # Ej: PVTSSF_lAHOXYZ
  TODO_OPTION_ID: [PEGAR_TODO_OPTION_ID]        # Ej: f75ad846
  IN_PROGRESS_OPTION_ID: [PEGAR_IN_PROGRESS_ID] # Ej: 47fc9ee4
  DONE_OPTION_ID: [PEGAR_DONE_OPTION_ID]        # Ej: 98236657
```

Y en la línea 67, reemplaza `[OWNER]` por tu username:

```yaml
gh project item-add ... --owner TU-USERNAME
```

### Paso 6: Configurar Protección de Rama Main

Sigue las instrucciones en: **`docs/workflow/02-branch-protection.md`**

### Paso 7: Verificar Automatización

Crea una issue de prueba:

```bash
gh issue create --title "Test: Verificar automatización" --body "Testing workflow"
```

Debería:
1. ✅ Añadirse automáticamente al proyecto
2. ✅ Moverse a columna "Todo"
3. ✅ Al asignártela, moverse a "In Progress"
4. ✅ Al cerrarla, moverse a "Done"

**📖 Guía completa:** Ver `docs/workflow/03-github-projects-setup.md`

---

## 🔐 Laravel Nova (Si fue instalado)

### Acceso al Panel

**URL:** http://localhost:8000/nova

### Credenciales por Defecto

Usa el usuario que creaste durante la instalación de Breeze.

### Configuración Incluida

- ✅ Licencia configurada en `auth.json` (no subir a git)
- ✅ Traducción al español preconfigurada
- ✅ **Devtools activados** (útil en desarrollo)
- ✅ **NO es autenticación principal** (Breeze lo es)

### Crear tu Primer Recurso

```bash
php artisan nova:resource Post
```

Edita `app/Nova/Post.php` para definir campos.

**Documentación:** https://nova.laravel.com/docs

---

## 🎨 Tailwind CSS v4

### Características Instaladas

- ✅ **Sin PostCSS** - Configuración moderna con Vite
- ✅ **Plugin `@tailwindcss/vite`** - Integración nativa
- ✅ **Sintaxis v4** - Usa `@import "tailwindcss"` en lugar de directivas

### Archivo de Configuración

**Ubicación:** `resources/css/app.css`

```css
@import "tailwindcss";

/* Rutas de plantillas */
@source "../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php";
@source "../../storage/framework/views/*.php";
@source "../**/*.blade.php";
@source "../**/*.js";

@theme {
  --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
}
```

### Agregar Colores o Fuentes Personalizadas

Edita el bloque `@theme` en `app.css`:

```css
@theme {
  --font-sans: 'Tu Fuente', ui-sans-serif, system-ui, sans-serif;
  --color-primary: #3b82f6;
  --color-secondary: #8b5cf6;
}
```

**Documentación Tailwind v4:** https://tailwindcss.com/docs

---

## 🌍 Internacionalización (ES/EN)

### Configuración Actual

- **Idioma principal:** Español (ES)
- **Idioma fallback:** Inglés (EN)
- **Paquetes instalados:** `laravel-lang/lang` + `laravel-lang/publisher`

### Cambiar Idioma de la App

Edita `.env`:

```env
APP_LOCALE=es
APP_FALLBACK_LOCALE=en
```

### Agregar Más Idiomas

```bash
# Ver idiomas disponibles
php artisan lang:available

# Agregar francés (ejemplo)
php artisan lang:add fr

# Actualizar traducciones
php artisan lang:update
```

### Usar Traducciones en Blade

```blade
{{ __('auth.failed') }}
{{ __('validation.required', ['attribute' => 'email']) }}
```

---

## 🔗 Laravel Socialite (OAuth)

### Providers Preconfigurados

El paquete ya está instalado. Para activar OAuth:

### 1. Agregar Credenciales en `.env`

```env
# GitHub
GITHUB_CLIENT_ID=tu_client_id
GITHUB_CLIENT_SECRET=tu_client_secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback

# Google
GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 2. Configurar en `config/services.php`

```php
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URI'),
],
```

### 3. Crear Rutas y Controlador

```bash
php artisan make:controller Auth/SocialiteController
```

**Documentación:** https://laravel.com/docs/socialite

---

## 🎬 GSAP (Animaciones)

### Librería Instalada

**Versión:** Última estable desde npm

### Uso Básico

Importa en tus archivos JS:

```javascript
// resources/js/app.js
import gsap from 'gsap';

// Ejemplo de animación
gsap.to('.mi-elemento', {
  duration: 1,
  x: 100,
  opacity: 0.5
});
```

**Documentación:** https://greensock.com/docs/

---

## 🗄️ Base de Datos

### Tablas Incluidas (Breeze)

- `users` - Usuarios registrados
- `password_reset_tokens` - Tokens de recuperación
- `sessions` - Sesiones de usuario
- `cache` - Cache de aplicación
- `jobs` - Cola de trabajos

### Crear Nuevas Migraciones

```bash
php artisan make:migration create_posts_table
```

### Ejecutar Migraciones

```bash
php artisan migrate
```

### Seeders

```bash
php artisan make:seeder PostSeeder
php artisan db:seed --class=PostSeeder
```

---

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=UserTest
```

### Crear Nuevos Tests

```bash
php artisan make:test UserTest
php artisan make:test PostTest --unit
```

---

## 📝 Comandos Útiles

### Desarrollo

```bash
npm run dev          # Compilar assets en modo watch
npm run build        # Compilar assets para producción
php artisan serve    # Servidor de desarrollo
php artisan tinker   # REPL de Laravel
```

### Base de Datos

```bash
php artisan migrate          # Ejecutar migraciones
php artisan migrate:fresh    # Recrear BD (CUIDADO: borra datos)
php artisan migrate:rollback # Revertir última migración
php artisan db:seed          # Ejecutar seeders
```

### Cache

```bash
php artisan optimize:clear   # Limpiar todo el cache
php artisan cache:clear      # Limpiar cache de aplicación
php artisan config:clear     # Limpiar cache de config
php artisan view:clear       # Limpiar cache de vistas
php artisan route:clear      # Limpiar cache de rutas
```

### Nova (si está instalado)

```bash
php artisan nova:install     # Reinstalar assets de Nova
php artisan nova:publish     # Publicar recursos de Nova
php artisan nova:resource    # Crear nuevo recurso
php artisan nova:user        # Crear usuario administrador
```

---

## 🤝 Trabajo en Equipo

### Onboarding de Nuevos Desarrolladores

Si vas a trabajar en equipo, comparte con cada miembro:

1. **`docs/AGENTS.md`** - Metodología OBLIGATORIA
2. **`docs/workflow/01-team-workflow.md`** - Flujo de trabajo
3. Este README

### Reglas de Commits

```bash
# Formato de mensajes
feat: Agregar login con OAuth
fix: Corregir validación de email
chore: Actualizar dependencias
docs: Documentar API de usuarios
```

### Flujo de Trabajo con Ramas

```bash
# Crear rama desde main
git checkout main
git pull origin main
git checkout -b feature/nombre-feature

# Trabajar y commitear
git add .
git commit -m "feat: Descripción del cambio"

# Push y crear PR
git push origin feature/nombre-feature
# Ir a GitHub y crear Pull Request
```

**⚠️ NUNCA hacer push directo a `main`** (debe estar protegida)

---

## 🔒 Seguridad

### Archivos que NO DEBES Subir a Git

Ya están en `.gitignore`:

- ✅ `.env` - Credenciales y configuración sensible
- ✅ `auth.json` - Credenciales de Nova
- ✅ `vendor/` - Dependencias de Composer
- ✅ `node_modules/` - Dependencias de NPM

### Generar Nueva APP_KEY (si es necesario)

```bash
php artisan key:generate
```

### Variables de Entorno Importantes

**NO compartir en GitHub:**
- `APP_KEY` - Clave de encriptación
- `DB_PASSWORD` - Contraseña de base de datos
- `NOVA_LICENSE_KEY` - Licencia de Nova
- OAuth credentials (client_id, client_secret)

---

## 🚀 Despliegue a Producción

### Checklist Pre-Deploy

- [ ] `APP_ENV=production` en `.env`
- [ ] `APP_DEBUG=false` en `.env`
- [ ] Ejecutar `composer install --no-dev --optimize-autoloader`
- [ ] Ejecutar `npm run build`
- [ ] Ejecutar `php artisan config:cache`
- [ ] Ejecutar `php artisan route:cache`
- [ ] Ejecutar `php artisan view:cache`
- [ ] Configurar correctamente `APP_URL` en `.env`
- [ ] Configurar HTTPS
- [ ] Revisar permisos de `storage/` y `bootstrap/cache/`

### Optimización

```bash
# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Optimizar autoload
composer install --optimize-autoloader --no-dev
```

---

## 📄 Licencia

Este template es software de código abierto licenciado bajo [MIT license](https://opensource.org/licenses/MIT).

---

## 🆘 Soporte y Recursos

### Documentación Oficial

- **Laravel:** https://laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Laravel Breeze:** https://laravel.com/docs/starter-kits#breeze
- **Livewire:** https://livewire.laravel.com/docs
- **Laravel Nova:** https://nova.laravel.com/docs

### Comunidad

- **Laravel Discord:** https://discord.gg/laravel
- **Laracasts:** https://laracasts.com
- **Laravel News:** https://laravel-news.com

---

**Template creado con ❤️ usando [Laravel Starter CLI](https://github.com/eddndev/laravel-starter-cli)**
