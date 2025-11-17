<?php

namespace App\Notifications\Tasks;

use App\Models\Task;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskOverdueNotification extends BaseNotification
{
    protected string $notificationType = 'task_overdue';
    protected string $priority = 'high';
    protected string $icon = 'exclamation-triangle';
    protected string $iconColor = 'text-red-500';
    protected string $group = 'tasks';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Task $task
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
        $daysOverdue = $this->task->due_date ? now()->diffInDays($this->task->due_date) : 0;

        return [
            'title' => 'Tarea vencida',
            'message' => "La tarea '{$this->task->title}' está vencida por {$daysOverdue} día(s)",
            'type' => 'error',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Task::class,
            'notifiable_id' => $this->task->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver tarea',
            'data' => [
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'due_date' => $this->task->due_date?->toISOString(),
                'days_overdue' => $daysOverdue,
            ],
            'area_id' => $this->task->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysOverdue = $this->task->due_date ? now()->diffInDays($this->task->due_date) : 0;
        $dueDate = $this->task->due_date?->format('d/m/Y') ?? 'N/A';

        return (new MailMessage)
            ->subject('URGENTE: Tarea vencida - ' . $this->task->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("La tarea '{$this->task->title}' ha superado su fecha de vencimiento.")
            ->line('**Fecha de vencimiento:** ' . $dueDate)
            ->line('**Días de retraso:** ' . $daysOverdue)
            ->line('Por favor, atiende esta tarea lo antes posible.')
            ->action('Ver tarea', route('my-tasks.index'))
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
