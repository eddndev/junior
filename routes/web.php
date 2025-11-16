<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleAssignmentController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TeamAvailabilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Availability routes
    Route::get('/profile/availability', [App\Http\Controllers\Profile\AvailabilityController::class, 'show'])->name('profile.availability.show');
    Route::patch('/profile/availability', [App\Http\Controllers\Profile\AvailabilityController::class, 'update'])->name('profile.availability.update');

    // Connected Accounts routes
    Route::get('/profile/connected-accounts', [App\Http\Controllers\Profile\ConnectedAccountsController::class, 'show'])->name('profile.connected-accounts.show');
});

// RRHH - User Management Routes
// Protected by 'gestionar-usuarios' permission
Route::middleware(['auth', 'permission:gestionar-usuarios'])->group(function () {
    // Resource routes for users (index, create, store, show, edit, update, destroy)
    Route::resource('users', UserController::class);

    // Additional route to restore soft-deleted users
    Route::post('users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore');

    // Role Assignment Routes (contextual roles by area)
    Route::get('users/{user}/roles/assign', [RoleAssignmentController::class, 'create'])
        ->name('users.roles.assign');
    Route::post('users/{user}/roles', [RoleAssignmentController::class, 'store'])
        ->name('users.roles.store');
    Route::delete('users/{user}/roles/{role}', [RoleAssignmentController::class, 'destroy'])
        ->name('users.roles.destroy');
});

// RRHH - Area Management Routes
// Protected by 'gestionar-areas' permission
Route::middleware(['auth', 'permission:gestionar-areas'])->group(function () {
    // Resource routes for areas management
    Route::resource('areas', AreaController::class)->except(['show']);
});

// RRHH - Audit Logs (Trazabilidad)
// Protected by 'ver-trazabilidad' permission
Route::middleware(['auth', 'permission:ver-trazabilidad'])->group(function () {
    Route::get('audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
});

// Tareas y Colaboración Routes
Route::middleware(['auth'])->group(function () {
    // Team Log Routes
    Route::get('team-logs', [App\Http\Controllers\TeamLogController::class, 'index'])
        ->middleware('permission:ver-bitacora')
        ->name('team-logs.index');
    Route::post('team-logs', [App\Http\Controllers\TeamLogController::class, 'store'])
        ->middleware('permission:crear-bitacora')
        ->name('team-logs.store');
    Route::get('team-logs/{teamLog}/edit', [App\Http\Controllers\TeamLogController::class, 'edit'])
        ->middleware('permission:crear-bitacora')
        ->name('team-logs.edit');
    Route::put('team-logs/{teamLog}', [App\Http\Controllers\TeamLogController::class, 'update'])
        ->middleware('permission:crear-bitacora')
        ->name('team-logs.update');
    Route::delete('team-logs/{teamLog}', [App\Http\Controllers\TeamLogController::class, 'destroy'])
        ->middleware('permission:crear-bitacora')
        ->name('team-logs.destroy');

    // Task Management Routes (Directors and Managers)
    Route::middleware('permission:ver-tareas')->group(function () {
        // Kanban board view
        Route::get('tasks/kanban', [App\Http\Controllers\TaskController::class, 'kanban'])
            ->name('tasks.kanban');

        // Task details for AJAX (el-dialog)
        Route::get('tasks/{task}/details', [App\Http\Controllers\TaskController::class, 'details'])
            ->name('tasks.details');

        // Resource routes for tasks
        Route::resource('tasks', App\Http\Controllers\TaskController::class);

        // Additional task action routes
        Route::post('tasks/{task}/complete', [App\Http\Controllers\TaskController::class, 'complete'])
            ->name('tasks.complete');
        Route::patch('tasks/{task}/status', [App\Http\Controllers\TaskController::class, 'updateStatus'])
            ->name('tasks.update-status');
        Route::patch('tasks/{task}/reassign', [App\Http\Controllers\TaskController::class, 'reassign'])
            ->middleware('permission:crear-tareas')
            ->name('tasks.reassign');
        Route::post('tasks/{task}/restore', [App\Http\Controllers\TaskController::class, 'restore'])
            ->middleware('permission:crear-tareas')
            ->name('tasks.restore');

        // Task Submission file download
        Route::get('task-submissions/{file}/download', [App\Http\Controllers\TaskSubmissionController::class, 'download'])
            ->name('task-submissions.download');
    });

    // Personal Task Dashboard (All Employees)
    Route::get('my-tasks', [App\Http\Controllers\MyTasksController::class, 'index'])
        ->name('my-tasks.index');
    Route::get('my-tasks/kanban', [App\Http\Controllers\MyTasksController::class, 'kanban'])
        ->name('my-tasks.kanban');
    Route::post('my-tasks/{task}/complete', [App\Http\Controllers\MyTasksController::class, 'complete'])
        ->name('my-tasks.complete');
    Route::patch('my-tasks/{task}/status', [App\Http\Controllers\MyTasksController::class, 'updateStatus'])
        ->name('my-tasks.status');

    // Calendar General Routes
    Route::middleware('permission:ver-calendario')->group(function () {
        // Main calendar view
        Route::get('calendar', [CalendarController::class, 'index'])
            ->name('calendar.index');

        // API endpoint for calendar data (JSON)
        Route::get('calendar/events/api', [CalendarController::class, 'events'])
            ->name('calendar.events.api');

        // Show specific event/meeting details
        Route::get('calendar/{calendarEvent}', [CalendarController::class, 'show'])
            ->name('calendar.show');

        // Upcoming events API (for dashboard widget)
        Route::get('calendar/upcoming/api', [CalendarController::class, 'upcoming'])
            ->name('calendar.upcoming.api');
    });

    // Team Availability Routes
    Route::get('team-availability', [TeamAvailabilityController::class, 'index'])
        ->name('team-availability.index');
    Route::get('team-availability/day', [TeamAvailabilityController::class, 'dayData'])
        ->name('team-availability.day');
});

// UI Components Showcase (solo en desarrollo)
if (config('app.debug')) {
    Route::get('/ui-components', function () {
        return view('ui-components.index');
    })->middleware(['auth'])->name('ui-components');
}

require __DIR__.'/socialite.php';
require __DIR__.'/auth.php';
