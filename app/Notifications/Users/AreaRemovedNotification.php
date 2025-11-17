<?php

namespace App\Notifications\Users;

use App\Models\Area;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class AreaRemovedNotification extends BaseNotification
{
    protected string $notificationType = 'area_removed';
    protected string $priority = 'medium';
    protected string $icon = 'building-office-2';
    protected string $iconColor = 'text-yellow-500';
    protected string $group = 'users';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Area $area,
        protected ?User $removedBy = null
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
        $removerName = $this->removedBy?->name ?? 'Sistema';

        return [
            'title' => 'Removido de área',
            'message' => "Has sido removido del área '{$this->area->name}'",
            'type' => 'warning',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Area::class,
            'notifiable_id' => $this->area->id,
            'action_url' => route('profile.edit'),
            'action_text' => 'Ver perfil',
            'data' => [
                'area_id' => $this->area->id,
                'area_name' => $this->area->name,
                'removed_by_id' => $this->removedBy?->id,
                'removed_by_name' => $removerName,
            ],
            'area_id' => $this->area->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $removerName = $this->removedBy?->name ?? 'Sistema';

        return (new MailMessage)
            ->subject('Removido de área: ' . $this->area->name)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Has sido removido de un área.")
            ->line('**Área:** ' . $this->area->name)
            ->line('**Removido por:** ' . $removerName)
            ->action('Ver perfil', route('profile.edit'))
            ->line('Si tienes dudas sobre este cambio, contacta a tu supervisor.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
