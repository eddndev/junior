<?php

namespace App\Notifications\Tasks;

use App\Models\Task;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends BaseNotification
{
    protected string $notificationType = 'task_assigned';
    protected string $priority = 'medium';
    protected string $icon = 'clipboard-document-list';
    protected string $iconColor = 'text-indigo-500';
    protected string $group = 'tasks';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Task $task
    ) {
        // Set priority based on task priority
        if ($this->task->priority === 'high' || $this->task->priority === 'urgent') {
            $this->priority = 'high';
        } elseif ($this->task->priority === 'low') {
            $this->priority = 'low';
        }
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $assignerName = $this->task->creator?->name ?? 'Sistema';

        return [
            'title' => 'Nueva tarea asignada',
            'message' => "{$assignerName} te ha asignado la tarea: {$this->task->title}",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Task::class,
            'notifiable_id' => $this->task->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver tarea',
            'data' => [
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'task_priority' => $this->task->priority,
                'task_due_date' => $this->task->due_date?->toISOString(),
                'assigner_id' => $this->task->created_by,
                'assigner_name' => $assignerName,
                'area_id' => $this->task->area_id,
            ],
            'area_id' => $this->task->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $assignerName = $this->task->creator?->name ?? 'Sistema';
        $dueDate = $this->task->due_date
            ? $this->task->due_date->format('d/m/Y H:i')
            : 'Sin fecha de vencimiento';

        $priorityText = match ($this->task->priority) {
            'urgent' => 'Urgente',
            'high' => 'Alta',
            'medium' => 'Media',
            'low' => 'Baja',
            default => 'Media',
        };

        return (new MailMessage)
            ->subject('Nueva tarea asignada: ' . $this->task->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("{$assignerName} te ha asignado una nueva tarea.")
            ->line('**Tarea:** ' . $this->task->title)
            ->line('**Prioridad:** ' . $priorityText)
            ->line('**Fecha de vencimiento:** ' . $dueDate)
            ->when($this->task->description, function ($mail) {
                return $mail->line('**Descripcion:** ' . $this->task->description);
            })
            ->action('Ver tarea', route('my-tasks.index'))
            ->line('Por favor, revisa los detalles de la tarea y comienza a trabajar en ella.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
