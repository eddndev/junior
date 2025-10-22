<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users with pagination, search and filters.
     */
    public function index(Request $request)
    {
        // Build query with eager loading for performance
        $query = User::with(['roles', 'areas']);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by active/inactive status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by area
        if ($request->filled('area_id')) {
            $query->whereHas('areas', function ($q) use ($request) {
                $q->where('areas.id', $request->area_id);
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Include soft deleted users if requested
        if ($request->filled('show_deleted') && $request->show_deleted === 'true') {
            $query->withTrashed();
        }

        // Order by newest first
        $query->orderBy('created_at', 'desc');

        // Paginate results
        $users = $query->paginate(15)->withQueryString();

        // Get areas and roles for filters
        $areas = Area::active()->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'areas', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Get active areas and all roles for the form
        $areas = Area::active()->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('areas', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Sync areas if provided
        if ($request->filled('areas')) {
            $user->areas()->sync($request->areas);
        }

        // Attach roles with their respective areas if provided
        if ($request->filled('roles')) {
            foreach ($request->roles as $roleData) {
                // roleData can be: ['role_id' => 1, 'area_id' => 2]
                // or just a role_id if no specific area context
                if (is_array($roleData)) {
                    $user->roles()->attach($roleData['role_id'], [
                        'area_id' => $roleData['area_id'] ?? null
                    ]);
                } else {
                    $user->roles()->attach($roleData);
                }
            }
        }

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user with roles and permissions.
     */
    public function show(User $user)
    {
        // Eager load relationships
        $user->load(['roles.permissions', 'areas']);

        // Get all accumulated permissions
        $permissions = $user->getAllPermissions();

        // Group permissions by module for better display
        $permissionsByModule = $permissions->groupBy('module');

        return view('users.show', compact('user', 'permissionsByModule'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Eager load relationships
        $user->load(['roles', 'areas']);

        // Get areas and roles for the form
        $areas = Area::active()->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        // Get current role-area assignments
        $userRoles = $user->roles->map(function ($role) {
            return [
                'role_id' => $role->id,
                'area_id' => $role->pivot->area_id,
            ];
        })->toArray();

        return view('users.edit', compact('user', 'areas', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Update basic user information
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync areas if provided
        if ($request->has('areas')) {
            $user->areas()->sync($request->areas ?? []);
        }

        // Sync roles with their respective areas if provided
        if ($request->has('roles')) {
            // Detach all current roles
            $user->roles()->detach();

            // Attach new roles with areas
            if ($request->filled('roles')) {
                foreach ($request->roles as $roleData) {
                    if (is_array($roleData)) {
                        $user->roles()->attach($roleData['role_id'], [
                            'area_id' => $roleData['area_id'] ?? null
                        ]);
                    } else {
                        $user->roles()->attach($roleData);
                    }
                }
            }
        }

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Soft delete the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Soft delete the user
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario desactivado exitosamente.');
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Restore the user
        $user->restore();

        // Optionally reactivate the user
        $user->update(['is_active' => true]);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Usuario restaurado exitosamente.');
    }
}