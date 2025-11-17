<?php

namespace App\Observers;

use App\Models\TaskAssignment;
use App\Notifications\Tasks\TaskAssignedNotification;

/**
 * TaskAssignmentNotificationObserver
 *
 * Observes TaskAssignment model changes and triggers notifications.
 */
class TaskAssignmentNotificationObserver
{
    /**
     * Handle the TaskAssignment "created" event.
     *
     * Sends notification to the assigned user.
     */
    public function created(TaskAssignment $assignment): void
    {
        // Only process task assignments (not campaign task assignments)
        if ($assignment->assignable_type !== 'App\\Models\\Task') {
            return;
        }

        $user = $assignment->user;
        $task = $assignment->assignable;

        if (!$user || !$task) {
            return;
        }

        // Don't notify if the user assigned themselves
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id === $user->id) {
            return;
        }

        // Send the task assigned notification
        $user->notify(new TaskAssignedNotification($task));
    }

    /**
     * Handle the TaskAssignment "deleted" event.
     *
     * Could send notification that user was unassigned, if needed.
     */
    public function deleted(TaskAssignment $assignment): void
    {
        // Optional: Send notification about unassignment
        // Currently not implemented as per requirements
    }
}
