# Sprint 4: Sistema de Calendario General

**Fecha de Inicio:** 2025-11-16
**Estado:** ✅ COMPLETADO
**Progreso:** 100%

---

## 1. Objetivos del Sprint

Implementar un sistema de Calendario General que permita:
- **Eventos**: Sucesos importantes con fecha, duración y archivos adjuntos
- **Reuniones**: Con participantes, registro de asistencia, archivos y notas
- **Integración con Tareas**: Visualizar tareas con fecha límite en el calendario
- **Control de Acceso**: Solo directores de área y director general pueden crear/editar

---

## 2. Historias de Usuario

### HU-01: Ver Calendario General
**Como** empleado
**Quiero** ver el calendario general de la empresa
**Para** conocer eventos y reuniones importantes

**Criterios de Aceptación:**
- [ ] Vista mensual del calendario
- [ ] Filtrar por área o ver todos
- [ ] Distinguir visualmente eventos vs reuniones vs tareas
- [ ] Navegación entre meses
- [ ] Responsive (móvil y escritorio)

### HU-02: Crear Evento
**Como** director de área o director general
**Quiero** crear eventos en el calendario
**Para** comunicar sucesos importantes a la empresa

**Criterios de Aceptación:**
- [ ] Título y descripción del evento
- [ ] Fecha y hora de inicio/fin
- [ ] Opción de evento de día completo
- [ ] Adjuntar archivos (documentos, imágenes)
- [ ] Seleccionar área o marcar como general
- [ ] Color/etiqueta personalizable
- [ ] Notificar a usuarios relevantes (opcional)

### HU-03: Crear Reunión
**Como** director de área o director general
**Quiero** programar reuniones
**Para** coordinar encuentros con el equipo

**Criterios de Aceptación:**
- [ ] Título, descripción y agenda
- [ ] Fecha, hora de inicio y fin
- [ ] Ubicación (física o virtual con enlace)
- [ ] Seleccionar participantes de la empresa
- [ ] Adjuntar archivos preparatorios
- [ ] Estado de la reunión (programada, en curso, completada, cancelada)

### HU-04: Registrar Asistencia
**Como** director o creador de la reunión
**Quiero** registrar quién asistió
**Para** mantener historial de participación

**Criterios de Aceptación:**
- [ ] Lista de participantes invitados
- [ ] Marcar asistencia (presente, ausente, tardanza, justificado)
- [ ] Notas por participante (opcional)
- [ ] Hora de llegada (opcional)
- [ ] Historial de asistencia por usuario

### HU-05: Ver Detalle de Evento/Reunión
**Como** empleado
**Quiero** ver los detalles completos de un evento o reunión
**Para** tener toda la información necesaria

**Criterios de Aceptación:**
- [ ] Información completa (título, descripción, fechas)
- [ ] Archivos adjuntos descargables
- [ ] Participantes (para reuniones)
- [ ] Registro de asistencia (para reuniones completadas)
- [ ] Creador y fecha de creación
- [ ] Opciones de edición (si tiene permisos)

### HU-06: Integrar Tareas en Calendario
**Como** empleado
**Quiero** ver mis tareas con fecha límite en el calendario
**Para** tener una vista unificada de mis compromisos

**Criterios de Aceptación:**
- [ ] Tareas con due_date aparecen en el calendario
- [ ] Diferente color/estilo que eventos y reuniones
- [ ] Click lleva al detalle de la tarea
- [ ] Filtrar para mostrar/ocultar tareas
- [ ] Indicador visual de tarea vencida

### HU-07: Editar/Eliminar Entradas
**Como** director con permisos
**Quiero** modificar o eliminar eventos/reuniones
**Para** mantener el calendario actualizado

**Criterios de Aceptación:**
- [ ] Editar todos los campos
- [ ] Soft delete (mantener historial)
- [ ] Notificar cambios a participantes
- [ ] Registro de auditoría

---

## 3. Arquitectura Técnica

### 3.1 Modelos de Base de Datos

#### Tabla: `calendar_events`
```sql
CREATE TABLE calendar_events (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    type ENUM('event', 'meeting') NOT NULL DEFAULT 'event',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    start_time TIME NULL,
    end_time TIME NULL,
    is_all_day BOOLEAN DEFAULT FALSE,
    location VARCHAR(500) NULL,
    virtual_link VARCHAR(500) NULL,
    color VARCHAR(20) DEFAULT '#6366f1',
    area_id BIGINT UNSIGNED NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    is_public BOOLEAN DEFAULT TRUE,
    metadata JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_start_date (start_date),
    INDEX idx_type (type),
    INDEX idx_area (area_id)
);
```

#### Tabla: `calendar_event_participants`
```sql
CREATE TABLE calendar_event_participants (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    calendar_event_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    is_required BOOLEAN DEFAULT TRUE,
    attendance_status ENUM('pending', 'confirmed', 'declined', 'tentative') DEFAULT 'pending',
    actual_attendance ENUM('present', 'absent', 'late', 'excused') NULL,
    arrival_time TIME NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (calendar_event_id) REFERENCES calendar_events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participant (calendar_event_id, user_id)
);
```

### 3.2 Modelos Eloquent

#### CalendarEvent
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CalendarEvent extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'description', 'type', 'start_date', 'end_date',
        'start_time', 'end_time', 'is_all_day', 'location', 'virtual_link',
        'color', 'area_id', 'created_by', 'status', 'is_public', 'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_all_day' => 'boolean',
        'is_public' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function area() { return $this->belongsTo(Area::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function participants() { return $this->hasMany(CalendarEventParticipant::class); }
    public function users() {
        return $this->belongsToMany(User::class, 'calendar_event_participants')
                    ->withPivot(['is_required', 'attendance_status', 'actual_attendance', 'arrival_time', 'notes'])
                    ->withTimestamps();
    }

    // Scopes
    public function scopeEvents($query) { return $query->where('type', 'event'); }
    public function scopeMeetings($query) { return $query->where('type', 'meeting'); }
    public function scopeInDateRange($query, $start, $end) {
        return $query->where(function($q) use ($start, $end) {
            $q->whereBetween('start_date', [$start, $end])
              ->orWhereBetween('end_date', [$start, $end])
              ->orWhere(function($q2) use ($start, $end) {
                  $q2->where('start_date', '<=', $start)
                     ->where('end_date', '>=', $end);
              });
        });
    }
    public function scopeForArea($query, $areaId) {
        return $query->where(function($q) use ($areaId) {
            $q->where('area_id', $areaId)->orWhereNull('area_id');
        });
    }
    public function scopeUpcoming($query) {
        return $query->where('start_date', '>=', now()->toDateString())
                     ->orderBy('start_date')
                     ->orderBy('start_time');
    }

    // Media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
             ->useDisk('public');
    }

    // Helpers
    public function isEvent(): bool { return $this->type === 'event'; }
    public function isMeeting(): bool { return $this->type === 'meeting'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
}
```

#### CalendarEventParticipant
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEventParticipant extends Model
{
    protected $fillable = [
        'calendar_event_id', 'user_id', 'is_required',
        'attendance_status', 'actual_attendance', 'arrival_time', 'notes'
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function event() { return $this->belongsTo(CalendarEvent::class, 'calendar_event_id'); }
    public function user() { return $this->belongsTo(User::class); }

    // Helpers
    public function isConfirmed(): bool { return $this->attendance_status === 'confirmed'; }
    public function wasPresent(): bool { return $this->actual_attendance === 'present'; }
}
```

### 3.3 Permisos y Autorización

#### Nuevos Permisos (PermissionSeeder)
```php
// Permisos de Calendario
['name' => 'Ver Calendario General', 'slug' => 'ver-calendario', 'module' => 'calendario'],
['name' => 'Crear Eventos', 'slug' => 'crear-eventos-calendario', 'module' => 'calendario'],
['name' => 'Editar Eventos', 'slug' => 'editar-eventos-calendario', 'module' => 'calendario'],
['name' => 'Eliminar Eventos', 'slug' => 'eliminar-eventos-calendario', 'module' => 'calendario'],
['name' => 'Crear Reuniones', 'slug' => 'crear-reuniones', 'module' => 'calendario'],
['name' => 'Registrar Asistencia', 'slug' => 'registrar-asistencia', 'module' => 'calendario'],
```

#### Asignación por Rol (RolePermissionSeeder)
```php
// direccion-general: TODOS los permisos de calendario
// director-area: crear/editar eventos y reuniones de su área
// empleado-general: solo ver calendario
```

#### CalendarEventPolicy
```php
namespace App\Policies;

class CalendarEventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-calendario');
    }

    public function view(User $user, CalendarEvent $event): bool
    {
        if (!$user->hasPermission('ver-calendario')) return false;

        // Eventos públicos visibles para todos
        if ($event->is_public) return true;

        // Si tiene área específica, verificar acceso
        if ($event->area_id) {
            return $user->areas->contains($event->area_id);
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('crear-eventos-calendario')
            || $user->hasPermission('crear-reuniones');
    }

    public function update(User $user, CalendarEvent $event): bool
    {
        if (!$user->hasPermission('editar-eventos-calendario')) return false;

        // Creador puede editar
        if ($event->created_by === $user->id) return true;

        // Director general puede editar todo
        if ($user->hasRole('direccion-general') || $user->hasRole('super-admin')) return true;

        // Director de área puede editar eventos de su área
        if ($event->area_id && $user->areas->contains($event->area_id)) {
            return $user->hasRole('director-area');
        }

        return false;
    }

    public function delete(User $user, CalendarEvent $event): bool
    {
        return $this->update($user, $event)
            && $user->hasPermission('eliminar-eventos-calendario');
    }

    public function recordAttendance(User $user, CalendarEvent $event): bool
    {
        if (!$event->isMeeting()) return false;
        if (!$user->hasPermission('registrar-asistencia')) return false;

        // Solo el creador o director puede registrar asistencia
        return $event->created_by === $user->id
            || $user->hasRole('direccion-general')
            || $user->hasRole('super-admin');
    }
}
```

### 3.4 Controladores

#### CalendarController (Vista principal)
```php
class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Vista del calendario con filtros
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $areaId = $request->get('area_id');

        return view('calendar.index', compact('month', 'year', 'areaId'));
    }

    public function events(Request $request)
    {
        // API JSON para el calendario
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $areaId = $request->get('area_id');

        $events = CalendarEvent::with(['area', 'creator'])
            ->inDateRange($start, $end)
            ->when($areaId, fn($q) => $q->forArea($areaId))
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d') . ($event->start_time ? 'T' . $event->start_time : ''),
                    'end' => $event->end_date->format('Y-m-d') . ($event->end_time ? 'T' . $event->end_time : ''),
                    'allDay' => $event->is_all_day,
                    'color' => $event->color,
                    'type' => $event->type,
                    'url' => route('calendar.events.show', $event),
                ];
            });

        // Agregar tareas con due_date
        $tasks = Task::whereNotNull('due_date')
            ->whereBetween('due_date', [$start, $end])
            ->where('assigned_to', auth()->id())
            ->get()
            ->map(function($task) {
                return [
                    'id' => 'task-' . $task->id,
                    'title' => '📋 ' . $task->title,
                    'start' => $task->due_date->format('Y-m-d'),
                    'allDay' => true,
                    'color' => '#f59e0b', // Amber for tasks
                    'type' => 'task',
                    'url' => route('tasks.show', $task),
                ];
            });

        return response()->json($events->merge($tasks));
    }
}
```

#### Componentes Livewire para Diálogos Laterales

##### CreateEventDialog (Livewire)
```php
namespace App\Livewire\Calendar;

use App\Models\Area;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateEventDialog extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Form fields
    public string $title = '';
    public string $description = '';
    public string $type = 'event'; // 'event' or 'meeting'
    public string $startDate = '';
    public string $endDate = '';
    public ?string $startTime = null;
    public ?string $endTime = null;
    public bool $isAllDay = false;
    public ?string $location = null;
    public ?string $virtualLink = null;
    public string $color = '#6366f1';
    public ?int $areaId = null;
    public bool $isPublic = true;
    public array $selectedParticipants = [];
    public $attachments = [];

    // Data for form
    public array $areas = [];
    public array $users = [];

    protected $listeners = [
        'openCreateEventDialog' => 'open',
    ];

    public function mount(): void
    {
        $this->areas = Area::active()->orderBy('name')->get()->toArray();
        $this->users = User::active()->orderBy('name')->get()->toArray();
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function open(?string $type = 'event', ?string $date = null): void
    {
        $this->resetForm();
        $this->type = $type;
        if ($date) {
            $this->startDate = $date;
            $this->endDate = $date;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:event,meeting'],
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
            'startTime' => ['nullable', 'date_format:H:i'],
            'endTime' => ['nullable', 'date_format:H:i'],
            'isAllDay' => ['boolean'],
            'location' => ['nullable', 'string', 'max:500'],
            'virtualLink' => ['nullable', 'url', 'max:500'],
            'color' => ['nullable', 'string', 'max:20'],
            'areaId' => ['nullable', 'exists:areas,id'],
            'isPublic' => ['boolean'],
            'selectedParticipants' => ['nullable', 'array'],
            'selectedParticipants.*' => ['exists:users,id'],
            'attachments.*' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        try {
            $this->authorize('create', CalendarEvent::class);

            $event = CalendarEvent::create([
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'is_all_day' => $this->isAllDay,
                'location' => $this->location,
                'virtual_link' => $this->virtualLink,
                'color' => $this->color,
                'area_id' => $this->areaId,
                'is_public' => $this->isPublic,
                'created_by' => auth()->id(),
            ]);

            // Attach participants for meetings
            if ($this->type === 'meeting' && !empty($this->selectedParticipants)) {
                foreach ($this->selectedParticipants as $userId) {
                    $event->participants()->create([
                        'user_id' => $userId,
                        'is_required' => true,
                    ]);
                }
            }

            // Handle attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $event->addMedia($file->getRealPath())
                          ->usingFileName($file->getClientOriginalName())
                          ->toMediaCollection('attachments');
                }
            }

            $this->dispatch('show-toast', message: 'Evento creado exitosamente', type: 'success');
            $this->dispatch('close-dialog', dialogId: 'create-event-dialog');
            $this->dispatch('calendar-event-created');
            $this->resetForm();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para crear eventos', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al crear evento: ' . $e->getMessage(), type: 'error');
        }
    }

    public function resetForm(): void
    {
        $this->reset(['title', 'description', 'location', 'virtualLink', 'selectedParticipants', 'attachments']);
        $this->type = 'event';
        $this->color = '#6366f1';
        $this->isAllDay = false;
        $this->isPublic = true;
        $this->areaId = null;
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->startTime = null;
        $this->endTime = null;
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->dispatch('close-dialog', dialogId: 'create-event-dialog');
    }

    public function render()
    {
        return view('livewire.calendar.create-event-dialog');
    }
}
```

##### EventDetailDialog (Livewire)
```php
namespace App\Livewire\Calendar;

use App\Models\CalendarEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EventDetailDialog extends Component
{
    use AuthorizesRequests;

    public ?int $eventId = null;
    public ?CalendarEvent $event = null;

    protected $listeners = [
        'loadCalendarEvent' => 'load',
    ];

    public function load(int $eventId): void
    {
        $this->eventId = $eventId;

        try {
            $this->event = CalendarEvent::with([
                'area',
                'creator',
                'participants.user',
                'media',
            ])->findOrFail($eventId);

            $this->authorize('view', $this->event);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Evento no encontrado', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tienes permiso para ver este evento', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        }
    }

    public function openEditDialog(): void
    {
        $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        $this->dispatch('loadCalendarEventForEdit', $this->eventId);
    }

    public function openAttendanceDialog(): void
    {
        $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        $this->dispatch('loadEventForAttendance', $this->eventId);
    }

    public function render()
    {
        return view('livewire.calendar.event-detail-dialog');
    }
}
```

##### AttendanceDialog (Livewire)
```php
namespace App\Livewire\Calendar;

use App\Models\CalendarEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AttendanceDialog extends Component
{
    use AuthorizesRequests;

    public ?int $eventId = null;
    public ?CalendarEvent $event = null;
    public array $attendanceData = [];

    protected $listeners = [
        'loadEventForAttendance' => 'load',
    ];

    public function load(int $eventId): void
    {
        $this->eventId = $eventId;

        try {
            $this->event = CalendarEvent::with(['participants.user'])
                ->findOrFail($eventId);

            $this->authorize('recordAttendance', $this->event);

            // Initialize attendance data
            $this->attendanceData = [];
            foreach ($this->event->participants as $participant) {
                $this->attendanceData[$participant->id] = [
                    'status' => $participant->actual_attendance ?? 'present',
                    'arrival_time' => $participant->arrival_time,
                    'notes' => $participant->notes ?? '',
                ];
            }

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al cargar la reunión', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'attendance-dialog');
        }
    }

    public function saveAttendance(): void
    {
        try {
            $this->authorize('recordAttendance', $this->event);

            foreach ($this->attendanceData as $participantId => $data) {
                $this->event->participants()->where('id', $participantId)->update([
                    'actual_attendance' => $data['status'],
                    'arrival_time' => $data['arrival_time'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            $this->event->update(['status' => 'completed']);

            $this->dispatch('show-toast', message: 'Asistencia registrada correctamente', type: 'success');
            $this->dispatch('close-dialog', dialogId: 'attendance-dialog');
            $this->dispatch('calendar-event-updated');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al guardar asistencia', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.calendar.attendance-dialog');
    }
}
```

### 3.5 Vistas y Componentes Livewire

#### Estructura de Archivos
```
resources/views/
├── calendar/
│   └── index.blade.php              # Vista principal del calendario (Day/Week/Month/Year view)
│
├── livewire/calendar/
│   ├── event-detail-dialog.blade.php     # Diálogo lateral: detalle evento/reunión
│   ├── create-event-dialog.blade.php     # Diálogo lateral: crear evento
│   ├── edit-event-dialog.blade.php       # Diálogo lateral: editar evento
│   └── attendance-dialog.blade.php       # Diálogo lateral: registro asistencia
│
app/Livewire/Calendar/
├── EventDetailDialog.php          # Componente: ver detalle
├── CreateEventDialog.php          # Componente: crear evento/reunión
├── EditEventDialog.php            # Componente: editar evento/reunión
└── AttendanceDialog.php           # Componente: registrar asistencia
```

#### Patrón de Diálogos Laterales (siguiendo TaskDetailDialog)
Los diálogos usan los componentes:
- `x-dialog-header` - Encabezado con título y descripción
- `x-dialog-footer` - Botones de acción
- `x-dialog-wrapper` - Contenedor con animación slide-in

#### Diseño del Calendario (basado en example-calendar.md)
El calendario principal implementará:
- **Vista por Día**: Grid de 48 filas (30 min cada una) con eventos posicionados por hora
- **Vista por Semana**: 7 columnas con horas
- **Vista por Mes**: Grid de días con eventos listados
- **Mini Calendario**: Panel lateral para navegación rápida
- **Componentes TailwindPlus**: `el-dropdown`, `el-menu` para menús
- **Colores por tipo**: Azul (eventos), Verde (reuniones), Ámbar (tareas)

#### Vista Principal (index.blade.php)
```blade
@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="md:flex md:items-center md:justify-between mb-6">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                Calendario General
            </h1>
            @can('create', App\Models\CalendarEvent::class)
            <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                <a href="{{ route('calendar.events.create', ['type' => 'event']) }}"
                   class="btn-primary">
                    Nuevo Evento
                </a>
                <a href="{{ route('calendar.events.create', ['type' => 'meeting']) }}"
                   class="btn-secondary">
                    Nueva Reunión
                </a>
            </div>
            @endcan
        </div>

        {{-- Calendar Grid with Alpine.js --}}
        <div x-data="calendarApp()" class="bg-white dark:bg-neutral-800 rounded-lg shadow">
            {{-- Navigation --}}
            <div class="flex items-center justify-between p-4 border-b">
                <button @click="previousMonth()" class="btn-icon">←</button>
                <h2 class="text-lg font-semibold" x-text="currentMonthName"></h2>
                <button @click="nextMonth()" class="btn-icon">→</button>
            </div>

            {{-- Days Grid --}}
            <div class="grid grid-cols-7 gap-px bg-neutral-200 dark:bg-neutral-700">
                {{-- Day headers --}}
                <template x-for="day in ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']">
                    <div class="bg-neutral-50 dark:bg-neutral-900 p-2 text-center text-sm font-medium"
                         x-text="day"></div>
                </template>

                {{-- Calendar days --}}
                <template x-for="day in calendarDays" :key="day.date">
                    <div class="bg-white dark:bg-neutral-800 min-h-[100px] p-2"
                         :class="{ 'bg-neutral-50 dark:bg-neutral-900': !day.isCurrentMonth }">
                        <span class="text-sm"
                              :class="{ 'font-bold text-primary-600': day.isToday }"
                              x-text="day.dayNumber"></span>

                        {{-- Events for this day --}}
                        <div class="mt-1 space-y-1">
                            <template x-for="event in getEventsForDay(day.date)" :key="event.id">
                                <a :href="event.url"
                                   class="block text-xs p-1 rounded truncate"
                                   :style="{ backgroundColor: event.color + '20', color: event.color }">
                                    <span x-text="event.title"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Legend --}}
        <div class="mt-4 flex items-center space-x-4 text-sm">
            <span class="flex items-center">
                <span class="w-3 h-3 rounded bg-primary-500 mr-2"></span> Eventos
            </span>
            <span class="flex items-center">
                <span class="w-3 h-3 rounded bg-green-500 mr-2"></span> Reuniones
            </span>
            <span class="flex items-center">
                <span class="w-3 h-3 rounded bg-amber-500 mr-2"></span> Tareas
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calendarApp() {
    return {
        currentDate: new Date(),
        events: [],

        async init() {
            await this.fetchEvents();
        },

        async fetchEvents() {
            const start = this.firstDayOfMonth;
            const end = this.lastDayOfMonth;
            const response = await fetch(`/calendar/events/api?start=${start}&end=${end}`);
            this.events = await response.json();
        },

        get currentMonthName() {
            return this.currentDate.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
        },

        get calendarDays() {
            // Generate array of day objects for the month grid
            // Including previous/next month days to fill the grid
        },

        getEventsForDay(date) {
            return this.events.filter(e => e.start === date);
        },

        previousMonth() {
            this.currentDate = new Date(this.currentDate.setMonth(this.currentDate.getMonth() - 1));
            this.fetchEvents();
        },

        nextMonth() {
            this.currentDate = new Date(this.currentDate.setMonth(this.currentDate.getMonth() + 1));
            this.fetchEvents();
        }
    }
}
</script>
@endpush
@endsection
```

### 3.6 Rutas

```php
// routes/web.php

Route::middleware(['auth'])->group(function () {
    // Calendario principal
    Route::get('/calendar', [CalendarController::class, 'index'])
        ->middleware('permission:ver-calendario')
        ->name('calendar.index');

    // API para datos del calendario
    Route::get('/calendar/events/api', [CalendarController::class, 'events'])
        ->middleware('permission:ver-calendario')
        ->name('calendar.events.api');

    // CRUD de eventos/reuniones
    Route::resource('calendar/events', CalendarEventController::class)
        ->except(['index'])
        ->names('calendar.events');

    // Registro de asistencia
    Route::post('/calendar/events/{event}/attendance', [CalendarEventController::class, 'recordAttendance'])
        ->middleware('permission:registrar-asistencia')
        ->name('calendar.events.attendance');
});
```

### 3.7 Validación (Form Requests)

#### StoreCalendarEventRequest
```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'type' => ['required', 'in:event,meeting'],
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        'start_time' => ['nullable', 'date_format:H:i'],
        'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
        'is_all_day' => ['boolean'],
        'location' => ['nullable', 'string', 'max:500'],
        'virtual_link' => ['nullable', 'url', 'max:500'],
        'color' => ['nullable', 'string', 'max:20'],
        'area_id' => ['nullable', 'exists:areas,id'],
        'is_public' => ['boolean'],
        'participants' => ['nullable', 'array'],
        'participants.*' => ['exists:users,id'],
        'attachments' => ['nullable', 'array'],
        'attachments.*' => ['file', 'max:10240'], // 10MB max
    ];
}
```

---

## 4. Plan de Implementación

### Fase 1: Fundación (Días 1-2)
- [ ] Crear migración `create_calendar_events_table`
- [ ] Crear migración `create_calendar_event_participants_table`
- [ ] Ejecutar migraciones
- [ ] Crear modelo `CalendarEvent` con Spatie Media Library
- [ ] Crear modelo `CalendarEventParticipant`
- [ ] Agregar permisos al `PermissionSeeder`
- [ ] Asignar permisos en `RolePermissionSeeder`
- [ ] Crear `CalendarEventPolicy`
- [ ] Registrar policy en `AuthServiceProvider`

### Fase 2: Backend - Controlador y Rutas (Días 3-4)
- [ ] Crear `CalendarController` (vista principal + API JSON)
- [ ] Configurar rutas en `web.php` con middleware de permisos
- [ ] Crear `CalendarEventObserver` para auditoría
- [ ] Implementar método `events()` para alimentar calendario

### Fase 3: Vista Principal del Calendario (Días 5-7)
- [ ] Crear vista `calendar/index.blade.php` siguiendo example-calendar.md
- [ ] Implementar vista por día con grid de 48 filas (30 min cada una)
- [ ] Implementar vista por semana (7 columnas)
- [ ] Implementar vista por mes (grid de días)
- [ ] Mini calendario lateral para navegación
- [ ] Selector de vistas con `el-dropdown` y `el-menu`
- [ ] Navegación entre fechas (anterior/siguiente/hoy)
- [ ] Posicionamiento de eventos por hora usando CSS Grid
- [ ] Colores diferenciados: Azul (eventos), Verde (reuniones), Ámbar (tareas)
- [ ] Responsive: vista móvil con días de semana
- [ ] Dark mode completo

### Fase 4: Diálogos Laterales Livewire (Días 8-11)
- [ ] Crear `App\Livewire\Calendar\CreateEventDialog`
- [ ] Crear vista `livewire/calendar/create-event-dialog.blade.php`
  - Usar `x-dialog-header`, `x-dialog-footer`
  - Formulario con campos: título, descripción, tipo (evento/reunión)
  - Selector de fechas y horas
  - Toggle día completo
  - Ubicación y enlace virtual
  - Selector de color
  - Selector de área
  - Checkbox público/privado
  - Selector múltiple de participantes (para reuniones)
  - Upload de archivos adjuntos
- [ ] Crear `App\Livewire\Calendar\EditEventDialog`
- [ ] Crear vista `livewire/calendar/edit-event-dialog.blade.php`
- [ ] Crear `App\Livewire\Calendar\EventDetailDialog`
- [ ] Crear vista `livewire/calendar/event-detail-dialog.blade.php`
  - Layout similar a task-detail-dialog
  - Grid de 2/3 contenido principal + 1/3 sidebar
  - Mostrar todos los detalles del evento
  - Lista de participantes (para reuniones)
  - Archivos adjuntos descargables
  - Información del creador y fechas
  - Botones de acción: Editar, Registrar Asistencia, Eliminar

### Fase 5: Registro de Asistencia (Días 12-13)
- [ ] Crear `App\Livewire\Calendar\AttendanceDialog`
- [ ] Crear vista `livewire/calendar/attendance-dialog.blade.php`
  - Lista de participantes invitados
  - Para cada participante: estado (presente, ausente, tardanza, justificado)
  - Campo opcional: hora de llegada
  - Campo opcional: notas
  - Botón guardar que marca reunión como completada

### Fase 6: Integración y Pulido (Días 14-15)
- [ ] Integrar tareas con due_date en calendario
- [ ] Al hacer click en evento, abrir diálogo lateral
- [ ] Eventos JavaScript para comunicación Livewire <-> Alpine
- [ ] Agregar enlace al sidebar (con verificación de permisos)
- [ ] Probar flujo completo: crear evento → ver en calendario → abrir detalle → editar
- [ ] Probar permisos: empleado (solo ver), director (crear/editar su área), DG (todo)
- [ ] Verificar soft deletes
- [ ] Testing manual de casos de uso
- [ ] Actualizar CLAUDE.md con progreso

---

## 5. Consideraciones Técnicas

### Performance
- Índices en `start_date`, `area_id`, `type`
- Paginación en listados
- Eager loading de relaciones
- Cache de eventos por mes (opcional)

### Seguridad
- Validación de permisos en cada acción
- Verificación de área para directores
- Sanitización de inputs
- Protección CSRF en formularios
- Soft deletes para historial

### UX
- Colores distintivos por tipo (evento/reunión/tarea)
- Navegación intuitiva entre meses
- Feedback visual en acciones
- Responsive design
- Dark mode compatible

### Integración Futura
- Notificaciones automáticas
- Recordatorios por email
- Exportar a Google Calendar / iCal
- Repetición de eventos (semanal, mensual)
- Sala de reuniones virtuales integrada

---

## 6. Archivos a Crear

### Migraciones
```
database/migrations/
├── 2025_11_16_000001_create_calendar_events_table.php
└── 2025_11_16_000002_create_calendar_event_participants_table.php
```

### Modelos
```
app/Models/
├── CalendarEvent.php
└── CalendarEventParticipant.php
```

### Controladores
```
app/Http/Controllers/
└── CalendarController.php     # Vista principal + API
```

### Componentes Livewire
```
app/Livewire/Calendar/
├── CreateEventDialog.php      # Diálogo crear evento/reunión
├── EditEventDialog.php        # Diálogo editar evento/reunión
├── EventDetailDialog.php      # Diálogo ver detalle
└── AttendanceDialog.php       # Diálogo registro asistencia
```

### Vistas Livewire (Diálogos Laterales)
```
resources/views/livewire/calendar/
├── create-event-dialog.blade.php
├── edit-event-dialog.blade.php
├── event-detail-dialog.blade.php
└── attendance-dialog.blade.php
```

### Vista Principal
```
resources/views/calendar/
└── index.blade.php            # Calendario con Day/Week/Month view
```

### Policies
```
app/Policies/
└── CalendarEventPolicy.php
```

### Observers
```
app/Observers/
└── CalendarEventObserver.php
```

### Seeders (actualizar)
```
database/seeders/
├── PermissionSeeder.php (agregar permisos)
└── RolePermissionSeeder.php (asignar permisos)
```

---

## 7. Checklist Final

### Pre-implementación
- [ ] Revisar esquema de BD con usuario
- [ ] Confirmar roles con permisos de creación
- [ ] Definir colores por tipo de evento
- [ ] Aprobar wireframes de UI

### Post-implementación
- [ ] Tests unitarios de modelos
- [ ] Tests feature de controladores
- [ ] Verificar permisos en producción
- [ ] Documentar API endpoints
- [ ] Actualizar CLAUDE.md con progreso

---

**Estimación Total:** 12 días de desarrollo
**Complejidad:** Media-Alta
**Dependencias:** Sprint 2 (usuarios/áreas), Sprint 3 (tareas)
