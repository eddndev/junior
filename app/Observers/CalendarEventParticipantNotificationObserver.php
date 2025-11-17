<?php

namespace App\Observers;

use App\Models\CalendarEventParticipant;
use App\Notifications\Calendar\EventInvitationNotification;

/**
 * CalendarEventParticipantNotificationObserver
 *
 * Observes CalendarEventParticipant model changes and triggers notifications.
 */
class CalendarEventParticipantNotificationObserver
{
    /**
     * Handle the CalendarEventParticipant "created" event.
     *
     * Sends invitation notification to the participant.
     */
    public function created(CalendarEventParticipant $participant): void
    {
        $user = $participant->user;
        $event = $participant->event;

        if (!$user || !$event) {
            return;
        }

        // Don't notify if the user added themselves (e.g., event creator)
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id === $user->id && $event->created_by === $user->id) {
            return;
        }

        // Send the event invitation notification
        $user->notify(new EventInvitationNotification(
            $event,
            $participant->is_required
        ));
    }
}
