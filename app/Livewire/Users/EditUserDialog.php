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
    public array $selectedAreas = [];
    public array $selectedRoles = [];

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

            // Get selected areas
            $this->selectedAreas = $this->user->areas->pluck('id')->toArray();

            // Get selected roles
            $this->selectedRoles = $this->user->roles->pluck('id')->toArray();

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
            'selectedAreas' => ['nullable', 'array'],
            'selectedAreas.*' => ['integer', 'exists:areas,id'],
            'selectedRoles' => ['nullable', 'array'],
            'selectedRoles.*' => ['integer', 'exists:roles,id'],
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
            'selectedAreas' => 'áreas',
            'selectedRoles' => 'roles',
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

            // Sync areas
            $this->user->areas()->sync($this->selectedAreas);

            // Sync roles
            $this->user->roles()->sync($this->selectedRoles);

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