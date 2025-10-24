<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Livewire\Component;

class EditAreaDialog extends Component
{
    use AuthorizesRequests;

    // Area being edited
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
        'loadAreaForEdit' => 'load',
    ];

    /**
     * Load area data for editing
     */
    public function load(int $areaId): void
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
            $this->dispatch('close-dialog', dialogId: 'edit-area-dialog');
        }
    }

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:areas,name,' . $this->areaId],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
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
     * Update area
     */
    public function save(): void
    {
        // Validate
        $this->validate();

        try {
            // Authorization check
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

            // Close dialog
            $this->dispatch('close-dialog', dialogId: 'edit-area-dialog');

            // Reset form
            $this->reset();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tiene permisos para editar esta área', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al actualizar área: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->reset();
        $this->dispatch('close-dialog', dialogId: 'edit-area-dialog');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.areas.edit-area-dialog');
    }
}
