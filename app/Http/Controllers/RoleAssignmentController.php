<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

/**
 * RoleAssignmentController
 *
 * Handles the assignment and removal of roles to/from users.
 * Supports contextual roles by area (e.g., a user can be "Director" in one area
 * and "Miembro" in another area).
 */
class RoleAssignmentController extends Controller
{
    /**
     * Assign a role to a user (optionally in a specific area).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate incoming request
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
        ], [
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'role_id.required' => 'El rol es obligatorio.',
            'role_id.exists' => 'El rol seleccionado no existe.',
            'area_id.exists' => 'El área seleccionada no existe.',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            // Authorization check - using custom policy method
            $this->authorize('assignRoles', $user);
            $role = Role::findOrFail($validated['role_id']);
            $area = $validated['area_id'] ? Area::findOrFail($validated['area_id']) : null;

            // Check if the user already has this role in this area
            $existingAssignment = $user->roles()
                ->where('role_id', $validated['role_id'])
                ->wherePivot('area_id', $validated['area_id'] ?? null)
                ->exists();

            if ($existingAssignment) {
                $context = $area ? " en el área {$area->name}" : ' de forma global';
                return redirect()->back()->with('error', "El usuario ya tiene el rol {$role->name}{$context}.");
            }

            // Assign the role (with area_id if provided)
            $user->roles()->attach($validated['role_id'], [
                'area_id' => $validated['area_id'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Success message
            $context = $area ? " en el área {$area->name}" : ' de forma global';
            $message = "Rol {$role->name} asignado exitosamente a {$user->name}{$context}.";

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error assigning role to user', [
                'user_id' => $validated['user_id'],
                'role_id' => $validated['role_id'],
                'area_id' => $validated['area_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error al asignar el rol. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Remove a role from a user.
     *
     * This method removes a specific role assignment. If the role is assigned
     * multiple times in different areas, you can specify which assignment to remove.
     *
     * @param Request $request
     * @param int $userId
     * @param int $roleId
     * @return RedirectResponse
     */
    public function destroy(Request $request, int $userId, int $roleId): RedirectResponse
    {
        // Validate that the area_id is provided if needed
        $validated = $request->validate([
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
        ], [
            'area_id.exists' => 'El área seleccionada no existe.',
        ]);

        try {
            $user = User::findOrFail($userId);

            // Authorization check - using custom policy method
            $this->authorize('assignRoles', $user);

            $role = Role::findOrFail($roleId);
            $area = $validated['area_id'] ? Area::findOrFail($validated['area_id']) : null;

            // Check if the role is assigned to the user
            $roleAssignment = $user->roles()
                ->where('role_id', $roleId)
                ->wherePivot('area_id', $validated['area_id'] ?? null)
                ->exists();

            if (!$roleAssignment) {
                $context = $area ? " en el área {$area->name}" : ' de forma global';
                return redirect()->back()->with('error', "El usuario no tiene el rol {$role->name}{$context}.");
            }

            // Remove the role assignment
            DB::table('role_user')
                ->where('user_id', $userId)
                ->where('role_id', $roleId)
                ->where('area_id', $validated['area_id'] ?? null)
                ->delete();

            // Success message
            $context = $area ? " en el área {$area->name}" : ' de forma global';
            $message = "Rol {$role->name} removido exitosamente de {$user->name}{$context}.";

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error removing role from user', [
                'user_id' => $userId,
                'role_id' => $roleId,
                'area_id' => $validated['area_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error al remover el rol. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Show the form for assigning roles to a user.
     *
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function create(int $userId)
    {
        $user = User::with(['roles', 'areas'])->findOrFail($userId);

        // Authorization check - using custom policy method
        $this->authorize('assignRoles', $user);

        $roles = Role::orderBy('name')->get();
        $areas = Area::orderBy('name')->get();

        // Get existing role assignments with their areas
        $existingAssignments = DB::table('role_user')
            ->where('user_id', $userId)
            ->get()
            ->groupBy('role_id')
            ->map(function ($assignments) {
                return $assignments->pluck('area_id')->toArray();
            });

        return view('roles.assign', compact('user', 'roles', 'areas', 'existingAssignments'));
    }
}
