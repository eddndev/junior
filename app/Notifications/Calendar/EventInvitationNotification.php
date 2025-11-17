<?php

namespace App\Notifications\Calendar;

use App\Models\CalendarEvent;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class EventInvitationNotification extends BaseNotification
{
    protected string $notificationType = 'event_invitation';
    protected string $priority = 'high';
    protected string $icon = 'calendar-days';
    protected string $iconColor = 'text-indigo-500';
    protected string $group = 'calendar';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected CalendarEvent $event,
        protected bool $isRequired = false
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
        $creatorName = $this->event->creator?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';

        return [
            'title' => 'Invitación a ' . $eventType,
            'message' => "{$creatorName} te ha invitado a: {$this->event->title}",
            'type' => 'info',
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
                'end_date' => $this->event->end_date?->toISOString(),
                'start_time' => $this->event->start_time,
                'end_time' => $this->event->end_time,
                'location' => $this->event->location,
                'virtual_link' => $this->event->virtual_link,
                'is_required' => $this->isRequired,
                'creator_id' => $this->event->created_by,
                'creator_name' => $creatorName,
            ],
            'area_id' => $this->event->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $creatorName = $this->event->creator?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';
        $requiredText = $this->isRequired ? ' (Asistencia obligatoria)' : '';

        return (new MailMessage)
            ->subject('Invitación: ' . $this->event->title . $requiredText)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("{$creatorName} te ha invitado a un {$eventType}.")
            ->line('**Título:** ' . $this->event->title)
            ->line('**Fecha:** ' . $this->event->formatted_date_range)
            ->when(!$this->event->is_all_day, function ($mail) {
                return $mail->line('**Hora:** ' . $this->event->formatted_time_range);
            })
            ->when($this->event->location, function ($mail) {
                return $mail->line('**Ubicación:** ' . $this->event->location);
            })
            ->when($this->event->virtual_link, function ($mail) {
                return $mail->line('**Enlace virtual:** ' . $this->event->virtual_link);
            })
            ->when($this->event->description, function ($mail) {
                return $mail->line('**Descripción:** ' . $this->event->description);
            })
            ->when($this->isRequired, function ($mail) {
                return $mail->line('**Tu asistencia es obligatoria.**');
            })
            ->action('Ver ' . $eventType, route('calendar.index'))
            ->line('Por favor, confirma tu asistencia.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
