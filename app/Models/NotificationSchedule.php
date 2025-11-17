<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'schedulable_type',
        'schedulable_id',
        'notification_type',
        'scheduled_at',
        'sent_at',
        'is_cancelled',
        'data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
            'is_cancelled' => 'boolean',
            'data' => 'array',
        ];
    }

    /**
     * Get the user that owns the schedule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent schedulable model (Task, CalendarEvent, etc.).
     */
    public function schedulable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include pending schedules.
     */
    public function scopePending($query)
    {
        return $query->whereNull('sent_at')
            ->where('is_cancelled', false)
            ->where('scheduled_at', '<=', now());
    }

    /**
     * Scope a query to only include upcoming schedules.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereNull('sent_at')
            ->where('is_cancelled', false)
            ->where('scheduled_at', '>', now());
    }

    /**
     * Scope a query to only include cancelled schedules.
     */
    public function scopeCancelled($query)
    {
        return $query->where('is_cancelled', true);
    }

    /**
     * Scope a query to only include sent schedules.
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    /**
     * Mark the schedule as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'sent_at' => now(),
        ]);
    }

    /**
     * Cancel the scheduled notification.
     */
    public function cancel(): void
    {
        $this->update([
            'is_cancelled' => true,
        ]);
    }
}
