# Diario del Sprint 3: Módulo de Tareas y Colaboración

**Periodo:** 2025-10-23 - TBD

**Épica Maestra en GitHub:** [Pendiente de crear]

---

## 1. Objetivo del Sprint

Implementar el sistema completo de gestión de tareas jerárquicas (tareas y subtareas), permitiendo a los directores crear y asignar trabajo, a los empleados gestionar sus tareas personales, y establecer la base para la colaboración entre áreas del sistema Junior.

---

## 2. Alcance y Tareas Incluidas

### Historias de Usuario del Sprint

Basado en `/docs/04-user-stories.md` - Módulo: Tareas y Colaboración

#### 2.1 Creación y Asignación de Tareas (Director)

**Perfil: Dirección General / Director de Área**

- [ ] `#[ID] - [Tareas] Crear tarea y asignar responsable`
  - Como Director, quiero crear una nueva tarea con título, descripción, fecha límite y prioridad para delegar trabajo
  - Como Director, quiero asignar la tarea a un responsable específico (empleado de mi área) para que sepa que debe realizarla

- [ ] `#[ID] - [Tareas] Crear subtareas (desglose de trabajo)`
  - Como Director de Área, quiero desglosar una tarea en subtareas más pequeñas para organizar el trabajo de forma granular
  - Como Director, quiero que las subtareas tengan su propio responsable y fecha límite para distribuir el trabajo

- [ ] `#[ID] - [Tareas] Ver dashboard de tareas de mi área`
  - Como Director de Área, quiero ver un dashboard con todas las tareas de mi área organizadas por estado para tener visibilidad del progreso
  - Como Director, quiero ver métricas de tareas (total, completadas, pendientes, atrasadas) para evaluar el desempeño del equipo

- [ ] `#[ID] - [Tareas] Reasignar tarea a otro empleado`
  - Como Director, quiero poder reasignar una tarea de un empleado a otro para ajustar la carga de trabajo o cubrir ausencias

#### 2.2 Gestión Personal de Tareas (Empleado)

**Perfil: Empleado General**

- [ ] `#[ID] - [Tareas] Ver mis tareas asignadas`
  - Como Empleado, quiero ver todas mis tareas y subtareas en un solo lugar para tener claridad sobre mi trabajo pendiente
  - Como Empleado, quiero ver las tareas organizadas por fecha límite y prioridad para planificar mi día

- [ ] `#[ID] - [Tareas] Marcar tarea como completada`
  - Como Empleado, quiero marcar tareas como completadas para comunicar mi progreso y liberar mi lista de pendientes
  - Como Empleado, quiero que el sistema registre la fecha y hora de completación para trazabilidad

- [ ] `#[ID] - [Tareas] Ver detalle de tarea con contexto`
  - Como Empleado, quiero ver el detalle completo de una tarea incluyendo descripción, archivos adjuntos y comentarios para entender qué se espera de mí
  - Como Empleado, quiero ver quién asignó la tarea y cuándo para contexto

- [ ] `#[ID] - [Tareas] Filtrar y buscar mis tareas`
  - Como Empleado, quiero filtrar mis tareas por estado (pendiente, en progreso, completada, atrasada) para enfocarme en lo urgente
  - Como Empleado, quiero buscar tareas por título o descripción para encontrar tareas específicas rápidamente

#### 2.3 Gestión Avanzada de Tareas

- [ ] `#[ID] - [Tareas] Agregar comentarios a tareas`
  - Como Empleado o Director, quiero agregar comentarios a una tarea para comunicar actualizaciones, dudas o bloqueos
  - Como usuario, quiero recibir notificación cuando alguien comenta en mis tareas

- [ ] `#[ID] - [Tareas] Adjuntar archivos a tareas`
  - Como Director, quiero adjuntar archivos (documentos, imágenes, PDFs) al crear una tarea para proporcionar contexto o recursos
  - Como Empleado, quiero adjuntar archivos al completar una tarea para entregar evidencia del trabajo realizado

- [ ] `#[ID] - [Tareas] Cambiar estado de tarea (workflow)`
  - Como Empleado, quiero cambiar el estado de una tarea a "En Progreso" cuando empiezo a trabajar en ella para comunicar mi actividad
  - Como Director, quiero poder cambiar el estado de cualquier tarea de mi área para gestionar el workflow

#### 2.4 Perfil Personal del Empleado

**Perfil: Empleado General** (PENDIENTE del Sprint 2)

- [ ] `#[ID] - [Perfil] Ver mi perfil personal`
  - Como Empleado, quiero ver mi propio perfil con mis datos personales, roles asignados y áreas para entender mi posición en la organización
  - Como Empleado, quiero ver un resumen de mis tareas (total asignadas, completadas este mes) en mi perfil

- [ ] `#[ID] - [Perfil] Actualizar mi información personal`
  - Como Empleado, quiero actualizar mi nombre, email y foto de perfil para mantener mis datos actuales
  - Como Empleado, quiero que ciertos campos estén bloqueados (roles, áreas) para evitar errores

---

## 3. Componentes Técnicos a Implementar

### 3.1 Migraciones de Base de Datos

Basado en `/docs/03-database-schema.md`:

- [ ] `tasks` - Tabla principal de tareas
  - `id` (PK)
  - `title` (string, max:255)
  - `description` (text, nullable)
  - `status` (enum: pending, in_progress, completed, cancelled)
  - `priority` (enum: low, medium, high, critical)
  - `due_date` (date, nullable)
  - `completed_at` (timestamp, nullable)
  - `area_id` (FK a areas, índice)
  - `assigned_to` (FK a users, índice, nullable)
  - `assigned_by` (FK a users, índice)
  - `parent_id` (FK a tasks, nullable - para subtareas)
  - `order` (integer, default 0 - para ordenamiento)
  - `timestamps`, `soft_deletes`

- [ ] `task_comments` - Comentarios en tareas
  - `id` (PK)
  - `task_id` (FK a tasks, índice)
  - `user_id` (FK a users, índice)
  - `comment` (text)
  - `timestamps`

- [ ] Índices compuestos para optimización:
  - `tasks(assigned_to, status, due_date)` - Para queries de "mis tareas pendientes"
  - `tasks(area_id, status)` - Para dashboards por área
  - `tasks(parent_id)` - Para queries de subtareas

### 3.2 Seeders

- [ ] `TaskSeeder` - Tareas de demostración
  - 10-15 tareas de ejemplo distribuidas en diferentes áreas
  - Mezcla de estados (pending, in_progress, completed)
  - Algunas con subtareas (2-3 niveles de profundidad)
  - Asignadas a diferentes usuarios

- [ ] `TaskCommentSeeder` - Comentarios de demostración
  - 20-30 comentarios distribuidos en las tareas
  - Conversaciones entre asignador y asignado

### 3.3 Modelos Eloquent

- [ ] `Task` model con relaciones
  - `belongsTo(Area)` - Área a la que pertenece
  - `belongsTo(User, 'assigned_to')` - Empleado asignado
  - `belongsTo(User, 'assigned_by')` - Director que asignó
  - `belongsTo(Task, 'parent_id')` - Tarea padre (si es subtarea)
  - `hasMany(Task, 'parent_id')` - Subtareas
  - `hasMany(TaskComment)` - Comentarios
  - `morphMany(Media)` - Archivos adjuntos (Spatie Media Library)
  - Scope `active()` - Tareas no eliminadas
  - Scope `forUser($userId)` - Tareas asignadas a usuario específico
  - Scope `forArea($areaId)` - Tareas de un área
  - Scope `pending()` - Tareas pendientes
  - Scope `overdue()` - Tareas atrasadas (due_date < now() AND status != completed)
  - Accessor `is_overdue` - Boolean si la tarea está atrasada
  - Accessor `is_subtask` - Boolean si tiene parent_id

- [ ] `TaskComment` model con relaciones
  - `belongsTo(Task)`
  - `belongsTo(User)`

### 3.4 Controladores y Rutas

**Rutas protegidas con autenticación:**

- [ ] `TaskController` - CRUD de tareas (para directores y empleados)
  - `index()` - Listar tareas (vistas diferentes para director vs empleado)
  - `create()` - Formulario de creación de tarea (solo directores)
  - `store()` - Crear tarea (solo directores)
  - `show($id)` - Ver detalle de tarea (con comentarios y subtareas)
  - `edit($id)` - Formulario de edición (solo creador o director de área)
  - `update($id)` - Actualizar tarea
  - `destroy($id)` - Soft delete de tarea (solo creador)
  - `complete($id)` - Marcar tarea como completada (asignado o director)
  - `updateStatus($id)` - Cambiar estado de tarea (workflow)
  - Middleware: `auth`, `permission:ver-tareas` (todos), `permission:crear-tareas` (create/store)

- [ ] `TaskCommentController` - Gestión de comentarios
  - `store($taskId)` - Agregar comentario a tarea
  - `destroy($id)` - Eliminar comentario propio
  - Middleware: `auth`, `permission:ver-tareas`

- [ ] `MyTasksController` - Vista personal del empleado
  - `index()` - Dashboard personal con mis tareas
  - Filtros: estado, prioridad, área, búsqueda
  - Métricas: total asignadas, completadas hoy, atrasadas
  - Middleware: `auth` (no requiere permiso especial)

- [ ] `ProfileController` - Perfil personal del empleado (PENDIENTE Sprint 2)
  - `show()` - Ver mi perfil
  - `edit()` - Formulario de edición de perfil
  - `update()` - Actualizar mi perfil
  - Middleware: `auth` (todos los usuarios autenticados)

### 3.5 Vistas Blade

**Usando los componentes del Sprint 1 y patrones del Sprint 2:**

**Vistas de Gestión de Tareas (Director):**

- [ ] `tasks/index.blade.php` - Lista de tareas del área con tabla (`x-layout.table`)
  - Filtros: estado, prioridad, asignado, búsqueda
  - Columnas: título, asignado, estado, prioridad, fecha límite, acciones
  - Dropdown actions por tarea: Ver, Editar, Eliminar
  - Botón "Nueva Tarea" (solo directores)
  - Métricas en cards: total, pendientes, en progreso, completadas, atrasadas

- [ ] `tasks/create.blade.php` - Formulario de creación de tarea
  - Campos: título, descripción (textarea), área (dropdown), asignado (dropdown de usuarios del área)
  - Fecha límite (date picker), prioridad (select)
  - Opción: "¿Es una subtarea?" → Selector de tarea padre
  - Botón: "Crear Tarea y Adjuntar Archivos" o "Solo Crear Tarea"

- [ ] `tasks/edit.blade.php` - Formulario de edición de tarea
  - Mismos campos que create
  - Botón de reasignar (modal confirmación)
  - Botón de completar tarea (si no está completada)

- [ ] `tasks/show.blade.php` - Vista de detalle de tarea
  - Header: título, estado (badge), prioridad (badge), fecha límite
  - Metadata: creado por, asignado a, área, creado el, completado el
  - Descripción completa
  - Archivos adjuntos (grid de thumbnails con Spatie Media Library)
  - Subtareas (si existen): lista con checkbox, nombre, asignado, estado
  - Sección de comentarios (feed de actividad con timeline)
  - Formulario de comentario (textarea + botón "Comentar")
  - Acciones: Editar, Eliminar, Cambiar Estado, Completar

- [ ] `tasks/_form.blade.php` - Parcial reutilizable para create/edit

**Vistas Personales (Empleado):**

- [ ] `my-tasks/index.blade.php` - Dashboard personal de tareas
  - Tarjetas de métricas: Total asignadas, Completadas hoy, Atrasadas, En progreso
  - Filtros compactos: estado, prioridad, área, búsqueda
  - Lista de tareas (cards o lista compacta)
    - Cada tarea: checkbox para completar, título, área, prioridad, fecha límite, botón "Ver"
  - Agrupación opcional: "Hoy", "Esta semana", "Atrasadas", "Sin fecha límite"

- [ ] `profile/show.blade.php` - Perfil personal del empleado
  - Avatar (UI Avatars o subido)
  - Información personal: nombre, email, teléfono (si existe)
  - Roles y áreas asignadas (badges)
  - Resumen de tareas: Total asignadas, Completadas este mes, Porcentaje de completación
  - Botón "Editar Perfil"

- [ ] `profile/edit.blade.php` - Edición de perfil personal
  - Campos editables: nombre, email, teléfono, avatar
  - Campos readonly: roles, áreas (mostrar pero no editar)
  - Cambio de contraseña (sección separada)
  - Botón "Guardar Cambios"

**Componentes Reutilizables:**

- [ ] `components/tasks/task-card.blade.php` - Card de tarea para listas
  - Props: task, showArea (bool), compact (bool)
  - Checkbox para completar (si asignado puede)
  - Badge de prioridad y estado
  - Indicador de fecha límite (color según si está atrasada)
  - Contador de subtareas y comentarios

- [ ] `components/tasks/task-status-badge.blade.php` - Badge de estado
  - Props: status
  - Colores: pending (gray), in_progress (blue), completed (green), cancelled (red)

- [ ] `components/tasks/priority-badge.blade.php` - Badge de prioridad
  - Props: priority
  - Colores: low (gray), medium (yellow), high (orange), critical (red)

- [ ] `components/tasks/comment-item.blade.php` - Item de comentario en feed
  - Props: comment
  - Avatar del autor, nombre, fecha relativa (hace 2 horas)
  - Texto del comentario
  - Botón eliminar (solo si es autor)

### 3.6 Middleware y Policies

- [ ] `TaskPolicy` - Políticas de autorización para Task model
  - `viewAny()` - Verificar permiso `ver-tareas`
  - `view($task)` - Puede ver: asignado, creador o director del área
  - `create()` - Verificar permiso `crear-tareas` (directores)
  - `update($task)` - Puede editar: creador o director del área
  - `delete($task)` - Puede eliminar: creador o director del área
  - `complete($task)` - Puede completar: asignado o director del área
  - `reassign($task)` - Puede reasignar: creador o director del área
  - Response::deny() con mensajes descriptivos en español

- [ ] Actualizar `CheckPermission` middleware
  - Ya existe del Sprint 2
  - Verificar que funciona con nuevos permisos de tareas

### 3.7 Observers y Events

- [ ] `TaskObserver` - Registrar en audit_logs
  - `created()` - Tarea creada
  - `updated()` - Tarea actualizada (capturar cambios de estado, asignación, completación)
  - `deleted()` - Tarea eliminada (soft delete)
  - `restored()` - Tarea restaurada
  - Filtrar campos sensibles (ninguno por ahora)
  - Incluye `'created_at' => now()` manualmente

- [ ] `TaskCompletedEvent` - Evento cuando tarea se completa
  - Disparado por `Task::complete()` method
  - Payload: task, completed_by (user)
  - Listener: enviar notificación al creador de la tarea

- [ ] `TaskAssignedEvent` - Evento cuando tarea se asigna/reasigna
  - Disparado por `TaskController::store()` y `TaskController::reassign()`
  - Payload: task, assigned_by (user), new_assignee (user)
  - Listener: enviar notificación al nuevo asignado

### 3.8 Validación

- [ ] `StoreTaskRequest` - Validación de creación de tarea
  - `title` requerido, max:255
  - `description` opcional, max:5000
  - `area_id` requerido, exists:areas,id
  - `assigned_to` opcional (puede dejarse sin asignar), exists:users,id
  - `due_date` opcional, date, after_or_equal:today
  - `priority` requerido, in:low,medium,high,critical
  - `parent_id` opcional (para subtareas), exists:tasks,id
  - Mensajes de error personalizados en español
  - **Autorización en authorize():** verificar permiso `crear-tareas` Y que usuario tiene acceso al área

- [ ] `UpdateTaskRequest` - Validación de actualización de tarea
  - Mismas reglas que StoreTaskRequest
  - **Autorización:** verificar que usuario puede editar la tarea (policy)

- [ ] `StoreTaskCommentRequest` - Validación de comentario
  - `comment` requerido, max:1000
  - **Autorización:** verificar que usuario puede ver la tarea

- [ ] `UpdateProfileRequest` - Validación de actualización de perfil personal
  - `name` requerido, max:255
  - `email` requerido, email, unique:users,email,[user_id]
  - `phone` opcional, max:20
  - `avatar` opcional, image, max:2048 (2MB)
  - Mensajes de error personalizados en español

### 3.9 Archivos Adjuntos (Spatie Media Library)

- [ ] **Task Model** - Implementación de HasMedia interface
  - Trait `InteractsWithMedia`
  - Método `registerMediaCollections()`:
    - Collection 'attachments': Archivos relacionados a tareas (documentos, imágenes, PDFs)
  - Método `registerMediaConversions()`:
    - Conversión thumbnail para imágenes (igual que TeamLog)
  - MIME types permitidos: igual que TeamLog
  - Límites: 10MB por archivo

- [ ] **Componente task/attachments-upload.blade.php**
  - Reutilizar lógica de `team-log-attachments.blade.php`
  - Drag & drop de archivos
  - Preview con thumbnails
  - Sin soporte para enlaces externos (solo archivos)

- [ ] **Componente task/attachments-display.blade.php**
  - Grid de thumbnails para imágenes
  - Lista de archivos para documentos
  - Botones de descarga
  - Similar a `team-log/attachments-display.blade.php` pero sin soporte para links

---

## 4. Registro de Decisiones Técnicas

*Esta sección es un log vivo. Se actualiza a medida que se toman decisiones durante el sprint.*

### Decisiones Iniciales (2025-10-23)

* **2025-10-23:** Se usará una estructura jerárquica con `parent_id` para subtareas.
    * **Razón:** Permite flexibilidad para múltiples niveles de anidación sin complicar el esquema de base de datos.
    * **Alternativa considerada:** Tabla separada `tasks` y `subtasks` se descartó por duplicación de esquema.
    * **Límite:** Se recomienda máximo 3 niveles de profundidad para UX clara.

* **2025-10-23:** El campo `assigned_to` es nullable para permitir tareas sin asignar.
    * **Razón:** Los directores pueden querer crear tareas "en borrador" antes de decidir quién las hará.
    * **Beneficio:** Flexibilidad en el workflow de asignación.

* **2025-10-23:** Se usará un campo `status` enum en lugar de boolean `is_completed`.
    * **Razón:** Workflow más rico que permite estados intermedios (pending, in_progress, completed, cancelled).
    * **Beneficio:** Mejor tracking del progreso y más opciones para filtros.

* **2025-10-23:** Las tareas tendrán archivos adjuntos mediante Spatie Media Library.
    * **Razón:** Reutilizar la implementación existente del Sprint 2 (TeamLog) para consistencia.
    * **Consistencia:** Mismo approach de collections, conversiones y validaciones.

* **2025-10-23:** Los comentarios se guardarán en tabla separada `task_comments`.
    * **Razón:** Normalización de datos y queries optimizados (no mezclar con audit logs).
    * **Beneficio:** Permite ordenar, filtrar y paginar comentarios independientemente.

* **2025-10-23:** ProfileController se implementará en este sprint aunque está pendiente del Sprint 2.
    * **Razón:** El dashboard personal de tareas (`my-tasks/index`) necesita enlace al perfil.
    * **Beneficio:** Completar funcionalidad básica de empleados en un solo sprint.

---

## 5. Registro de Bloqueos y Soluciones

*Esta sección documenta los problemas inesperados y cómo se resolvieron.*

---

## 6. Criterios de Aceptación del Sprint

El Sprint 3 se considerará **COMPLETADO** cuando:

### Funcionalidad Mínima Viable:

1. [ ] Un Director puede:
   - Crear una tarea con título, descripción, área, asignado, fecha límite y prioridad
   - Crear subtareas vinculadas a una tarea padre
   - Ver dashboard de tareas de su área con métricas y filtros
   - Reasignar tareas de un empleado a otro
   - Ver detalle de cualquier tarea de su área
   - Editar tareas que creó
   - Eliminar tareas que creó (soft delete)
   - Agregar comentarios a tareas
   - Cambiar el estado de tareas de su área

2. [ ] Un Empleado puede:
   - Ver todas sus tareas asignadas en dashboard personal
   - Filtrar tareas por estado, prioridad y área
   - Buscar tareas por título
   - Ver detalle completo de una tarea asignada
   - Marcar tareas como completadas
   - Cambiar tarea a estado "En Progreso"
   - Agregar comentarios a sus tareas
   - Ver archivos adjuntos en tareas
   - Ver su perfil personal con resumen de tareas
   - Actualizar su información personal (nombre, email, foto)

3. [ ] El sistema debe:
   - Implementar middleware de autenticación y permisos en todas las rutas de tareas
   - Verificar policies antes de permitir acciones (crear, editar, eliminar, completar)
   - Registrar en audit_logs:
     - Creación de tareas
     - Actualización de tareas (especialmente cambios de estado y asignación)
     - Eliminación de tareas
     - Completación de tareas
   - Calcular automáticamente si una tarea está atrasada (`is_overdue`)
   - Mostrar contadores de subtareas en tareas padre
   - Mostrar contadores de comentarios en tareas

4. [ ] Base de Datos:
   - Migraciones de `tasks` y `task_comments` creadas y ejecutadas
   - Seeders poblando tareas de demostración con diferentes estados y asignaciones
   - Índices optimizados para queries comunes (assigned_to, area_id, status)
   - Relaciones Eloquent funcionando correctamente

5. [ ] UI/UX:
   - Todas las vistas usando componentes del Sprint 1 y patrones del Sprint 2
   - Dashboard de director con métricas visuales (cards de estadísticas)
   - Dashboard personal de empleado con vista limpia y filtros
   - Vista de detalle de tarea con comentarios en timeline
   - Badges de prioridad y estado con colores distintivos
   - Navegación clara entre vistas (breadcrumbs o botones de regreso)
   - Responsive design funcionando en mobile

---

## 7. Estructura de Archivos del Sprint

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── TaskController.php (NUEVO)
│   │   ├── TaskCommentController.php (NUEVO)
│   │   ├── MyTasksController.php (NUEVO)
│   │   └── ProfileController.php (NUEVO - pendiente Sprint 2)
│   ├── Requests/
│   │   ├── StoreTaskRequest.php (NUEVO)
│   │   ├── UpdateTaskRequest.php (NUEVO)
│   │   ├── StoreTaskCommentRequest.php (NUEVO)
│   │   └── UpdateProfileRequest.php (NUEVO)
│   └── Policies/
│       └── TaskPolicy.php (NUEVO)
├── Models/
│   ├── Task.php (NUEVO)
│   └── TaskComment.php (NUEVO)
└── Observers/
    └── TaskObserver.php (NUEVO)

database/
├── migrations/
│   ├── 2025_10_23_000001_create_tasks_table.php (NUEVO)
│   └── 2025_10_23_000002_create_task_comments_table.php (NUEVO)
└── seeders/
    ├── TaskSeeder.php (NUEVO)
    └── TaskCommentSeeder.php (NUEVO)

resources/views/
├── tasks/
│   ├── index.blade.php (NUEVO)
│   ├── create.blade.php (NUEVO)
│   ├── edit.blade.php (NUEVO)
│   ├── show.blade.php (NUEVO)
│   └── _form.blade.php (NUEVO)
├── my-tasks/
│   └── index.blade.php (NUEVO)
├── profile/
│   ├── show.blade.php (NUEVO)
│   └── edit.blade.php (NUEVO)
└── components/
    └── tasks/
        ├── task-card.blade.php (NUEVO)
        ├── task-status-badge.blade.php (NUEVO)
        ├── priority-badge.blade.php (NUEVO)
        ├── comment-item.blade.php (NUEVO)
        ├── attachments-upload.blade.php (NUEVO)
        └── attachments-display.blade.php (NUEVO)

routes/
└── web.php (agregar rutas de tareas)
```

---

## 8. Testing Strategy

### Tests Mínimos del Sprint:

- [ ] **Feature Tests:**
  - `TaskManagementTest` - CRUD de tareas (director)
  - `TaskAssignmentTest` - Asignación y reasignación
  - `TaskCompletionTest` - Marcar como completada
  - `TaskCommentTest` - Agregar y eliminar comentarios
  - `MyTasksTest` - Vista personal de empleado
  - `TaskPolicyTest` - Autorización de acciones

- [ ] **Unit Tests:**
  - `Task::is_overdue` accessor
  - `Task::forUser()` scope
  - `Task::overdue()` scope
  - `TaskPolicy::complete()` method

---

## 9. Resultado del Sprint (A completar al final)

* **Tareas Completadas:** [ ] X de Y
* **Resumen:** [Escribe un resumen ejecutivo del resultado del sprint. ¿Se cumplió el objetivo?]
* **Aprendizajes / Retrospectiva:**
    * **Qué funcionó bien:** [Anota los puntos positivos y las prácticas exitosas]
    * **Qué se puede mejorar:** [Identifica áreas de mejora para futuros sprints]

---

**Estado:** 📝 EN PLANIFICACIÓN

**Progreso General:** ⬜⬜⬜⬜⬜⬜⬜⬜⬜⬜ 0% (Sprint NO INICIADO)

### Próximos Pasos para Iniciar el Sprint:

1. 📋 Crear Épica Maestra en GitHub con todas las historias de usuario
2. 📦 Crear migraciones para `tasks` y `task_comments`
3. 🏗️ Implementar modelos Task y TaskComment con relaciones
4. 🎨 Crear seeders con datos de demostración
5. 🔧 Implementar TaskController con CRUD básico
6. 🎨 Crear vistas básicas (index, create, show)
7. 🛡️ Implementar TaskPolicy y validaciones
8. 📊 Implementar dashboard personal (MyTasksController)
9. 👤 Implementar ProfileController (pendiente Sprint 2)
10. 🧪 Escribir tests básicos

---

**Notas Finales:**

Este sprint es fundamental para el MVP ya que el módulo de tareas es el core del sistema de colaboración. Se recomienda implementar primero la funcionalidad básica (crear, asignar, completar tareas) antes de agregar features avanzados como comentarios y archivos adjuntos.

La reutilización de componentes del Sprint 1 y patrones del Sprint 2 (especialmente Spatie Media Library para archivos adjuntos) debería acelerar significativamente el desarrollo.
