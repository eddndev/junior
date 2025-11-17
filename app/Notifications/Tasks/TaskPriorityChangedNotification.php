<?php

namespace App\Notifications\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskPriorityChangedNotification extends BaseNotification
{
    protected string $notificationType = 'task_priority_changed';
    protected string $priority = 'medium';
    protected string $icon = 'arrow-trending-up';
    protected string $iconColor = 'text-yellow-500';
    protected string $group = 'tasks';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Task $task,
        protected string $oldPriority,
        protected string $newPriority,
        protected ?User $changedBy = null
    ) {
        // Adjust notification priority based on new task priority
        if ($this->newPriority === 'urgent' || $this->newPriority === 'high') {
            $this->priority = 'high';
        }
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
            'title' => 'Prioridad de tarea actualizada',
            'message' => "La prioridad de la tarea '{$this->task->title}' ha cambiado de {$this->getPriorityLabel($this->oldPriority)} a {$this->getPriorityLabel($this->newPriority)}",
            'type' => 'warning',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Task::class,
            'notifiable_id' => $this->task->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver tarea',
            'data' => [
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'old_priority' => $this->oldPriority,
                'new_priority' => $this->newPriority,
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
            ->subject('Prioridad de tarea actualizada: ' . $this->task->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("La prioridad de la tarea '{$this->task->title}' ha sido actualizada.")
            ->line('**Prioridad anterior:** ' . $this->getPriorityLabel($this->oldPriority))
            ->line('**Nueva prioridad:** ' . $this->getPriorityLabel($this->newPriority))
            ->line('**Actualizado por:** ' . $changerName)
            ->action('Ver tarea', route('my-tasks.index'))
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    /**
     * Get human-readable priority label.
     */
    protected function getPriorityLabel(string $priority): string
    {
        return match ($priority) {
            'urgent' => 'Urgente',
            'high' => 'Alta',
            'medium' => 'Media',
            'low' => 'Baja',
            default => $priority,
        };
    }
}
