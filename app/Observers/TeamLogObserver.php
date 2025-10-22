<?php

namespace App\Observers;

use App\Models\TeamLog;
use App\Models\AuditLog;

/**
 * TeamLogObserver
 *
 * Automatically logs all TeamLog model changes to the audit_logs table.
 * Captures created, updated, deleted, and restored events.
 *
 * Logged information includes:
 * - Action performed
 * - User who performed the action
 * - Old values (for updates and deletes)
 * - New values (for creates and updates)
 * - IP address and user agent
 */
class TeamLogObserver
{
    /**
     * Handle the TeamLog "created" event.
     *
     * Logs when a new team log entry is created.
     */
    public function created(TeamLog $teamLog): void
    {
        // Get the authenticated user who created this team log
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => TeamLog::class,
            'auditable_id' => $teamLog->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => [
                'title' => $teamLog->title,
                'content' => $teamLog->content,
                'type' => $teamLog->type,
                'area_id' => $teamLog->area_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the TeamLog "updated" event.
     *
     * Logs when a team log entry is updated, capturing both old and new values.
     */
    public function updated(TeamLog $teamLog): void
    {
        // Get the authenticated user who updated this team log
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Get the changed attributes
        $changes = $teamLog->getChanges();

        // Don't log if nothing changed (shouldn't happen, but safety check)
        if (empty($changes)) {
            return;
        }

        // Remove timestamp fields to keep logs clean
        unset($changes['updated_at']);

        // Get the original (old) values before the update
        $oldValues = $teamLog->getOriginal();

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
            'auditable_type' => TeamLog::class,
            'auditable_id' => $teamLog->id,
            'action' => 'updated',
            'old_values' => $oldValuesForLog,
            'new_values' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the TeamLog "deleted" event.
     *
     * Logs when a team log entry is soft deleted.
     */
    public function deleted(TeamLog $teamLog): void
    {
        // Get the authenticated user who deleted this team log
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => TeamLog::class,
            'auditable_id' => $teamLog->id,
            'action' => 'deleted',
            'old_values' => [
                'title' => $teamLog->title,
                'content' => $teamLog->content,
                'type' => $teamLog->type,
                'area_id' => $teamLog->area_id,
                'deleted_at' => null,
            ],
            'new_values' => [
                'deleted_at' => $teamLog->deleted_at,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the TeamLog "restored" event.
     *
     * Logs when a soft deleted team log entry is restored.
     */
    public function restored(TeamLog $teamLog): void
    {
        // Get the authenticated user who restored this team log
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => TeamLog::class,
            'auditable_id' => $teamLog->id,
            'action' => 'restored',
            'old_values' => [
                'deleted_at' => $teamLog->getOriginal('deleted_at'),
            ],
            'new_values' => [
                'title' => $teamLog->title,
                'content' => $teamLog->content,
                'type' => $teamLog->type,
                'area_id' => $teamLog->area_id,
                'deleted_at' => null,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the TeamLog "force deleted" event.
     *
     * Logs when a team log entry is permanently deleted from the database.
     */
    public function forceDeleted(TeamLog $teamLog): void
    {
        // Get the authenticated user who force deleted this team log
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => TeamLog::class,
            'auditable_id' => $teamLog->id,
            'action' => 'force_deleted',
            'old_values' => [
                'title' => $teamLog->title,
                'content' => $teamLog->content,
                'type' => $teamLog->type,
                'area_id' => $teamLog->area_id,
            ],
            'new_values' => [
                'permanently_deleted' => true,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
