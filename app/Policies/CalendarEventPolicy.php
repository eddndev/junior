<?php

namespace App\Policies;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * CalendarEventPolicy
 *
 * Defines authorization rules for CalendarEvent model operations.
 * Combines permission-based authorization with business logic rules.
 *
 * Usage in controllers:
 * - $this->authorize('viewAny', CalendarEvent::class);
 * - $this->authorize('update', $calendarEvent);
 *
 * Usage in Blade:
 * - @can('update', $calendarEvent)
 * - @cannot('delete', $calendarEvent)
 */
class CalendarEventPolicy
{
    /**
     * Determine whether the user can view any calendar events (index page).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-calendario');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CalendarEvent $calendarEvent): Response|bool
    {
        if (!$user->hasPermission('ver-calendario')) {
            return Response::deny('No tienes permiso para ver el calendario.');
        }

        // Public events are visible to everyone with calendar permission
        if ($calendarEvent->is_public) {
            return true;
        }

        // If event has a specific area, check if user has access
        if ($calendarEvent->area_id) {
            return $user->areas->contains($calendarEvent->area_id)
                ? true
                : Response::deny('No tienes acceso a los eventos de esta área.');
        }

        return true;
    }

    /**
     * Determine whether the user can create calendar events.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('crear-eventos-calendario')
            || $user->hasPermission('crear-reuniones');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CalendarEvent $calendarEvent): Response|bool
    {
        if (!$user->hasPermission('editar-eventos-calendario')) {
            return Response::deny('No tienes permiso para editar eventos del calendario.');
        }

        // Creator can always edit their own events
        if ($calendarEvent->created_by === $user->id) {
            return true;
        }

        // Super admin or direccion-general can edit everything
        if ($user->hasRole('super-admin') || $user->hasRole('direccion-general')) {
            return true;
        }

        // Director de área can edit events from their area
        if ($calendarEvent->area_id && $user->hasRole('director-area')) {
            return $user->areas->contains($calendarEvent->area_id)
                ? true
                : Response::deny('Solo puedes editar eventos de tu área.');
        }

        return Response::deny('No tienes permiso para editar este evento.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CalendarEvent $calendarEvent): Response|bool
    {
        if (!$user->hasPermission('eliminar-eventos-calendario')) {
            return Response::deny('No tienes permiso para eliminar eventos del calendario.');
        }

        // Creator can always delete their own events
        if ($calendarEvent->created_by === $user->id) {
            return true;
        }

        // Super admin or direccion-general can delete everything
        if ($user->hasRole('super-admin') || $user->hasRole('direccion-general')) {
            return true;
        }

        // Director de área can delete events from their area
        if ($calendarEvent->area_id && $user->hasRole('director-area')) {
            return $user->areas->contains($calendarEvent->area_id)
                ? true
                : Response::deny('Solo puedes eliminar eventos de tu área.');
        }

        return Response::deny('No tienes permiso para eliminar este evento.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CalendarEvent $calendarEvent): Response|bool
    {
        if (!$calendarEvent->trashed()) {
            return Response::deny('Este evento no está eliminado.');
        }

        // Same rules as update
        return $this->update($user, $calendarEvent);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CalendarEvent $calendarEvent): Response|bool
    {
        // Only super admins can force delete
        if (!$user->hasRole('super-admin')) {
            return Response::deny('Solo los super administradores pueden eliminar permanentemente eventos.');
        }

        return true;
    }

    /**
     * Determine whether the user can record attendance for a meeting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function recordAttendance(User $user, CalendarEvent $calendarEvent): Response|bool
    {
        // Only meetings can have attendance records
        if (!$calendarEvent->isMeeting()) {
            return Response::deny('Solo se puede registrar asistencia en reuniones.');
        }

        if (!$user->hasPermission('registrar-asistencia')) {
            return Response::deny('No tienes permiso para registrar asistencia.');
        }

        // Creator can record attendance
        if ($calendarEvent->created_by === $user->id) {
            return true;
        }

        // Super admin or direccion-general can record attendance
        if ($user->hasRole('super-admin') || $user->hasRole('direccion-general')) {
            return true;
        }

        return Response::deny('Solo el creador de la reunión o directores pueden registrar asistencia.');
    }

    /**
     * Determine whether the user can manage participants of a meeting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return bool
     */
    public function manageParticipants(User $user, CalendarEvent $calendarEvent): bool
    {
        // Only meetings have participants
        if (!$calendarEvent->isMeeting()) {
            return false;
        }

        // Creator can manage participants
        if ($calendarEvent->created_by === $user->id) {
            return true;
        }

        // Super admin or direccion-general can manage participants
        if ($user->hasRole('super-admin') || $user->hasRole('direccion-general')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can attach files to the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return bool
     */
    public function attachFiles(User $user, CalendarEvent $calendarEvent): bool
    {
        // Same rules as update
        $result = $this->update($user, $calendarEvent);

        return $result === true || $result->allowed();
    }
}
