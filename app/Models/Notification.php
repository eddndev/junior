<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'area_id',
        'title',
        'message',
        'type',
        'notification_type',
        'notifiable_type',
        'notifiable_id',
        'action_url',
        'action_text',
        'data',
        'icon',
        'icon_color',
        'priority',
        'group',
        'is_read',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'data' => 'array',
        ];
    }

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the area for area-wide notifications.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include notifications of a specific type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the notifiable model (Task, CalendarEvent, etc.).
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include notifications for a specific notification type.
     */
    public function scopeForType($query, string $notificationType)
    {
        return $query->where('notification_type', $notificationType);
    }

    /**
     * Scope a query to only include notifications of a specific priority.
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include high priority notifications.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Scope a query to only include notifications in a specific group.
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get the icon class for display.
     */
    public function getIconClass(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        // Default icons based on type
        return match ($this->type) {
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            default => 'information-circle',
        };
    }

    /**
     * Get the icon color class for display.
     */
    public function getIconColorClass(): string
    {
        if ($this->icon_color) {
            return $this->icon_color;
        }

        // Default colors based on type
        return match ($this->type) {
            'success' => 'text-green-500',
            'warning' => 'text-yellow-500',
            'error' => 'text-red-500',
            default => 'text-blue-500',
        };
    }

    /**
     * Get the priority badge class for display.
     */
    public function getPriorityBadgeClass(): string
    {
        return match ($this->priority) {
            'high' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        };
    }
}

