<?php

namespace App\Notifications\Tasks;

use App\Models\Task;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskDueDateApproachingNotification extends BaseNotification
{
    protected string $notificationType = 'task_due_date_approaching';
    protected string $priority = 'high';
    protected string $icon = 'clock';
    protected string $iconColor = 'text-orange-500';
    protected string $group = 'tasks';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Task $task,
        protected int $hoursRemaining
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
        $timeText = $this->getTimeRemainingText();

        return [
            'title' => 'Fecha de vencimiento próxima',
            'message' => "La tarea '{$this->task->title}' vence en {$timeText}",
            'type' => 'warning',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Task::class,
            'notifiable_id' => $this->task->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver tarea',
            'data' => [
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'due_date' => $this->task->due_date?->toISOString(),
                'hours_remaining' => $this->hoursRemaining,
            ],
            'area_id' => $this->task->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $timeText = $this->getTimeRemainingText();
        $dueDate = $this->task->due_date?->format('d/m/Y H:i') ?? 'N/A';

        return (new MailMessage)
            ->subject('Recordatorio: Tarea próxima a vencer - ' . $this->task->title)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Te recordamos que la tarea '{$this->task->title}' está próxima a vencer.")
            ->line('**Fecha de vencimiento:** ' . $dueDate)
            ->line('**Tiempo restante:** ' . $timeText)
            ->line('Por favor, asegúrate de completarla a tiempo.')
            ->action('Ver tarea', route('my-tasks.index'))
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    /**
     * Get human-readable time remaining text.
     */
    protected function getTimeRemainingText(): string
    {
        if ($this->hoursRemaining >= 48) {
            $days = floor($this->hoursRemaining / 24);
            return "{$days} días";
        } elseif ($this->hoursRemaining >= 24) {
            return '1 día';
        } else {
            return "{$this->hoursRemaining} horas";
        }
    }
}
