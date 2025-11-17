<?php

namespace App\Notifications\Submissions;

use App\Models\TaskSubmission;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class SubmissionApprovedNotification extends BaseNotification
{
    protected string $notificationType = 'submission_approved';
    protected string $priority = 'high';
    protected string $icon = 'check-badge';
    protected string $iconColor = 'text-green-500';
    protected string $group = 'submissions';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected TaskSubmission $submission
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
        $reviewerName = $this->submission->reviewer?->name ?? 'Revisor';
        $taskTitle = $this->submission->task?->title ?? 'Tarea';

        return [
            'title' => 'Entrega aprobada',
            'message' => "Tu entrega para la tarea '{$taskTitle}' ha sido aprobada por {$reviewerName}",
            'type' => 'success',
            'notification_type' => $this->notificationType,
            'notifiable_type' => TaskSubmission::class,
            'notifiable_id' => $this->submission->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver detalles',
            'data' => [
                'submission_id' => $this->submission->id,
                'task_id' => $this->submission->task_id,
                'task_title' => $taskTitle,
                'reviewer_id' => $this->submission->reviewed_by,
                'reviewer_name' => $reviewerName,
                'reviewed_at' => $this->submission->reviewed_at?->toISOString(),
                'feedback' => $this->submission->feedback,
            ],
            'area_id' => $this->submission->task?->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reviewerName = $this->submission->reviewer?->name ?? 'Revisor';
        $taskTitle = $this->submission->task?->title ?? 'Tarea';
        $reviewedAt = $this->submission->reviewed_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Entrega aprobada: ' . $taskTitle)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Tu entrega para la tarea '{$taskTitle}' ha sido aprobada.")
            ->line('**Aprobado por:** ' . $reviewerName)
            ->line('**Fecha de aprobación:** ' . $reviewedAt)
            ->when($this->submission->feedback, function ($mail) {
                return $mail->line('**Comentarios:** ' . $this->submission->feedback);
            })
            ->action('Ver detalles', route('my-tasks.index'))
            ->line('Excelente trabajo.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
