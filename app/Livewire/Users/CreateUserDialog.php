<?php

namespace App\Livewire\Users;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class CreateUserDialog extends Component
{
    use AuthorizesRequests;

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
        'resetUserForm' => 'resetForm',
    ];

    /**
     * Reset form to create mode
     */
    public function resetForm(): void
    {
        $this->reset();
        $this->mount();
    }

    /**
     * Mount component and load initial data
     */
    public function mount(): void
    {
        // Load areas and roles for the form
        $this->areas = Area::active()->orderBy('name')->get()->toArray();
        $this->roles = Role::orderBy('name')->get()->toArray();
    }


    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['required'],
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
     * Create user
     */
    public function save(): void
    {
        // Validate
        $this->validate();

        try {
            // Authorization check
            $this->authorize('create', User::class);

            // Create the user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_active' => $this->is_active,
            ]);

            // Sync areas if provided
            if (!empty($this->selectedAreas)) {
                $user->areas()->sync($this->selectedAreas);
            }

            // Attach roles if provided
            if (!empty($this->selectedRoles)) {
                $user->roles()->attach($this->selectedRoles);
            }

            // Success notification
            $this->dispatch('show-toast', message: 'Usuario creado exitosamente', type: 'success');

            // Dispatch event
            $this->dispatch('user-created');

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'create-user-dialog');

            // Reset form
            $this->reset();

            // Reload areas and roles for next use
            $this->mount();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para crear usuarios', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al crear usuario: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->reset();
        $this->mount();
        $this->dispatch('close-dialog', dialogId: 'create-user-dialog');
    }


    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.users.create-user-dialog');
    }
}