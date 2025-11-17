<?php

namespace App\Notifications\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskCompletedNotification extends BaseNotification
{
    protected string $notificationType = 'task_completed';
    protected string $priority = 'medium';
    protected string $icon = 'check-circle';
    protected string $iconColor = 'text-green-500';
    protected string $group = 'tasks';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Task $task,
        protected ?User $completedBy = null
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
        $completerName = $this->completedBy?->name ?? 'Sistema';

        return [
            'title' => 'Tarea completada',
            'message' => "La tarea '{$this->task->title}' ha sido completada por {$completerName}",
            'type' => 'success',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Task::class,
            'notifiable_id' => $this->task->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver tarea',
            'data' => [
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'completed_by_id' => $this->completedBy?->id,
                'completed_by_name' => $completerName,
                'completed_at' => $this->task->completed_at?->toISOString() ?? now()->toISOString(),
            ],
            'area_id' => $this->task->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $completerName = $this->completedBy?->name ?? 'Sistema';
        $completedAt = $this->task->completed_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Tarea completada: ' . $this->task->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("La tarea '{$this->task->title}' ha sido completada.")
            ->line('**Completada por:** ' . $completerName)
            ->line('**Fecha de finalización:** ' . $completedAt)
            ->action('Ver tarea', route('my-tasks.index'))
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
