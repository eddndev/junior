<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware/policies
        // The TaskPolicy::update() method handles the actual authorization logic
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // If this is an AJAX partial update (inline editing)
        if ($this->ajax() && ($this->has('title') || $this->has('description')) && !$this->has('area_id')) {
            return [
                'title' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    'min:3',
                ],
                'description' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ];
        }

        // Full form update
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
                // Prevent circular references (task can't be its own parent)
                'different:task_id',
            ],

            // Priority and status
            'priority' => [
                'required',
                'in:low,medium,high,critical',
            ],
            'status' => [
                'required',
                'in:pending,in_progress,completed,cancelled',
            ],

            // Due date
            'due_date' => [
                'nullable',
                'date',
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
            'parent_task_id.different' => 'Una tarea no puede ser su propia tarea padre.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad debe ser: baja, media, alta o crítica.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser: pendiente, en progreso, completada o cancelada.',
            'due_date.date' => 'La fecha límite debe ser una fecha válida.',
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
        // Get the task being updated from route parameter
        $task = $this->route('task');

        if ($task) {
            // Add task_id to request data for validation rules
            $this->merge([
                'task_id' => $task->id,
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
        // Skip additional validation for AJAX partial updates
        if ($this->ajax() && ($this->has('title') || $this->has('description')) && !$this->has('area_id')) {
            return;
        }

        // Additional custom validation logic for full updates
        $user = $this->user();
        $task = $this->route('task');

        // Verify user has access to the selected area
        if (!$user->hasRole('super-admin')) {
            $userAreaIds = $user->areas->pluck('id')->toArray();

            if (!in_array($this->area_id, $userAreaIds)) {
                abort(403, 'No tienes acceso al área seleccionada.');
            }
        }

        // Prevent creating circular parent-child relationships
        if ($this->filled('parent_task_id') && $task) {
            // Check if the new parent is actually a child of the current task
            $potentialParent = \App\Models\Task::find($this->parent_task_id);

            if ($potentialParent && $potentialParent->parent_task_id == $task->id) {
                abort(422, 'No se puede establecer una relación circular: la tarea padre seleccionada es hija de esta tarea.');
            }

            // Check if trying to make it a child of one of its descendants
            $childIds = $task->childTasks->pluck('id')->toArray();
            if (in_array($this->parent_task_id, $childIds)) {
                abort(422, 'No se puede establecer como padre una tarea que es hija de esta tarea.');
            }
        }

        // Verify assigned users belong to the selected area (warning only)
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
