<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;

class NotificationDetailDialog extends Component
{
    public ?int $notificationId = null;
    public ?Notification $notification = null;

    protected $listeners = [
        'openNotificationDetail' => 'load',
    ];

    /**
     * Load notification data and open dialog
     */
    public function load(int|array $notificationId): void
    {
        // Handle both direct int and array from Livewire dispatch
        if (is_array($notificationId)) {
            $notificationId = $notificationId['notificationId'] ?? null;
        }

        if (!$notificationId) {
            $this->dispatch('show-toast', message: 'ID de notificacion invalido', type: 'error');
            return;
        }

        $this->notificationId = $notificationId;
        $this->notification = auth()->user()->notifications()->find($notificationId);

        if (!$this->notification) {
            $this->dispatch('show-toast', message: 'Notificacion no encontrada', type: 'error');
            return;
        }

        // Mark as read when opened
        if (!$this->notification->is_read) {
            $this->notification->markAsRead();
            $this->dispatch('notificationRead');
        }

        $this->dispatch('open-dialog', dialogId: 'notification-detail-dialog');
    }

    /**
     * Navigate to action URL
     */
    public function goToAction(): void
    {
        if ($this->notification && $this->notification->action_url) {
            $this->redirect($this->notification->action_url);
        }
    }

    /**
     * Delete the notification
     */
    public function deleteNotification(): void
    {
        if ($this->notification) {
            $this->notification->delete();
            $this->dispatch('show-toast', message: 'Notificacion eliminada', type: 'success');
            $this->dispatch('close-dialog', dialogId: 'notification-detail-dialog');
            $this->dispatch('notificationDeleted');
            $this->notification = null;
            $this->notificationId = null;
        }
    }

    /**
     * Get icon based on notification type
     */
    public function getNotificationIcon(): string
    {
        if ($this->notification && $this->notification->notification_type) {
            return match (true) {
                str_starts_with($this->notification->notification_type, 'task_') => 'clipboard-document-check',
                str_starts_with($this->notification->notification_type, 'submission_') => 'document-check',
                str_starts_with($this->notification->notification_type, 'event_') => 'calendar',
                $this->notification->notification_type === 'new_direct_message' => 'chat-bubble-left-right',
                str_starts_with($this->notification->notification_type, 'role_') => 'user-circle',
                str_starts_with($this->notification->notification_type, 'area_') => 'users',
                default => 'information-circle',
            };
        }

        return match ($this->notification?->type ?? 'info') {
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            default => 'information-circle',
        };
    }

    /**
     * Get icon background color class
     */
    public function getIconBgClass(): string
    {
        return match ($this->notification?->type ?? 'info') {
            'success' => 'bg-green-100 dark:bg-green-900/50',
            'warning' => 'bg-yellow-100 dark:bg-yellow-900/50',
            'error' => 'bg-red-100 dark:bg-red-900/50',
            default => 'bg-blue-100 dark:bg-blue-900/50',
        };
    }

    /**
     * Get icon color class
     */
    public function getIconColorClass(): string
    {
        return match ($this->notification?->type ?? 'info') {
            'success' => 'text-green-600 dark:text-green-400',
            'warning' => 'text-yellow-600 dark:text-yellow-400',
            'error' => 'text-red-600 dark:text-red-400',
            default => 'text-blue-600 dark:text-blue-400',
        };
    }

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeClass(): string
    {
        return match ($this->notification?->priority ?? 'normal') {
            'high' => 'bg-red-100 text-red-800 ring-red-600/10 dark:bg-red-900 dark:text-red-200',
            'low' => 'bg-gray-100 text-gray-800 ring-gray-600/20 dark:bg-gray-700 dark:text-gray-200',
            default => 'bg-blue-100 text-blue-800 ring-blue-700/10 dark:bg-blue-900 dark:text-blue-200',
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabel(): string
    {
        return match ($this->notification?->priority ?? 'normal') {
            'high' => 'Alta',
            'low' => 'Baja',
            default => 'Normal',
        };
    }

    public function render()
    {
        return view('livewire.notifications.notification-detail-dialog');
    }
}
