<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'area_id',
        'parent_task_id',
        'priority',
        'status',
        'due_date',
        'completed_at',
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

    /**
     * Get the area that owns the task.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the parent task.
     */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /**
     * Get the child tasks (dependent tasks).
     */
    public function childTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    /**
     * Get the subtasks for the task.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    /**
     * Get all task assignments (polymorphic).
     */
    public function assignments(): MorphMany
    {
        return $this->morphMany(TaskAssignment::class, 'assignable');
    }

    /**
     * Scope a query to only include tasks with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include tasks with a specific priority.
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope a query to only include active tasks (not soft deleted).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to tasks belonging to a specific area.
     */
    public function scopeForArea($query, int $areaId)
    {
        return $query->where('area_id', $areaId);
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
     * Determine if this task has a parent task (is a dependent task).
     */
    public function getIsChildTaskAttribute(): bool
    {
        return !is_null($this->parent_task_id);
    }

    /**
     * Get all users assigned to this task (via polymorphic assignments).
     */
    public function getAssignedUsersAttribute()
    {
        return $this->assignments()->with('user')->get()->pluck('user');
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
