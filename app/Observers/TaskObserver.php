<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\AuditLog;

/**
 * TaskObserver
 *
 * Automatically logs all Task model changes to the audit_logs table.
 * Captures created, updated, deleted, restored, and force deleted events.
 *
 * Logged information includes:
 * - Action performed
 * - User who performed the action
 * - Old values (for updates and deletes)
 * - New values (for creates and updates)
 * - IP address and user agent
 */
class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * Logs when a new task is created in the system.
     */
    public function created(Task $task): void
    {
        // Get the authenticated user who created this task
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Task::class,
            'auditable_id' => $task->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => json_encode([
                'title' => $task->title,
                'description' => $task->description,
                'area_id' => $task->area_id,
                'parent_task_id' => $task->parent_task_id,
                'priority' => $task->priority,
                'status' => $task->status,
                'due_date' => $task->due_date?->format('Y-m-d'),
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Task "updated" event.
     *
     * Logs when a task is updated, capturing both old and new values.
     * Special attention to status changes and completion events.
     */
    public function updated(Task $task): void
    {
        // Get the authenticated user who updated this task
        $performedBy = auth()->id();

        // Don't log if no authenticated user (e.g., seeder, console)
        if (!$performedBy) {
            return;
        }

        // Get the original (old) values before the update
        $oldValues = $task->getOriginal();

        // Get the new (current) values after the update
        $newValues = $task->getAttributes();

        // Get only the changed attributes
        $changes = $task->getChanges();

        // Don't log if nothing changed (shouldn't happen, but safety check)
        if (empty($changes)) {
            return;
        }

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

        // Determine the action type based on what changed
        $action = 'updated';

        // Special case: Task completed
        if (isset($changes['status']) && $changes['status'] === 'completed') {
            $action = 'completed';
        }

        // Special case: Task status changed (but not to completed)
        if (isset($changes['status']) && $changes['status'] !== 'completed') {
            $action = 'status_changed';
        }

        // Special case: Task reassigned (this would be handled by assignments, but log it here too)
        // Note: Assignment changes are handled separately via TaskAssignment model

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Task::class,
            'auditable_id' => $task->id,
            'action' => $action,
            'old_values' => json_encode($oldValuesForLog),
            'new_values' => json_encode($changes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Task "deleted" event.
     *
     * Logs when a task is soft deleted.
     */
    public function deleted(Task $task): void
    {
        // Get the authenticated user who deleted this task
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Task::class,
            'auditable_id' => $task->id,
            'action' => 'deleted',
            'old_values' => json_encode([
                'title' => $task->title,
                'area_id' => $task->area_id,
                'status' => $task->status,
                'priority' => $task->priority,
                'deleted_at' => null,
            ]),
            'new_values' => json_encode([
                'deleted_at' => $task->deleted_at,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Task "restored" event.
     *
     * Logs when a soft deleted task is restored.
     */
    public function restored(Task $task): void
    {
        // Get the authenticated user who restored this task
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Task::class,
            'auditable_id' => $task->id,
            'action' => 'restored',
            'old_values' => json_encode([
                'deleted_at' => $task->getOriginal('deleted_at'),
            ]),
            'new_values' => json_encode([
                'title' => $task->title,
                'area_id' => $task->area_id,
                'status' => $task->status,
                'deleted_at' => null,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the Task "force deleted" event.
     *
     * Logs when a task is permanently deleted from the database.
     * This is a critical action that should rarely be performed.
     */
    public function forceDeleted(Task $task): void
    {
        // Get the authenticated user who force deleted this task
        $performedBy = auth()->id();

        // Don't log if no authenticated user
        if (!$performedBy) {
            return;
        }

        // Create audit log entry
        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => Task::class,
            'auditable_id' => $task->id,
            'action' => 'force_deleted',
            'old_values' => json_encode([
                'title' => $task->title,
                'description' => $task->description,
                'area_id' => $task->area_id,
                'status' => $task->status,
                'priority' => $task->priority,
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
