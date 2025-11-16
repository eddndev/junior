<?php

namespace App\Livewire\Users;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class EditUserDialog extends Component
{
    use AuthorizesRequests;

    // User being edited
    public ?int $userId = null;
    public ?User $user = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $is_active = true;

    // Role assignments per area: [area_id => [role_id, role_id, ...], ...]
    public array $areaRoleAssignments = [];

    // For Alpine.js multi-select (passed via @js())
    public array $areas = [];
    public array $roles = [];

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadUserForEdit' => 'load',
    ];

    /**
     * Load user data for editing
     */
    public function load(int $userId): void
    {
        $this->userId = $userId;

        try {
            // Load user with relationships
            $this->user = User::with(['roles', 'areas'])
                ->withTrashed()
                ->findOrFail($userId);

            // Populate form fields
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->is_active = $this->user->is_active;

            // Build area-role assignments from existing pivot data
            $this->areaRoleAssignments = [];
            foreach ($this->user->roles as $role) {
                $areaId = $role->pivot->area_id;
                if ($areaId) {
                    if (!isset($this->areaRoleAssignments[$areaId])) {
                        $this->areaRoleAssignments[$areaId] = [];
                    }
                    $this->areaRoleAssignments[$areaId][] = $role->id;
                }
            }

            // Load areas and roles for the form
            $this->areas = Area::active()->orderBy('name')->get()->toArray();
            $this->roles = Role::orderBy('name')->get()->toArray();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Usuario no encontrado', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'edit-user-dialog');
        }
    }

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->userId],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['nullable'],
            'is_active' => ['boolean'],
            'areaRoleAssignments' => ['nullable', 'array'],
            'areaRoleAssignments.*' => ['array'],
            'areaRoleAssignments.*.*' => ['integer', 'exists:roles,id'],
        ];
    }

    /**
     * Custom attribute names for validation
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'is_active' => 'estado activo',
            'areaRoleAssignments' => 'asignaciones de roles por área',
        ];
    }

    /**
     * Update user
     */
    public function save(): void
    {
        // Validate
        $this->validate();

        try {
            // Authorization check
            $this->authorize('update', $this->user);

            // Prepare update data
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->is_active,
            ];

            // Only update password if provided
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            // Update user
            $this->user->update($data);

            // Process area-role assignments
            $areasToSync = [];
            $rolesToSync = [];

            if (!empty($this->areaRoleAssignments)) {
                foreach ($this->areaRoleAssignments as $areaId => $roleIds) {
                    if (!empty($roleIds)) {
                        // Add area to sync list
                        $areasToSync[] = $areaId;

                        // Add each role with its area_id pivot
                        foreach ($roleIds as $roleId) {
                            $rolesToSync[] = [
                                'role_id' => $roleId,
                                'area_id' => $areaId,
                            ];
                        }
                    }
                }
            }

            // Sync areas
            $this->user->areas()->sync($areasToSync);

            // Detach all roles first, then attach with proper area context
            $this->user->roles()->detach();
            foreach ($rolesToSync as $attachment) {
                $this->user->roles()->attach($attachment['role_id'], ['area_id' => $attachment['area_id']]);
            }

            // Success notification
            $this->dispatch('show-toast', message: 'Usuario actualizado exitosamente', type: 'success');

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'edit-user-dialog');

            // Refresh parent component
            $this->dispatch('user-updated');

            // Reset form
            $this->reset();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para editar este usuario', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al actualizar usuario: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->reset();
        $this->dispatch('close-dialog', dialogId: 'edit-user-dialog');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.users.edit-user-dialog');
    }
}