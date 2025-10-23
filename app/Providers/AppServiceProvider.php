<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\Permission;
use App\Models\Task;
use App\Models\TeamLog;
use App\Models\User;
use App\Observers\AreaObserver;
use App\Observers\TaskObserver;
use App\Observers\TeamLogObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Gate;
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
