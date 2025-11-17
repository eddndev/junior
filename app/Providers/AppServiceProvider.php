<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\CalendarEvent;
use App\Models\CalendarEventParticipant;
use App\Models\DirectMessage;
use App\Models\Permission;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TaskSubmission;
use App\Models\TeamLog;
use App\Models\User;
use App\Observers\AreaObserver;
use App\Observers\CalendarEventNotificationObserver;
use App\Observers\CalendarEventObserver;
use App\Observers\CalendarEventParticipantNotificationObserver;
use App\Observers\DirectMessageNotificationObserver;
use App\Observers\TaskAssignmentNotificationObserver;
use App\Observers\TaskNotificationObserver;
use App\Observers\TaskObserver;
use App\Observers\TaskSubmissionNotificationObserver;
use App\Observers\TeamLogObserver;
use App\Observers\UserObserver;
use App\Notifications\Channels\DatabaseCustomChannel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for audit logging
        User::observe(UserObserver::class);
        Area::observe(AreaObserver::class);
        Task::observe(TaskObserver::class);
        TeamLog::observe(TeamLogObserver::class);
        CalendarEvent::observe(CalendarEventObserver::class);

        // Register notification observers (these trigger notifications automatically)
        Task::observe(TaskNotificationObserver::class);
        TaskAssignment::observe(TaskAssignmentNotificationObserver::class);
        TaskSubmission::observe(TaskSubmissionNotificationObserver::class);
        CalendarEvent::observe(CalendarEventNotificationObserver::class);
        CalendarEventParticipant::observe(CalendarEventParticipantNotificationObserver::class);
        DirectMessage::observe(DirectMessageNotificationObserver::class);

        // Register custom notification channel
        Notification::extend('database_custom', function ($app) {
            return new DatabaseCustomChannel();
        });

        // Registrar Gates dinámicamente basados en permisos
        try {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermission($permission->slug);
                });
            });
        } catch (\Exception $e) {
            // Si las tablas no existen aún (primera migración), ignorar el error
        }

        // Gate para super-admin (Dirección General tiene todos los permisos)
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('direccion-general')) {
                return true; // Dirección General bypasses todas las verificaciones
            }
        });
    }
}
