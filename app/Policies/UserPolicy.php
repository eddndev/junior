<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * UserPolicy
 *
 * Defines authorization rules for User model operations.
 * Combines permission-based authorization with business logic rules.
 *
 * Usage in controllers:
 * - $this->authorize('viewAny', User::class);
 * - $this->authorize('update', $user);
 *
 * Usage in Blade:
 * - @can('update', $user)
 * - @cannot('delete', $user)
 */
class UserPolicy
{
    /**
     * Determine whether the user can view any users (index page).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Requires 'gestionar-usuarios' permission to view the user list
        return $user->hasPermission('gestionar-usuarios');
    }

    /**
     * Determine whether the user can view a specific user (show page).
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $model  The user being viewed
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        // Users can always view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Or requires 'gestionar-usuarios' permission to view other users
        return $user->hasPermission('gestionar-usuarios');
    }

    /**
     * Determine whether the user can create new users.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Requires 'gestionar-usuarios' permission
        return $user->hasPermission('gestionar-usuarios');
    }

    /**
     * Determine whether the user can update a specific user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $model  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model): Response|bool
    {
        // Users can edit their own profile (limited fields via separate controller/form request)
        if ($user->id === $model->id) {
            return true;
        }

        // Requires 'gestionar-usuarios' permission to edit other users
        if (!$user->hasPermission('gestionar-usuarios')) {
            return Response::deny('No tienes permiso para editar otros usuarios.');
        }

        // Additional rule: Cannot deactivate yourself via the admin interface
        // This is handled in the controller, but we can add extra validation here if needed

        return true;
    }

    /**
     * Determine whether the user can delete (soft delete) a user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $model  The user being deleted
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model): Response|bool
    {
        // Requires 'gestionar-usuarios' permission
        if (!$user->hasPermission('gestionar-usuarios')) {
            return Response::deny('No tienes permiso para desactivar usuarios.');
        }

        // Cannot delete yourself (safety measure)
        if ($user->id === $model->id) {
            return Response::deny('No puedes desactivar tu propia cuenta. Contacta a otro administrador.');
        }

        // Cannot delete already deleted users (must restore first)
        if ($model->trashed()) {
            return Response::deny('Este usuario ya está desactivado.');
        }

        return true;
    }

    /**
     * Determine whether the user can restore a soft-deleted user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $model  The user being restored
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model): Response|bool
    {
        // Requires 'gestionar-usuarios' permission
        if (!$user->hasPermission('gestionar-usuarios')) {
            return Response::deny('No tienes permiso para restaurar usuarios.');
        }

        // Can only restore soft-deleted users
        if (!$model->trashed()) {
            return Response::deny('Este usuario no está desactivado.');
        }

        return true;
    }

    /**
     * Determine whether the user can permanently delete a user.
     *
     * Note: Force delete is typically not exposed in the UI for safety.
     * This is here for future admin-only functionality.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $model  The user being force deleted
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model): Response|bool
    {
        // Requires 'gestionar-usuarios' permission
        if (!$user->hasPermission('gestionar-usuarios')) {
            return Response::deny('No tienes permiso para eliminar permanentemente usuarios.');
        }

        // Cannot force delete yourself (safety measure)
        if ($user->id === $model->id) {
            return Response::deny('No puedes eliminar tu propia cuenta.');
        }

        // Additional safety: Only super admins can force delete (optional)
        // Uncomment if you want extra protection:
        // if (!$user->hasRole('super-admin')) {
        //     return Response::deny('Solo los super administradores pueden eliminar permanentemente usuarios.');
        // }

        return true;
    }

    /**
     * Determine whether the user can assign roles to a user.
     *
     * Custom policy method for role assignment.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $model  The user receiving role assignment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assignRoles(User $user, User $model): Response|bool
    {
        // Requires 'gestionar-usuarios' permission
        if (!$user->hasPermission('gestionar-usuarios')) {
            return Response::deny('No tienes permiso para asignar roles.');
        }

        // Optional: Prevent users from modifying their own roles
        // This can prevent accidental self-demotion
        if ($user->id === $model->id) {
            return Response::deny('No puedes modificar tus propios roles. Contacta a otro administrador.');
        }

        return true;
    }

    /**
     * Determine whether the user can manage (view/edit) permissions.
     *
     * This is typically restricted to super admins only.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function managePermissions(User $user): bool
    {
        // Only super admins can manage the permission system itself
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can export user data.
     *
     * Custom policy for data export functionality (future feature).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function export(User $user): bool
    {
        // Requires 'gestionar-usuarios' permission
        return $user->hasPermission('gestionar-usuarios');
    }

    /**
     * Determine whether the user can import user data.
     *
     * Custom policy for bulk user import functionality (future feature).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function import(User $user): bool
    {
        // Requires 'gestionar-usuarios' permission
        // Could add additional validation like 'importar-usuarios' permission in the future
        return $user->hasPermission('gestionar-usuarios');
    }
}
