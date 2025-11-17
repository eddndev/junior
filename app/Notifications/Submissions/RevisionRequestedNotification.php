<?php

namespace App\Notifications\Submissions;

use App\Models\TaskSubmission;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class RevisionRequestedNotification extends BaseNotification
{
    protected string $notificationType = 'revision_requested';
    protected string $priority = 'high';
    protected string $icon = 'arrow-path';
    protected string $iconColor = 'text-yellow-500';
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
            'title' => 'Revisión solicitada',
            'message' => "{$reviewerName} ha solicitado cambios en tu entrega para la tarea '{$taskTitle}'",
            'type' => 'warning',
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
            ->subject('Revisión solicitada: ' . $taskTitle)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Se han solicitado cambios en tu entrega para la tarea '{$taskTitle}'.")
            ->line('**Solicitado por:** ' . $reviewerName)
            ->line('**Fecha:** ' . $reviewedAt)
            ->when($this->submission->feedback, function ($mail) {
                return $mail->line('**Comentarios del revisor:** ' . $this->submission->feedback);
            })
            ->action('Ver comentarios y realizar cambios', route('my-tasks.index'))
            ->line('Por favor, realiza los cambios solicitados y vuelve a enviar tu entrega.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
