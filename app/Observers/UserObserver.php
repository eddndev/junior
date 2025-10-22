<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AuditLog;

/**
 * UserObserver
 *
 * Automatically logs all User model changes to the audit_logs table.
 * Captures created, updated, deleted, restored, and force deleted events.
 *
 * Logged information includes:
 * - Action performed
 * - User who performed the action
 * - Old values (for updates and deletes)
 * - New values (for creates and updates)
 * - IP address and user agent
 */
class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * Logs when a new user is created in the system.
     */
    public function created(User $user): void
    {
        // Get the authenticated user who created this user
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * Logs when a user is updated, capturing both old and new values.
     */
    public function updated(User $user): void
    {
        // Get the authenticated user who updated this user
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Get the original (old) values before the update
        $oldValues = $user->getOriginal();

        // Get the new (current) values after the update
        $newValues = $user->getAttributes();

        // Get only the changed attributes
        $changes = $user->getChanges();

        // Don't log if nothing changed (shouldn't happen, but safety check)
        if (empty($changes)) {
            return;
        }

        // Remove sensitive fields from logging
        unset($changes['password']);
        unset($oldValues['password']);
        unset($newValues['password']);
        unset($changes['remember_token']);
        unset($oldValues['remember_token']);
        unset($newValues['remember_token']);

        // Remove timestamp fields to keep logs clean
        unset($changes['updated_at']);
        unset($oldValues['updated_at']);
        unset($newValues['updated_at']);

        // Build old values object with only changed fields
        $oldValuesForLog = [];
        foreach (array_keys($changes) as $key) {
            if (isset($oldValues[$key])) {
                $oldValuesForLog[$key] = $oldValues[$key];
            }
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'updated',
            'old_values' => json_encode($oldValuesForLog),
            'new_values' => json_encode($changes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * Logs when a user is soft deleted (deactivated).
     */
    public function deleted(User $user): void
    {
        // Get the authenticated user who deleted this user
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'deleted',
            'old_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'deleted_at' => null,
            ]),
            'new_values' => json_encode([
                'deleted_at' => $user->deleted_at,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the User "restored" event.
     *
     * Logs when a soft deleted user is restored (reactivated).
     */
    public function restored(User $user): void
    {
        // Get the authenticated user who restored this user
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'restored',
            'old_values' => json_encode([
                'deleted_at' => $user->getOriginal('deleted_at'),
            ]),
            'new_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'deleted_at' => null,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * Logs when a user is permanently deleted from the database.
     * This is a critical action that should rarely be performed.
     */
    public function forceDeleted(User $user): void
    {
        // Get the authenticated user who force deleted this user
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'force_deleted',
            'old_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
            ]),
            'new_values' => json_encode([
                'permanently_deleted' => true,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
