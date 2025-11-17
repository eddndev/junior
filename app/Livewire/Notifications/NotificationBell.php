<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    protected $listeners = [
        'notificationReceived' => 'updateCount',
        'refreshNotifications' => '$refresh',
    ];

    public function mount(): void
    {
        $this->updateCount();
    }

    public function updateCount(): void
    {
        $this->unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();
    }

    #[Computed]
    public function recentNotifications()
    {
        return Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::where('user_id', auth()->id())
            ->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->updateCount();
        }
    }

    public function markAllAsRead(): void
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $this->updateCount();
    }

    public function render()
    {
        return view('livewire.notifications.notification-bell');
    }
}
