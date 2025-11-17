<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notification_type',
        'database_enabled',
        'email_enabled',
        'push_enabled',
        'settings',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'database_enabled' => 'boolean',
            'email_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'settings' => 'array',
        ];
    }

    /**
     * Get the user that owns the preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include preferences for a specific notification type.
     */
    public function scopeForType($query, string $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Scope a query to only include preferences where database is enabled.
     */
    public function scopeDatabaseEnabled($query)
    {
        return $query->where('database_enabled', true);
    }

    /**
     * Scope a query to only include preferences where email is enabled.
     */
    public function scopeEmailEnabled($query)
    {
        return $query->where('email_enabled', true);
    }

    /**
     * Scope a query to only include preferences where push is enabled.
     */
    public function scopePushEnabled($query)
    {
        return $query->where('push_enabled', true);
    }
}
