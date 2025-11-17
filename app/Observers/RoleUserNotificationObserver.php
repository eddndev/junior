<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\Users\RoleAssignedNotification;
use App\Notifications\Users\RoleRemovedNotification;
use Illuminate\Support\Facades\DB;

/**
 * RoleUserNotificationObserver
 *
 * Observes role_user pivot table changes and triggers notifications.
 *
 * Note: This needs to be registered differently since it's observing a pivot table.
 * Use model events on User/Role models or listen to sync events.
 */
class RoleUserNotificationObserver
{
    /**
     * Handle role attached event.
     *
     * @param User $user The user being assigned the role
     * @param Role $role The role being assigned
     * @param int|null $areaId The area context (if any)
     */
    public static function roleAttached(User $user, Role $role, ?int $areaId = null): void
    {
        $assignedBy = auth()->user();

        // Don't notify if user assigned themselves
        if ($assignedBy && $assignedBy->id === $user->id) {
            return;
        }

        $user->notify(new RoleAssignedNotification($role, $assignedBy, $areaId));
    }

    /**
     * Handle role detached event.
     *
     * @param User $user The user from whom the role is being removed
     * @param Role $role The role being removed
     * @param int|null $areaId The area context (if any)
     */
    public static function roleDetached(User $user, Role $role, ?int $areaId = null): void
    {
        $removedBy = auth()->user();

        // Don't notify if user removed their own role
        if ($removedBy && $removedBy->id === $user->id) {
            return;
        }

        $user->notify(new RoleRemovedNotification($role, $removedBy, $areaId));
    }
}
