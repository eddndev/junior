<?php

namespace App\Livewire\Calendar;

use App\Models\Area;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditEventDialog extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Event being edited
    public ?int $eventId = null;
    public ?CalendarEvent $event = null;

    // Form fields
    public string $title = '';
    public string $description = '';
    public string $type = 'event';
    public string $startDate = '';
    public string $endDate = '';
    public string $startTime = '09:00';
    public string $endTime = '10:00';
    public bool $isAllDay = false;
    public string $location = '';
    public string $virtualLink = '';
    public string $color = '#3B82F6';
    public ?int $areaId = null;
    public bool $isPublic = true;
    public string $status = 'scheduled';

    // Participants for meetings
    public array $selectedParticipants = [];

    // File attachments
    public $newAttachments = [];

    // Data arrays for form
    public array $areas = [];
    public array $users = [];

    // Color options
    public array $colorOptions = [
        '#3B82F6' => 'Azul',
        '#10B981' => 'Verde',
        '#F59E0B' => 'Amarillo',
        '#EF4444' => 'Rojo',
        '#8B5CF6' => 'Violeta',
        '#EC4899' => 'Rosa',
        '#6B7280' => 'Gris',
        '#14B8A6' => 'Turquesa',
    ];

    // Status options
    public array $statusOptions = [
        'scheduled' => 'Programado',
        'in_progress' => 'En Progreso',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ];

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadCalendarEventForEdit' => 'load',
    ];

    /**
     * Mount component and load initial data
     */
    public function mount(): void
    {
        $this->areas = Area::active()->orderBy('name')->get()->toArray();
        $this->users = User::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->toArray();
    }

    /**
     * Load event data for editing
     *
     * @param int|array $eventId
     * @return void
     */
    public function load(int|array $eventId): void
    {
        // Handle both direct int and array from Livewire dispatch
        if (is_array($eventId)) {
            $eventId = $eventId['eventId'] ?? null;
        }

        if (!$eventId) {
            $this->dispatch('show-toast', message: 'ID de evento inválido', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'edit-event-dialog');
            return;
        }

        $this->eventId = $eventId;

        try {
            $this->event = CalendarEvent::with([
                'participants.user',
            ])->findOrFail($eventId);

            // Authorization check
            $this->authorize('update', $this->event);

            // Populate form fields
            $this->title = $this->event->title;
            $this->description = $this->event->description ?? '';
            $this->type = $this->event->type;
            $this->startDate = $this->event->start_date->format('Y-m-d');
            $this->endDate = $this->event->end_date->format('Y-m-d');
            $this->startTime = $this->event->start_time ? substr($this->event->start_time, 0, 5) : '09:00';
            $this->endTime = $this->event->end_time ? substr($this->event->end_time, 0, 5) : '10:00';
            $this->isAllDay = $this->event->is_all_day;
            $this->location = $this->event->location ?? '';
            $this->virtualLink = $this->event->virtual_link ?? '';
            $this->color = $this->event->color;
            $this->areaId = $this->event->area_id;
            $this->isPublic = $this->event->is_public;
            $this->status = $this->event->status;

            // Load participants
            $this->selectedParticipants = $this->event->participants->pluck('user_id')->toArray();

            // Reset file uploads
            $this->newAttachments = [];

            $this->resetErrorBag();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Evento no encontrado', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'edit-event-dialog');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tienes permiso para editar este evento', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'edit-event-dialog');
        }
    }

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'in:event,meeting'],
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
            'isAllDay' => ['boolean'],
            'location' => ['nullable', 'string', 'max:255'],
            'virtualLink' => ['nullable', 'url', 'max:500'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'areaId' => ['nullable', 'exists:areas,id'],
            'isPublic' => ['boolean'],
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled'],
            'newAttachments.*' => ['file', 'max:10240'],
        ];

        // Time validation only if not all day
        if (!$this->isAllDay) {
            $rules['startTime'] = ['required', 'date_format:H:i'];
            $rules['endTime'] = ['required', 'date_format:H:i', 'after:startTime'];
        }

        // Participants required for meetings
        if ($this->type === 'meeting') {
            $rules['selectedParticipants'] = ['required', 'array', 'min:1'];
            $rules['selectedParticipants.*'] = ['integer', 'exists:users,id'];
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'startDate.required' => 'La fecha de inicio es obligatoria.',
            'endDate.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'startTime.required' => 'La hora de inicio es obligatoria.',
            'endTime.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'virtualLink.url' => 'El enlace virtual debe ser una URL válida.',
            'color.regex' => 'El color debe ser un código hexadecimal válido.',
            'selectedParticipants.required' => 'Debe seleccionar al menos un participante para la reunión.',
            'selectedParticipants.min' => 'Debe seleccionar al menos un participante.',
            'newAttachments.*.max' => 'Cada archivo no debe superar los 10MB.',
        ];
    }

    /**
     * Save the calendar event
     */
    public function save(): void
    {
        $this->validate();

        if (!$this->event) {
            $this->dispatch('show-toast', message: 'Evento no encontrado', type: 'error');
            return;
        }

        try {
            // Authorization check
            $this->authorize('update', $this->event);

            // Prepare event data
            $eventData = [
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'start_time' => $this->isAllDay ? null : $this->startTime,
                'end_time' => $this->isAllDay ? null : $this->endTime,
                'is_all_day' => $this->isAllDay,
                'location' => $this->location ?: null,
                'virtual_link' => $this->virtualLink ?: null,
                'color' => $this->color,
                'area_id' => $this->areaId,
                'is_public' => $this->isPublic,
                'status' => $this->status,
            ];

            // Update the event
            $this->event->update($eventData);

            // Update participants for meetings
            if ($this->type === 'meeting') {
                // Get current participant user IDs
                $currentParticipantIds = $this->event->participants->pluck('user_id')->toArray();

                // Find participants to add
                $toAdd = array_diff($this->selectedParticipants, $currentParticipantIds);

                // Find participants to remove
                $toRemove = array_diff($currentParticipantIds, $this->selectedParticipants);

                // Add new participants
                foreach ($toAdd as $userId) {
                    $this->event->participants()->create([
                        'user_id' => $userId,
                        'is_required' => true,
                        'attendance_status' => 'pending',
                    ]);
                }

                // Remove old participants
                if (!empty($toRemove)) {
                    $this->event->participants()->whereIn('user_id', $toRemove)->delete();
                }
            } else {
                // If type changed to event, remove all participants
                $this->event->participants()->delete();
            }

            // Handle new file attachments
            if (!empty($this->newAttachments)) {
                foreach ($this->newAttachments as $attachment) {
                    $this->event->addMedia($attachment->getRealPath())
                          ->usingFileName($attachment->getClientOriginalName())
                          ->toMediaCollection('attachments');
                }
            }

            // Success notification
            $this->dispatch('show-toast', message: 'Evento actualizado exitosamente', type: 'success');

            // Dispatch event
            $this->dispatch('calendar-event-updated');

            // Refresh the event detail if it's open
            $this->dispatch('refreshCalendarEventDetail');

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'edit-event-dialog');

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para editar este evento', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al actualizar evento: ' . $e->getMessage(), type: 'error');
            \Log::error('Error updating calendar event: ' . $e->getMessage(), [
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
            'title',
            'description',
            'type',
            'startDate',
            'endDate',
            'startTime',
            'endTime',
            'isAllDay',
            'location',
            'virtualLink',
            'color',
            'areaId',
            'isPublic',
            'status',
            'selectedParticipants',
            'newAttachments',
        ]);
        $this->resetErrorBag();
        $this->dispatch('close-dialog', dialogId: 'edit-event-dialog');
    }

    /**
     * Toggle participant selection
     */
    public function toggleParticipant(int $userId): void
    {
        if (in_array($userId, $this->selectedParticipants)) {
            $this->selectedParticipants = array_values(array_diff($this->selectedParticipants, [$userId]));
        } else {
            $this->selectedParticipants[] = $userId;
        }
    }

    /**
     * Remove an attachment
     */
    public function removeNewAttachment(int $index): void
    {
        if (isset($this->newAttachments[$index])) {
            unset($this->newAttachments[$index]);
            $this->newAttachments = array_values($this->newAttachments);
        }
    }

    /**
     * Remove an existing attachment
     */
    public function removeExistingAttachment(int $mediaId): void
    {
        if (!$this->event) {
            return;
        }

        try {
            $media = $this->event->getMedia('attachments')->find($mediaId);
            if ($media) {
                $media->delete();
                $this->event->refresh();
                $this->dispatch('show-toast', message: 'Archivo eliminado', type: 'success');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al eliminar archivo', type: 'error');
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.calendar.edit-event-dialog');
    }
}
