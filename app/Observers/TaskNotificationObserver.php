<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\Tasks\TaskCompletedNotification;
use App\Notifications\Tasks\TaskPriorityChangedNotification;
use App\Notifications\Tasks\TaskStatusChangedNotification;

/**
 * TaskNotificationObserver
 *
 * Observes Task model changes and triggers appropriate notifications.
 * This is separate from TaskObserver which handles audit logging.
 */
class TaskNotificationObserver
{
    /**
     * Handle the Task "updated" event.
     *
     * Sends notifications when status or priority changes.
     */
    public function updated(Task $task): void
    {
        $changedBy = auth()->user();

        // Check if status changed
        if ($task->wasChanged('status')) {
            $oldStatus = $task->getOriginal('status');
            $newStatus = $task->status;

            // Send status changed notification to all assigned users
            $assignedUsers = $task->assignedUsers;

            foreach ($assignedUsers as $user) {
                // Don't notify the user who made the change
                if ($changedBy && $user->id === $changedBy->id) {
                    continue;
                }

                $user->notify(new TaskStatusChangedNotification(
                    $task,
                    $oldStatus,
                    $newStatus,
                    $changedBy
                ));
            }

            // If task is completed, notify creator and area director
            if ($newStatus === 'completed') {
                $this->notifyTaskCompleted($task, $changedBy);
            }
        }

        // Check if priority changed
        if ($task->wasChanged('priority')) {
            $oldPriority = $task->getOriginal('priority');
            $newPriority = $task->priority;

            // Send priority changed notification to all assigned users
            $assignedUsers = $task->assignedUsers;

            foreach ($assignedUsers as $user) {
                // Don't notify the user who made the change
                if ($changedBy && $user->id === $changedBy->id) {
                    continue;
                }

                $user->notify(new TaskPriorityChangedNotification(
                    $task,
                    $oldPriority,
                    $newPriority,
                    $changedBy
                ));
            }
        }
    }

    /**
     * Notify relevant users when a task is completed.
     */
    protected function notifyTaskCompleted(Task $task, ?User $completedBy): void
    {
        $notifiedIds = [];

        // Notify task creator (if exists and not the one who completed it)
        if ($task->created_by && (!$completedBy || $task->created_by !== $completedBy->id)) {
            $creator = User::find($task->created_by);
            if ($creator) {
                $creator->notify(new TaskCompletedNotification($task, $completedBy));
                $notifiedIds[] = $creator->id;
            }
        }

        // Notify area director (if task has area)
        if ($task->area_id) {
            $areaDirectors = User::whereHas('roles', function ($query) use ($task) {
                $query->where('slug', 'director-area')
                      ->where('role_user.area_id', $task->area_id);
            })->get();

            foreach ($areaDirectors as $director) {
                if (!in_array($director->id, $notifiedIds) && (!$completedBy || $director->id !== $completedBy->id)) {
                    $director->notify(new TaskCompletedNotification($task, $completedBy));
                    $notifiedIds[] = $director->id;
                }
            }
        }
    }
}
