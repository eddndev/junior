<?php

namespace App\Livewire\Calendar;

use App\Models\CalendarEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EventDetailDialog extends Component
{
    use AuthorizesRequests;

    public ?int $eventId = null;
    public ?CalendarEvent $event = null;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadCalendarEvent' => 'load',
        'refreshCalendarEventDetail' => 'refresh',
    ];

    /**
     * Load event data
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
            $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
            return;
        }

        $this->eventId = $eventId;

        try {
            $this->event = CalendarEvent::with([
                'area',
                'creator',
                'participants.user',
                'media',
            ])->findOrFail($eventId);

            // Authorization check
            $this->authorize('view', $this->event);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Evento no encontrado', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tienes permiso para ver este evento', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        }
    }

    /**
     * Refresh event data
     */
    public function refresh(): void
    {
        if ($this->eventId) {
            $this->load($this->eventId);
        }
    }

    /**
     * Open edit dialog for this event
     */
    public function openEditDialog(): void
    {
        if (!$this->event) {
            return;
        }

        $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        $this->dispatch('loadCalendarEventForEdit', eventId: $this->event->id);
        $this->dispatch('open-dialog', dialogId: 'edit-event-dialog');
    }

    /**
     * Open attendance dialog for this event
     */
    public function openAttendanceDialog(): void
    {
        if (!$this->event) {
            return;
        }

        $this->dispatch('close-dialog', dialogId: 'event-detail-dialog');
        $this->dispatch('loadEventForAttendance', eventId: $this->event->id);
        $this->dispatch('open-dialog', dialogId: 'attendance-dialog');
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        if (!$this->event) {
            return 'neutral';
        }

        return match ($this->event->status) {
            'scheduled' => 'blue',
            'in_progress' => 'amber',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'neutral',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        if (!$this->event) {
            return 'Desconocido';
        }

        return match ($this->event->status) {
            'scheduled' => 'Programado',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => 'Desconocido',
        };
    }

    /**
     * Get type badge color
     */
    public function getTypeColor(): string
    {
        if (!$this->event) {
            return 'neutral';
        }

        return $this->event->isMeeting() ? 'green' : 'blue';
    }

    /**
     * Get type label
     */
    public function getTypeLabel(): string
    {
        if (!$this->event) {
            return 'Desconocido';
        }

        return $this->event->isMeeting() ? 'Reunión' : 'Evento';
    }

    /**
     * Format date for display
     */
    public function formatDate($date): string
    {
        if (!$date) {
            return 'Sin fecha';
        }

        try {
            return \Carbon\Carbon::parse($date)->translatedFormat('d M Y');
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }

    /**
     * Format time for display
     */
    public function formatTime(?string $time): string
    {
        if (!$time) {
            return '';
        }

        try {
            return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('H:i');
        } catch (\Exception $e) {
            try {
                return \Carbon\Carbon::createFromFormat('H:i', $time)->format('H:i');
            } catch (\Exception $e2) {
                return $time;
            }
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.calendar.event-detail-dialog');
    }
}
