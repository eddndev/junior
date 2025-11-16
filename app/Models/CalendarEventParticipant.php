<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEventParticipant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'calendar_event_id',
        'user_id',
        'is_required',
        'attendance_status',
        'actual_attendance',
        'arrival_time',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the calendar event this participant belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class, 'calendar_event_id');
    }

    /**
     * Get the user who is participating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Check if the participant has confirmed attendance.
     */
    public function isConfirmed(): bool
    {
        return $this->attendance_status === 'confirmed';
    }

    /**
     * Check if the participant has declined the invitation.
     */
    public function isDeclined(): bool
    {
        return $this->attendance_status === 'declined';
    }

    /**
     * Check if the participant attendance is pending.
     */
    public function isPending(): bool
    {
        return $this->attendance_status === 'pending';
    }

    /**
     * Check if the participant attendance is tentative.
     */
    public function isTentative(): bool
    {
        return $this->attendance_status === 'tentative';
    }

    /**
     * Check if the participant was actually present.
     */
    public function wasPresent(): bool
    {
        return $this->actual_attendance === 'present';
    }

    /**
     * Check if the participant was absent.
     */
    public function wasAbsent(): bool
    {
        return $this->actual_attendance === 'absent';
    }

    /**
     * Check if the participant was late.
     */
    public function wasLate(): bool
    {
        return $this->actual_attendance === 'late';
    }

    /**
     * Check if the participant had an excused absence.
     */
    public function wasExcused(): bool
    {
        return $this->actual_attendance === 'excused';
    }

    /**
     * Get the attendance status label for display.
     */
    public function getAttendanceStatusLabelAttribute(): string
    {
        return match ($this->attendance_status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'declined' => 'Rechazado',
            'tentative' => 'Tentativo',
            default => 'Desconocido',
        };
    }

    /**
     * Get the actual attendance label for display.
     */
    public function getActualAttendanceLabelAttribute(): ?string
    {
        if (!$this->actual_attendance) {
            return null;
        }

        return match ($this->actual_attendance) {
            'present' => 'Presente',
            'absent' => 'Ausente',
            'late' => 'Tardanza',
            'excused' => 'Justificado',
            default => 'Desconocido',
        };
    }
}
