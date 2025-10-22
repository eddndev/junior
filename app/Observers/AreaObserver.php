<?php

namespace App\Observers;

use App\Models\Area;
use App\Models\AuditLog;

/**
 * AreaObserver
 *
 * Automatically logs all Area model changes to the audit_logs table.
 * Captures created, updated, and deleted events (areas are deactivated, not deleted).
 *
 * Logged information includes:
 * - Action performed
 * - User who performed the action
 * - Old values (for updates)
 * - New values (for creates and updates)
 * - IP address and user agent
 */
class AreaObserver
{
    /**
     * Handle the Area "created" event.
     *
     * Logs when a new area is created in the system.
     */
    public function created(Area $area): void
    {
        // Get the authenticated user who created this area
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Area::class,
            'auditable_id' => $area->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => [
                'name' => $area->name,
                'slug' => $area->slug,
                'description' => $area->description,
                'is_active' => $area->is_active,
                'is_system' => $area->is_system,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Area "updated" event.
     *
     * Logs when an area is updated, capturing both old and new values.
     */
    public function updated(Area $area): void
    {
        // Get the authenticated user who updated this area
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Get only the changed attributes
        $changes = $area->getChanges();

        // Don't log if nothing changed
        if (empty($changes)) {
            return;
        }

        // Remove timestamp fields to keep logs clean
        unset($changes['updated_at']);

        // Get the original (old) values before the update
        $oldValues = $area->getOriginal();

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
            'auditable_type' => Area::class,
            'auditable_id' => $area->id,
            'action' => 'updated',
            'old_values' => $oldValuesForLog,
            'new_values' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Area "deleted" event.
     *
     * Note: Areas are typically deactivated (is_active = false) rather than deleted.
     * This event would only fire for actual database deletions.
     */
    public function deleted(Area $area): void
    {
        // Get the authenticated user who deleted this area
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Area::class,
            'auditable_id' => $area->id,
            'action' => 'deleted',
            'old_values' => [
                'name' => $area->name,
                'slug' => $area->slug,
                'is_active' => $area->is_active,
                'is_system' => $area->is_system,
            ],
            'new_values' => [
                'deleted' => true,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Area "restored" event.
     */
    public function restored(Area $area): void
    {
        // Get the authenticated user who restored this area
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Area::class,
            'auditable_id' => $area->id,
            'action' => 'restored',
            'old_values' => [
                'deleted_at' => $area->getOriginal('deleted_at'),
            ],
            'new_values' => [
                'name' => $area->name,
                'is_active' => $area->is_active,
                'deleted_at' => null,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Area "force deleted" event.
     *
     * This is a critical action that should rarely be performed.
     */
    public function forceDeleted(Area $area): void
    {
        // Get the authenticated user who force deleted this area
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Area::class,
            'auditable_id' => $area->id,
            'action' => 'force_deleted',
            'old_values' => [
                'name' => $area->name,
                'slug' => $area->slug,
                'is_system' => $area->is_system,
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
