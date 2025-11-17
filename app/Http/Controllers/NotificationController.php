<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()->notifications()->orderByDesc('created_at');

        // Filter by read status
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('notification_type', $request->type);
        }

        $notifications = $query->paginate(20)->withQueryString();

        // Get notification type counts for filters
        $typeCounts = auth()->user()->notifications()
            ->selectRaw('notification_type, count(*) as count')
            ->groupBy('notification_type')
            ->pluck('count', 'notification_type')
            ->toArray();

        $unreadCount = auth()->user()->notifications()->unread()->count();

        return view('notifications.index', compact('notifications', 'typeCounts', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Verify ownership
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notificacion marcada como leida');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Todas las notificaciones marcadas como leidas');
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification)
    {
        // Verify ownership
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notificacion eliminada');
    }
}
