<?php

namespace App\Notifications\Calendar;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class EventRescheduledNotification extends BaseNotification
{
    protected string $notificationType = 'event_rescheduled';
    protected string $priority = 'high';
    protected string $icon = 'arrow-path-rounded-square';
    protected string $iconColor = 'text-purple-500';
    protected string $group = 'calendar';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected CalendarEvent $event,
        protected array $oldSchedule,
        protected ?User $rescheduledBy = null
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
        $reschedulerName = $this->rescheduledBy?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';

        return [
            'title' => ucfirst($eventType) . ' reprogramado',
            'message' => "El {$eventType} '{$this->event->title}' ha sido reprogramado",
            'type' => 'warning',
            'notification_type' => $this->notificationType,
            'notifiable_type' => CalendarEvent::class,
            'notifiable_id' => $this->event->id,
            'action_url' => route('calendar.index'),
            'action_text' => 'Ver nueva fecha',
            'data' => [
                'event_id' => $this->event->id,
                'event_title' => $this->event->title,
                'event_type' => $this->event->type,
                'old_start_date' => $this->oldSchedule['start_date'] ?? null,
                'old_end_date' => $this->oldSchedule['end_date'] ?? null,
                'old_start_time' => $this->oldSchedule['start_time'] ?? null,
                'old_end_time' => $this->oldSchedule['end_time'] ?? null,
                'new_start_date' => $this->event->start_date?->toISOString(),
                'new_end_date' => $this->event->end_date?->toISOString(),
                'new_start_time' => $this->event->start_time,
                'new_end_time' => $this->event->end_time,
                'rescheduled_by_id' => $this->rescheduledBy?->id,
                'rescheduled_by_name' => $reschedulerName,
            ],
            'area_id' => $this->event->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reschedulerName = $this->rescheduledBy?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';

        $oldDate = isset($this->oldSchedule['start_date'])
            ? \Carbon\Carbon::parse($this->oldSchedule['start_date'])->format('d/m/Y')
            : 'N/A';
        $newDate = $this->event->start_date?->format('d/m/Y') ?? 'N/A';

        return (new MailMessage)
            ->subject('REPROGRAMADO: ' . $this->event->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("El {$eventType} '{$this->event->title}' ha sido reprogramado.")
            ->line('**Reprogramado por:** ' . $reschedulerName)
            ->line('**Fecha anterior:** ' . $oldDate . ($this->oldSchedule['start_time'] ?? ''))
            ->line('**Nueva fecha:** ' . $newDate . ' ' . ($this->event->start_time ?? ''))
            ->when($this->event->location, function ($mail) {
                return $mail->line('**Ubicación:** ' . $this->event->location);
            })
            ->when($this->event->virtual_link, function ($mail) {
                return $mail->line('**Enlace virtual:** ' . $this->event->virtual_link);
            })
            ->action('Ver nueva fecha', route('calendar.index'))
            ->line('Por favor, actualiza tu agenda.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
