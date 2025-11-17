<?php

namespace App\Notifications\Submissions;

use App\Models\TaskSubmission;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class SubmissionRejectedNotification extends BaseNotification
{
    protected string $notificationType = 'submission_rejected';
    protected string $priority = 'high';
    protected string $icon = 'x-circle';
    protected string $iconColor = 'text-red-500';
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
            'title' => 'Entrega rechazada',
            'message' => "Tu entrega para la tarea '{$taskTitle}' ha sido rechazada",
            'type' => 'error',
            'notification_type' => $this->notificationType,
            'notifiable_type' => TaskSubmission::class,
            'notifiable_id' => $this->submission->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Ver comentarios',
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
            ->subject('Entrega rechazada: ' . $taskTitle)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Lamentablemente, tu entrega para la tarea '{$taskTitle}' ha sido rechazada.")
            ->line('**Revisado por:** ' . $reviewerName)
            ->line('**Fecha de revisión:** ' . $reviewedAt)
            ->when($this->submission->feedback, function ($mail) {
                return $mail->line('**Motivo del rechazo:** ' . $this->submission->feedback);
            })
            ->action('Ver comentarios', route('my-tasks.index'))
            ->line('Por favor, revisa los comentarios del revisor.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
