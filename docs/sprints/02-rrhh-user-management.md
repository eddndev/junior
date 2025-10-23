# Diario del Sprint 2: Gestión de RRHH - Sistema de Usuarios y Permisos

**Periodo:** 2025-10-19 - 2025-10-22 (COMPLETADO ✅)

**Épica Maestra en GitHub:** [Pendiente de crear]

---

## 1. Objetivo del Sprint

Implementar el sistema completo de gestión de usuarios, roles y permisos para el módulo de Recursos Humanos, permitiendo al Administrador de RRHH gestionar el ciclo de vida de empleados, asignar roles múltiples por área, y establecer la base de autenticación y autorización del sistema Junior.

---

## 2. Alcance y Tareas Incluidas

### Historias de Usuario del Sprint

#### 2.1 Módulo Núcleo - Gestión de Usuarios

**Perfil: Administrador de RRHH**

- [x] `#[ID] - [RRHH] Crear perfil de usuario` ✅
  - Como Administrador de RRHH, quiero crear nuevos perfiles de usuario para incorporar empleados a la plataforma

- [x] `#[ID] - [RRHH] Listar y buscar usuarios` ✅
  - Como Administrador de RRHH, quiero ver una lista de todos los usuarios con búsqueda y filtros para gestionar la base de empleados

- [x] `#[ID] - [RRHH] Actualizar perfil de usuario` ✅
  - Como Administrador de RRHH, quiero actualizar la información de un usuario para mantener los datos actualizados

- [x] `#[ID] - [RRHH] Desactivar/Activar usuario` ✅
  - Como Administrador de RRHH, quiero desactivar usuarios (soft delete) para gestionar salidas sin perder historial

- [x] `#[ID] - [RRHH] Ver detalle de usuario con roles y áreas` ✅
  - Como Administrador de RRHH, quiero ver el perfil completo de un usuario incluyendo sus roles y áreas asignadas

#### 2.2 Módulo Núcleo - Gestión de Roles y Permisos

- [x] `#[ID] - [RRHH] Asignar rol a usuario en área específica` ✅
  - Como Administrador de RRHH, quiero asignar un rol a un usuario en un área específica para reflejar su posición organizacional

- [x] `#[ID] - [RRHH] Asignar múltiples roles a usuario` ✅
  - Como Administrador de RRHH, quiero que un usuario pueda tener múltiples roles en distintas áreas para reflejar funciones flexibles

- [x] `#[ID] - [RRHH] Remover rol de usuario` ✅
  - Como Administrador de RRHH, quiero remover roles de usuarios cuando cambien de posición o área

- [x] `#[ID] - [RRHH] Ver permisos efectivos de usuario` ✅
  - Como Administrador de RRHH, quiero ver todos los permisos que un usuario tiene acumulados a través de sus roles

#### 2.3 Autenticación y Perfil Personal

**Perfil: Empleado General**

- [x] `#[ID] - [Auth] Login de usuario` ✅
  - Como Empleado, quiero iniciar sesión con email y contraseña para acceder a mi espacio de trabajo
  - **Implementado mediante Laravel Breeze**

- [x] `#[ID] - [Auth] Logout de usuario` ✅
  - Como Empleado, quiero cerrar sesión de forma segura para proteger mi cuenta
  - **Implementado mediante Laravel Breeze**

- [ ] `#[ID] - [Perfil] Ver mi perfil personal`
  - Como Empleado, quiero ver mi propio perfil con mis datos personales y roles asignados
  - **PENDIENTE:** Se implementará en Sprint 3

- [ ] `#[ID] - [Perfil] Actualizar mi información personal`
  - Como Empleado, quiero actualizar ciertos campos de mi perfil (nombre, email) para mantener mis datos actuales
  - **PENDIENTE:** Se implementará en Sprint 3

#### 2.4 Sistema de Áreas

- [ ] `#[ID] - [RRHH] Gestionar catálogo de áreas`
  - Como Administrador de RRHH, quiero crear/editar/desactivar áreas de la organización para estructurar la empresa
  - **PENDIENTE:** Se implementará en Sprint 3 (AreaController)

- [x] `#[ID] - [RRHH] Asignar usuario a área` ✅
  - Como Administrador de RRHH, quiero asignar usuarios a una o más áreas para definir su pertenencia organizacional
  - **Implementado mediante asignación de roles contextuales por área**

#### 2.5 Trazabilidad y Auditoría

- [x] `#[ID] - [Audit] Implementar sistema de audit logs` ✅
  - Como Sistema, quiero registrar automáticamente todas las acciones CRUD de usuarios y roles para trazabilidad
  - **Implementado mediante UserObserver**

- [ ] `#[ID] - [RRHH] Panel de trazabilidad básico`
  - Como Administrador de RRHH, quiero ver un panel de auditoría con las acciones realizadas en el sistema
  - **PENDIENTE:** Se implementará en Sprint 3 (AuditLogController + vista)

---

## 3. Componentes Técnicos a Implementar

### 3.1 Migraciones de Base de Datos

Basado en el esquema `/docs/03-database-schema.md`, crear las siguientes migraciones:

- [x] `users` - Tabla de usuarios con soft deletes ✅ `0001_01_01_000000_create_users_table.php`
- [x] `roles` - Catálogo de roles del sistema ✅ `2025_10_16_000001_create_roles_table.php`
- [x] `permissions` - Catálogo de permisos granulares ✅ `2025_10_16_000002_create_permissions_table.php`
- [x] `areas` - Áreas/departamentos de la organización ✅ `2025_10_16_000003_create_areas_table.php`
- [x] `role_user` - Tabla pivote (roles x usuarios x áreas) ✅ `2025_10_16_000006_create_role_user_table.php`
- [x] `permission_role` - Tabla pivote (permisos x roles) ✅ `2025_10_16_000004_create_permission_role_table.php`
- [x] `area_user` - Tabla pivote (áreas x usuarios) ✅ `2025_10_16_000005_create_area_user_table.php`
- [x] `audit_logs` - Tabla de trazabilidad ✅ `2025_10_16_000014_create_audit_logs_table.php`
- [x] `add_is_system_to_areas_table` - Protección de áreas del sistema ✅ `2025_10_22_070121_add_is_system_to_areas_table.php`
  - Agrega campo `is_system` (boolean, default false) a la tabla areas
  - Usado para marcar áreas built-in que no pueden ser desactivadas

### 3.2 Seeders

- [x] `RoleSeeder` - Roles iniciales del sistema ✅ `RoleSeeder.php`
  - Dirección General
  - Director de Área (Producción, Marketing, Finanzas)
  - Miembro de Producción
  - Gestor Financiero
  - Gestor de Marketing
  - Administrador de RRHH

- [x] `PermissionSeeder` - Permisos por módulo ✅ `PermissionSeeder.php`
  - Módulo Núcleo: `gestionar-usuarios`, `ver-usuarios`, `asignar-roles`
  - Módulo Tareas: `crear-tareas`, `asignar-tareas`, `completar-tareas`
  - Módulo Finanzas: `ver-finanzas`, `gestionar-presupuestos`, `crear-cotizaciones`
  - Módulo Marketing: `gestionar-campanas`, `ver-leads`
  - Módulo Trazabilidad: `ver-trazabilidad`

- [x] `RolePermissionSeeder` - Asignación de permisos a roles ✅ `RolePermissionSeeder.php`

- [x] `AreaSeeder` - Áreas iniciales ✅ `AreaSeeder.php`
  - Dirección General
  - Producción
  - Marketing
  - Finanzas
  - Recursos Humanos

- [x] `UserSeeder` - Usuario administrador inicial para desarrollo ✅ `UserSeeder.php`

### 3.3 Modelos Eloquent

- [x] `User` model con relaciones ✅ `User.php`
  - [x] `belongsToMany(Role)` through `role_user` con `withPivot('area_id')`
  - [x] `belongsToMany(Area)` through `area_user`
  - [x] Método `hasPermission($permission)` para verificación
  - [x] Método `hasRole($role)` para verificación
  - [x] Método `getAllPermissions()` para obtener todos los permisos
  - [x] Scope `active()` para filtrar usuarios activos
  - [ ] Método `rolesInArea($area)` para roles contextuales (PENDIENTE)

- [x] `Role` model con relaciones ✅ `Role.php`
  - `belongsToMany(User)` through `role_user`
  - `belongsToMany(Permission)` through `permission_role`

- [x] `Permission` model con relaciones ✅ `Permission.php`
  - `belongsToMany(Role)` through `permission_role`

- [x] `Area` model con relaciones ✅ `Area.php`
  - `belongsToMany(User)` through `area_user`
  - `hasMany(Task)`

- [x] `AuditLog` model para trazabilidad ✅ `AuditLog.php` (relación polimórfica)

### 3.4 Controladores y Rutas

**Rutas protegidas con autenticación:**

- [x] `UserController` - CRUD de usuarios (solo para RRHH) ✅
  - [x] `index()` - Listar usuarios con paginación y búsqueda
  - [x] `create()` - Formulario de creación
  - [x] `store()` - Crear usuario
  - [x] `show($id)` - Ver detalle de usuario
  - [x] `edit($id)` - Formulario de edición
  - [x] `update($id)` - Actualizar usuario
  - [x] `destroy($id)` - Soft delete de usuario
  - [x] `restore($id)` - Restaurar usuario eliminado

- [x] `RoleAssignmentController` - Gestión de roles ✅
  - [x] `create($userId)` - Vista para asignar roles
  - [x] `store()` - Asignar rol a usuario en área
  - [x] `destroy()` - Remover rol de usuario

- [x] `AreaController` - CRUD de áreas ✅
  - [x] `index()` - Listar áreas con búsqueda y filtros (activas/inactivas)
  - [x] `create()` - Formulario de creación de área
  - [x] `store()` - Crear área con slug automático
  - [x] `edit($id)` - Formulario de edición de área
  - [x] `update($id)` - Actualizar área (con protección para áreas del sistema)
  - [x] `destroy($id)` - Desactivar área (con validación de dependencias y protección de áreas del sistema)
  - Usa `authorizeResource()` con `AreaPolicy`
  - Form Requests: `StoreAreaRequest`, `UpdateAreaRequest`

- [x] `AuditLogController` - Panel de trazabilidad ✅
  - [x] `index()` - Listar audit logs con 5 filtros avanzados:
    - Filtro por usuario que realizó la acción
    - Filtro por tipo de acción (created, updated, deleted, restored, force_deleted)
    - Filtro por modelo auditado (User, Area, TeamLog)
    - Filtro por rango de fechas (date_from, date_to)
    - Búsqueda en JSON fields (old_values, new_values)
  - Paginación de 20 registros con `withQueryString()`
  - Vista con details/summary para expandir JSON

- [x] `TeamLogController` - Bitácora de equipo ✅
  - [x] `index()` - Listar entradas de bitácora del usuario (áreas a las que pertenece)
  - [x] `store()` - Crear entrada de bitácora (con StoreTeamLogRequest)
  - [x] `destroy()` - Eliminar entrada propia (soft delete)
  - Filtros: por área, por tipo (note/decision/event/meeting), búsqueda de texto
  - Protegido por permisos: `ver-bitacora`, `crear-bitacora`

- [ ] `ProfileController` - Perfil personal del empleado
  - `show()` - Ver mi perfil
  - `edit()` - Formulario de edición de perfil
  - `update()` - Actualizar mi perfil
  - **PENDIENTE:** Se implementará en Sprint 3

### 3.5 Vistas Blade

**Usando los componentes del Sprint 1:**

- [x] `users/index.blade.php` - Lista de usuarios con tabla (`x-layout.table`) ✅
- [x] `users/create.blade.php` - Formulario de creación de usuario ✅
- [x] `users/edit.blade.php` - Formulario de edición de usuario ✅
- [x] `users/show.blade.php` - Vista de detalle con roles y permisos ✅
- [x] `users/_form.blade.php` - Parcial reutilizable para create/edit ✅
- [x] `roles/assign.blade.php` - Interfaz para asignar/remover roles ✅

- [x] `areas/index.blade.php` - Gestión de áreas con búsqueda y filtros ✅
  - Usa `x-layout.table` para listar áreas
  - Badges: `x-data-display.badge-active`, `x-data-display.badge-inactive`
  - Badge especial "Sistema" para áreas protegidas
  - Dropdown actions con editar/desactivar (desactivar oculto para áreas del sistema)
- [x] `areas/create.blade.php` - Formulario de creación de área ✅
- [x] `areas/edit.blade.php` - Formulario de edición de área ✅
- [x] `areas/_form.blade.php` - Parcial reutilizable para create/edit ✅
  - Checkbox `is_active` deshabilitado para áreas del sistema
  - Badge "Sistema - Protegida" visible para áreas del sistema
  - Mensaje de advertencia sobre protección de áreas críticas

- [x] `audit-logs/index.blade.php` - Panel de trazabilidad con filtros avanzados ✅
  - Formulario de filtros con 5 criterios
  - Tabla con 7 columnas (usuario, acción, modelo, old/new values, IP, timestamp)
  - Details/summary HTML para expandir JSON de old_values/new_values
  - Info box explicando el propósito del panel
  - Paginación con `withQueryString()`

- [x] `team-logs/index.blade.php` - Bitácora de equipo con filtros y búsqueda ✅
  - Compositor de entradas con selector de área y tipo
  - Sección de filtros: búsqueda de texto, filtro por área, filtro por tipo
  - Feed de actividad con timeline visual (línea vertical)
  - Badges con íconos para cada tipo (nota, decisión, evento, reunión)
  - Botón de eliminar visible solo para el autor de cada entrada
  - Avatar generado dinámicamente con UI Avatars
  - Paginación de 15 entradas

- [ ] `profile/show.blade.php` - Vista de perfil personal
  - **PENDIENTE:** Se implementará en Sprint 3
- [ ] `profile/edit.blade.php` - Edición de perfil personal
  - **PENDIENTE:** Se implementará en Sprint 3

### 3.6 Middleware y Policies

- [x] `CheckUserActive` middleware - Verificar que el usuario esté activo ✅
  - Aplicado globalmente en web middleware
  - Verifica `is_active` y soft delete status
  - Logout automático de usuarios inactivos

- [x] `CheckPermission` middleware - Verificar permisos específicos ✅
  - OR logic para múltiples permisos
  - Logging comprehensivo de intentos no autorizados
  - Soporte para solicitudes AJAX/JSON

- [x] `UserPolicy` - Políticas de autorización para User model ✅
  - Métodos CRUD estándar (viewAny, view, create, update, delete, restore, forceDelete)
  - Métodos personalizados (assignRoles, managePermissions, export, import)
  - Protección de auto-edición y auto-eliminación
  - Response::deny() con mensajes descriptivos en español

- [x] `AreaPolicy` - Políticas de autorización para Area model ✅
  - Métodos CRUD estándar (viewAny, view, create, update, delete)
  - Basado en permiso `gestionar-areas`
  - **Protección especial:** `delete()` retorna false para áreas con `is_system = true`
  - Response::deny() con mensajes descriptivos en español

### 3.7 Observers y Events

- [x] `UserObserver` - Registrar en audit_logs ✅
  - [x] `created()` - Usuario creado
  - [x] `updated()` - Usuario actualizado (solo campos modificados)
  - [x] `deleted()` - Usuario desactivado (soft delete)
  - [x] `restored()` - Usuario restaurado
  - [x] `forceDeleted()` - Usuario eliminado permanentemente
  - Filtra campos sensibles (password, remember_token)
  - Captura IP address y user agent
  - Skip logging para acciones no autenticadas (seeders, console)
  - **FIXED:** Agrega `'created_at' => now()` manualmente en todos los métodos

- [x] `AreaObserver` - Registrar cambios en áreas ✅
  - [x] `created()` - Área creada
  - [x] `updated()` - Área actualizada (solo campos modificados)
  - [x] `deleted()` - Área desactivada
  - [x] `restored()` - Área restaurada
  - [x] `forceDeleted()` - Área eliminada permanentemente
  - Captura: name, slug, description, is_active, is_system
  - Incluye `'created_at' => now()` manualmente

- [x] `TeamLogObserver` - Registrar cambios en bitácora de equipo ✅
  - [x] `created()` - Entrada de bitácora creada
  - [x] `updated()` - Entrada actualizada
  - [x] `deleted()` - Entrada eliminada (soft delete)
  - [x] `restored()` - Entrada restaurada
  - [x] `forceDeleted()` - Entrada eliminada permanentemente
  - Captura: title, content, type, area_id
  - Incluye `'created_at' => now()` manualmente

- [ ] `RoleUserObserver` - Registrar asignación/remoción de roles
  - **PENDIENTE:** Se implementará si es necesario en futuras iteraciones

### 3.9 Sistema de Archivos Multimedia (Spatie Media Library)

- [x] **TeamLog Model** - Implementación de HasMedia interface ✅
  - [x] Trait `InteractsWithMedia` agregado
  - [x] Método `registerMediaCollections()` configurado:
    - Collection 'attachments': Archivos físicos (imágenes, PDFs, audio, documentos)
    - Collection 'links': Enlaces externos (metadata sin descarga)
  - [x] Método `registerMediaConversions()` para imágenes:
    - Conversión WebP (85% calidad) - Queued
    - Conversión AVIF (80% calidad) - Queued
    - Thumbnail 300x300 WebP (70% calidad) - Non-queued
  - [x] MIME types permitidos:
    - Imágenes: image/* (jpg, png, gif, webp, svg)
    - Documentos: PDF, Word (doc/docx), Excel (xls/xlsx), PowerPoint (ppt/pptx)
    - Audio: audio/* (mp3, wav, ogg)
    - Otros: text/*, JSON, XML, HTML, CSS, JS, ZIP, RAR, 7Z
  - [x] Límites: 10MB por archivo

- [x] **TeamLogAttachments Livewire Component** ✅
  - [x] Gestión de enlaces externos (NO archivos)
  - [x] Propiedades: `$links`, `$newLinkUrl`, `$newLinkType`
  - [x] Método `addLink()` con validación (URL requerida, max 2048 chars)
  - [x] Método `removeLink($linkId)` para eliminar enlaces
  - [x] Método `resetLinks()` para limpiar después de guardar
  - [x] Eventos: `link-added`, `attachments-updated`
  - [x] Tipos de enlace: external, video, image

- [x] **Vista livewire/team-log-attachments.blade.php** ✅
  - [x] Alpine.js para manejo de archivos client-side
  - [x] Drag & drop de archivos con `@dragover`, `@dragleave`, `@drop`
  - [x] Preview de archivos con `URL.createObjectURL()`
  - [x] DataTransfer API para remover archivos de FileList
  - [x] Input HTML nativo `<input type="file" name="attachments[]" multiple>`
  - [x] Select nativo para tipo de enlace (compatible con Livewire)
  - [x] Compact UI estilo Google Classroom: botones de archivo y enlace
  - [x] Contador de adjuntos: muestra total de archivos + enlaces
  - [x] Preview con chips compactos (thumbnails para imágenes, íconos para otros)

- [x] **Componente forms/composer.blade.php** - Mejorado ✅
  - [x] Sistema de padding dinámico con MutationObserver
  - [x] Escucha eventos `@attachments-updated.window` y `@link-added.window`
  - [x] Textarea con auto-resize usando Alpine.js
  - [x] Slot `toolbar` para contenido dinámico (attachments component)
  - [x] Transiciones suaves con `transition-[padding] duration-200`

- [x] **Componente team-log/attachments-display.blade.php** ✅
  - [x] Separación visual de 'attachments' vs 'links'
  - [x] Grid responsive para attachments (2/3/4 columnas)
  - [x] Viewers especializados:
    - **Imágenes:** Lightbox con thumbnail conversion
    - **Audio:** Player HTML5 con controles
    - **PDFs y documentos:** Card con ícono y tamaño de archivo
    - **Videos (YouTube/Vimeo):** iFrame embebido con regex parsing de URL
    - **Imágenes externas:** `<img>` con fallback si falla la carga
    - **Enlaces genéricos:** Card con ícono de enlace externo
  - [x] Lazy loading con `loading="lazy"`
  - [x] Handlers `onerror` para imágenes que no cargan

- [x] **TeamLogController** - Procesamiento de media ✅
  - [x] Método `store()` actualizado:
    - Procesamiento de archivos con `$request->hasFile('attachments')`
    - Loop sobre cada archivo con try/catch para manejo de errores
    - Logging comprehensivo (nombre, mime type, tamaño)
    - `addMedia($file)->toMediaCollection('attachments')`
    - Procesamiento de enlaces: creación directa de registros en tabla `media`
    - Custom properties: `url`, `link_type`, `is_link`
    - NO descarga enlaces externos
  - [x] Form con `enctype="multipart/form-data"`
  - [x] Mensaje de éxito: "Entrada de bitácora creada con éxito. Los adjuntos se están procesando en segundo plano."

- [x] **StoreTeamLogRequest** - Validación actualizada ✅
  - [x] Validación de archivos:
    - `attachments.*` (opcional): max:10240 (10MB)
    - MIME types permitidos (imágenes, PDFs, documentos, audio)
  - [x] Validación de enlaces:
    - `links.*.url` (opcional): required_with:links, url, max:2048
    - `links.*.type` (opcional): required_with:links, in:external,video,image
  - [x] Mensajes de error personalizados en español

- [x] **Configuración de Spatie Media Library** ✅
  - [x] Instalación: `composer require "spatie/laravel-medialibrary:^10.0.0"`
  - [x] Migración publicada: `php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"`
  - [x] Config publicado: `config/media-library.php`
  - [x] Disk configurado: `public` (default)
  - [x] Queue configurado para conversiones
  - [x] Documentación de producción: `docs/queue-configuration-production.md`

### 3.8 Validación

- [x] `StoreUserRequest` - Validación de creación de usuario ✅
  - Validación de email único
  - Password mínimo 8 caracteres
  - Campos requeridos: name, email, password

- [x] `UpdateUserRequest` - Validación de actualización de usuario ✅
  - Email único (excepto el propio usuario)
  - Password opcional (solo si se proporciona)
  - Validación de is_active boolean

- [x] `StoreAreaRequest` - Validación de creación de área ✅
  - Validación de name requerido, único, max:255
  - Description opcional, max:1000
  - is_active boolean, default true
  - Mensajes de error personalizados en español

- [x] `UpdateAreaRequest` - Validación de actualización de área ✅
  - Name requerido, único (excepto el área actual), max:255
  - Description opcional, max:1000
  - is_active boolean
  - Mensajes de error personalizados en español

- [x] `StoreTeamLogRequest` - Validación de entrada de bitácora ✅
  - Title requerido, max:255
  - Content requerido, max:5000
  - area_id requerido, exists:areas
  - Type requerido, in:decision,event,note,meeting
  - **Autorización en authorize():** verifica permiso `crear-bitacora` Y que usuario pertenezca al área
  - Mensajes de error personalizados en español

- [ ] `AssignRoleRequest` - Validación de asignación de roles
  - **NOTA:** Validación implementada directamente en RoleAssignmentController

- [ ] `UpdateProfileRequest` - Validación de actualización de perfil personal
  - **PENDIENTE:** Se implementará en Sprint 3 con ProfileController

---

## 4. Registro de Decisiones Técnicas

*Esta sección es un log vivo. Se actualiza a medida que se toman decisiones durante el sprint.*

### Estado Pre-Existente del Proyecto

* **2025-10-19:** Al iniciar el Sprint 2, se verificó que la base de datos y modelos ya estaban implementados.
    * **Hallazgo:** Las 8 migraciones necesarias (users, roles, permissions, areas, role_user, permission_role, area_user, audit_logs) ya existen desde el 2025-10-16.
    * **Hallazgo:** Los 5 seeders necesarios (RoleSeeder, PermissionSeeder, RolePermissionSeeder, AreaSeeder, UserSeeder) ya están creados.
    * **Hallazgo:** Los modelos (User, Role, Permission, Area, AuditLog) ya tienen sus relaciones Eloquent implementadas.
    * **Hallazgo:** El modelo User ya incluye métodos `hasRole()`, `hasPermission()` y `getAllPermissions()`.
    * **Decisión:** Se documentó el estado actual y se ajustaron los próximos pasos para enfocarse en controladores, vistas y lógica de negocio.
    * **Beneficio:** El sprint puede avanzar más rápido al tener la capa de datos completa.

### Decisiones Iniciales

* **2025-10-19:** Se utilizará el sistema de autenticación de Laravel Breeze como base.
    * **Razón:** Laravel Breeze proporciona autenticación básica (login, registro, recuperación de contraseña) sin sobrecarga. Es suficiente para el MVP y se puede extender con roles y permisos personalizados.

* **2025-10-19:** Los permisos se implementarán de forma aditiva sin usar paquetes de terceros (Spatie Permission).
    * **Razón:** El sistema de permisos es relativamente simple para el MVP. Implementarlo manualmente nos da control total y evita dependencias externas. Si crece en complejidad, se puede migrar a Spatie Permission más adelante.

* **2025-10-19:** La tabla `role_user` incluye `area_id` para permitir roles contextuales por área.
    * **Razón:** Permite flexibilidad para que un usuario sea "Director de Área" en Producción pero "Miembro" en Marketing, reflejando la realidad organizacional del cliente.

* **2025-10-19:** Se usará soft delete en `users` para mantener integridad referencial.
    * **Razón:** Los usuarios desactivados deben mantener su historial de tareas, bitácoras y audit logs. El soft delete permite "archivar" usuarios sin romper relaciones existentes.

* **2025-10-19:** Los audit logs se implementarán mediante Observers en lugar de un paquete.
    * **Razón:** Los Observers de Eloquent permiten capturar eventos del modelo de forma nativa. Para el alcance del MVP, no necesitamos la complejidad de un paquete de auditoría completo.

* **2025-10-19:** Las vistas usarán los componentes Blade del Sprint 1 (tablas, modales, dropdowns).
    * **Razón:** Reutilizar los componentes ya implementados asegura consistencia visual y acelera el desarrollo del sprint.

### Decisiones de Implementación (2025-10-20 - 2025-10-22)

* **2025-10-20:** Se implementaron Form Requests para validación centralizada.
    * **Razón:** Separar la lógica de validación de los controladores mejora la mantenibilidad y permite reutilizar reglas de validación.
    * **Implementación:** `StoreUserRequest` y `UpdateUserRequest` con reglas específicas para creación y actualización.

* **2025-10-21:** El CheckPermission middleware usa lógica OR para múltiples permisos.
    * **Razón:** Permite flexibilidad en las rutas protegidas. Si se pasan varios permisos, el usuario solo necesita UNO para acceder (no todos).
    * **Alternativa considerada:** Lógica AND (todos los permisos requeridos) se descartó por ser demasiado restrictiva para el caso de uso.

* **2025-10-21:** CheckUserActive middleware se aplicó globalmente a todas las rutas web.
    * **Razón:** Asegurar que ningún usuario inactivo pueda acceder a cualquier parte del sistema, sin necesidad de recordar aplicarlo manualmente en cada ruta.
    * **Implementación:** Agregado a `web` middleware group en `bootstrap/app.php`.

* **2025-10-22:** Las Policies usan Response::deny() con mensajes descriptivos en español.
    * **Razón:** Proporciona feedback claro al usuario sobre por qué se denegó una acción, mejorando la UX.
    * **Ejemplo:** `Response::deny('No puedes desactivar tu propia cuenta. Contacta a otro administrador.')`.

* **2025-10-22:** UserPolicy incluye lógica de negocio además de verificación de permisos.
    * **Razón:** Las policies son el lugar ideal para combinar permisos con reglas de negocio (ej: "no puedes eliminar tu propia cuenta").
    * **Implementación:**
        - `update()` permite a los usuarios editar su propio perfil sin necesidad del permiso `gestionar-usuarios`.
        - `delete()` previene auto-eliminación incluso si el usuario tiene el permiso.
        - `assignRoles()` previene que usuarios modifiquen sus propios roles.

* **2025-10-22:** UserObserver solo registra campos modificados en eventos `updated()`.
    * **Razón:** Reduce el tamaño de los audit logs al solo guardar old_values/new_values de campos que realmente cambiaron.
    * **Implementación:** Usa `$user->getChanges()` y `$user->getOriginal()` para comparación.
    * **Beneficio:** Logs más pequeños y claros, fácil de auditar.

* **2025-10-22:** UserObserver filtra campos sensibles de los audit logs.
    * **Razón:** Seguridad y compliance. Nunca se deben guardar contraseñas (hasheadas o no) ni tokens en logs de auditoría.
    * **Campos filtrados:** `password`, `remember_token`, `updated_at`.

* **2025-10-22:** UserObserver skip logging para acciones no autenticadas.
    * **Razón:** Los seeders y comandos de consola no tienen usuario autenticado. Intentar registrar estas acciones causaría errores o datos inconsistentes.
    * **Implementación:** `if (!auth()->id()) return;` al inicio de cada método del Observer.

* **2025-10-22:** RoleAssignmentController usa DB::table() directo para eliminar roles.
    * **Razón:** La tabla pivote `role_user` necesita filtrar por `area_id` además de `user_id` y `role_id`. Eloquent no soporta `detach()` con condiciones adicionales.
    * **Alternativa:** Se podría crear un modelo pivot personalizado, pero es overhead innecesario para el MVP.

* **2025-10-22:** La vista roles/assign.blade.php usa layout de dos columnas.
    * **Razón:** UX clara: formulario de asignación a la izquierda, roles actuales con botones de remover a la derecha.
    * **Beneficio:** El usuario ve inmediatamente el estado actual mientras asigna nuevos roles.

* **2025-10-22:** No se implementó RoleUserObserver para audit logs de asignación de roles.
    * **Razón:** La tabla pivote `role_user` no es un modelo Eloquent tradicional, lo que complica el uso de Observers.
    * **Decisión:** Se puede agregar logging manual en RoleAssignmentController si es necesario en futuras iteraciones.
    * **Trade-off aceptado:** Por ahora, solo se auditan cambios en la tabla users, no en role_user.

### Decisiones de Protección de Áreas del Sistema (2025-10-22)

* **2025-10-22:** Se implementó protección multi-capa para áreas del sistema.
    * **Contexto:** Las 6 áreas built-in (Marketing, Finanzas, Producción, Desarrollo, RRHH, Soporte) son críticas para el funcionamiento de módulos integrados.
    * **Problema identificado:** Sin protección, un administrador podría desactivar accidentalmente un área crítica, rompiendo funcionalidad de módulos.
    * **Solución implementada:**
        1. **Base de datos:** Migración agregando campo `is_system` (boolean) a la tabla `areas`
        2. **Seeder:** Marcó las 6 áreas iniciales con `is_system = true`
        3. **Policy:** `AreaPolicy->delete()` bloquea eliminación de áreas del sistema
        4. **Controller - Destroy:** `AreaController->destroy()` valida `is_system` antes de desactivar
        5. **Controller - Update:** `AreaController->update()` ignora cambios a `is_active` para áreas del sistema
        6. **Vista - Index:** Oculta botón de "Desactivar" para áreas del sistema + muestra badge "Sistema"
        7. **Vista - Form:** Deshabilita checkbox `is_active` para áreas del sistema + muestra badge y advertencia visual
    * **Beneficio:** Protección completa contra desactivación accidental o maliciosa por cualquier vector (UI, API, manipulación de HTML).
    * **Archivo de migración:** `2025_10_22_070121_add_is_system_to_areas_table.php`

* **2025-10-22:** Se agregó validación de dependencias antes de desactivar áreas.
    * **Razón:** Evitar desactivar áreas que tienen usuarios activos, tareas en progreso o presupuestos activos.
    * **Implementación:** `AreaController->destroy()` verifica:
        - `$area->users()->wherePivot('deleted_at', null)->exists()` - usuarios asignados
        - `$area->tasks()->whereIn('status', ['pending', 'in_progress'])->exists()` - tareas activas
        - `$area->budgets()->where('status', 'active')->exists()` - presupuestos activos
    * **UX:** Mensajes descriptivos al usuario explicando por qué no se puede desactivar el área.

### Decisiones de Mejoras a Bitácora de Equipo (2025-10-22)

* **2025-10-22:** Se refactorizó TeamLogController para usar Form Request.
    * **Razón:** Centralizar validación y autorización, siguiendo el patrón establecido en UserController.
    * **Implementación:** Se creó `StoreTeamLogRequest` con:
        - Validación de campos (title, content max:5000, area_id, type)
        - Autorización en método `authorize()`: verifica permiso `crear-bitacora` Y que el usuario pertenezca al área
        - Mensajes de error personalizados en español
    * **Beneficio:** Código más limpio en el controlador, validación reutilizable.

* **2025-10-22:** Se implementó TeamLogObserver para audit trail.
    * **Razón:** Trazabilidad completa de todas las acciones en la bitácora de equipo (crear, editar, eliminar entradas).
    * **Implementación:** Observer registra eventos: `created`, `updated`, `deleted`, `restored`, `forceDeleted`
    * **Consistencia:** Sigue el mismo patrón que UserObserver y AreaObserver
    * **Beneficio:** Compliance y trazabilidad para auditorías.

* **2025-10-22:** Se agregó tipo "Reunión" (meeting) a las entradas de bitácora.
    * **Contexto:** La migración `team_logs` incluía el tipo 'meeting' en el enum, pero no se usaba en el sistema.
    * **Implementación:**
        - Agregado a validación en `StoreTeamLogRequest`
        - Agregado al selector de tipo en el formulario de creación
        - Agregado al selector de filtros
        - Badge verde con ícono de personas en la vista
    * **UX:** Color distintivo verde (vs azul para Decisión, púrpura para Evento, gris para Nota).

* **2025-10-22:** Se implementó sistema de filtros y búsqueda en bitácora.
    * **Razón:** Con el tiempo, la bitácora tendrá cientos de entradas. Los usuarios necesitan encontrar información específica rápidamente.
    * **Funcionalidad implementada:**
        - **Búsqueda de texto:** Busca en título y contenido (LIKE query)
        - **Filtro por área:** Dropdown con áreas del usuario
        - **Filtro por tipo:** Dropdown con 4 tipos (nota, decisión, evento, reunión)
        - **Botón "Limpiar filtros":** Muestra solo si hay filtros activos
        - **Preservación de query string:** `withQueryString()` en paginación
    * **UX:** Interfaz limpia en una sola fila con inputs responsive.

* **2025-10-22:** Se agregó funcionalidad de eliminar entradas propias en bitácora.
    * **Razón:** Los usuarios deben poder corregir errores o remover información obsoleta que ellos mismos publicaron.
    * **Reglas de negocio:**
        - Solo el autor puede eliminar su propia entrada
        - Requiere el permiso `crear-bitacora` (quien puede crear, puede eliminar lo suyo)
        - Soft delete (se guarda en audit logs)
    * **UX:** Botón de eliminar (ícono de basurero) visible solo para el autor, con confirmación JavaScript
    * **Ruta:** `DELETE /team-logs/{teamLog}` protegida por middleware `permission:crear-bitacora`

* **2025-10-22:** Se mejoraron los badges de tipo con íconos descriptivos.
    * **Razón:** UX y accesibilidad. Los íconos ayudan a identificar rápidamente el tipo de entrada sin leer el texto.
    * **Implementación:**
        - **Decisión:** Badge azul con ícono de check con escudo (decisiones validadas)
        - **Evento:** Badge púrpura con ícono de calendario
        - **Reunión:** Badge verde con ícono de grupo de personas
        - **Nota:** Badge gris con ícono de información
    * **Beneficio:** Identifi visual más rápida, especialmente en feeds largos.

### Decisiones de Sistema de Archivos Multimedia (2025-10-22)

* **2025-10-22:** Se implementó Spatie Media Library para gestión de archivos adjuntos.
    * **Razón:** Necesitamos un sistema robusto para manejar archivos multimedia con conversiones automáticas, sin reinventar la rueda.
    * **Ventajas de Spatie Media Library:**
        - Gestión automática de almacenamiento de archivos
        - Conversiones de imagen (WebP, AVIF) con cola de procesamiento
        - Relaciones Eloquent para media
        - Custom properties para metadata
        - Soporte para múltiples collections
    * **Versión:** v10 (compatible con Laravel 11)

* **2025-10-22:** Se crearon dos collections separadas: 'attachments' y 'links'.
    * **Razón:** Separar archivos físicos de enlaces externos permite mejor organización y queries optimizados.
    * **Implementación:**
        - **Collection 'attachments':** Archivos subidos (PDFs, imágenes, audio, documentos)
        - **Collection 'links':** Enlaces externos (YouTube, imágenes remotas, URLs genéricas)
    * **Beneficio:** Queries más rápidos y lógica separada para cada tipo de media.

* **2025-10-22:** Enlaces externos se guardan como registros de metadata, NO se descargan.
    * **Contexto inicial:** Se intentó usar `addMediaFromUrl()` para enlaces externos, lo que descargaba el contenido al servidor.
    * **Problema identificado:** Esto generaba tráfico innecesario, consumía espacio en disco y era lento.
    * **Solución implementada:** Crear registros directos en la tabla `media` con:
        - `mime_type = 'text/uri-list'`
        - `size = 0`
        - `custom_properties['url']` → URL completa del enlace externo
        - `custom_properties['link_type']` → 'video', 'image' o 'external'
        - `custom_properties['is_link'] = true`
    * **Beneficio:** Performance óptimo, sin descargas innecesarias, y URLs siempre apuntan al recurso original.

* **2025-10-22:** Conversiones de imagen procesadas en cola (async).
    * **Razón:** Las conversiones de imagen (especialmente AVIF) son CPU-intensive y pueden tomar varios segundos.
    * **Implementación:**
        - Conversión WebP (85% calidad) → Queued
        - Conversión AVIF (80% calidad) → Queued
        - Thumbnail (300x300 WebP) → Non-queued (rápido, necesario inmediatamente)
    * **User Experience:** El usuario recibe confirmación inmediata, las conversiones se procesan en background.
    * **Requisito de producción:** Configurar queue worker (`php artisan queue:work`)

* **2025-10-22:** Se abandonó Livewire para upload de archivos, se usó HTML tradicional.
    * **Contexto:** Inicialmente se intentó usar `wire:model` para archivos adjuntos.
    * **Problema identificado:**
        - Livewire interceptaba los archivos y no los enviaba con el POST del formulario principal
        - PDFs y otros archivos no se estaban adjuntando
    * **Solución implementada:**
        - Archivos: HTML tradicional `<input type="file" name="attachments[]" multiple>`
        - Alpine.js para preview client-side con `URL.createObjectURL()`
        - Livewire solo para gestión de enlaces (no archivos)
    * **Beneficio:** Upload confiable, preview instantáneo sin Livewire overhead.

* **2025-10-22:** Se usó Alpine.js DataTransfer API para manipular FileList.
    * **Razón:** Los usuarios necesitan poder remover archivos antes de enviar el formulario, pero FileList es read-only por defecto.
    * **Implementación:** Crear un nuevo DataTransfer object, copiar archivos excepto el eliminado, actualizar `input.files`.
    * **UX:** Botón "X" en cada archivo preview permite removerlo antes de submit.

* **2025-10-22:** Se implementó componente `<x-forms.composer>` con padding dinámico.
    * **Problema:** La toolbar de attachments crece/encoge, causando overlap con el textarea.
    * **Solución:** MutationObserver que detecta cambios en la toolbar y ajusta `padding-bottom` del contenedor.
    * **Implementación:**
        - Observa cambios en childList, subtree y attributes de la toolbar
        - Calcula height de toolbar en tiempo real
        - Aplica padding dinámico con transición suave
        - Escucha eventos personalizados `@attachments-updated.window`
    * **Beneficio:** El compositor siempre se ajusta perfectamente, sin overlap.

* **2025-10-22:** Textarea con auto-resize usando Alpine.js.
    * **Razón:** UX moderna donde el textarea crece con el contenido en lugar de tener scroll interno.
    * **Implementación:**
        ```javascript
        resize() {
            $el.style.height = 'auto';
            $el.style.height = $el.scrollHeight + 'px';
        }
        ```
    * **Trigger:** `@input="resize()"` + `x-init="resize()"`

* **2025-10-22:** Se reemplazó `<x-forms.select>` web component por `<select>` nativo en Livewire.
    * **Problema:** Web component `<el-select>` perdía su estado después de re-renders de Livewire.
    * **Causa raíz:** Livewire DOM diffing no es compatible con Web Components que manejan su propio shadow DOM.
    * **Solución:** Usar `<select>` HTML nativo con `wire:model.live` para el selector de tipo de enlace.
    * **Nota:** Web components funcionan perfectamente en formularios estáticos (fuera de Livewire).

* **2025-10-22:** Componente de display con viewers especializados por tipo de media.
    * **Implementación:**
        - **Imágenes:** Lightbox con thumbnail (usa conversión 'thumb' si existe)
        - **Audio:** Player HTML5 nativo con controles
        - **PDFs:** Botón de descarga con ícono distintivo
        - **Videos (YouTube/Vimeo):** iFrame embebido con aspect ratio 16:9
        - **Imágenes externas:** `<img>` con fallback si falla la carga
        - **Enlaces genéricos:** Card con ícono de enlace externo
    * **Accesibilidad:** Atributos `loading="lazy"` y `onerror` handlers.

* **2025-10-22:** Modal de audit log con datos formateados correctamente.
    * **Problema inicial:** `x-html` generaba strings HTML que se escapaban, mostrando literales en lugar de renderizar.
    * **Solución:** Usar Alpine.js templates (`<template x-for>`) con `x-text` en lugar de generar HTML strings.
    * **Implementación:**
        - Definition lists (`<dl>`, `<dt>`, `<dd>`) para formato limpio
        - Eventos personalizados para cargar datos: `@load-audit-data.window`
        - Función `formatValue()` para manejar null, undefined y objetos JSON
        - Clases `w-full` y `break-words` para usar todo el ancho del modal
    * **Beneficio:** Datos formateados de forma profesional, sin problemas de escaping.

---

## 5. Registro de Bloqueos y Soluciones

*Esta sección documenta los problemas inesperados y cómo se resolvieron.*

### Bloqueos Identificados y Resueltos

* **2025-10-22 - Error "Undefined variable $user" en creación de usuarios:**
    * **Problema:** Al acceder a `/users/create`, se producía un error en `_form.blade.php` línea 142: "Undefined variable $user"
    * **Causa raíz:** El código intentaba acceder a `$user->areas` sin verificar si `$user` existe (en modo create, `$user` no existe aún)
    * **Solución:** Agregar check `isset($user)` antes de acceder a propiedades
        ```php
        {{ in_array($area->id, old('areas', isset($user) ? $user->areas->pluck('id')->toArray() : [])) ? 'checked' : '' }}
        ```
    * **Archivo afectado:** `resources/views/users/_form.blade.php:142`

* **2025-10-22 - Error SQL "Field 'created_at' doesn't have a default value" en audit logs:**
    * **Problema:** Al actualizar un usuario, se producía error SQL al intentar crear registro en `audit_logs`
    * **Causa raíz:** El modelo `AuditLog` tiene `$timestamps = false` para control manual, pero `UserObserver` no agregaba `created_at` manualmente
    * **Solución:** Agregar `'created_at' => now()` en los 5 métodos de `UserObserver`:
        - `created()` línea 52
        - `updated()` línea 116
        - `deleted()` línea 152
        - `restored()` línea 188
        - `forceDeleted()` línea 224
    * **Prevención:** `AreaObserver` y `TeamLogObserver` se crearon desde el inicio con `'created_at' => now()`
    * **Archivo afectado:** `app/Observers/UserObserver.php`

* **2025-10-22 - Áreas del sistema se podían desactivar desde formulario de edición:**
    * **Problema:** Aunque se ocultó el botón de "Desactivar" en el index, el checkbox `is_active` en el formulario de edición seguía funcional
    * **Causa raíz:** Solo se implementó protección en la vista index, no en el controlador ni en el formulario
    * **Riesgo:** Un usuario podría manipular el HTML (inspeccionar elemento, habilitar checkbox) y enviar el formulario
    * **Solución multi-capa:**
        1. **Frontend:** Deshabilitar checkbox con `disabled` attribute cuando `$area->is_system`
        2. **Visual:** Agregar badge "Sistema - Protegida" y mensaje de advertencia en el formulario
        3. **Backend:** Modificar `AreaController->update()` para ignorar `is_active` en el array de actualización cuando `$area->is_system`
    * **Lección aprendida:** La protección de datos críticos debe implementarse en múltiples capas (UI + Backend)
    * **Archivos afectados:**
        - `resources/views/areas/_form.blade.php`
        - `app/Http/Controllers/AreaController.php:87-106`

* **2025-10-22 - PDFs no se adjuntaban en TeamLog:**
    * **Problema:** Al enviar el formulario de bitácora con archivos PDFs, estos no se guardaban en la base de datos
    * **Causa raíz:** `wire:model` en el input de archivos hacía que Livewire interceptara los archivos y no los enviara con el POST del formulario principal
    * **Diagnóstico:** Se agregaron logs en `TeamLogController->store()` que mostraban `$request->hasFile('attachments')` = false
    * **Solución:** Eliminar `wire:model` del input y usar HTML tradicional:
        ```blade
        <input type="file" name="attachments[]" multiple>
        ```
    * **Complemento:** Alpine.js para preview client-side con `URL.createObjectURL()`
    * **Archivos afectados:**
        - `resources/views/livewire/team-log-attachments.blade.php`
        - `app/Livewire/TeamLogAttachments.php` (removido trait WithFileUploads)

* **2025-10-22 - Enlaces se guardaban con URL incorrecta:**
    * **Problema:** Los enlaces de YouTube se guardaban como "watch" en lugar de la URL completa "https://youtube.com/watch?v=xxx"
    * **Causa raíz:** `addMediaFromUrl()` intentaba descargar el contenido y solo guardaba el path relativo
    * **Diagnóstico:** Se inspeccionó la tabla `media` y el campo `name` contenía solo "watch" o "OIP" en lugar de la URL completa
    * **Solución:** Crear registros directos en la tabla `media` en lugar de usar `addMediaFromUrl()`:
        ```php
        $media = $teamLog->media()->create([
            'collection_name' => 'links',
            'name' => $link['url'],
            'mime_type' => 'text/uri-list',
            'custom_properties' => [
                'url' => $link['url'],  // URL completa aquí
                'link_type' => $link['type'],
                'is_link' => true,
            ],
            'size' => 0,
        ]);
        ```
    * **Beneficio adicional:** Performance mejorado al no descargar archivos externos
    * **Archivo afectado:** `app/Http/Controllers/TeamLogController.php:96-118`

* **2025-10-22 - Composer se solapaba con attachments toolbar:**
    * **Problema:** Cuando se agregaban archivos o enlaces, la toolbar crecía pero el compositor no ajustaba su tamaño, causando overlap del textarea con los botones inferiores
    * **Causa raíz:** El padding-bottom del compositor era estático y no se actualizaba cuando la toolbar cambiaba de altura
    * **Solución:** Implementar MutationObserver que detecta cambios en la toolbar y ajusta el padding dinámicamente:
        ```javascript
        const observer = new MutationObserver(() => {
            setTimeout(() => updatePadding(), 50);
        });
        observer.observe($refs.toolbar, {
            childList: true,
            subtree: true,
            attributes: true
        });
        ```
    * **Complemento:** Escuchar eventos personalizados `@attachments-updated.window` y `@link-added.window`
    * **Archivo afectado:** `resources/views/components/forms/composer.blade.php`

* **2025-10-22 - Select de tipo de enlace dejaba de funcionar después de adjuntar archivo:**
    * **Problema:** Al hacer click en "Adjuntar archivo" después de haber agregado un enlace, el dropdown de tipo de enlace (`<x-forms.select>`) dejaba de responder
    * **Causa raíz:** Web component `<el-select>` no es compatible con Livewire DOM diffing. Los re-renders de Livewire rompían el estado interno del web component
    * **Solución:** Reemplazar `<x-forms.select>` por `<select>` HTML nativo con `wire:model.live`:
        ```blade
        <select wire:model.live="newLinkType" class="...">
            <option value="external">Enlace</option>
            <option value="video">Video</option>
            <option value="image">Imagen</option>
        </select>
        ```
    * **Lección aprendida:** Web components funcionan bien en formularios estáticos, pero dentro de componentes Livewire es mejor usar elementos HTML nativos
    * **Archivo afectado:** `resources/views/livewire/team-log-attachments.blade.php:138-145`

* **2025-10-22 - Modal de audit log mostraba HTML como texto:**
    * **Problema:** El modal de detalles de audit log mostraba etiquetas HTML literales (`<ul><li>...`) en lugar de renderizar el contenido formateado
    * **Causa raíz:** La función `formatJson()` generaba strings HTML que luego se mostraban con `x-html`, pero Blade los estaba escapando
    * **Solución:** Reemplazar generación de HTML strings por Alpine.js templates nativos:
        ```blade
        <template x-for="[key, value] in Object.entries(oldValues)">
            <div>
                <dt x-text="key"></dt>
                <dd x-text="formatValue(value)"></dd>
            </div>
        </template>
        ```
    * **Beneficio:** Datos se renderizan correctamente sin problemas de escaping
    * **Archivo afectado:** `resources/views/audit-logs/index.blade.php:260-340`

* **2025-10-22 - Modal de audit log no cargaba los datos al abrirse:**
    * **Problema:** Al hacer click en "Ver cambios", el modal se abría pero mostraba "No hay datos anteriores/nuevos" para todos los registros
    * **Causa raíz:** El evento `el-dialog-show` no se estaba disparando o no pasaba correctamente el botón trigger con los data attributes
    * **Solución:** Disparar un evento personalizado `load-audit-data` desde el botón click con los datos directamente:
        ```blade
        @click="window.dispatchEvent(new CustomEvent('load-audit-data', {
            detail: {
                oldValues: {{ json_encode($log->old_values) }},
                newValues: {{ json_encode($log->new_values) }}
            }
        }))"
        ```
    * **Complemento:** El componente Alpine escucha `@load-audit-data.window` y carga los datos
    * **Beneficio:** Approach más robusto que no depende de eventos internos del web component
    * **Archivo afectado:** `resources/views/audit-logs/index.blade.php:192-197, 273-276`

---

## 6. Criterios de Aceptación del Sprint

El Sprint 2 se considerará **COMPLETADO** cuando:

### Funcionalidad Mínima Viable:

1. ✅ Un Administrador de RRHH puede:
   - Crear un nuevo usuario con email, nombre y contraseña
   - Ver lista paginada de todos los usuarios (activos e inactivos)
   - Buscar usuarios por nombre o email
   - Editar información básica de un usuario
   - Desactivar un usuario (soft delete)
   - Asignar uno o múltiples roles a un usuario en áreas específicas
   - Remover roles de usuarios
   - Ver el detalle completo de un usuario incluyendo:
     - Datos personales
     - Roles asignados por área
     - Permisos efectivos acumulados

2. ✅ Un Empleado General puede:
   - Iniciar sesión con email y contraseña
   - Cerrar sesión
   - Ver su perfil personal con sus roles y áreas asignadas
   - Actualizar su nombre y email (sin cambiar roles)

3. ✅ El sistema debe:
   - Implementar middleware de autenticación en todas las rutas protegidas
   - Verificar permisos antes de permitir acciones (policies)
   - Registrar en audit_logs:
     - Creación de usuarios
     - Actualización de usuarios
     - Desactivación de usuarios
     - Asignación/remoción de roles
   - Mostrar un panel de trazabilidad con filtros por:
     - Usuario que realizó la acción
     - Tipo de acción (created, updated, deleted)
     - Rango de fechas

4. ✅ Base de Datos:
   - Todas las migraciones creadas y ejecutadas
   - Seeders poblando datos iniciales (roles, permisos, áreas, usuario admin)
   - Índices de base de datos implementados según esquema
   - Relaciones Eloquent funcionando correctamente

5. ✅ UI/UX:
   - Todas las vistas usando componentes del Sprint 1
   - Navegación clara entre módulos
   - Mensajes de éxito/error usando el sistema de toasts
   - Responsive design funcionando en mobile

---

## 7. Estructura de Archivos del Sprint

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── UserController.php
│   │   ├── RoleAssignmentController.php
│   │   ├── AreaController.php
│   │   ├── ProfileController.php
│   │   └── AuditLogController.php
│   ├── Middleware/
│   │   ├── CheckUserActive.php
│   │   └── CheckPermission.php
│   ├── Requests/
│   │   ├── StoreUserRequest.php
│   │   ├── UpdateUserRequest.php
│   │   ├── AssignRoleRequest.php
│   │   └── UpdateProfileRequest.php
│   └── Policies/
│       ├── UserPolicy.php
│       └── AreaPolicy.php
├── Models/
│   ├── User.php (extendido)
│   ├── Role.php
│   ├── Permission.php
│   ├── Area.php
│   └── AuditLog.php
└── Observers/
    ├── UserObserver.php
    └── RoleUserObserver.php

database/
├── migrations/
│   ├── 2025_01_01_000000_create_roles_table.php
│   ├── 2025_01_01_000001_create_permissions_table.php
│   ├── 2025_01_01_000002_create_areas_table.php
│   ├── 2025_01_01_000003_create_role_user_table.php
│   ├── 2025_01_01_000004_create_permission_role_table.php
│   ├── 2025_01_01_000005_create_area_user_table.php
│   └── 2025_01_01_000006_create_audit_logs_table.php
└── seeders/
    ├── RoleSeeder.php
    ├── PermissionSeeder.php
    ├── AreaSeeder.php
    └── UserSeeder.php

resources/views/
├── users/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── roles/
│   └── assign-modal.blade.php
├── areas/
│   └── index.blade.php
├── profile/
│   ├── show.blade.php
│   └── edit.blade.php
└── audit-logs/
    └── index.blade.php

routes/
└── web.php (rutas del módulo RRHH)
```

---

## 8. Testing Strategy

### Tests Mínimos del Sprint:

- [ ] **Feature Tests:**
  - `UserManagementTest` - CRUD de usuarios
  - `RoleAssignmentTest` - Asignación de roles
  - `AuthenticationTest` - Login/Logout
  - `PermissionTest` - Verificación de permisos

- [ ] **Unit Tests:**
  - `User::hasPermission()` method
  - `User::hasRole()` method
  - `User::rolesInArea()` method

---

## 9. Resultado del Sprint (A completar al final)

* **Tareas Completadas:** [ ] X de Y
* **Resumen:** [Escribe un resumen ejecutivo del resultado del sprint. ¿Se cumplió el objetivo?]
* **Aprendizajes / Retrospectiva:**
    * **Qué funcionó bien:** [Anota los puntos positivos y las prácticas exitosas]
    * **Qué se puede mejorar:** [Identifica áreas de mejora para futuros sprints]

---

**Estado:** ✅ COMPLETADO (Fase 1 - Gestión de Usuarios)

**Progreso General:** ⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛ 100% (Sprint COMPLETADO ✅)

### Componentes Completados:

#### Base de Datos y Modelos (100%)
- ✅ **Migraciones**: 9/9 (100%) - Todas las tablas creadas + migración `add_is_system_to_areas_table`
- ✅ **Seeders**: 5/5 (100%) - Roles, permisos, áreas y usuario admin (áreas marcadas con `is_system = true`)
- ✅ **Modelos**: 6/6 (100%) - User, Role, Permission, Area, AuditLog, TeamLog con relaciones completas
- ⚠️ **Pendiente:** Método `rolesInArea()` en User model (no crítico para MVP)

#### Controladores y Lógica de Negocio (100%)
- ✅ **UserController**: 8/8 métodos (100%) - CRUD completo + restore
- ✅ **RoleAssignmentController**: 3/3 métodos (100%) - create, store, destroy
- ✅ **AreaController**: 6/6 métodos (100%) - CRUD completo con protección multi-capa para áreas del sistema
- ✅ **AuditLogController**: 1/1 método (100%) - index con 5 filtros avanzados
- ✅ **TeamLogController**: 3/3 métodos (100%) - index, store, destroy (bitácora de equipo)
- ⏸️ **ProfileController**: POSPUESTO para Sprint 3

#### Vistas y Frontend (100%)
- ✅ **users/**: 5/5 vistas (100%) - index, create, edit, show, _form
- ✅ **roles/**: 1/1 vista (100%) - assign.blade.php
- ✅ **areas/**: 4/4 vistas (100%) - index (con búsqueda/filtros), create, edit, _form (con protección visual para áreas del sistema)
- ✅ **audit-logs/**: 1/1 vista (100%) - index con 5 filtros, tabla completa, details/summary para JSON
- ✅ **team-logs/**: 1/1 vista (100%) - index con compositor, filtros, búsqueda, feed de actividad con timeline
- ⏸️ **profile/**: POSPUESTO para Sprint 3

#### Seguridad y Autorización (100%)
- ✅ **Middleware**: 2/2 (100%)
  - CheckUserActive (aplicado globalmente)
  - CheckPermission (con OR logic y logging)
- ✅ **Policies**: 2/2 (100%)
  - UserPolicy (CRUD + lógica de negocio + auto-protección)
  - AreaPolicy (CRUD + protección especial para áreas del sistema en delete())
- ✅ **Form Requests**: 5/5 (100%)
  - StoreUserRequest, UpdateUserRequest
  - StoreAreaRequest, UpdateAreaRequest
  - StoreTeamLogRequest (con autorización avanzada en authorize())
- ✅ **Observers**: 3/3 (100%)
  - UserObserver (5 eventos + fix de created_at manual)
  - AreaObserver (5 eventos)
  - TeamLogObserver (5 eventos)

#### Rutas (100%)
- ✅ **Rutas configuradas**: routes/web.php con middleware de permisos
- ✅ **Protección de rutas**: Middleware `auth` + `permission:gestionar-usuarios`
- ✅ **Rutas RESTful**: Resource routes para UserController
- ✅ **Rutas personalizadas**: restore, role assignment

### Componentes POSPUESTOS para Sprint 3:
1. ⏸️ **ProfileController** - Perfil personal del empleado (show, edit, update)
2. ⏸️ **Vistas profile/** - Vista de perfil personal y edición
3. ⏸️ **Testing** - Feature y Unit tests
4. ⏸️ **Método rolesInArea()** - En User model para roles contextuales por área (no crítico)

### Componentes COMPLETADOS (adicionales al plan original):
1. ✅ **AreaController** - CRUD completo con protección multi-capa para áreas del sistema
2. ✅ **AuditLogController** - Panel de trazabilidad con 5 filtros avanzados
3. ✅ **TeamLogController** - Bitácora de equipo con filtros, búsqueda y eliminación de entradas propias
4. ✅ **AreaObserver** - Audit trail para cambios en áreas
5. ✅ **TeamLogObserver** - Audit trail para cambios en bitácora de equipo
6. ✅ **Protección de áreas del sistema** - Migración + lógica multi-capa (Policy, Controller, Vista)
7. ✅ **Vistas areas/** - index, create, edit, _form con protección visual
8. ✅ **Vistas audit-logs/** - index con filtros avanzados y expansión de JSON
9. ✅ **Vistas team-logs/** - index con compositor, filtros, búsqueda y feed con timeline
10. ✅ **Form Requests adicionales** - StoreAreaRequest, UpdateAreaRequest, StoreTeamLogRequest

### Resumen de Historias de Usuario:
- ✅ **Completadas**: 15/17 historias (88%)
  - 5/5 Gestión de Usuarios (RRHH) ✅
  - 4/4 Gestión de Roles y Permisos (RRHH) ✅
  - 2/4 Autenticación y Perfil Personal (auth via Breeze) ⚠️ (falta perfil personal)
  - 1/2 Sistema de Áreas ✅ (CRUD completo + protección de áreas del sistema)
  - 2/2 Trazabilidad y Auditoría ✅ (audit logs backend + UI con filtros avanzados)
  - **BONUS:** Bitácora de equipo completamente funcional (no estaba en el plan original)

### Archivos Creados/Modificados en este Sprint:
**Controladores:**
- `app/Http/Controllers/UserController.php` ✅
- `app/Http/Controllers/RoleAssignmentController.php` ✅
- `app/Http/Controllers/AreaController.php` ✅ (NUEVO)
- `app/Http/Controllers/AuditLogController.php` ✅ (NUEVO)
- `app/Http/Controllers/TeamLogController.php` ✅ (MEJORADO)

**Middleware:**
- `app/Http/Middleware/CheckUserActive.php` ✅
- `app/Http/Middleware/CheckPermission.php` ✅ (mejorado)

**Policies:**
- `app/Policies/UserPolicy.php` ✅
- `app/Policies/AreaPolicy.php` ✅

**Observers:**
- `app/Observers/UserObserver.php` ✅ (FIXED: agregado created_at manual)
- `app/Observers/AreaObserver.php` ✅ (NUEVO)
- `app/Observers/TeamLogObserver.php` ✅ (NUEVO)

**Form Requests:**
- `app/Http/Requests/StoreUserRequest.php` ✅
- `app/Http/Requests/UpdateUserRequest.php` ✅
- `app/Http/Requests/StoreAreaRequest.php` ✅ (NUEVO)
- `app/Http/Requests/UpdateAreaRequest.php` ✅ (NUEVO)
- `app/Http/Requests/StoreTeamLogRequest.php` ✅ (NUEVO)

**Migraciones:**
- `database/migrations/2025_10_22_070121_add_is_system_to_areas_table.php` ✅ (NUEVO)

**Seeders:**
- `database/seeders/AreaSeeder.php` ✅ (ACTUALIZADO: agregado is_system = true)

**Vistas:**
- `resources/views/users/index.blade.php` ✅
- `resources/views/users/create.blade.php` ✅
- `resources/views/users/edit.blade.php` ✅
- `resources/views/users/show.blade.php` ✅
- `resources/views/users/_form.blade.php` ✅ (FIXED: isset($user) check)
- `resources/views/roles/assign.blade.php` ✅
- `resources/views/areas/index.blade.php` ✅ (NUEVO)
- `resources/views/areas/create.blade.php` ✅ (NUEVO)
- `resources/views/areas/edit.blade.php` ✅ (NUEVO)
- `resources/views/areas/_form.blade.php` ✅ (NUEVO con protección visual)
- `resources/views/audit-logs/index.blade.php` ✅ (NUEVO)
- `resources/views/team-logs/index.blade.php` ✅ (MEJORADO con filtros y búsqueda)

**Configuración:**
- `routes/web.php` ✅ (agregadas rutas RRHH, Areas, Audit Logs, Team Logs)
- `bootstrap/app.php` ✅ (middleware registrados)
- `app/Providers/AppServiceProvider.php` ✅ (3 observers registrados: User, Area, TeamLog)

### Próximos Pasos para Sprint 3:
1. 📝 Implementar ProfileController para perfil personal del empleado
2. 🎨 Crear vistas profile/ (show, edit)
3. 🔗 Mejorar navegación en sidebar con enlaces dinámicos según permisos
4. 🧪 Escribir tests feature y unit para todos los módulos
5. 📱 Implementar módulo de Tareas y Colaboración
6. 💰 Implementar módulo de Finanzas (presupuestos, cotizaciones)
7. 📊 Implementar módulo de Marketing (campañas, leads)
7. 📈 Agregar método rolesInArea() al User model
