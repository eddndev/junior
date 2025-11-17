<?php

namespace App\Notifications;

use App\Services\NotificationPreferenceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification type identifier.
     */
    protected string $notificationType = '';

    /**
     * The notification priority.
     */
    protected string $priority = 'medium';

    /**
     * The notification icon.
     */
    protected string $icon = '';

    /**
     * The notification icon color.
     */
    protected string $iconColor = '';

    /**
     * The notification group.
     */
    protected string $group = '';

    /**
     * Get the notification's delivery channels based on user preferences.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        $preferenceService = app(NotificationPreferenceService::class);

        if ($preferenceService->isDatabaseEnabled($notifiable, $this->notificationType)) {
            $channels[] = 'database_custom';
        }

        if ($preferenceService->isEmailEnabled($notifiable, $this->notificationType)) {
            $channels[] = 'mail';
        }

        // Push notifications can be added in the future
        // if ($preferenceService->isPushEnabled($notifiable, $this->notificationType)) {
        //     $channels[] = 'broadcast';
        // }

        return $channels;
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    abstract public function toDatabase(object $notifiable): array;

    /**
     * Get the mail representation of the notification.
     */
    abstract public function toMail(object $notifiable);

    /**
     * Get the notification type.
     */
    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    /**
     * Get the notification priority.
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * Get the notification icon.
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Get the notification icon color.
     */
    public function getIconColor(): string
    {
        return $this->iconColor;
    }

    /**
     * Get the notification group.
     */
    public function getGroup(): string
    {
        return $this->group;
    }
}
