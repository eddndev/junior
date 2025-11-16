<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CalendarEvent extends Model implements HasMedia
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
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_all_day',
        'location',
        'virtual_link',
        'color',
        'area_id',
        'created_by',
        'status',
        'is_public',
        'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_all_day' => 'boolean',
            'is_public' => 'boolean',
            'metadata' => 'array',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the area that owns this event.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the user who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the participants for this event (through pivot model).
     */
    public function participants(): HasMany
    {
        return $this->hasMany(CalendarEventParticipant::class);
    }

    /**
     * Get the users associated with this event (through pivot table).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_participants')
                    ->withPivot([
                        'is_required',
                        'attendance_status',
                        'actual_attendance',
                        'arrival_time',
                        'notes',
                    ])
                    ->withTimestamps();
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope a query to only include events (not meetings).
     */
    public function scopeEvents($query)
    {
        return $query->where('type', 'event');
    }

    /**
     * Scope a query to only include meetings.
     */
    public function scopeMeetings($query)
    {
        return $query->where('type', 'meeting');
    }

    /**
     * Scope a query to events within a date range.
     */
    public function scopeInDateRange($query, $start, $end)
    {
        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_date', [$start, $end])
              ->orWhereBetween('end_date', [$start, $end])
              ->orWhere(function ($q2) use ($start, $end) {
                  $q2->where('start_date', '<=', $start)
                     ->where('end_date', '>=', $end);
              });
        });
    }

    /**
     * Scope a query to events for a specific area (or general events).
     */
    public function scopeForArea($query, $areaId)
    {
        return $query->where(function ($q) use ($areaId) {
            $q->where('area_id', $areaId)->orWhereNull('area_id');
        });
    }

    /**
     * Scope a query to upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
                     ->orderBy('start_date')
                     ->orderBy('start_time');
    }

    /**
     * Scope a query to events with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // ========================================
    // SPATIE MEDIA LIBRARY
    // ========================================

    /**
     * Register media collections for this model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
             ->useDisk('public')
             ->acceptsMimeTypes([
                 // Images
                 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                 // Documents
                 'application/pdf',
                 'application/msword',
                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                 'application/vnd.ms-excel',
                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                 'application/vnd.ms-powerpoint',
                 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                 // Audio
                 'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4',
                 // Text/Code
                 'text/plain', 'application/json', 'text/xml', 'text/html',
                 'text/css', 'application/javascript',
                 // Compressed
                 'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
             ]);
    }

    /**
     * Register media conversions for this model.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        if ($media && $media->collection_name === 'attachments' && str_starts_with($media->mime_type, 'image/')) {
            // WebP conversion for better compression
            $this->addMediaConversion('webp')
                 ->format('webp')
                 ->quality(85)
                 ->performOnCollections('attachments')
                 ->queued();

            // AVIF conversion for modern browsers
            $this->addMediaConversion('avif')
                 ->format('avif')
                 ->quality(80)
                 ->performOnCollections('attachments')
                 ->queued();

            // Thumbnail for quick preview
            $this->addMediaConversion('thumb')
                 ->width(300)
                 ->height(300)
                 ->format('webp')
                 ->quality(70)
                 ->performOnCollections('attachments')
                 ->nonQueued();
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Determine if this is an event (not a meeting).
     */
    public function isEvent(): bool
    {
        return $this->type === 'event';
    }

    /**
     * Determine if this is a meeting.
     */
    public function isMeeting(): bool
    {
        return $this->type === 'meeting';
    }

    /**
     * Determine if this event/meeting is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Determine if this event/meeting is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Determine if this event/meeting is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Determine if this event/meeting is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Get formatted date range for display.
     */
    public function getFormattedDateRangeAttribute(): string
    {
        if ($this->start_date->isSameDay($this->end_date)) {
            return $this->start_date->format('d/m/Y');
        }

        return $this->start_date->format('d/m/Y') . ' - ' . $this->end_date->format('d/m/Y');
    }

    /**
     * Get formatted time range for display.
     */
    public function getFormattedTimeRangeAttribute(): ?string
    {
        if ($this->is_all_day) {
            return 'Todo el día';
        }

        if ($this->start_time && $this->end_time) {
            return $this->start_time . ' - ' . $this->end_time;
        }

        return $this->start_time;
    }
}
