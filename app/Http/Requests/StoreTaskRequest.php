<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware/policies
        // Only users with 'crear-tareas' permission should reach here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic task information
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            // Area and parent task
            'area_id' => [
                'required',
                'integer',
                'exists:areas,id',
            ],
            'parent_task_id' => [
                'nullable',
                'integer',
                'exists:tasks,id',
            ],

            // Priority and status
            'priority' => [
                'required',
                'in:low,medium,high,critical',
            ],
            'status' => [
                'nullable',
                'in:pending,in_progress,completed,cancelled',
            ],

            // Due date
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            // Assigned users (polymorphic assignments)
            'assigned_users' => [
                'nullable',
                'array',
            ],
            'assigned_users.*' => [
                'integer',
                'exists:users,id',
            ],

            // File attachments
            'attachments' => [
                'nullable',
                'array',
            ],
            'attachments.*' => [
                'file',
                'max:10240', // 10MB max per file
                'mimes:jpeg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,json,zip,rar,7z',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descripción',
            'area_id' => 'área',
            'parent_task_id' => 'tarea padre',
            'priority' => 'prioridad',
            'status' => 'estado',
            'due_date' => 'fecha límite',
            'assigned_users' => 'usuarios asignados',
            'assigned_users.*' => 'usuario asignado',
            'attachments' => 'archivos adjuntos',
            'attachments.*' => 'archivo adjunto',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos :min caracteres.',
            'title.max' => 'El título no puede exceder :max caracteres.',
            'description.max' => 'La descripción no puede exceder :max caracteres.',
            'area_id.required' => 'Debes seleccionar un área.',
            'area_id.exists' => 'El área seleccionada no es válida.',
            'parent_task_id.exists' => 'La tarea padre seleccionada no es válida.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad debe ser: baja, media, alta o crítica.',
            'status.in' => 'El estado debe ser: pendiente, en progreso, completada o cancelada.',
            'due_date.date' => 'La fecha límite debe ser una fecha válida.',
            'due_date.after_or_equal' => 'La fecha límite no puede ser anterior a hoy.',
            'assigned_users.array' => 'Los usuarios asignados deben ser una lista.',
            'assigned_users.*.exists' => 'Uno de los usuarios asignados no es válido.',
            'attachments.*.file' => 'El archivo adjunto debe ser un archivo válido.',
            'attachments.*.max' => 'El archivo adjunto no puede exceder 10MB.',
            'attachments.*.mimes' => 'El archivo adjunto debe ser de un tipo válido (imagen, documento, etc.).',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status to 'pending' if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => 'pending',
            ]);
        }

        // Convert empty parent_task_id to null
        if ($this->has('parent_task_id') && empty($this->parent_task_id)) {
            $this->merge([
                'parent_task_id' => null,
            ]);
        }

        // Convert empty due_date to null
        if ($this->has('due_date') && empty($this->due_date)) {
            $this->merge([
                'due_date' => null,
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Additional custom validation logic can go here
        // For example, verify user has access to the selected area
        $user = $this->user();

        if (!$user->hasRole('super-admin')) {
            $userAreaIds = $user->areas->pluck('id')->toArray();

            if (!in_array($this->area_id, $userAreaIds)) {
                abort(403, 'No tienes acceso al área seleccionada.');
            }
        }

        // Verify assigned users belong to the selected area (optional business rule)
        if ($this->filled('assigned_users')) {
            $areaUserIds = \App\Models\User::whereHas('areas', function ($q) {
                $q->where('areas.id', $this->area_id);
            })->pluck('id')->toArray();

            foreach ($this->assigned_users as $userId) {
                if (!in_array($userId, $areaUserIds)) {
                    // Log warning but don't block (flexibility for cross-area collaboration)
                    \Log::warning("User {$userId} assigned to task in area {$this->area_id} but doesn't belong to that area");
                }
            }
        }
    }
}