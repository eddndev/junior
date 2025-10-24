<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateAreaDialog extends Component
{
    use AuthorizesRequests;

    // Area being edited (null for create mode)
    public ?int $areaId = null;
    public ?Area $area = null;

    // Form fields
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadAreaForEdit' => 'loadForEdit',
        'resetAreaForm' => 'resetForm',
    ];

    /**
     * Reset form to create mode
     */
    public function resetForm(): void
    {
        $this->reset();
    }

    /**
     * Load area data for editing
     */
    public function loadForEdit(int $areaId): void
    {
        $this->areaId = $areaId;

        try {
            // Load area
            $this->area = Area::findOrFail($areaId);

            // Populate form fields
            $this->name = $this->area->name;
            $this->description = $this->area->description ?? '';
            $this->is_active = $this->area->is_active;

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Área no encontrada', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'create-area-dialog');
        }
    }

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        $rules = [
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];

        // Name validation (unique except for current area in edit mode)
        if ($this->areaId) {
            $rules['name'] = ['required', 'string', 'max:255', 'unique:areas,name,' . $this->areaId];
        } else {
            $rules['name'] = ['required', 'string', 'max:255', 'unique:areas,name'];
        }

        return $rules;
    }

    /**
     * Custom attribute names for validation
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'is_active' => 'estado activo',
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre del área es obligatorio.',
            'name.unique' => 'Ya existe un área con este nombre.',
        ];
    }

    /**
     * Save area (create or update)
     */
    public function save(): void
    {
        // Validate
        $this->validate();

        try {
            if ($this->areaId) {
                // Edit mode
                $this->authorize('update', $this->area);

                // Update area
                $this->area->update([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                // Success notification
                $this->dispatch('show-toast', message: 'Área actualizada exitosamente', type: 'success');

                // Dispatch event
                $this->dispatch('area-updated');

            } else {
                // Create mode
                $this->authorize('create', Area::class);

                // Create the area
                Area::create([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                // Success notification
                $this->dispatch('show-toast', message: 'Área creada exitosamente', type: 'success');

                // Dispatch event
                $this->dispatch('area-created');
            }

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'create-area-dialog');

            // Reset form
            $this->reset();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $message = $this->areaId ? 'No tiene permisos para editar esta área' : 'No tiene permisos para crear áreas';
            $this->dispatch('show-toast', message: $message, type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al guardar área: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->reset();
        $this->dispatch('close-dialog', dialogId: 'create-area-dialog');
    }

    /**
     * Check if in edit mode
     */
    public function isEditMode(): bool
    {
        return $this->areaId !== null;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.areas.create-area-dialog');
    }
}
