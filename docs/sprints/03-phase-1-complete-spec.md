# Sprint 3 - Fase 1: Base de Datos y Modelos - Especificación Completa

**Fecha:** 2025-10-22
**Sprint:** Tareas y Colaboración
**Fase:** 1 de 4

---

## 📋 Índice

1. [Migraciones](#1-migraciones)
2. [Modelos Eloquent](#2-modelos-eloquent)
3. [Seeders](#3-seeders)
4. [Checklist de Completitud](#4-checklist-de-completitud)

---

## 1. Migraciones

### 1.1 Migration: `tasks` table

**Archivo:** `database/migrations/2025_10_23_000001_create_tasks_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Basic Information
            $table->string('title');
            $table->text('description')->nullable();

            // Status & Priority
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending')
                  ->comment('Estado de la tarea');

            $table->enum('priority', ['low', 'medium', 'high', 'critical'])
                  ->default('medium')
                  ->comment('Prioridad de la tarea');

            // Dates
            $table->date('due_date')->nullable()->comment('Fecha límite de entrega');
            $table->timestamp('completed_at')->nullable()->comment('Fecha de completación');

            // Foreign Keys
            $table->foreignId('area_id')
                  ->constrained('areas')
                  ->onDelete('restrict')
                  ->comment('Área a la que pertenece la tarea');

            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Usuario asignado a la tarea');

            $table->foreignId('assigned_by')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->comment('Usuario que asignó la tarea');

            // Hierarchical Structure (for subtasks)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('tasks')
                  ->onDelete('cascade')
                  ->comment('Tarea padre (si es subtarea)');

            // Ordering
            $table->integer('order')
                  ->default(0)
                  ->comment('Orden de visualización');

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes for Performance
            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
            $table->index('parent_id');

            // Composite indexes for common queries
            $table->index(['assigned_to', 'status', 'due_date'], 'tasks_user_status_due');
            $table->index(['area_id', 'status'], 'tasks_area_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
```

### 1.2 Migration: `task_comments` table

**Archivo:** `database/migrations/2025_10_23_000002_create_task_comments_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_comments', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys
            $table->foreignId('task_id')
                  ->constrained('tasks')
                  ->onDelete('cascade')
                  ->comment('Tarea a la que pertenece el comentario');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Usuario que creó el comentario');

            // Content
            $table->text('comment')->comment('Contenido del comentario');

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('task_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_comments');
    }
};
```

---

## 2. Modelos Eloquent

### 2.1 Model: `Task`

**Archivo:** `app/Models/Task.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Task extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'area_id',
        'assigned_to',
        'assigned_by',
        'parent_id',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // ========================================
    // RELACIONES
    // ========================================

    /**
     * Get the area that owns the task.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who assigned the task.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the parent task (if this is a subtask).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Get the subtasks of this task.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Get the comments for the task.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope a query to only include active tasks (not soft deleted).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to tasks assigned to a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to tasks belonging to a specific area.
     */
    public function scopeForArea($query, int $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    /**
     * Scope a query to only include pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    /**
     * Determine if the task is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        // No overdue if no due date or if completed/cancelled
        if (!$this->due_date || in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }

        return $this->due_date->isPast();
    }

    /**
     * Determine if this task is a subtask.
     */
    public function getIsSubtaskAttribute(): bool
    {
        return !is_null($this->parent_id);
    }

    // ========================================
    // SPATIE MEDIA LIBRARY
    // ========================================

    /**
     * Register media collections for this model.
     */
    public function registerMediaCollections(): void
    {
        // Collection para archivos adjuntos (igual que TeamLog)
        $this->addMediaCollection('attachments')
             ->useDisk('public')
             ->acceptsMimeTypes([
                 // Imágenes
                 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                 // Documentos
                 'application/pdf',
                 'application/msword',
                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                 'application/vnd.ms-excel',
                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                 'application/vnd.ms-powerpoint',
                 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                 // Audio
                 'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4',
                 // Código/Texto
                 'text/plain', 'application/json', 'text/xml', 'text/html',
                 'text/css', 'application/javascript',
                 // Comprimidos
                 'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
             ]);
    }

    /**
     * Register media conversions for this model.
     *
     * Conversiones de imágenes a formatos modernos (webp/avif)
     * Se procesan de forma asíncrona en cola.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // Solo procesar conversiones para imágenes en la colección 'attachments'
        if ($media && $media->collection_name === 'attachments' && str_starts_with($media->mime_type, 'image/')) {
            // Conversión a WebP (mejor soporte)
            $this->addMediaConversion('webp')
                 ->format('webp')
                 ->quality(85)
                 ->performOnCollections('attachments')
                 ->queued(); // ← Procesar en cola

            // Conversión a AVIF (más moderno, mejor compresión)
            $this->addMediaConversion('avif')
                 ->format('avif')
                 ->quality(80)
                 ->performOnCollections('attachments')
                 ->queued(); // ← Procesar en cola

            // Thumbnail para previsualización rápida (pequeño, sync)
            $this->addMediaConversion('thumb')
                 ->width(300)
                 ->height(300)
                 ->format('webp')
                 ->quality(70)
                 ->performOnCollections('attachments')
                 ->nonQueued(); // ← Procesar inmediatamente para preview
        }
    }
}
```

### 2.2 Model: `TaskComment`

**Archivo:** `app/Models/TaskComment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    /**
     * Get the task that owns the comment.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who created the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope a query to order comments by newest first.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order comments by oldest first (chronological).
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }
}
```

---

## 3. Seeders

### 3.1 Seeder: `TaskSeeder`

**Archivo:** `database/seeders/TaskSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get areas and users
        $produccion = Area::where('slug', 'produccion')->first();
        $marketing = Area::where('slug', 'marketing')->first();
        $finanzas = Area::where('slug', 'finanzas')->first();

        // Get director general (admin user)
        $director = User::where('email', 'admin@junior.com')->first();

        // Get users by area (assuming they exist from UserSeeder)
        $usersProduccion = User::whereHas('areas', function ($q) use ($produccion) {
            $q->where('areas.id', $produccion->id);
        })->get();

        $usersMarketing = User::whereHas('areas', function ($q) use ($marketing) {
            $q->where('areas.id', $marketing->id);
        })->get();

        // ========================================
        // TAREAS DE PRODUCCIÓN
        // ========================================

        $taskProduccion1 = Task::create([
            'title' => 'Planificar sesión de fotos de producto',
            'description' => 'Coordinar con el equipo de fotografía para la sesión de fotos del nuevo catálogo de productos. Incluye selección de locación, equipo necesario y modelos.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
            'area_id' => $produccion->id,
            'assigned_to' => $usersProduccion->random()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        // Subtareas de la tarea anterior
        Task::create([
            'title' => 'Reservar estudio fotográfico',
            'description' => 'Contactar con Estudio XYZ y reservar para el viernes próximo',
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => now()->addDays(1),
            'completed_at' => now()->subHours(2),
            'area_id' => $produccion->id,
            'assigned_to' => $usersProduccion->random()->id ?? null,
            'assigned_by' => $director->id,
            'parent_id' => $taskProduccion1->id,
            'order' => 1,
        ]);

        Task::create([
            'title' => 'Preparar lista de productos a fotografiar',
            'description' => 'Listado de 50 productos prioritarios del nuevo catálogo',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(2),
            'area_id' => $produccion->id,
            'assigned_to' => $usersProduccion->random()->id ?? null,
            'assigned_by' => $director->id,
            'parent_id' => $taskProduccion1->id,
            'order' => 2,
        ]);

        Task::create([
            'title' => 'Revisar calidad de edición de video promocional',
            'description' => 'El equipo de edición ha entregado el primer borrador del video. Revisar y dar feedback.',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(5),
            'area_id' => $produccion->id,
            'assigned_to' => $usersProduccion->random()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        // ========================================
        // TAREAS DE MARKETING
        // ========================================

        $taskMarketing1 = Task::create([
            'title' => 'Diseñar campaña de email marketing para Black Friday',
            'description' => 'Crear estrategia de email marketing para la campaña de Black Friday. Incluye diseño de plantillas, segmentación de audiencia y calendario de envíos.',
            'status' => 'pending',
            'priority' => 'critical',
            'due_date' => now()->addDays(7),
            'area_id' => $marketing->id,
            'assigned_to' => $usersMarketing->random()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        // Subtareas de marketing
        Task::create([
            'title' => 'Diseñar plantilla de email principal',
            'description' => 'Diseño responsivo con imágenes de productos destacados',
            'status' => 'in_progress',
            'priority' => 'critical',
            'due_date' => now()->addDays(4),
            'area_id' => $marketing->id,
            'assigned_to' => $usersMarketing->random()->id ?? null,
            'assigned_by' => $director->id,
            'parent_id' => $taskMarketing1->id,
            'order' => 1,
        ]);

        Task::create([
            'title' => 'Redactar copy para emails',
            'description' => 'Textos persuasivos y urgencia para Black Friday',
            'status' => 'pending',
            'priority' => 'critical',
            'due_date' => now()->addDays(5),
            'area_id' => $marketing->id,
            'assigned_to' => $usersMarketing->random()->id ?? null,
            'assigned_by' => $director->id,
            'parent_id' => $taskMarketing1->id,
            'order' => 2,
        ]);

        Task::create([
            'title' => 'Actualizar contenido de redes sociales',
            'description' => 'Publicar 10 posts en Instagram y Facebook sobre los nuevos productos',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(2),
            'area_id' => $marketing->id,
            'assigned_to' => $usersMarketing->random()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        // ========================================
        // TAREAS DE FINANZAS
        // ========================================

        Task::create([
            'title' => 'Preparar reporte financiero Q4',
            'description' => 'Consolidar estados financieros del cuarto trimestre para presentación a dirección.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(10),
            'area_id' => $finanzas->id,
            'assigned_to' => User::whereHas('areas', function ($q) use ($finanzas) {
                $q->where('areas.id', $finanzas->id);
            })->first()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        Task::create([
            'title' => 'Revisar presupuesto de campaña de marketing',
            'description' => 'Validar costos proyectados de la campaña Black Friday y aprobar desembolsos',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
            'area_id' => $finanzas->id,
            'assigned_to' => User::whereHas('areas', function ($q) use ($finanzas) {
                $q->where('areas.id', $finanzas->id);
            })->first()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        // ========================================
        // TAREAS ATRASADAS (OVERDUE) para testing
        // ========================================

        Task::create([
            'title' => 'Entregar manual de identidad corporativa',
            'description' => 'Documento final del manual de marca con guidelines de uso de logo, colores y tipografías',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->subDays(2), // Atrasada por 2 días
            'area_id' => $marketing->id,
            'assigned_to' => $usersMarketing->random()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        Task::create([
            'title' => 'Archivar facturas del mes anterior',
            'description' => 'Organizar y archivar todas las facturas de septiembre en el sistema contable',
            'status' => 'pending',
            'priority' => 'low',
            'due_date' => now()->subDays(5), // Atrasada por 5 días
            'area_id' => $finanzas->id,
            'assigned_to' => User::whereHas('areas', function ($q) use ($finanzas) {
                $q->where('areas.id', $finanzas->id);
            })->first()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        // ========================================
        // TAREAS COMPLETADAS para statistics
        // ========================================

        Task::create([
            'title' => 'Configurar sistema de analytics en sitio web',
            'description' => 'Implementar Google Analytics 4 y configurar eventos de conversión',
            'status' => 'completed',
            'priority' => 'medium',
            'due_date' => now()->subDays(10),
            'completed_at' => now()->subDays(5),
            'area_id' => $marketing->id,
            'assigned_to' => $usersMarketing->random()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        Task::create([
            'title' => 'Aprobar presupuesto anual 2025',
            'description' => 'Revisión y aprobación del presupuesto operativo del próximo año fiscal',
            'status' => 'completed',
            'priority' => 'critical',
            'due_date' => now()->subDays(15),
            'completed_at' => now()->subDays(12),
            'area_id' => $finanzas->id,
            'assigned_to' => User::whereHas('areas', function ($q) use ($finanzas) {
                $q->where('areas.id', $finanzas->id);
            })->first()->id ?? null,
            'assigned_by' => $director->id,
        ]);

        $this->command->info('✅ 15 tareas de demostración creadas exitosamente.');
    }
}
```

### 3.2 Seeder: `TaskCommentSeeder`

**Archivo:** `database/seeders/TaskCommentSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tasks
        $tasks = Task::all();

        // Get all users
        $users = User::all();

        // Skip if no tasks or users
        if ($tasks->isEmpty() || $users->isEmpty()) {
            $this->command->warn('⚠️  No hay tareas o usuarios. Saltando TaskCommentSeeder.');
            return;
        }

        // ========================================
        // COMENTARIOS EN TAREAS ESPECÍFICAS
        // ========================================

        // Tarea: "Planificar sesión de fotos de producto"
        $taskFotos = Task::where('title', 'like', '%sesión de fotos%')->first();
        if ($taskFotos) {
            TaskComment::create([
                'task_id' => $taskFotos->id,
                'user_id' => $taskFotos->assigned_by,
                'comment' => 'Es importante coordinar con el equipo de fotografía con al menos 48 horas de anticipación. Revisa disponibilidad del estudio XYZ.',
            ]);

            TaskComment::create([
                'task_id' => $taskFotos->id,
                'user_id' => $taskFotos->assigned_to ?? $users->random()->id,
                'comment' => 'Entendido. Ya contacté al estudio y tienen disponibilidad para el viernes. ¿Confirmamos esa fecha?',
            ]);

            TaskComment::create([
                'task_id' => $taskFotos->id,
                'user_id' => $taskFotos->assigned_by,
                'comment' => 'Perfecto, confirmemos el viernes. Asegúrate de tener la lista de productos lista para el miércoles.',
            ]);
        }

        // Tarea: "Diseñar campaña de email marketing para Black Friday"
        $taskEmail = Task::where('title', 'like', '%email marketing%Black Friday%')->first();
        if ($taskEmail) {
            TaskComment::create([
                'task_id' => $taskEmail->id,
                'user_id' => $taskEmail->assigned_to ?? $users->random()->id,
                'comment' => 'He comenzado con la segmentación de audiencia. Propongo dividir en 3 grupos: clientes VIP, clientes recurrentes y nuevos prospectos. ¿Están de acuerdo?',
            ]);

            TaskComment::create([
                'task_id' => $taskEmail->id,
                'user_id' => $taskEmail->assigned_by,
                'comment' => 'Excelente propuesta. Agrega también un segmento de "carrito abandonado" para maximizar conversiones.',
            ]);

            TaskComment::create([
                'task_id' => $taskEmail->id,
                'user_id' => $taskEmail->assigned_to ?? $users->random()->id,
                'comment' => 'Perfecto, agregaré ese segmento. Estimo tener el primer borrador de la estrategia para mañana.',
            ]);

            TaskComment::create([
                'task_id' => $taskEmail->id,
                'user_id' => $users->random()->id,
                'comment' => '¿Ya tienen los assets gráficos listos? Necesitaré banners y gráficos para los emails.',
            ]);
        }

        // Tarea: "Preparar reporte financiero Q4"
        $taskReporte = Task::where('title', 'like', '%reporte financiero Q4%')->first();
        if ($taskReporte) {
            TaskComment::create([
                'task_id' => $taskReporte->id,
                'user_id' => $taskReporte->assigned_to ?? $users->random()->id,
                'comment' => 'He consolidado los estados de septiembre y octubre. Falta cerrar noviembre. ¿Cuándo es la fecha límite exacta?',
            ]);

            TaskComment::create([
                'task_id' => $taskReporte->id,
                'user_id' => $taskReporte->assigned_by,
                'comment' => 'La presentación a la junta es el 10 de noviembre. Necesitamos el reporte al menos 2 días antes para revisión.',
            ]);

            TaskComment::create([
                'task_id' => $taskReporte->id,
                'user_id' => $taskReporte->assigned_to ?? $users->random()->id,
                'comment' => 'Entendido, lo tendré listo para el 8 de noviembre. ¿Necesitan algún análisis adicional de gastos operativos?',
            ]);
        }

        // Tarea atrasada: "Entregar manual de identidad corporativa"
        $taskManual = Task::where('title', 'like', '%manual de identidad%')->first();
        if ($taskManual) {
            TaskComment::create([
                'task_id' => $taskManual->id,
                'user_id' => $taskManual->assigned_by,
                'comment' => '⚠️ Esta tarea está atrasada. Por favor, actualízame sobre el progreso. Es de alta prioridad.',
            ]);

            TaskComment::create([
                'task_id' => $taskManual->id,
                'user_id' => $taskManual->assigned_to ?? $users->random()->id,
                'comment' => 'Disculpa el retraso. He tenido un bloqueo con la aprobación del departamento legal sobre el uso del logo. Estimo completarlo en 2 días.',
            ]);
        }

        // ========================================
        // COMENTARIOS ALEATORIOS EN OTRAS TAREAS
        // ========================================

        $remainingTasks = Task::whereNotIn('id', [
            $taskFotos->id ?? 0,
            $taskEmail->id ?? 0,
            $taskReporte->id ?? 0,
            $taskManual->id ?? 0,
        ])->take(5)->get();

        foreach ($remainingTasks as $task) {
            // 1-3 comentarios por tarea
            $numComments = rand(1, 3);

            for ($i = 0; $i < $numComments; $i++) {
                TaskComment::create([
                    'task_id' => $task->id,
                    'user_id' => $users->random()->id,
                    'comment' => $this->getRandomComment(),
                ]);
            }
        }

        $this->command->info('✅ Comentarios de tareas creados exitosamente.');
    }

    /**
     * Get a random comment for demonstration.
     */
    private function getRandomComment(): string
    {
        $comments = [
            '¿Cuál es el status actual de esta tarea?',
            'He avanzado un 50% aproximadamente. Actualizaré cuando complete.',
            '¿Necesitas ayuda con algo específico?',
            'Perfecto, mantennos actualizados.',
            'He encontrado un bloqueo. ¿Podemos coordinar una reunión?',
            'Completado parcialmente. Falta la revisión final.',
            '¿Ya tenemos los recursos necesarios para esta tarea?',
            'Excelente trabajo. Sigue así.',
            'Voy retrasado por dependencias externas. Informaré cambios.',
            '¿Cuál es la prioridad vs. otras tareas del sprint?',
        ];

        return $comments[array_rand($comments)];
    }
}
```

### 3.3 Actualización: `DatabaseSeeder`

**Archivo:** `database/seeders/DatabaseSeeder.php`

Agregar las nuevas seeders al método `run()`:

```php
public function run(): void
{
    // Existing seeders...
    $this->call([
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        AreaSeeder::class,
        UserSeeder::class,

        // NEW: Sprint 3 Seeders
        TaskSeeder::class,
        TaskCommentSeeder::class,
    ]);
}
```

---

## 4. Checklist de Completitud

### ✅ Checklist de Implementación

#### 4.1 Migraciones
- [ ] `2025_10_23_000001_create_tasks_table.php` creada
- [ ] `2025_10_23_000002_create_task_comments_table.php` creada
- [ ] Ambas migraciones ejecutadas sin errores (`php artisan migrate`)
- [ ] Verificar estructura de tablas en base de datos:
  ```bash
  php artisan db:show
  php artisan db:table tasks
  php artisan db:table task_comments
  ```

#### 4.2 Modelos
- [ ] `app/Models/Task.php` creado con:
  - [ ] Trait `SoftDeletes` implementado
  - [ ] Trait `InteractsWithMedia` implementado
  - [ ] Interface `HasMedia` implementada
  - [ ] 7 relaciones definidas (area, assignedTo, assignedBy, parent, subtasks, comments, media)
  - [ ] 5 scopes implementados (active, forUser, forArea, pending, overdue)
  - [ ] 2 accessors implementados (is_overdue, is_subtask)
  - [ ] `registerMediaCollections()` configurado
  - [ ] `registerMediaConversions()` configurado (webp, avif, thumb)

- [ ] `app/Models/TaskComment.php` creado con:
  - [ ] 2 relaciones definidas (task, user)
  - [ ] 2 scopes implementados (latest, oldest)

#### 4.3 Seeders
- [ ] `database/seeders/TaskSeeder.php` creado con:
  - [ ] 15+ tareas de demostración
  - [ ] Mezcla de estados (pending, in_progress, completed, cancelled)
  - [ ] Tareas con subtareas (jerarquía de 2 niveles)
  - [ ] Tareas atrasadas (overdue) para testing
  - [ ] Tareas distribuidas en 3 áreas (Producción, Marketing, Finanzas)

- [ ] `database/seeders/TaskCommentSeeder.php` creado con:
  - [ ] 25+ comentarios de demostración
  - [ ] Comentarios específicos en tareas clave
  - [ ] Conversaciones entre asignador y asignado

- [ ] `database/seeders/DatabaseSeeder.php` actualizado
  - [ ] TaskSeeder agregado a `call()`
  - [ ] TaskCommentSeeder agregado a `call()`

#### 4.4 Actualización de Modelos Existentes
- [ ] `app/Models/User.php` - Verificar relaciones existentes:
  - [ ] Relación `tasks()` (si no existe, agregar)
  - [ ] Relación `assignedTasks()` (si no existe, agregar)
  - [ ] Relación `taskComments()` (si no existe, agregar)

- [ ] `app/Models/Area.php` - Ya tiene relación `tasks()` ✅

#### 4.5 Verificación Final
- [ ] Ejecutar todas las migraciones:
  ```bash
  php artisan migrate:fresh
  ```

- [ ] Ejecutar todos los seeders:
  ```bash
  php artisan db:seed
  ```

- [ ] Verificar datos en base de datos:
  ```bash
  php artisan tinker
  >>> Task::count()
  >>> TaskComment::count()
  >>> Task::with(['assignedTo', 'area', 'subtasks'])->first()
  >>> Task::overdue()->get()
  ```

- [ ] Verificar relaciones Eloquent:
  ```bash
  php artisan tinker
  >>> $task = Task::first()
  >>> $task->area->name
  >>> $task->assignedTo->name
  >>> $task->subtasks->count()
  >>> $task->comments->count()
  ```

- [ ] Verificar scopes:
  ```bash
  php artisan tinker
  >>> Task::forUser(1)->count()
  >>> Task::forArea(1)->count()
  >>> Task::pending()->count()
  >>> Task::overdue()->count()
  ```

- [ ] Verificar accessors:
  ```bash
  php artisan tinker
  >>> $task = Task::first()
  >>> $task->is_overdue
  >>> $task->is_subtask
  ```

---

## 5. Comandos de Implementación

### Paso a Paso

```bash
# 1. Crear las migraciones
php artisan make:migration create_tasks_table
php artisan make:migration create_task_comments_table

# 2. Crear los modelos
php artisan make:model Task
php artisan make:model TaskComment

# 3. Crear los seeders
php artisan make:seeder TaskSeeder
php artisan make:seeder TaskCommentSeeder

# 4. Ejecutar migraciones
php artisan migrate

# 5. Ejecutar seeders
php artisan db:seed --class=TaskSeeder
php artisan db:seed --class=TaskCommentSeeder

# 6. O ejecutar todo desde cero (CUIDADO: borra toda la BD)
php artisan migrate:fresh --seed
```

---

## 6. Notas Técnicas

### 6.1 Índices Optimizados

Los índices compuestos están diseñados para queries comunes:

- **`tasks_user_status_due` (assigned_to, status, due_date):**
  - Query: "Mis tareas pendientes ordenadas por fecha"
  - Ejemplo: `Task::forUser($userId)->pending()->orderBy('due_date')->get()`

- **`tasks_area_status` (area_id, status):**
  - Query: "Tareas del área por estado"
  - Ejemplo: `Task::forArea($areaId)->where('status', 'pending')->get()`

### 6.2 Soft Deletes

Las tareas usan soft delete para:
- Mantener historial de tareas eliminadas
- Permitir restauración
- Preservar integridad referencial con comentarios

### 6.3 Jerarquía de Tareas

La relación `parent_id` permite:
- Jerarquía recursiva (tarea → subtarea → sub-subtarea)
- Recomendación: Limitar a 3 niveles para UX
- Cascade delete: Si se elimina tarea padre, se eliminan subtareas

### 6.4 Spatie Media Library

**Importante:** Las conversiones de imagen se procesan en cola.

**En desarrollo:**
```bash
php artisan queue:work
```

**En producción:**
Configurar supervisor para `queue:work` (ver documentación de Spatie)

---

**Fecha de creación:** 2025-10-22
**Próxima fase:** Controladores y Lógica de Negocio