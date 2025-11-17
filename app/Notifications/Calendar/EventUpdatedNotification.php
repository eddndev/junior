<?php

namespace App\Notifications\Calendar;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class EventUpdatedNotification extends BaseNotification
{
    protected string $notificationType = 'event_updated';
    protected string $priority = 'high';
    protected string $icon = 'pencil-square';
    protected string $iconColor = 'text-blue-500';
    protected string $group = 'calendar';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected CalendarEvent $event,
        protected array $changedFields,
        protected ?User $changedBy = null
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
        $changerName = $this->changedBy?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';
        $changesText = $this->getChangesText();

        return [
            'title' => ucfirst($eventType) . ' actualizado',
            'message' => "El {$eventType} '{$this->event->title}' ha sido actualizado: {$changesText}",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => CalendarEvent::class,
            'notifiable_id' => $this->event->id,
            'action_url' => route('calendar.index'),
            'action_text' => 'Ver cambios',
            'data' => [
                'event_id' => $this->event->id,
                'event_title' => $this->event->title,
                'event_type' => $this->event->type,
                'changed_fields' => $this->changedFields,
                'changed_by_id' => $this->changedBy?->id,
                'changed_by_name' => $changerName,
            ],
            'area_id' => $this->event->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $changerName = $this->changedBy?->name ?? 'Sistema';
        $eventType = $this->event->isMeeting() ? 'reunión' : 'evento';

        $mail = (new MailMessage)
            ->subject(ucfirst($eventType) . ' actualizado: ' . $this->event->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("El {$eventType} '{$this->event->title}' ha sido actualizado por {$changerName}.")
            ->line('**Cambios realizados:**');

        foreach ($this->changedFields as $field => $values) {
            $fieldName = $this->getFieldLabel($field);
            $oldValue = $values['old'] ?? 'N/A';
            $newValue = $values['new'] ?? 'N/A';
            $mail->line("- {$fieldName}: {$oldValue} -> {$newValue}");
        }

        return $mail
            ->action('Ver ' . $eventType, route('calendar.index'))
            ->line('Por favor, revisa los cambios.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    /**
     * Get a summary of changes for the message.
     */
    protected function getChangesText(): string
    {
        $changes = [];
        foreach (array_keys($this->changedFields) as $field) {
            $changes[] = $this->getFieldLabel($field);
        }
        return implode(', ', $changes);
    }

    /**
     * Get human-readable field label.
     */
    protected function getFieldLabel(string $field): string
    {
        return match ($field) {
            'title' => 'título',
            'description' => 'descripción',
            'location' => 'ubicación',
            'virtual_link' => 'enlace virtual',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de fin',
            'start_time' => 'hora de inicio',
            'end_time' => 'hora de fin',
            'is_all_day' => 'todo el día',
            default => $field,
        };
    }
}
