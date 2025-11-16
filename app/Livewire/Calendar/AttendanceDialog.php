<?php

namespace App\Livewire\Calendar;

use App\Models\CalendarEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AttendanceDialog extends Component
{
    use AuthorizesRequests;

    public ?int $eventId = null;
    public ?CalendarEvent $event = null;

    // Attendance data: [participant_id => ['status' => ..., 'arrival_time' => ..., 'notes' => ...]]
    public array $attendanceData = [];

    // Available attendance status options
    public array $attendanceOptions = [
        'present' => 'Presente',
        'absent' => 'Ausente',
        'late' => 'Tardanza',
        'excused' => 'Justificado',
    ];

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadEventForAttendance' => 'load',
    ];

    /**
     * Load event data for attendance recording
     *
     * @param int $eventId
     * @return void
     */
    public function load(int $eventId): void
    {
        $this->eventId = $eventId;

        try {
            $this->event = CalendarEvent::with([
                'participants.user',
            ])->findOrFail($eventId);

            // Authorization check
            $this->authorize('recordAttendance', $this->event);

            // Initialize attendance data from current participants
            $this->attendanceData = [];
            foreach ($this->event->participants as $participant) {
                $this->attendanceData[$participant->id] = [
                    'status' => $participant->actual_attendance ?? '',
                    'arrival_time' => $participant->arrival_time ?? '',
                    'notes' => $participant->notes ?? '',
                ];
            }

            $this->resetErrorBag();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Evento no encontrado', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'attendance-dialog');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tienes permiso para registrar asistencia', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'attendance-dialog');
        }
    }

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        $rules = [];

        foreach ($this->attendanceData as $participantId => $data) {
            $rules["attendanceData.{$participantId}.status"] = ['nullable', 'in:present,absent,late,excused'];
            $rules["attendanceData.{$participantId}.arrival_time"] = ['nullable', 'date_format:H:i'];
            $rules["attendanceData.{$participantId}.notes"] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'attendanceData.*.status.in' => 'Estado de asistencia inválido.',
            'attendanceData.*.arrival_time.date_format' => 'Formato de hora inválido.',
            'attendanceData.*.notes.max' => 'Las notas no pueden exceder 500 caracteres.',
        ];
    }

    /**
     * Save attendance records
     */
    public function saveAttendance(): void
    {
        $this->validate();

        if (!$this->event) {
            $this->dispatch('show-toast', message: 'Evento no encontrado', type: 'error');
            return;
        }

        try {
            // Authorization check
            $this->authorize('recordAttendance', $this->event);

            // Update each participant's attendance
            foreach ($this->attendanceData as $participantId => $data) {
                $participant = $this->event->participants->find($participantId);

                if ($participant) {
                    $updateData = [];

                    if (!empty($data['status'])) {
                        $updateData['actual_attendance'] = $data['status'];
                    }

                    if (!empty($data['arrival_time'])) {
                        $updateData['arrival_time'] = $data['arrival_time'];
                    } elseif (empty($data['status']) || $data['status'] === 'absent') {
                        $updateData['arrival_time'] = null;
                    }

                    if (isset($data['notes'])) {
                        $updateData['notes'] = $data['notes'] ?: null;
                    }

                    if (!empty($updateData)) {
                        $participant->update($updateData);
                    }
                }
            }

            // Success notification
            $this->dispatch('show-toast', message: 'Asistencia registrada exitosamente', type: 'success');

            // Dispatch event
            $this->dispatch('attendance-recorded');

            // Refresh the event detail if it's open
            $this->dispatch('refreshCalendarEventDetail');

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'attendance-dialog');

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para registrar asistencia', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al registrar asistencia: ' . $e->getMessage(), type: 'error');
            \Log::error('Error recording attendance: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->reset([
            'eventId',
            'event',
            'attendanceData',
        ]);
        $this->resetErrorBag();
        $this->dispatch('close-dialog', dialogId: 'attendance-dialog');
    }

    /**
     * Mark all participants as present
     */
    public function markAllPresent(): void
    {
        foreach ($this->attendanceData as $participantId => $data) {
            $this->attendanceData[$participantId]['status'] = 'present';
        }
    }

    /**
     * Clear all attendance records
     */
    public function clearAll(): void
    {
        foreach ($this->attendanceData as $participantId => $data) {
            $this->attendanceData[$participantId]['status'] = '';
            $this->attendanceData[$participantId]['arrival_time'] = '';
            $this->attendanceData[$participantId]['notes'] = '';
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.calendar.attendance-dialog');
    }
}
