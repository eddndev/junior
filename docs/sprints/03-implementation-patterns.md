# Patrones de Implementación - Sprint 3

**Fecha:** 2025-10-22
**Propósito:** Documentar patrones establecidos para mantener homogeneidad en el Sprint 3

---

## 1. Patrones de Controladores

### Patrón A: `authorizeResource()` (Recomendado para CRUD simple)

**Ejemplo:** `AreaController`, usar para `TaskController`

```php
class TaskController extends Controller
{
    public function __construct()
    {
        // Autorización automática para todos los métodos CRUD
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request)
    {
        // NO necesita $this->authorize() - ya está manejado por authorizeResource
        $query = Task::with(['area', 'assignedTo', 'assignedBy']);

        // Búsqueda
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Paginación con query string
        $tasks = $query->latest()->paginate(15)->withQueryString();

        return view('tasks.index', compact('tasks'));
    }
}
```

### Patrón B: `authorize()` por método (Para lógica compleja)

**Ejemplo:** `UserController`, usar para `MyTasksController`

```php
class MyTasksController extends Controller
{
    public function index(Request $request)
    {
        // NO necesita authorize - todos los usuarios autenticados pueden ver sus tareas
        $user = auth()->user();

        $query = Task::with(['area', 'assignedBy'])
            ->where('assigned_to', $user->id);

        // ... filtros y búsqueda

        $tasks = $query->latest()->paginate(15)->withQueryString();

        return view('my-tasks.index', compact('tasks'));
    }
}
```

### Patrón C: Middleware de permisos (Para acciones específicas)

**Ejemplo:** `TeamLogController`

```php
public function __construct()
{
    $this->middleware('permission:ver-tareas')->only('index', 'show');
    $this->middleware('permission:crear-tareas')->only(['create', 'store']);
}
```

---

## 2. Patrones de Form Requests

### Estructura Estándar

```php
class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Opción A: Solo validación básica (policy maneja autorización)
        return true;

        // Opción B: Lógica de negocio compleja (como StoreTeamLogRequest)
        $user = $this->user();
        return $user->hasPermission('crear-tareas')
            && $user->areas->pluck('id')->contains($this->input('area_id'));
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'area_id' => ['required', 'exists:areas,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'assigned_by' => ['required', 'exists:users,id'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'parent_id' => ['nullable', 'exists:tasks,id'],

            // Archivos (patrón de TeamLog)
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => [
                'file',
                'max:10240', // 10MB
                'mimes:jpeg,png,jpg,gif,webp,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,mp3,wav,ogg'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descripción',
            'area_id' => 'área',
            'assigned_to' => 'asignado a',
            'due_date' => 'fecha límite',
            'priority' => 'prioridad',
            'parent_id' => 'tarea padre',
            'attachments' => 'archivos adjuntos',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El :attribute es obligatorio.',
            'title.max' => 'El :attribute no puede exceder :max caracteres.',
            'due_date.after_or_equal' => 'La :attribute debe ser hoy o una fecha futura.',
            // ... más mensajes personalizados
        ];
    }

    protected function prepareForValidation(): void
    {
        // Transformaciones antes de validación
        $this->merge([
            'assigned_by' => auth()->id(),
        ]);
    }
}
```

---

## 3. Patrones de Policies

### Estructura Estándar (basada en UserPolicy y AreaPolicy)

```php
class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-tareas');
    }

    /**
     * Determine whether the user can view a specific task.
     */
    public function view(User $user, Task $task): Response|bool
    {
        // Lógica de negocio: puede ver si es asignado, creador o director del área
        if ($task->assigned_to === $user->id) {
            return true;
        }

        if ($task->assigned_by === $user->id) {
            return true;
        }

        // Director del área
        if ($user->areas->pluck('id')->contains($task->area_id)
            && $user->hasPermission('gestionar-tareas-area')) {
            return true;
        }

        return Response::deny('No tienes permiso para ver esta tarea.');
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('crear-tareas');
    }

    /**
     * Determine whether the user can update a task.
     */
    public function update(User $user, Task $task): Response|bool
    {
        // Solo el creador o director del área puede editar
        if ($task->assigned_by === $user->id) {
            return true;
        }

        if ($user->areas->pluck('id')->contains($task->area_id)
            && $user->hasPermission('gestionar-tareas-area')) {
            return true;
        }

        return Response::deny('No tienes permiso para editar esta tarea.');
    }

    /**
     * Determine whether the user can delete a task.
     */
    public function delete(User $user, Task $task): Response|bool
    {
        // Solo el creador o director del área puede eliminar
        if (!$user->hasPermission('crear-tareas')) {
            return Response::deny('No tienes permiso para eliminar tareas.');
        }

        if ($task->assigned_by !== $user->id) {
            return Response::deny('Solo el creador de la tarea puede eliminarla.');
        }

        return true;
    }

    /**
     * Determine whether the user can complete a task.
     * (Custom policy method)
     */
    public function complete(User $user, Task $task): Response|bool
    {
        // El asignado o el director del área pueden completar
        if ($task->assigned_to === $user->id) {
            return true;
        }

        if ($user->areas->pluck('id')->contains($task->area_id)
            && $user->hasPermission('gestionar-tareas-area')) {
            return true;
        }

        return Response::deny('No tienes permiso para completar esta tarea.');
    }

    /**
     * Determine whether the user can reassign a task.
     * (Custom policy method)
     */
    public function reassign(User $user, Task $task): Response|bool
    {
        // Solo el creador o director del área puede reasignar
        if ($task->assigned_by === $user->id) {
            return true;
        }

        if ($user->areas->pluck('id')->contains($task->area_id)
            && $user->hasPermission('gestionar-tareas-area')) {
            return true;
        }

        return Response::deny('No tienes permiso para reasignar esta tarea.');
    }
}
```

---

## 4. Patrones de Modelos Eloquent

### Estructura Estándar (basada en User, Area, TeamLog)

```php
class Task extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

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

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // === RELACIONES ===

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    // === SCOPES ===

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeForArea($query, int $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // === ACCESSORS ===

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date || in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }

        return $this->due_date->isPast();
    }

    public function getIsSubtaskAttribute(): bool
    {
        return !is_null($this->parent_id);
    }

    // === SPATIE MEDIA LIBRARY ===

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
                 // Audio
                 'audio/mpeg', 'audio/wav', 'audio/ogg',
             ]);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Solo procesar imágenes (igual que TeamLog)
        if ($media && $media->collection_name === 'attachments' && str_starts_with($media->mime_type, 'image/')) {
            // WebP conversion (queued)
            $this->addMediaConversion('webp')
                 ->format('webp')
                 ->quality(85)
                 ->performOnCollections('attachments')
                 ->queued();

            // AVIF conversion (queued)
            $this->addMediaConversion('avif')
                 ->format('avif')
                 ->quality(80)
                 ->performOnCollections('attachments')
                 ->queued();

            // Thumbnail (non-queued para preview inmediato)
            $this->addMediaConversion('thumb')
                 ->width(300)
                 ->height(300)
                 ->format('webp')
                 ->quality(70)
                 ->performOnCollections('attachments')
                 ->nonQueued();
        }
    }
}
```

---

## 5. Patrones de Migraciones

### Ejemplo Completo

```php
public function up(): void
{
    Schema::create('tasks', function (Blueprint $table) {
        // Primary Key
        $table->id();

        // Basic Info
        $table->string('title');
        $table->text('description')->nullable();

        // Status & Priority
        $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
              ->default('pending');
        $table->enum('priority', ['low', 'medium', 'high', 'critical'])
              ->default('medium');

        // Dates
        $table->date('due_date')->nullable();
        $table->timestamp('completed_at')->nullable();

        // Foreign Keys
        $table->foreignId('area_id')->constrained()->onDelete('restrict');
        $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('assigned_by')->constrained('users')->onDelete('restrict');
        $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');

        // Ordering
        $table->integer('order')->default(0);

        // Timestamps & Soft Deletes
        $table->timestamps();
        $table->softDeletes();

        // Indexes
        $table->index('status');
        $table->index('due_date');
        $table->index(['assigned_to', 'status', 'due_date']); // Compuesto para queries comunes
        $table->index(['area_id', 'status']);
        $table->index('parent_id');
    });
}
```

---

## 6. Patrones de Procesamiento de Archivos (Spatie Media Library)

### En el Controlador (basado en TeamLogController)

```php
public function store(StoreTaskRequest $request)
{
    $validated = $request->validated();

    // Crear la tarea
    $task = Task::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'area_id' => $validated['area_id'],
        'assigned_to' => $validated['assigned_to'],
        'assigned_by' => auth()->id(),
        'priority' => $validated['priority'],
        'status' => 'pending',
        'due_date' => $validated['due_date'],
    ]);

    // Procesar archivos adjuntos
    if ($request->hasFile('attachments')) {
        \Log::info('Procesando archivos adjuntos:', ['count' => count($request->file('attachments'))]);

        foreach ($request->file('attachments') as $index => $file) {
            try {
                \Log::info("Archivo {$index}:", [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                $task->addMedia($file)
                     ->toMediaCollection('attachments');
                // Las conversiones se procesarán automáticamente en cola
            } catch (\Exception $e) {
                \Log::error('Error al procesar archivo adjunto: ' . $e->getMessage(), [
                    'file' => $file->getClientOriginalName(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    return redirect()
        ->route('tasks.show', $task)
        ->with('success', 'Tarea creada con éxito. Los adjuntos se están procesando en segundo plano.');
}
```

---

## 7. Resumen de Mejores Prácticas

### ✅ DO (Hacer)

1. **Controladores:**
   - Usar `authorizeResource()` para CRUD simple
   - Eager loading con `with()` para evitar N+1
   - Paginación con `withQueryString()`
   - Mensajes flash con `with('success', '...')`

2. **Form Requests:**
   - Validaciones detalladas con mensajes en español
   - `attributes()` y `messages()` personalizados
   - `prepareForValidation()` para transformaciones

3. **Policies:**
   - `Response::deny()` con mensajes descriptivos
   - Combinar permisos con lógica de negocio
   - Métodos custom para acciones específicas

4. **Modelos:**
   - Relaciones explícitas con tipos de retorno
   - Scopes para queries comunes
   - Accessors para propiedades calculadas
   - Spatie Media Library para archivos

5. **Archivos:**
   - Logging comprehensivo con `\Log::info()` y `\Log::error()`
   - Try-catch para cada archivo procesado
   - Conversiones queued para imágenes pesadas
   - Thumbnail non-queued para preview inmediato

### ❌ DON'T (No hacer)

1. No usar `authorize()` cuando ya tienes `authorizeResource()`
2. No olvidar eager loading (causa N+1 queries)
3. No hardcodear textos en inglés (siempre en español)
4. No procesar archivos sin logging
5. No olvidar `withQueryString()` en paginación con filtros
6. No usar `wire:model` para file uploads (conflicto con POST)

---

**Última actualización:** 2025-10-22