<?php

namespace App\Notifications\Users;

use App\Models\Role;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class RoleAssignedNotification extends BaseNotification
{
    protected string $notificationType = 'role_assigned';
    protected string $priority = 'high';
    protected string $icon = 'shield-check';
    protected string $iconColor = 'text-indigo-500';
    protected string $group = 'users';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Role $role,
        protected ?User $assignedBy = null,
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
        $assignerName = $this->assignedBy?->name ?? 'Sistema';

        return [
            'title' => 'Nuevo rol asignado',
            'message' => "Se te ha asignado el rol '{$this->role->name}' por {$assignerName}",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Role::class,
            'notifiable_id' => $this->role->id,
            'action_url' => route('profile.edit'),
            'action_text' => 'Ver perfil',
            'data' => [
                'role_id' => $this->role->id,
                'role_name' => $this->role->name,
                'role_slug' => $this->role->slug,
                'assigned_by_id' => $this->assignedBy?->id,
                'assigned_by_name' => $assignerName,
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
        $assignerName = $this->assignedBy?->name ?? 'Sistema';

        return (new MailMessage)
            ->subject('Nuevo rol asignado: ' . $this->role->name)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Se te ha asignado un nuevo rol en el sistema.")
            ->line('**Rol:** ' . $this->role->name)
            ->line('**Asignado por:** ' . $assignerName)
            ->when($this->role->description, function ($mail) {
                return $mail->line('**Descripción:** ' . $this->role->description);
            })
            ->action('Ver perfil', route('profile.edit'))
            ->line('Con este nuevo rol tendrás acceso a nuevas funcionalidades.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
