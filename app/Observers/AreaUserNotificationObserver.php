<?php

namespace App\Observers;

use App\Models\Area;
use App\Models\User;
use App\Notifications\Users\AreaAssignedNotification;
use App\Notifications\Users\AreaRemovedNotification;

/**
 * AreaUserNotificationObserver
 *
 * Observes area_user pivot table changes and triggers notifications.
 *
 * Note: This needs to be registered differently since it's observing a pivot table.
 * Use model events on User/Area models or listen to sync events.
 */
class AreaUserNotificationObserver
{
    /**
     * Handle area attached event.
     *
     * @param User $user The user being assigned to the area
     * @param Area $area The area being assigned
     */
    public static function areaAttached(User $user, Area $area): void
    {
        $assignedBy = auth()->user();

        // Don't notify if user assigned themselves
        if ($assignedBy && $assignedBy->id === $user->id) {
            return;
        }

        $user->notify(new AreaAssignedNotification($area, $assignedBy));
    }

    /**
     * Handle area detached event.
     *
     * @param User $user The user from whom the area is being removed
     * @param Area $area The area being removed
     */
    public static function areaDetached(User $user, Area $area): void
    {
        $removedBy = auth()->user();

        // Don't notify if user removed themselves
        if ($removedBy && $removedBy->id === $user->id) {
            return;
        }

        $user->notify(new AreaRemovedNotification($area, $removedBy));
    }
}
