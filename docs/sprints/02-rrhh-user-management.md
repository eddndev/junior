# Diario del Sprint 2: Gestión de RRHH - Sistema de Usuarios y Permisos

**Periodo:** 2025-10-19 - [Fecha de Fin]

**Épica Maestra en GitHub:** [Pendiente de crear]

---

## 1. Objetivo del Sprint

Implementar el sistema completo de gestión de usuarios, roles y permisos para el módulo de Recursos Humanos, permitiendo al Administrador de RRHH gestionar el ciclo de vida de empleados, asignar roles múltiples por área, y establecer la base de autenticación y autorización del sistema Junior.

---

## 2. Alcance y Tareas Incluidas

### Historias de Usuario del Sprint

#### 2.1 Módulo Núcleo - Gestión de Usuarios

**Perfil: Administrador de RRHH**

- [ ] `#[ID] - [RRHH] Crear perfil de usuario`
  - Como Administrador de RRHH, quiero crear nuevos perfiles de usuario para incorporar empleados a la plataforma

- [ ] `#[ID] - [RRHH] Listar y buscar usuarios`
  - Como Administrador de RRHH, quiero ver una lista de todos los usuarios con búsqueda y filtros para gestionar la base de empleados

- [ ] `#[ID] - [RRHH] Actualizar perfil de usuario`
  - Como Administrador de RRHH, quiero actualizar la información de un usuario para mantener los datos actualizados

- [ ] `#[ID] - [RRHH] Desactivar/Activar usuario`
  - Como Administrador de RRHH, quiero desactivar usuarios (soft delete) para gestionar salidas sin perder historial

- [ ] `#[ID] - [RRHH] Ver detalle de usuario con roles y áreas`
  - Como Administrador de RRHH, quiero ver el perfil completo de un usuario incluyendo sus roles y áreas asignadas

#### 2.2 Módulo Núcleo - Gestión de Roles y Permisos

- [ ] `#[ID] - [RRHH] Asignar rol a usuario en área específica`
  - Como Administrador de RRHH, quiero asignar un rol a un usuario en un área específica para reflejar su posición organizacional

- [ ] `#[ID] - [RRHH] Asignar múltiples roles a usuario`
  - Como Administrador de RRHH, quiero que un usuario pueda tener múltiples roles en distintas áreas para reflejar funciones flexibles

- [ ] `#[ID] - [RRHH] Remover rol de usuario`
  - Como Administrador de RRHH, quiero remover roles de usuarios cuando cambien de posición o área

- [ ] `#[ID] - [RRHH] Ver permisos efectivos de usuario`
  - Como Administrador de RRHH, quiero ver todos los permisos que un usuario tiene acumulados a través de sus roles

#### 2.3 Autenticación y Perfil Personal

**Perfil: Empleado General**

- [ ] `#[ID] - [Auth] Login de usuario`
  - Como Empleado, quiero iniciar sesión con email y contraseña para acceder a mi espacio de trabajo

- [ ] `#[ID] - [Auth] Logout de usuario`
  - Como Empleado, quiero cerrar sesión de forma segura para proteger mi cuenta

- [ ] `#[ID] - [Perfil] Ver mi perfil personal`
  - Como Empleado, quiero ver mi propio perfil con mis datos personales y roles asignados

- [ ] `#[ID] - [Perfil] Actualizar mi información personal`
  - Como Empleado, quiero actualizar ciertos campos de mi perfil (nombre, email) para mantener mis datos actuales

#### 2.4 Sistema de Áreas

- [ ] `#[ID] - [RRHH] Gestionar catálogo de áreas`
  - Como Administrador de RRHH, quiero crear/editar/desactivar áreas de la organización para estructurar la empresa

- [ ] `#[ID] - [RRHH] Asignar usuario a área`
  - Como Administrador de RRHH, quiero asignar usuarios a una o más áreas para definir su pertenencia organizacional

#### 2.5 Trazabilidad y Auditoría

- [ ] `#[ID] - [Audit] Implementar sistema de audit logs`
  - Como Sistema, quiero registrar automáticamente todas las acciones CRUD de usuarios y roles para trazabilidad

- [ ] `#[ID] - [RRHH] Panel de trazabilidad básico`
  - Como Administrador de RRHH, quiero ver un panel de auditoría con las acciones realizadas en el sistema

---

## 3. Componentes Técnicos a Implementar

### 3.1 Migraciones de Base de Datos

Basado en el esquema `/docs/03-database-schema.md`, crear las siguientes migraciones:

- [ ] `users` - Tabla de usuarios con soft deletes
- [ ] `roles` - Catálogo de roles del sistema
- [ ] `permissions` - Catálogo de permisos granulares
- [ ] `areas` - Áreas/departamentos de la organización
- [ ] `role_user` - Tabla pivote (roles x usuarios x áreas)
- [ ] `permission_role` - Tabla pivote (permisos x roles)
- [ ] `area_user` - Tabla pivote (áreas x usuarios)
- [ ] `audit_logs` - Tabla de trazabilidad

### 3.2 Seeders

- [ ] `RoleSeeder` - Roles iniciales del sistema:
  - Dirección General
  - Director de Área (Producción, Marketing, Finanzas)
  - Miembro de Producción
  - Gestor Financiero
  - Gestor de Marketing
  - Administrador de RRHH

- [ ] `PermissionSeeder` - Permisos por módulo:
  - Módulo Núcleo: `gestionar-usuarios`, `ver-usuarios`, `asignar-roles`
  - Módulo Tareas: `crear-tareas`, `asignar-tareas`, `completar-tareas`
  - Módulo Finanzas: `ver-finanzas`, `gestionar-presupuestos`, `crear-cotizaciones`
  - Módulo Marketing: `gestionar-campanas`, `ver-leads`
  - Módulo Trazabilidad: `ver-trazabilidad`

- [ ] `AreaSeeder` - Áreas iniciales:
  - Dirección General
  - Producción
  - Marketing
  - Finanzas
  - Recursos Humanos

- [ ] `UserSeeder` - Usuario administrador inicial para desarrollo

### 3.3 Modelos Eloquent

- [ ] `User` model con relaciones:
  - `belongsToMany(Role)` through `role_user`
  - `belongsToMany(Area)` through `area_user`
  - Método `hasPermission($permission)` para verificación
  - Método `hasRole($role)` para verificación
  - Método `rolesInArea($area)` para roles contextuales

- [ ] `Role` model con relaciones:
  - `belongsToMany(User)` through `role_user`
  - `belongsToMany(Permission)` through `permission_role`

- [ ] `Permission` model con relaciones:
  - `belongsToMany(Role)` through `permission_role`

- [ ] `Area` model con relaciones:
  - `belongsToMany(User)` through `area_user`
  - `hasMany(Task)`

- [ ] `AuditLog` model para trazabilidad (relación polimórfica)

### 3.4 Controladores y Rutas

**Rutas protegidas con autenticación:**

- [ ] `UserController` - CRUD de usuarios (solo para RRHH)
  - `index()` - Listar usuarios con paginación y búsqueda
  - `create()` - Formulario de creación
  - `store()` - Crear usuario
  - `show($id)` - Ver detalle de usuario
  - `edit($id)` - Formulario de edición
  - `update($id)` - Actualizar usuario
  - `destroy($id)` - Soft delete de usuario
  - `restore($id)` - Restaurar usuario eliminado

- [ ] `RoleAssignmentController` - Gestión de roles
  - `store()` - Asignar rol a usuario en área
  - `destroy()` - Remover rol de usuario

- [ ] `AreaController` - CRUD de áreas
  - `index()` - Listar áreas
  - `store()` - Crear área
  - `update($id)` - Actualizar área
  - `destroy($id)` - Desactivar área

- [ ] `ProfileController` - Perfil personal del empleado
  - `show()` - Ver mi perfil
  - `edit()` - Formulario de edición de perfil
  - `update()` - Actualizar mi perfil

- [ ] `AuditLogController` - Panel de trazabilidad
  - `index()` - Listar audit logs con filtros

### 3.5 Vistas Blade

**Usando los componentes del Sprint 1:**

- [ ] `users/index.blade.php` - Lista de usuarios con tabla (`x-layout.table`)
- [ ] `users/create.blade.php` - Formulario de creación de usuario
- [ ] `users/edit.blade.php` - Formulario de edición de usuario
- [ ] `users/show.blade.php` - Vista de detalle con roles y permisos
- [ ] `roles/assign-modal.blade.php` - Modal para asignar roles (`x-layout.modal`)
- [ ] `areas/index.blade.php` - Gestión de áreas
- [ ] `profile/show.blade.php` - Vista de perfil personal
- [ ] `profile/edit.blade.php` - Edición de perfil personal
- [ ] `audit-logs/index.blade.php` - Panel de trazabilidad con tabla filtrable

### 3.6 Middleware y Policies

- [ ] `CheckUserActive` middleware - Verificar que el usuario esté activo
- [ ] `CheckPermission` middleware - Verificar permisos específicos
- [ ] `UserPolicy` - Políticas de autorización para User model
- [ ] `AreaPolicy` - Políticas de autorización para Area model

### 3.7 Observers y Events

- [ ] `UserObserver` - Registrar en audit_logs:
  - `created()` - Usuario creado
  - `updated()` - Usuario actualizado
  - `deleted()` - Usuario desactivado
  - `restored()` - Usuario restaurado

- [ ] `RoleUserObserver` - Registrar asignación/remoción de roles

### 3.8 Validación

- [ ] `StoreUserRequest` - Validación de creación de usuario
- [ ] `UpdateUserRequest` - Validación de actualización de usuario
- [ ] `AssignRoleRequest` - Validación de asignación de roles
- [ ] `UpdateProfileRequest` - Validación de actualización de perfil personal

---

## 4. Registro de Decisiones Técnicas

*Esta sección es un log vivo. Se actualiza a medida que se toman decisiones durante el sprint.*

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

---

## 5. Registro de Bloqueos y Soluciones

*Esta sección documenta los problemas inesperados y cómo se resolvieron.*

### Bloqueos Identificados

* **[FECHA]:**
    * **Problema:** [Descripción del bloqueo]
    * **Solución:** [Cómo se resolvió el problema]

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

**Estado:** 🚧 EN PLANIFICACIÓN

**Próximos Pasos:**
1. Crear la Épica Maestra en GitHub con todas las issues del sprint
2. Crear las issues individuales usando la plantilla de AGENTS.md
3. Configurar las labels correspondientes (Module: RRHH, Sprint: 2, Type: Feature)
4. Comenzar con las migraciones y seeders
5. Implementar los modelos y relaciones
6. Desarrollar los controladores y vistas
