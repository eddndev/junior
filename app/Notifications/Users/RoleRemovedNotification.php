<?php

namespace App\Notifications\Users;

use App\Models\Role;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class RoleRemovedNotification extends BaseNotification
{
    protected string $notificationType = 'role_removed';
    protected string $priority = 'high';
    protected string $icon = 'shield-exclamation';
    protected string $iconColor = 'text-red-500';
    protected string $group = 'users';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Role $role,
        protected ?User $removedBy = null,
        protected ?int $areaId = null
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
            'title' => 'Rol removido',
            'message' => "Se te ha removido el rol '{$this->role->name}'",
            'type' => 'warning',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Role::class,
            'notifiable_id' => $this->role->id,
            'action_url' => route('profile.edit'),
            'action_text' => 'Ver perfil',
            'data' => [
                'role_id' => $this->role->id,
                'role_name' => $this->role->name,
                'role_slug' => $this->role->slug,
                'removed_by_id' => $this->removedBy?->id,
                'removed_by_name' => $removerName,
                'area_id' => $this->areaId,
            ],
            'area_id' => $this->areaId,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $removerName = $this->removedBy?->name ?? 'Sistema';

        return (new MailMessage)
            ->subject('Rol removido: ' . $this->role->name)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Se te ha removido un rol en el sistema.")
            ->line('**Rol removido:** ' . $this->role->name)
            ->line('**Removido por:** ' . $removerName)
            ->action('Ver perfil', route('profile.edit'))
            ->line('Si tienes dudas sobre este cambio, contacta a tu supervisor.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
