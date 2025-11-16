<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'status',
        'notes',
        'feedback',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Possible submission statuses.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVISION_REQUESTED = 'revision_requested';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Get all available statuses.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Borrador',
            self::STATUS_SUBMITTED => 'Entregado',
            self::STATUS_REVISION_REQUESTED => 'Revisión solicitada',
            self::STATUS_APPROVED => 'Aprobado',
            self::STATUS_REJECTED => 'Rechazado',
        ];
    }

    /**
     * Get the task this submission belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who submitted this.
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the user who reviewed this.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the files attached to this submission.
     */
    public function files(): HasMany
    {
        return $this->hasMany(TaskSubmissionFile::class);
    }

    /**
     * Check if submission is in draft status.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if submission has been submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * Check if submission needs revision.
     */
    public function needsRevision(): bool
    {
        return $this->status === self::STATUS_REVISION_REQUESTED;
    }

    /**
     * Check if submission is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if submission is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if submission can be edited (draft or revision requested).
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REVISION_REQUESTED]);
    }

    /**
     * Check if submission can be submitted.
     */
    public function canBeSubmitted(): bool
    {
        return $this->canBeEdited() && $this->files()->count() > 0;
    }

    /**
     * Check if submission can be reviewed.
     */
    public function canBeReviewed(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_SUBMITTED => 'blue',
            self::STATUS_REVISION_REQUESTED => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    /**
     * Submit the deliverable.
     */
    public function submit(User $user): void
    {
        $this->update([
            'status' => self::STATUS_SUBMITTED,
            'submitted_by' => $user->id,
            'submitted_at' => now(),
        ]);
    }

    /**
     * Request revision.
     */
    public function requestRevision(User $reviewer, string $feedback): void
    {
        $this->update([
            'status' => self::STATUS_REVISION_REQUESTED,
            'feedback' => $feedback,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Approve the submission.
     */
    public function approve(User $reviewer, ?string $feedback = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'feedback' => $feedback,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Reject the submission.
     */
    public function reject(User $reviewer, string $feedback): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'feedback' => $feedback,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }
}
