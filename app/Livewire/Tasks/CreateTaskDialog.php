<?php

namespace App\Livewire\Tasks;

use App\Models\Area;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTaskDialog extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Form fields
    public string $title = '';
    public string $description = '';
    public int $areaId = 0;
    public array $assignedUsers = [];
    public string $priority = 'medium';
    public string $status = 'pending';
    public ?string $dueDate = null;
    public ?int $parentTaskId = null;

    // Subtasks
    public array $subtasks = [];

    // File uploads
    public array $attachments = [];

    // Data for dropdowns
    public $areas = [];
    public $users = [];
    public $parentTasks = [];

    // Loading state
    public bool $isLoading = false;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:5000',
            'areaId' => 'required|integer|exists:areas,id',
            'assignedUsers' => 'nullable|array',
            'assignedUsers.*' => 'exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'dueDate' => 'nullable|date|after_or_equal:today',
            'parentTaskId' => 'nullable|integer|exists:tasks,id',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.description' => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|max:10240', // 10MB
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder 255 caracteres.',
            'description.max' => 'La descripción no puede exceder 5000 caracteres.',
            'areaId.required' => 'Debes seleccionar un área.',
            'areaId.exists' => 'El área seleccionada no es válida.',
            'assignedUsers.*.exists' => 'Uno de los usuarios asignados no es válido.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad debe ser: baja, media, alta o crítica.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser: pendiente, en progreso, completada o cancelada.',
            'dueDate.date' => 'La fecha límite debe ser una fecha válida.',
            'dueDate.after_or_equal' => 'La fecha límite no puede ser anterior a hoy.',
            'parentTaskId.exists' => 'La tarea padre seleccionada no es válida.',
            'subtasks.*.title.required' => 'El título de la subtarea es obligatorio.',
            'subtasks.*.title.max' => 'El título de la subtarea no puede exceder 255 caracteres.',
            'attachments.*.max' => 'El archivo no puede exceder 10MB.',
        ];
    }

    /**
     * Mount component
     */
    public function mount(): void
    {
        $this->loadFormData();
    }

    /**
     * Load form data (areas, users, parent tasks)
     */
    public function loadFormData(): void
    {
        $user = auth()->user();

        // Load areas
        $this->areas = $user->hasRole('super-admin')
            ? Area::where('is_active', true)->orderBy('name')->get()
            : $user->areas()->where('is_active', true)->orderBy('name')->get();

        // Load users
        $this->users = User::where('is_active', true)->orderBy('name')->get();

        // Load parent tasks (for creating dependent tasks)
        $this->parentTasks = Task::whereNull('parent_task_id')
            ->whereIn('area_id', $this->areas->pluck('id'))
            ->orderBy('title')
            ->get();

        // Set default area if only one
        if ($this->areas->count() === 1) {
            $this->areaId = $this->areas->first()->id;
        }
    }

    /**
     * Add a subtask to the list
     */
    public function addSubtask(): void
    {
        $this->subtasks[] = [
            'title' => '',
            'description' => '',
            'temp_id' => uniqid(), // For wire:key
        ];
    }

    /**
     * Remove a subtask from the list
     */
    public function removeSubtask(string $tempId): void
    {
        $this->subtasks = array_values(
            array_filter($this->subtasks, fn($subtask) => $subtask['temp_id'] !== $tempId)
        );
    }

    /**
     * Save the new task
     */
    public function save(): void
    {
        $this->isLoading = true;

        try {
            // Authorization check
            $this->authorize('create', Task::class);

            // Validate form
            $validated = $this->validate();

            // Create task
            $task = Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'area_id' => $this->areaId,
                'parent_task_id' => $this->parentTaskId,
                'priority' => $this->priority,
                'status' => $this->status,
                'due_date' => $this->dueDate,
            ]);

            // Assign users (polymorphic assignments)
            if (!empty($this->assignedUsers)) {
                foreach ($this->assignedUsers as $userId) {
                    TaskAssignment::create([
                        'assignable_type' => Task::class,
                        'assignable_id' => $task->id,
                        'user_id' => $userId,
                        'assigned_at' => now(),
                    ]);
                }
            }

            // Create subtasks
            if (!empty($this->subtasks)) {
                foreach ($this->subtasks as $index => $subtaskData) {
                    if (!empty($subtaskData['title'])) {
                        Subtask::create([
                            'task_id' => $task->id,
                            'title' => $subtaskData['title'],
                            'description' => $subtaskData['description'] ?? null,
                            'status' => 'pending',
                            'order' => $index,
                        ]);
                    }
                }
            }

            // Handle file attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $task->addMedia($file)->toMediaCollection('attachments');
                }
            }

            // Dispatch events
            $this->dispatch('task-created', taskId: $task->id);
            $this->dispatch('close-dialog', dialogId: 'create-task-dialog');
            $this->dispatch('show-toast', message: 'Tarea creada exitosamente', type: 'success');

            // Reset form
            $this->reset();
            $this->loadFormData();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tienes permiso para crear tareas', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al crear la tarea: ' . $e->getMessage(), type: 'error');
            \Log::error('Error creating task: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Cancel and close dialog
     */
    public function cancel(): void
    {
        $this->reset();
        $this->loadFormData();
        $this->dispatch('close-dialog', dialogId: 'create-task-dialog');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.tasks.create-task-dialog');
    }
}
