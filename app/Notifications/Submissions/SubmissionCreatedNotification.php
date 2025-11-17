<?php

namespace App\Notifications\Submissions;

use App\Models\TaskSubmission;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class SubmissionCreatedNotification extends BaseNotification
{
    protected string $notificationType = 'submission_created';
    protected string $priority = 'high';
    protected string $icon = 'document-arrow-up';
    protected string $iconColor = 'text-blue-500';
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
        $submitterName = $this->submission->submitter?->name ?? 'Usuario';
        $taskTitle = $this->submission->task?->title ?? 'Tarea';

        return [
            'title' => 'Nueva entrega recibida',
            'message' => "{$submitterName} ha realizado una entrega para la tarea '{$taskTitle}'",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => TaskSubmission::class,
            'notifiable_id' => $this->submission->id,
            'action_url' => route('my-tasks.index'),
            'action_text' => 'Revisar entrega',
            'data' => [
                'submission_id' => $this->submission->id,
                'task_id' => $this->submission->task_id,
                'task_title' => $taskTitle,
                'submitter_id' => $this->submission->submitted_by,
                'submitter_name' => $submitterName,
                'submitted_at' => $this->submission->submitted_at?->toISOString(),
            ],
            'area_id' => $this->submission->task?->area_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $submitterName = $this->submission->submitter?->name ?? 'Usuario';
        $taskTitle = $this->submission->task?->title ?? 'Tarea';
        $submittedAt = $this->submission->submitted_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Nueva entrega recibida: ' . $taskTitle)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Se ha recibido una nueva entrega para la tarea '{$taskTitle}'.")
            ->line('**Enviado por:** ' . $submitterName)
            ->line('**Fecha de entrega:** ' . $submittedAt)
            ->when($this->submission->notes, function ($mail) {
                return $mail->line('**Notas:** ' . $this->submission->notes);
            })
            ->action('Revisar entrega', route('my-tasks.index'))
            ->line('Por favor, revisa la entrega y proporciona tu retroalimentación.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
