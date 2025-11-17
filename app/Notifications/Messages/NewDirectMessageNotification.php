<?php

namespace App\Notifications\Messages;

use App\Models\DirectMessage;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class NewDirectMessageNotification extends BaseNotification
{
    protected string $notificationType = 'new_direct_message';
    protected string $priority = 'high';
    protected string $icon = 'chat-bubble-left-right';
    protected string $iconColor = 'text-green-500';
    protected string $group = 'messages';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected DirectMessage $message
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
        $senderName = $this->message->sender?->name ?? 'Usuario';
        $messagePreview = $this->getMessagePreview();

        return [
            'title' => 'Nuevo mensaje',
            'message' => "{$senderName} te ha enviado un mensaje: \"{$messagePreview}\"",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => DirectMessage::class,
            'notifiable_id' => $this->message->id,
            'action_url' => route('messages.index'),
            'action_text' => 'Ver mensaje',
            'data' => [
                'message_id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'sender_id' => $this->message->sender_id,
                'sender_name' => $senderName,
                'message_preview' => $messagePreview,
                'sent_at' => $this->message->created_at?->toISOString(),
            ],
            'area_id' => null,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $senderName = $this->message->sender?->name ?? 'Usuario';
        $sentAt = $this->message->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Nuevo mensaje de ' . $senderName)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("{$senderName} te ha enviado un nuevo mensaje.")
            ->line('**Enviado:** ' . $sentAt)
            ->line('**Mensaje:**')
            ->line('"' . $this->getMessagePreview(200) . '"')
            ->action('Ver mensaje completo', route('messages.index'))
            ->line('Responde para continuar la conversación.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    /**
     * Get a preview of the message body.
     */
    protected function getMessagePreview(int $length = 50): string
    {
        $body = $this->message->body ?? '';

        if (strlen($body) > $length) {
            return substr($body, 0, $length) . '...';
        }

        return $body;
    }
}
