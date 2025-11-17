<?php

namespace App\Notifications\Calendar;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class EventCancelledNotification extends BaseNotification
{
    protected string $notificationType = 'event_cancelled';
    protected string $priority = 'high';
    protected string $icon = 'calendar-x-mark';
    protected string $iconColor = 'text-red-500';
    protected string $group = 'calendar';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected CalendarEvent $event,
        protected ?string $cancellationReason = null,
        protected ?User $cancelledBy = null
    ) {
        //
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $cancellerName = $this->cancelledBy?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';

        return [
            'title' => ucfirst($eventType) . ' cancelado',
            'message' => "El {$eventType} '{$this->event->title}' ha sido cancelado",
            'type' => 'error',
            'notification_type' => $this->notificationType,
            'notifiable_type' => CalendarEvent::class,
            'notifiable_id' => $this->event->id,
            'action_url' => route('calendar.index'),
            'action_text' => 'Ver calendario',
            'data' => [
                'event_id' => $this->event->id,
                'event_title' => $this->event->title,
                'event_type' => $this->event->type,
                'original_start_date' => $this->event->start_date?->toISOString(),
                'original_start_time' => $this->event->start_time,
                'cancellation_reason' => $this->cancellationReason,
                'cancelled_by_id' => $this->cancelledBy?->id,
                'cancelled_by_name' => $cancellerName,
            ],
            'area_id' => $this->event->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $cancellerName = $this->cancelledBy?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';

        return (new MailMessage)
            ->subject('CANCELADO: ' . $this->event->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("El {$eventType} '{$this->event->title}' ha sido cancelado.")
            ->line('**Cancelado por:** ' . $cancellerName)
            ->line('**Fecha original:** ' . $this->event->start_date?->format('d/m/Y'))
            ->when(!$this->event->is_all_day, function ($mail) {
                return $mail->line('**Hora original:** ' . $this->event->start_time);
            })
            ->when($this->cancellationReason, function ($mail) {
                return $mail->line('**Motivo de cancelación:** ' . $this->cancellationReason);
            })
            ->action('Ver calendario', route('calendar.index'))
            ->line('Lamentamos los inconvenientes.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
