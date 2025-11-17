<?php

namespace App\Notifications\Users;

use App\Models\Area;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class AreaAssignedNotification extends BaseNotification
{
    protected string $notificationType = 'area_assigned';
    protected string $priority = 'medium';
    protected string $icon = 'building-office';
    protected string $iconColor = 'text-blue-500';
    protected string $group = 'users';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Area $area,
        protected ?User $assignedBy = null
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
        $teamSize = $this->area->users()->count();

        return [
            'title' => 'Asignado a nueva área',
            'message' => "Has sido asignado al área '{$this->area->name}'",
            'type' => 'info',
            'notification_type' => $this->notificationType,
            'notifiable_type' => Area::class,
            'notifiable_id' => $this->area->id,
            'action_url' => route('profile.edit'),
            'action_text' => 'Ver perfil',
            'data' => [
                'area_id' => $this->area->id,
                'area_name' => $this->area->name,
                'assigned_by_id' => $this->assignedBy?->id,
                'assigned_by_name' => $assignerName,
                'team_size' => $teamSize,
            ],
            'area_id' => $this->area->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $assignerName = $this->assignedBy?->name ?? 'Sistema';
        $teamSize = $this->area->users()->count();

        return (new MailMessage)
            ->subject('Asignado a nueva área: ' . $this->area->name)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line("Has sido asignado a una nueva área.")
            ->line('**Área:** ' . $this->area->name)
            ->line('**Asignado por:** ' . $assignerName)
            ->line('**Miembros del equipo:** ' . $teamSize)
            ->when($this->area->description, function ($mail) {
                return $mail->line('**Descripción:** ' . $this->area->description);
            })
            ->action('Ver perfil', route('profile.edit'))
            ->line('Bienvenido al equipo.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }
}
