<?php

namespace App\Observers;

use App\Models\TaskSubmission;
use App\Models\User;
use App\Notifications\Submissions\RevisionRequestedNotification;
use App\Notifications\Submissions\SubmissionApprovedNotification;
use App\Notifications\Submissions\SubmissionCreatedNotification;
use App\Notifications\Submissions\SubmissionRejectedNotification;

/**
 * TaskSubmissionNotificationObserver
 *
 * Observes TaskSubmission model changes and triggers appropriate notifications.
 */
class TaskSubmissionNotificationObserver
{
    /**
     * Handle the TaskSubmission "created" event.
     *
     * Notifies task creator and reviewers when a new submission is created.
     */
    public function created(TaskSubmission $submission): void
    {
        // Only notify if submission is actually submitted (not draft)
        if ($submission->status !== TaskSubmission::STATUS_SUBMITTED) {
            return;
        }

        $this->notifySubmissionCreated($submission);
    }

    /**
     * Handle the TaskSubmission "updated" event.
     *
     * Handles status transitions and sends appropriate notifications.
     */
    public function updated(TaskSubmission $submission): void
    {
        // Check if status changed
        if (!$submission->wasChanged('status')) {
            return;
        }

        $oldStatus = $submission->getOriginal('status');
        $newStatus = $submission->status;

        // Handle submission when changed from draft to submitted
        if ($oldStatus === TaskSubmission::STATUS_DRAFT && $newStatus === TaskSubmission::STATUS_SUBMITTED) {
            $this->notifySubmissionCreated($submission);
            return;
        }

        // Handle approval
        if ($newStatus === TaskSubmission::STATUS_APPROVED) {
            $this->notifySubmissionApproved($submission);
            return;
        }

        // Handle rejection
        if ($newStatus === TaskSubmission::STATUS_REJECTED) {
            $this->notifySubmissionRejected($submission);
            return;
        }

        // Handle revision requested
        if ($newStatus === TaskSubmission::STATUS_REVISION_REQUESTED) {
            $this->notifyRevisionRequested($submission);
            return;
        }
    }

    /**
     * Notify relevant users when a submission is created.
     */
    protected function notifySubmissionCreated(TaskSubmission $submission): void
    {
        $task = $submission->task;
        if (!$task) {
            return;
        }

        $notifiedIds = [];

        // Notify task creator
        if ($task->created_by) {
            $creator = User::find($task->created_by);
            if ($creator && $creator->id !== $submission->submitted_by) {
                $creator->notify(new SubmissionCreatedNotification($submission));
                $notifiedIds[] = $creator->id;
            }
        }

        // Notify area directors (potential reviewers)
        if ($task->area_id) {
            $areaDirectors = User::whereHas('roles', function ($query) use ($task) {
                $query->where('slug', 'director-area')
                      ->where('role_user.area_id', $task->area_id);
            })->get();

            foreach ($areaDirectors as $director) {
                if (!in_array($director->id, $notifiedIds) && $director->id !== $submission->submitted_by) {
                    $director->notify(new SubmissionCreatedNotification($submission));
                    $notifiedIds[] = $director->id;
                }
            }
        }
    }

    /**
     * Notify submitter when their submission is approved.
     */
    protected function notifySubmissionApproved(TaskSubmission $submission): void
    {
        $submitter = $submission->submitter;

        if ($submitter) {
            $submitter->notify(new SubmissionApprovedNotification($submission));
        }
    }

    /**
     * Notify submitter when their submission is rejected.
     */
    protected function notifySubmissionRejected(TaskSubmission $submission): void
    {
        $submitter = $submission->submitter;

        if ($submitter) {
            $submitter->notify(new SubmissionRejectedNotification($submission));
        }
    }

    /**
     * Notify submitter when revision is requested.
     */
    protected function notifyRevisionRequested(TaskSubmission $submission): void
    {
        $submitter = $submission->submitter;

        if ($submitter) {
            $submitter->notify(new RevisionRequestedNotification($submission));
        }
    }
}
