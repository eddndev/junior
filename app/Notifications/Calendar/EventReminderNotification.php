<?php

namespace App\Notifications\Calendar;

use App\Models\CalendarEvent;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class EventReminderNotification extends BaseNotification
{
    protected string $notificationType = 'event_reminder';
    protected string $priority = 'high';
    protected string $icon = 'bell-alert';
    protected string $iconColor = 'text-orange-500';
    protected string $group = 'calendar';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected CalendarEvent $event,
        protected int $minutesUntil
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
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';
        $timeText = $this->getTimeUntilText();

        return [
            'title' => 'Recordatorio de ' . $eventType,
            'message' => "El {$eventType} '{$this->event->title}' comienza en {$timeText}",
            'type' => 'warning',
            'notification_type' => $this->notificationType,
            'notifiable_type' => CalendarEvent::class,
            'notifiable_id' => $this->event->id,
            'action_url' => route('calendar.index'),
            'action_text' => 'Ver ' . $eventType,
            'data' => [
                'event_id' => $this->event->id,
                'event_title' => $this->event->title,
                'event_type' => $this->event->type,
                'start_date' => $this->event->start_date?->toISOString(),
                'start_time' => $this->event->start_time,
                'location' => $this->event->location,
                'virtual_link' => $this->event->virtual_link,
                'minutes_until' => $this->minutesUntil,
            ],
            'area_id' => $this->event->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';
        $timeText = $this->getTimeUntilText();

        return (new MailMessage)
            ->subject('Recordatorio: ' . $this->event->title . ' en ' . $timeText)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Te recordamos que el {$eventType} '{$this->event->title}' comienza en {$timeText}.")
            ->line('**Fecha:** ' . $this->event->start_date?->format('d/m/Y'))
            ->when(!$this->event->is_all_day, function ($mail) {
                return $mail->line('**Hora:** ' . $this->event->start_time);
            })
            ->when($this->event->location, function ($mail) {
                return $mail->line('**Ubicación:** ' . $this->event->location);
            })
            ->when($this->event->virtual_link, function ($mail) {
                return $mail->line('**Enlace virtual:** ' . $this->event->virtual_link);
            })
            ->action('Ver ' . $eventType, route('calendar.index'))
            ->line('No olvides prepararte para el ' . $eventType . '.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    /**
     * Get human-readable time until text.
     */
    protected function getTimeUntilText(): string
    {
        if ($this->minutesUntil >= 1440) {
            $days = floor($this->minutesUntil / 1440);
            return $days === 1 ? '1 día' : "{$days} días";
        } elseif ($this->minutesUntil >= 60) {
            $hours = floor($this->minutesUntil / 60);
            return $hours === 1 ? '1 hora' : "{$hours} horas";
        } else {
            return "{$this->minutesUntil} minutos";
        }
    }
}
