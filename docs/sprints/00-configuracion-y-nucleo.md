# Diario del Sprint 0: Configuración y Núcleo del Sistema

**Periodo:** 2025-10-16 - 2025-10-16

**Épica Maestra en GitHub:** [Pendiente de crear]

---

## 1. Objetivo del Sprint

Establecer las bases del proyecto Junior implementando la infraestructura completa de base de datos, modelos Eloquent, y el sistema de usuarios, roles y permisos. Este sprint sienta los cimientos técnicos para todos los módulos del MVP.

---

## 2. Alcance y Tareas Incluidas

### Diseño de Base de Datos
- [x] Diseñar esquema completo de base de datos con 26 tablas
- [x] Documentar esquema en `/docs/03-database-schema.md` con diagrama ERD Mermaid
- [x] Definir relaciones entre tablas (FK, índices, soft deletes)

### Migraciones de Laravel
- [x] Modificar migración de `users` para agregar `is_active` y `softDeletes()`
- [x] Crear 25 migraciones para todas las tablas del sistema:
  - Módulo Núcleo: `roles`, `permissions`, `areas`, `role_user`, `permission_role`, `area_user`
  - Módulo Tareas: `tasks`, `subtasks`, `task_assignments`, `team_logs`, `availability`
  - Módulo Comunicación: `notifications`, `messages`, `audit_logs`
  - Módulo Finanzas: `clients`, `cost_catalog`, `budgets`, `budget_items`, `expenses`, `quotes`, `quote_items`
  - Módulo Marketing: `campaigns`, `campaign_tasks`, `campaign_task_assignments`, `leads`
- [x] Corregir índices duplicados en migraciones polimórficas y enums
- [x] Ejecutar migraciones exitosamente sin errores

### Modelos Eloquent
- [x] Crear 23 modelos Eloquent con relaciones completas:
  - Módulo Núcleo: `Role`, `Permission`, `Area`, `User` (modificado)
  - Módulo Tareas: `Task`, `Subtask`, `TaskAssignment`, `TeamLog`, `Availability`
  - Módulo Comunicación: `Notification`, `Message`, `AuditLog`
  - Módulo Finanzas: `Client`, `CostCatalog`, `Budget`, `BudgetItem`, `Expense`, `Quote`, `QuoteItem`
  - Módulo Marketing: `Campaign`, `CampaignTask`, `CampaignTaskAssignment`, `Lead`
- [x] Implementar relaciones: BelongsTo, HasMany, BelongsToMany, MorphTo, MorphMany
- [x] Agregar casts para tipos de datos apropiados
- [x] Implementar scopes para consultas comunes
- [x] Crear métodos helper: `hasRole()`, `hasPermission()`, `markAsRead()`, etc.

### Seeders
- [x] Crear `RoleSeeder` con 7 roles del sistema
- [x] Crear `PermissionSeeder` con 46 permisos organizados por módulo
- [x] Crear `AreaSeeder` con 6 áreas/departamentos
- [x] Crear `RolePermissionSeeder` para vincular permisos con roles
- [x] Crear `UserSeeder` con 9 usuarios de ejemplo
- [x] Actualizar `DatabaseSeeder` para ejecutar todos los seeders
- [x] Ejecutar seeders exitosamente sin errores

### Middleware y Autorización
- [x] Crear `CheckPermission` middleware para verificación de permisos
- [x] Crear `CheckRole` middleware para verificación de roles
- [x] Registrar middlewares en `bootstrap/app.php` con aliases 'permission' y 'role'
- [x] Implementar Gates dinámicos en `AppServiceProvider`
- [x] Configurar super-admin bypass (Dirección General) con `Gate::before()`

### Factories
- [x] Crear `RoleFactory` con estados `active()` e `inactive()`
- [x] Crear `PermissionFactory` con generación de módulos aleatorios
- [x] Crear `AreaFactory` con estados `active()` e `inactive()`
- [x] Crear `TaskFactory` con estados `completed()`, `highPriority()`, `subtaskOf()`
- [x] Crear `ClientFactory` con estados `active()` e `inactive()`
- [x] Crear `BudgetFactory` con estados `active()` y `fullyConsumed()`
- [x] Crear `QuoteFactory` con estados `accepted()`, `sent()`, `expired()`
- [x] Crear `CampaignFactory` con estados `active()`, `completed()`, `planning()`

---

## 3. Registro de Decisiones Técnicas

### 2025-10-16: Diseño del Sistema de Permisos Aditivos
**Decisión:** Implementar un sistema de permisos aditivos donde los usuarios acumulan permisos de todos sus roles.

**Razón:**
- Permite flexibilidad para que usuarios tengan múltiples roles en diferentes áreas
- Refleja la realidad organizacional donde las personas desempeñan múltiples funciones
- Evita conflictos de permisos: si un rol otorga un permiso, el usuario lo tiene
- Facilita la gestión: agregar un rol nuevo no requiere revocar permisos existentes

**Implementación:**
- Tabla pivote `role_user` con columna `area_id` nullable
- Método `hasPermission()` en User que consulta todos los roles
- Método `getAllPermissions()` que devuelve colección única de permisos

---

### 2025-10-16: Uso de Relaciones Polimórficas
**Decisión:** Implementar relaciones polimórficas para `task_assignments` y `audit_logs`.

**Razón:**
- `task_assignments` puede apuntar tanto a `Task` como a `Subtask`, evitando duplicación de tablas
- `audit_logs` puede auditar cualquier modelo del sistema de forma genérica
- Facilita extensibilidad futura sin cambios en el esquema

**Implementación:**
- Uso de `morphs()` en migraciones (crea `*_type` y `*_id`)
- MorphTo y MorphMany en modelos Eloquent
- Nota: `morphs()` ya crea índices automáticamente, evitar duplicados

---

### 2025-10-16: Soft Deletes en Tablas Críticas
**Decisión:** Implementar soft deletes en 8 tablas: `users`, `tasks`, `team_logs`, `clients`, `budgets`, `quotes`, `campaigns`.

**Razón:**
- Mantener integridad referencial e historial
- Permitir auditorías y reportes históricos
- Facilitar recuperación de datos eliminados accidentalmente
- Cumplir con requisitos de trazabilidad del sistema

---

### 2025-10-16: Índices Estratégicos
**Decisión:** Crear índices en campos de uso frecuente para optimizar consultas.

**Razón:**
- Mejorar performance de consultas comunes (status, dates, FKs)
- Prevenir scans completos de tabla en joins
- Optimizar búsquedas por área, usuario, fecha

**Implementación:**
- Índices simples: `status`, `is_active`, `email`, `date`
- Índices compuestos: `(user_id, is_read)`, `(user_id, date)`, `(assignable_type, assignable_id)`
- Índices únicos: `email`, `code`, `slug`, `quote_number`

---

### 2025-10-16: Estructura de Seeders Modular
**Decisión:** Separar seeders en 5 archivos independientes ejecutados en orden.

**Razón:**
- Facilita mantenimiento y modificaciones futuras
- Permite re-ejecutar seeders individuales durante desarrollo
- Claridad en las dependencias (roles → permisos → vinculación → áreas → usuarios)
- Mejor organización del código

---

### 2025-10-16: Infraestructura de Autorización con Middleware y Gates
**Decisión:** Implementar un sistema completo de autorización con middleware personalizados y Gates dinámicos.

**Razón:**
- Protección de rutas mediante middleware con aliases simples (`permission:slug`, `role:slug`)
- Gates dinámicos registrados automáticamente desde la tabla de permisos
- Facilita verificación de permisos en vistas, controladores y policies
- Super-admin bypass permite que Dirección General tenga acceso total sin asignación explícita de cada permiso

**Implementación:**
- Middleware `CheckPermission` para verificar permisos del usuario
- Middleware `CheckRole` para verificar roles del usuario
- Registro en `bootstrap/app.php` con aliases 'permission' y 'role'
- Gates dinámicos en `AppServiceProvider::boot()` basados en `Permission::all()`
- `Gate::before()` para bypass de 'direccion-general'

**Uso en Rutas:**
```php
Route::get('/usuarios', [UserController::class, 'index'])
    ->middleware('permission:gestionar-usuarios');

Route::get('/finanzas', [FinanceController::class, 'index'])
    ->middleware('role:gestor-financiero');
```

---

### 2025-10-16: Factories con Estados para Testing
**Decisión:** Crear factories con múltiples estados (states) para facilitar testing y seeding flexible.

**Razón:**
- Permite generar datos de prueba realistas con diferentes escenarios
- Estados específicos facilitan testing de casos edge (completados, expirados, etc.)
- Reduce código duplicado en tests
- Acelera desarrollo de features al tener datos de prueba listos

**Implementación:**
- 8 factories creados para modelos principales
- Estados comunes: `active()`, `inactive()`, `completed()`, `expired()`
- Estados específicos: `highPriority()`, `subtaskOf()`, `fullyConsumed()`, `planning()`
- Uso de Faker para datos realistas y únicos

**Ejemplo de Uso:**
```php
// Crear tarea de alta prioridad completada
Task::factory()->highPriority()->completed()->create();

// Crear cliente inactivo con 5 cotizaciones
Client::factory()->inactive()->has(Quote::factory()->count(5))->create();
```

---

## 4. Registro de Bloqueos y Soluciones

### 2025-10-16: Error de Índices Duplicados en Migraciones
**Problema:** Al ejecutar `php artisan migrate` aparecían errores `Duplicate key name` en varias tablas:
- `task_assignments`: índice duplicado de morphs
- `audit_logs`: índice duplicado de morphs
- `cost_catalog`: índice duplicado de `category`
- `budgets`, `quotes`, `campaigns`, `campaign_tasks`, `leads`: índices duplicados de `status`

**Causa:**
- El método `morphs()` de Laravel ya crea automáticamente un índice compuesto
- Los campos enum con `->index()` ya crean índice, no se debe agregar manualmente

**Solución:**
- Remover líneas `$table->index(['assignable_type', 'assignable_id'])` en tablas polimórficas
- Remover líneas duplicadas de `$table->index('status')` y `$table->index('category')`
- Total: 8 migraciones corregidas

**Aprendizaje:** Verificar siempre si los métodos de Laravel ya incluyen índices antes de agregarlos manualmente.

---

## 5. Resultado del Sprint

### Tareas Completadas: 11 de 11

**Resumen:** El Sprint 0 se completó exitosamente. Se establecieron las bases completas del proyecto Junior con:
- ✅ Esquema de base de datos robusto y documentado (26 tablas)
- ✅ 25 migraciones ejecutadas sin errores
- ✅ 23 modelos Eloquent con relaciones completas
- ✅ 5 seeders funcionales con datos de ejemplo
- ✅ Sistema de permisos aditivos implementado
- ✅ 7 roles, 46 permisos, 6 áreas y 9 usuarios de prueba
- ✅ Middleware de autorización (CheckPermission, CheckRole)
- ✅ Gates dinámicos registrados en AppServiceProvider
- ✅ 8 factories con estados para testing (Role, Permission, Area, Task, Client, Budget, Quote, Campaign)

**Estado de la Base de Datos:**
```
Roles creados: 7
Permisos creados: 46
Áreas creadas: 6
Usuarios creados: 9
Tiempo total de ejecución: ~3.4 segundos
```

**Componentes de Autorización:**
```
Middleware: 2 (CheckPermission, CheckRole)
Gates dinámicos: 46 (uno por cada permiso)
Super-admin bypass: ✅ (Dirección General)
```

**Factories Creados:**
```
Factories con estados: 8
Estados totales implementados: 17
Modelos cubiertos: Núcleo (3), Tareas (1), Finanzas (3), Marketing (1)
```

---

### Aprendizajes / Retrospectiva

#### Qué funcionó bien:
1. **Enfoque Docs-First:** Documentar el esquema completo en `/docs/03-database-schema.md` antes de crear migraciones ahorró tiempo y evitó refactorizaciones.
2. **Diseño Modular:** Separar modelos por módulo facilita navegación y mantenimiento del código.
3. **Seeders Detallados:** Los seeders con datos realistas permiten pruebas inmediatas del sistema.
4. **Sistema de Permisos Flexible:** El diseño de permisos aditivos con contexto de área cumple perfectamente con los requisitos del negocio.

#### Qué se puede mejorar:
1. **Validación de Migraciones:** Implementar un script o comando que valide índices duplicados antes de ejecutar migraciones.
2. **Documentación de Modelos:** Considerar agregar PHPDoc más detallado en los métodos de relaciones.
3. **Tests Unitarios:** Crear tests para verificar relaciones de modelos (tarea para Sprint 1).
4. **Factory Classes:** Crear factories para cada modelo para facilitar testing (tarea para Sprint 1).

#### Acciones para Sprints Futuros:
- [ ] Crear factories para todos los modelos
- [ ] Implementar tests unitarios para relaciones
- [ ] Documentar ejemplos de uso de métodos helper en modelos
- [ ] Crear middleware para verificación de permisos
- [ ] Implementar observers para auditoría automática

---

## 6. Información de Referencia

### Usuarios de Ejemplo Creados

| Email | Nombre | Rol | Área(s) | Password |
|-------|--------|-----|---------|----------|
| director@junior.com | Carlos Mendoza | Dirección General | Dirección General | password |
| rrhh@junior.com | Ana García | Admin RRHH | Recursos Humanos | password |
| finanzas@junior.com | Roberto López | Gestor Financiero | Finanzas | password |
| marketing@junior.com | Laura Martínez | Gestor Marketing | Marketing | password |
| produccion@junior.com | Miguel Torres | Director de Área | Producción | password |
| pedro.produccion@junior.com | Pedro Ramírez | Miembro Producción | Producción | password |
| sofia.produccion@junior.com | Sofía González | Miembro Producción | Producción | password |
| javier.marketing@junior.com | Javier Hernández | Empleado General | Marketing | password |
| maria.tech@junior.com | María Fernández | Empleado General | Tecnología + Producción | password |

### Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Refrescar base de datos completa
php artisan migrate:fresh

# Ejecutar seeders
php artisan db:seed

# Refrescar y poblar base de datos
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status

# Rollback última migración
php artisan migrate:rollback
```

### Estructura de Archivos Creados

```
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php (modificado)
│   ├── 2025_10_16_000001_create_roles_table.php
│   ├── 2025_10_16_000002_create_permissions_table.php
│   ├── 2025_10_16_000003_create_areas_table.php
│   └── ... (22 migraciones más)
└── seeders/
    ├── DatabaseSeeder.php (modificado)
    ├── RoleSeeder.php
    ├── PermissionSeeder.php
    ├── RolePermissionSeeder.php
    ├── AreaSeeder.php
    └── UserSeeder.php

app/Models/
├── User.php (modificado)
├── Role.php
├── Permission.php
├── Area.php
└── ... (19 modelos más)

docs/
├── 03-database-schema.md (actualizado)
└── sprints/
    └── 00-configuracion-y-nucleo.md (este archivo)
```

---

## 7. Próximos Pasos

**Sprint 1: Tareas, Comunicación y Calendarios**
- Implementar CRUD de tareas con asignaciones
- Sistema de notificaciones en tiempo real
- Calendarios de disponibilidad de producción
- Bitácora de equipos con filtros
- Panel de trazabilidad/auditoría

**Preparación Requerida:**
- Configurar autenticación (Laravel Breeze/Jetstream)
- Instalar y configurar Livewire para frontend reactivo
- Configurar Filament para panel de administración
- Crear middleware de permisos personalizado

---

**Notas Finales:**

Este sprint establece una base sólida y escalable para todo el proyecto Junior. El diseño de base de datos está completo y probado, los modelos Eloquent están implementados con todas sus relaciones, y el sistema de permisos aditivos permite la flexibilidad requerida por el negocio.

El enfoque **Docs-First** demostró ser efectivo: documentar primero el esquema completo evitó refactorizaciones y proporcionó una referencia clara durante toda la implementación.

**Estado del Proyecto:** ✅ Bases técnicas completadas - Listo para Sprint 1
