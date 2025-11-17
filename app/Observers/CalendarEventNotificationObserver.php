<?php

namespace App\Observers;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Notifications\Calendar\EventCancelledNotification;
use App\Notifications\Calendar\EventRescheduledNotification;
use App\Notifications\Calendar\EventUpdatedNotification;

/**
 * CalendarEventNotificationObserver
 *
 * Observes CalendarEvent model changes and triggers notifications.
 * Separate from CalendarEventObserver which handles audit logging.
 */
class CalendarEventNotificationObserver
{
    /**
     * Handle the CalendarEvent "updated" event.
     *
     * Sends appropriate notifications based on what changed.
     */
    public function updated(CalendarEvent $event): void
    {
        $changedBy = auth()->user();
        $participants = $event->users;

        // Check if event was cancelled
        if ($event->wasChanged('status') && $event->status === 'cancelled') {
            $this->notifyEventCancelled($event, $participants, $changedBy);
            return;
        }

        // Check if event was rescheduled (date/time changed)
        $scheduleFields = ['start_date', 'end_date', 'start_time', 'end_time'];
        $scheduleChanged = false;
        $oldSchedule = [];

        foreach ($scheduleFields as $field) {
            if ($event->wasChanged($field)) {
                $scheduleChanged = true;
                $oldSchedule[$field] = $event->getOriginal($field);
            }
        }

        if ($scheduleChanged) {
            $this->notifyEventRescheduled($event, $oldSchedule, $participants, $changedBy);
            return;
        }

        // Check for other changes
        $otherFields = ['title', 'description', 'location', 'virtual_link', 'is_all_day'];
        $changedFields = [];

        foreach ($otherFields as $field) {
            if ($event->wasChanged($field)) {
                $changedFields[$field] = [
                    'old' => $event->getOriginal($field),
                    'new' => $event->$field,
                ];
            }
        }

        if (!empty($changedFields)) {
            $this->notifyEventUpdated($event, $changedFields, $participants, $changedBy);
        }
    }

    /**
     * Notify all participants that the event was cancelled.
     */
    protected function notifyEventCancelled(
        CalendarEvent $event,
        $participants,
        ?User $cancelledBy
    ): void {
        // Get cancellation reason from metadata if available
        $reason = $event->metadata['cancellation_reason'] ?? null;

        foreach ($participants as $user) {
            // Don't notify the user who cancelled it
            if ($cancelledBy && $user->id === $cancelledBy->id) {
                continue;
            }

            $user->notify(new EventCancelledNotification($event, $reason, $cancelledBy));
        }
    }

    /**
     * Notify all participants that the event was rescheduled.
     */
    protected function notifyEventRescheduled(
        CalendarEvent $event,
        array $oldSchedule,
        $participants,
        ?User $rescheduledBy
    ): void {
        foreach ($participants as $user) {
            // Don't notify the user who rescheduled it
            if ($rescheduledBy && $user->id === $rescheduledBy->id) {
                continue;
            }

            $user->notify(new EventRescheduledNotification($event, $oldSchedule, $rescheduledBy));
        }
    }

    /**
     * Notify all participants that the event details were updated.
     */
    protected function notifyEventUpdated(
        CalendarEvent $event,
        array $changedFields,
        $participants,
        ?User $changedBy
    ): void {
        foreach ($participants as $user) {
            // Don't notify the user who made the changes
            if ($changedBy && $user->id === $changedBy->id) {
                continue;
            }

            $user->notify(new EventUpdatedNotification($event, $changedFields, $changedBy));
        }
    }
}
