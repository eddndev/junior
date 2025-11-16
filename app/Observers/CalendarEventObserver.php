<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\CalendarEvent;

/**
 * CalendarEventObserver
 *
 * Automatically logs all CalendarEvent model changes to the audit_logs table.
 * Captures created, updated, deleted, restored, and force deleted events.
 *
 * Logged information includes:
 * - Action performed
 * - User who performed the action
 * - Old values (for updates and deletes)
 * - New values (for creates and updates)
 * - IP address and user agent
 */
class CalendarEventObserver
{
    /**
     * Handle the CalendarEvent "created" event.
     *
     * Logs when a new calendar event/meeting is created.
     */
    public function created(CalendarEvent $calendarEvent): void
    {
        $performedBy = auth()->id();

        if (!$performedBy) {
            return;
        }

        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => CalendarEvent::class,
            'auditable_id' => $calendarEvent->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => json_encode([
                'title' => $calendarEvent->title,
                'type' => $calendarEvent->type,
                'start_date' => $calendarEvent->start_date?->format('Y-m-d'),
                'end_date' => $calendarEvent->end_date?->format('Y-m-d'),
                'start_time' => $calendarEvent->start_time,
                'end_time' => $calendarEvent->end_time,
                'is_all_day' => $calendarEvent->is_all_day,
                'location' => $calendarEvent->location,
                'area_id' => $calendarEvent->area_id,
                'status' => $calendarEvent->status,
                'is_public' => $calendarEvent->is_public,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the CalendarEvent "updated" event.
     *
     * Logs when a calendar event/meeting is updated.
     */
    public function updated(CalendarEvent $calendarEvent): void
    {
        $performedBy = auth()->id();

        if (!$performedBy) {
            return;
        }

        $oldValues = $calendarEvent->getOriginal();
        $changes = $calendarEvent->getChanges();

        if (empty($changes)) {
            return;
        }

        // Remove timestamp fields
        unset($changes['updated_at']);
        unset($oldValues['updated_at']);

        // Build old values with only changed fields
        $oldValuesForLog = [];
        foreach (array_keys($changes) as $key) {
            if (isset($oldValues[$key])) {
                $oldValuesForLog[$key] = $oldValues[$key];
            }
        }

        // Determine the action type based on what changed
        $action = 'updated';

        // Special case: Status changed to completed
        if (isset($changes['status']) && $changes['status'] === 'completed') {
            $action = 'completed';
        }

        // Special case: Status changed to cancelled
        if (isset($changes['status']) && $changes['status'] === 'cancelled') {
            $action = 'cancelled';
        }

        // Special case: Status changed to in_progress
        if (isset($changes['status']) && $changes['status'] === 'in_progress') {
            $action = 'started';
        }

        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => CalendarEvent::class,
            'auditable_id' => $calendarEvent->id,
            'action' => $action,
            'old_values' => json_encode($oldValuesForLog),
            'new_values' => json_encode($changes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the CalendarEvent "deleted" event.
     *
     * Logs when a calendar event/meeting is soft deleted.
     */
    public function deleted(CalendarEvent $calendarEvent): void
    {
        $performedBy = auth()->id();

        if (!$performedBy) {
            return;
        }

        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => CalendarEvent::class,
            'auditable_id' => $calendarEvent->id,
            'action' => 'deleted',
            'old_values' => json_encode([
                'title' => $calendarEvent->title,
                'type' => $calendarEvent->type,
                'start_date' => $calendarEvent->start_date?->format('Y-m-d'),
                'area_id' => $calendarEvent->area_id,
                'status' => $calendarEvent->status,
                'deleted_at' => null,
            ]),
            'new_values' => json_encode([
                'deleted_at' => $calendarEvent->deleted_at,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the CalendarEvent "restored" event.
     *
     * Logs when a soft deleted calendar event/meeting is restored.
     */
    public function restored(CalendarEvent $calendarEvent): void
    {
        $performedBy = auth()->id();

        if (!$performedBy) {
            return;
        }

        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => CalendarEvent::class,
            'auditable_id' => $calendarEvent->id,
            'action' => 'restored',
            'old_values' => json_encode([
                'deleted_at' => $calendarEvent->getOriginal('deleted_at'),
            ]),
            'new_values' => json_encode([
                'title' => $calendarEvent->title,
                'type' => $calendarEvent->type,
                'area_id' => $calendarEvent->area_id,
                'status' => $calendarEvent->status,
                'deleted_at' => null,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the CalendarEvent "force deleted" event.
     *
     * Logs when a calendar event/meeting is permanently deleted.
     */
    public function forceDeleted(CalendarEvent $calendarEvent): void
    {
        $performedBy = auth()->id();

        if (!$performedBy) {
            return;
        }

        AuditLog::create([
            'user_id' => $performedBy,
            'auditable_type' => CalendarEvent::class,
            'auditable_id' => $calendarEvent->id,
            'action' => 'force_deleted',
            'old_values' => json_encode([
                'title' => $calendarEvent->title,
                'type' => $calendarEvent->type,
                'description' => $calendarEvent->description,
                'start_date' => $calendarEvent->start_date?->format('Y-m-d'),
                'area_id' => $calendarEvent->area_id,
                'status' => $calendarEvent->status,
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
