<?php

namespace App\Livewire\Calendar;

use App\Models\Area;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateEventDialog extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Form fields
    public string $title = '';
    public string $description = '';
    public string $type = 'event'; // event or meeting
    public string $startDate = '';
    public string $endDate = '';
    public string $startTime = '09:00';
    public string $endTime = '10:00';
    public bool $isAllDay = false;
    public string $location = '';
    public string $virtualLink = '';
    public string $color = '#3B82F6'; // Default blue
    public ?int $areaId = null;
    public bool $isPublic = true;

    // Participants for meetings
    public array $selectedParticipants = [];

    // File attachments
    public $attachments = [];

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

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'openCreateEventDialog' => 'open',
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

        // Set default date to today
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    /**
     * Open dialog with optional parameters
     */
    public function open(string $type = 'event', ?string $date = null): void
    {
        $this->resetForm();

        $this->type = $type;

        if ($date) {
            $this->startDate = $date;
            $this->endDate = $date;
        }
    }

    /**
     * Reset form to initial state
     */
    public function resetForm(): void
    {
        $this->reset([
            'title',
            'description',
            'type',
            'startTime',
            'endTime',
            'isAllDay',
            'location',
            'virtualLink',
            'color',
            'areaId',
            'isPublic',
            'selectedParticipants',
            'attachments',
        ]);

        $this->type = 'event';
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->startTime = '09:00';
        $this->endTime = '10:00';
        $this->color = '#3B82F6';
        $this->isPublic = true;

        $this->resetErrorBag();
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
            'startDate' => ['required', 'date', 'after_or_equal:today'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
            'isAllDay' => ['boolean'],
            'location' => ['nullable', 'string', 'max:255'],
            'virtualLink' => ['nullable', 'url', 'max:500'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'areaId' => ['nullable', 'exists:areas,id'],
            'isPublic' => ['boolean'],
            'attachments.*' => ['file', 'max:10240'], // 10MB max per file
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
            'startDate.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            'endDate.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'startTime.required' => 'La hora de inicio es obligatoria.',
            'endTime.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'virtualLink.url' => 'El enlace virtual debe ser una URL válida.',
            'color.regex' => 'El color debe ser un código hexadecimal válido.',
            'selectedParticipants.required' => 'Debe seleccionar al menos un participante para la reunión.',
            'selectedParticipants.min' => 'Debe seleccionar al menos un participante.',
            'attachments.*.max' => 'Cada archivo no debe superar los 10MB.',
        ];
    }

    /**
     * Save the calendar event
     */
    public function save(): void
    {
        $this->validate();

        try {
            // Authorization check
            $this->authorize('create', CalendarEvent::class);

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
                'created_by' => auth()->id(),
                'status' => 'scheduled',
                'is_public' => $this->isPublic,
            ];

            // Create the event
            $event = CalendarEvent::create($eventData);

            // Attach participants for meetings
            if ($this->type === 'meeting' && !empty($this->selectedParticipants)) {
                foreach ($this->selectedParticipants as $userId) {
                    $event->participants()->create([
                        'user_id' => $userId,
                        'is_required' => true,
                        'attendance_status' => 'pending',
                    ]);
                }
            }

            // Handle file attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $attachment) {
                    $event->addMedia($attachment->getRealPath())
                          ->usingFileName($attachment->getClientOriginalName())
                          ->toMediaCollection('attachments');
                }
            }

            // Success notification
            $this->dispatch('show-toast', message: 'Evento creado exitosamente', type: 'success');

            // Dispatch event
            $this->dispatch('calendar-event-created');

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'create-event-dialog');

            // Reset form
            $this->resetForm();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para crear eventos', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al crear evento: ' . $e->getMessage(), type: 'error');
            \Log::error('Error creating calendar event: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->dispatch('close-dialog', dialogId: 'create-event-dialog');
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
    public function removeAttachment(int $index): void
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            $this->attachments = array_values($this->attachments);
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.calendar.create-event-dialog');
    }
}
