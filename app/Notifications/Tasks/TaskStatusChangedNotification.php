<?php

namespace App\Notifications\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskStatusChangedNotification extends BaseNotification
{
    protected string $notificationType = 'task_status_changed';
    protected string $priority = 'medium';
    protected string $icon = 'clipboard-document-check';
    protected string $iconColor = 'text-blue-500';
    protected string $group = 'tasks';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Task $task,
        protected string $oldStatus,
        protected string $newStatus,
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

        return [
            'title' => 'Estado de tarea actualizado',
            'message' => "El estado de la tarea '{$this->task->title}' ha cambiado de {$this->getStatusLabel($this->oldStatus)} a {$this->getStatusLabel($this->newStatus)}",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Task::class,
            'notifiable_id' => $this->task->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver tarea',
            'data' => [
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'changed_by_id' => $this->changedBy?->id,
                'changed_by_name' => $changerName,
            ],
            'area_id' => $this->task->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $changerName = $this->changedBy?->name ?? 'Sistema';

        return (new MailMessage)
            ->subject('Estado de tarea actualizado: ' . $this->task->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("El estado de la tarea '{$this->task->title}' ha sido actualizado.")
            ->line('**Estado anterior:** ' . $this->getStatusLabel($this->oldStatus))
            ->line('**Nuevo estado:** ' . $this->getStatusLabel($this->newStatus))
            ->line('**Actualizado por:** ' . $changerName)
            ->action('Ver tarea', route('my-tasks.index'))
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    /**
     * Get human-readable status label.
     */
    protected function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En progreso',
            'review' => 'En revisión',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $status,
        };
    }
}
