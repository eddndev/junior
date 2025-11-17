<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TaskDetailDialog extends Component
{
    use AuthorizesRequests;

    public ?int $taskId = null;
    public ?Task $task = null;

    // Form fields
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public string $status = 'pending';
    public ?string $dueDate = null;

    // Edit modes
    public bool $editingTitle = false;
    public bool $editingDescription = false;

    // Loading state
    public bool $isLoading = false;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadTask' => 'load',
        'openTaskDetail' => 'openDialog',
        'refreshTaskDetail' => 'load',
    ];

    /**
     * Open dialog and load task
     *
     * @param int|array $taskId
     * @return void
     */
    public function openDialog(int|array $taskId): void
    {
        // Handle both direct int and array from Livewire dispatch
        if (is_array($taskId)) {
            $taskId = $taskId['taskId'] ?? null;
        }

        if (!$taskId) {
            $this->dispatch('show-toast', message: 'ID de tarea inválido', type: 'error');
            return;
        }

        $this->load($taskId);
        $this->dispatch('open-dialog', dialogId: 'task-detail-dialog');
    }

    /**
     * Load task data
     *
     * @param int|array $taskId
     * @return void
     */
    public function load(int|array $taskId): void
    {
        // Handle both direct int and array from Livewire dispatch
        if (is_array($taskId)) {
            $taskId = $taskId['taskId'] ?? null;
        }

        if (!$taskId) {
            return;
        }

        $this->taskId = $taskId;

        try {
            $this->task = Task::with([
                'area',
                'parentTask',
                'childTasks.assignments.user',
                'subtasks.assignments.user',
                'assignments.user',
                'media',
            ])->findOrFail($taskId);

            // Authorization check
            $this->authorize('view', $this->task);

            // Populate form fields
            $this->title = $this->task->title;
            $this->description = $this->task->description ?? '';
            $this->priority = $this->task->priority;
            $this->status = $this->task->status;
            $this->dueDate = $this->task->due_date?->format('Y-m-d');

            // Reset edit modes
            $this->editingTitle = false;
            $this->editingDescription = false;

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Tarea no encontrada', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'task-detail-dialog');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->dispatch('show-toast', message: 'No tienes permiso para ver esta tarea', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'task-detail-dialog');
        }
    }

    /**
     * Start editing title
     */
    public function startEditTitle(): void
    {
        $this->editingTitle = true;
    }

    /**
     * Save title
     */
    public function saveTitle(): void
    {
        $this->validate([
            'title' => 'required|string|min:3|max:255',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder 255 caracteres.',
        ]);

        try {
            $this->task->update(['title' => $this->title]);
            $this->task->refresh();
            $this->editingTitle = false;

            $this->dispatch('task-updated', taskId: $this->taskId);
            $this->dispatch('show-toast', message: 'Título actualizado exitosamente', type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al actualizar el título', type: 'error');
            \Log::error('Error updating task title: ' . $e->getMessage());
        }
    }

    /**
     * Cancel title editing
     */
    public function cancelEditTitle(): void
    {
        $this->title = $this->task->title;
        $this->editingTitle = false;
        $this->resetErrorBag('title');
    }

    /**
     * Start editing description
     */
    public function startEditDescription(): void
    {
        $this->editingDescription = true;
    }

    /**
     * Save description
     */
    public function saveDescription(): void
    {
        $this->validate([
            'description' => 'nullable|string|max:5000',
        ], [
            'description.max' => 'La descripción no puede exceder 5000 caracteres.',
        ]);

        try {
            $this->task->update(['description' => $this->description]);
            $this->task->refresh();
            $this->editingDescription = false;

            $this->dispatch('task-updated', taskId: $this->taskId);
            $this->dispatch('show-toast', message: 'Descripción actualizada exitosamente', type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al actualizar la descripción', type: 'error');
            \Log::error('Error updating task description: ' . $e->getMessage());
        }
    }

    /**
     * Cancel description editing
     */
    public function cancelEditDescription(): void
    {
        $this->description = $this->task->description ?? '';
        $this->editingDescription = false;
        $this->resetErrorBag('description');
    }

    /**
     * Toggle subtask status
     */
    public function toggleSubtask(int $subtaskId): void
    {
        try {
            $subtask = $this->task->subtasks()->findOrFail($subtaskId);

            $newStatus = $subtask->status === 'completed' ? 'pending' : 'completed';
            $subtask->update([
                'status' => $newStatus,
                'completed_at' => $newStatus === 'completed' ? now() : null,
            ]);

            // Refresh task relationship without full reload
            $this->task->load('subtasks.assignments.user');

            $this->dispatch('task-updated', taskId: $this->taskId);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al actualizar la subtarea', type: 'error');
            \Log::error('Error toggling subtask: ' . $e->getMessage());
        }
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'low' => 'neutral',
            'medium' => 'blue',
            'high' => 'orange',
            'critical' => 'red',
            default => 'neutral',
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'critical' => 'Crítica',
            default => 'Media',
        };
    }

    /**
     * Format date for display
     */
    public function formatDate(?string $date): string
    {
        if (!$date) {
            return 'Sin fecha';
        }

        try {
            return \Carbon\Carbon::parse($date)->translatedFormat('d M Y');
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->task && $this->task->is_overdue;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.tasks.task-detail-dialog');
    }
}
