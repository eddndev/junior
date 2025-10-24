<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * TaskPolicy
 *
 * Defines authorization rules for Task model operations.
 * Combines permission-based authorization with business logic rules.
 *
 * Usage in controllers:
 * - $this->authorize('viewAny', Task::class);
 * - $this->authorize('update', $task);
 *
 * Usage in Blade:
 * - @can('update', $task)
 * - @cannot('delete', $task)
 */
class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks (index page).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view tasks
        // (employees see only their tasks, directors see area tasks)
        return $user->hasPermission('ver-tareas');
    }

    /**
     * Determine whether the user can view a specific task (show page).
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being viewed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Task $task): Response|bool
    {
        // Can view if:
        // 1. User is assigned to the task
        $isAssigned = $task->assignments()
            ->where('user_id', $user->id)
            ->exists();

        if ($isAssigned) {
            return true;
        }

        // 2. User is a director/manager of the task's area
        if ($user->hasPermission('crear-tareas')) {
            $userAreaIds = $user->areas->pluck('id')->toArray();

            if (in_array($task->area_id, $userAreaIds)) {
                return true;
            }
        }

        // 3. User is super admin
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return Response::deny('No tienes permiso para ver esta tarea.');
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Requires 'crear-tareas' permission (directors and managers)
        return $user->hasPermission('crear-tareas');
    }

    /**
     * Determine whether the user can update a specific task.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Task $task): Response|bool
    {
        // Requires 'crear-tareas' OR 'editar-tareas' permission
        if (!$user->hasPermission('crear-tareas') && !$user->hasPermission('editar-tareas')) {
            return Response::deny('No tienes permiso para editar tareas.');
        }

        // Can edit if user is a director/manager of the task's area
        $userAreaIds = $user->areas->pluck('id')->toArray();

        if (!in_array($task->area_id, $userAreaIds) && !$user->hasRole('super-admin')) {
            return Response::deny('Solo puedes editar tareas de tu área.');
        }

        // Cannot edit completed tasks (optional business rule)
        // Uncomment if you want to prevent editing completed tasks:
        // if ($task->status === 'completed') {
        //     return Response::deny('No se pueden editar tareas completadas.');
        // }

        return true;
    }

    /**
     * Determine whether the user can delete (soft delete) a task.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being deleted
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Task $task): Response|bool
    {
        // Requires 'eliminar-tareas' permission
        if (!$user->hasPermission('eliminar-tareas')) {
            return Response::deny('No tienes permiso para eliminar tareas.');
        }

        // Can delete if user is a director/manager of the task's area
        $userAreaIds = $user->areas->pluck('id')->toArray();

        if (!in_array($task->area_id, $userAreaIds) && !$user->hasRole('super-admin')) {
            return Response::deny('Solo puedes eliminar tareas de tu área.');
        }

        // Cannot delete already deleted tasks (must restore first)
        if ($task->trashed()) {
            return Response::deny('Esta tarea ya está eliminada.');
        }

        // Optional: Prevent deletion of tasks with child tasks
        if ($task->childTasks()->count() > 0) {
            return Response::deny('No se puede eliminar una tarea que tiene subtareas dependientes. Elimina las subtareas primero.');
        }

        return true;
    }

    /**
     * Determine whether the user can restore a soft-deleted task.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being restored
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Task $task): Response|bool
    {
        // Requires 'crear-tareas' OR 'editar-tareas' permission
        if (!$user->hasPermission('crear-tareas') && !$user->hasPermission('editar-tareas')) {
            return Response::deny('No tienes permiso para restaurar tareas.');
        }

        // Can only restore soft-deleted tasks
        if (!$task->trashed()) {
            return Response::deny('Esta tarea no está eliminada.');
        }

        // Can restore if user is a director/manager of the task's area
        $userAreaIds = $user->areas->pluck('id')->toArray();

        if (!in_array($task->area_id, $userAreaIds) && !$user->hasRole('super-admin')) {
            return Response::deny('Solo puedes restaurar tareas de tu área.');
        }

        return true;
    }

    /**
     * Determine whether the user can complete a task.
     *
     * Custom policy method for task completion.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being completed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function complete(User $user, Task $task): Response|bool
    {
        // Requires 'completar-tareas' permission
        if (!$user->hasPermission('completar-tareas')) {
            return Response::deny('No tienes permiso para completar tareas.');
        }

        // Can complete if:
        // 1. User is assigned to the task
        $isAssigned = $task->assignments()
            ->where('user_id', $user->id)
            ->exists();

        if ($isAssigned) {
            return true;
        }

        // 2. User is a director/manager of the task's area
        if ($user->hasPermission('crear-tareas') || $user->hasPermission('editar-tareas')) {
            $userAreaIds = $user->areas->pluck('id')->toArray();

            if (in_array($task->area_id, $userAreaIds)) {
                return true;
            }
        }

        // 3. User is super admin
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return Response::deny('Solo los usuarios asignados o directores del área pueden completar esta tarea.');
    }

    /**
     * Determine whether the user can reassign a task to different users.
     *
     * Custom policy method for task reassignment.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being reassigned
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reassign(User $user, Task $task): Response|bool
    {
        // Requires 'asignar-tareas' permission
        if (!$user->hasPermission('asignar-tareas')) {
            return Response::deny('No tienes permiso para reasignar tareas.');
        }

        // Can reassign if user is a director/manager of the task's area
        $userAreaIds = $user->areas->pluck('id')->toArray();

        if (!in_array($task->area_id, $userAreaIds) && !$user->hasRole('super-admin')) {
            return Response::deny('Solo puedes reasignar tareas de tu área.');
        }

        return true;
    }

    /**
     * Determine whether the user can attach files to a task.
     *
     * Custom policy for file attachments.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task receiving attachments
     * @return bool
     */
    public function attachFiles(User $user, Task $task): bool
    {
        // Can attach files if:
        // 1. User is assigned to the task
        $isAssigned = $task->assignments()
            ->where('user_id', $user->id)
            ->exists();

        if ($isAssigned) {
            return true;
        }

        // 2. User is a director/manager of the task's area
        if ($user->hasPermission('crear-tareas') || $user->hasPermission('editar-tareas')) {
            $userAreaIds = $user->areas->pluck('id')->toArray();

            if (in_array($task->area_id, $userAreaIds)) {
                return true;
            }
        }

        // 3. User is super admin
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete file attachments from a task.
     *
     * Custom policy for file deletion.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task with attachments
     * @return bool
     */
    public function deleteFiles(User $user, Task $task): bool
    {
        // Same rules as attachFiles
        return $this->attachFiles($user, $task);
    }

    /**
     * Determine whether the user can permanently delete a task.
     *
     * Note: Force delete is typically not exposed in the UI for safety.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\Task  $task  The task being force deleted
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Task $task): Response|bool
    {
        // Only super admins can force delete tasks
        if (!$user->hasRole('super-admin')) {
            return Response::deny('Solo los super administradores pueden eliminar permanentemente tareas.');
        }

        return true;
    }

    /**
     * Determine whether the user can export task data.
     *
     * Custom policy for data export functionality (future feature).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function export(User $user): bool
    {
        // Requires 'crear-tareas' permission
        return $user->hasPermission('crear-tareas');
    }
}
