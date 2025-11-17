<?php

namespace App\Notifications\Channels;

use App\Models\Notification;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Notification as LaravelNotification;

class DatabaseCustomChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, LaravelNotification $notification): void
    {
        $data = $notification->toDatabase($notifiable);

        // Get additional metadata if the notification extends BaseNotification
        $notificationType = '';
        $priority = 'medium';
        $icon = '';
        $iconColor = '';
        $group = '';

        if ($notification instanceof BaseNotification) {
            $notificationType = $notification->getNotificationType();
            $priority = $notification->getPriority();
            $icon = $notification->getIcon();
            $iconColor = $notification->getIconColor();
            $group = $notification->getGroup();
        }

        Notification::create([
            'user_id' => $notifiable->id,
            'area_id' => $data['area_id'] ?? null,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'notification_type' => $notificationType ?: ($data['notification_type'] ?? ''),
            'notifiable_type' => $data['notifiable_type'] ?? null,
            'notifiable_id' => $data['notifiable_id'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'action_text' => $data['action_text'] ?? null,
            'data' => $data['data'] ?? null,
            'icon' => $icon ?: ($data['icon'] ?? null),
            'icon_color' => $iconColor ?: ($data['icon_color'] ?? null),
            'priority' => $priority ?: ($data['priority'] ?? 'medium'),
            'group' => $group ?: ($data['group'] ?? null),
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}
