<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\TaskSubmissionFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskSubmissionManager extends Component
{
    use WithFileUploads;

    public ?Task $task = null;
    public ?TaskSubmission $submission = null;

    public $notes = '';
    public $feedback = '';
    public $uploads = [];

    public bool $showFeedbackForm = false;
    public string $feedbackAction = '';

    protected $listeners = [
        'loadTaskSubmission' => 'loadTask',
        'refreshSubmission' => '$refresh',
    ];

    public function mount(?int $taskId = null): void
    {
        if ($taskId) {
            $this->loadTask($taskId);
        }
    }

    public function loadTask(int $taskId): void
    {
        $this->task = Task::with(['submission.files', 'assignments.user'])->findOrFail($taskId);
        $this->submission = $this->task->submission;

        if ($this->submission) {
            $this->notes = $this->submission->notes ?? '';
        } else {
            $this->notes = '';
        }

        $this->uploads = [];
        $this->feedback = '';
        $this->showFeedbackForm = false;
    }

    public function updatedUploads(): void
    {
        $this->validate([
            'uploads.*' => 'file|max:10240', // 10MB max per file
        ]);

        // Ensure submission exists
        if (!$this->submission) {
            $this->submission = TaskSubmission::create([
                'task_id' => $this->task->id,
                'status' => TaskSubmission::STATUS_DRAFT,
            ]);
            $this->task->refresh();
        }

        // Save each uploaded file
        foreach ($this->uploads as $upload) {
            $path = $upload->store("task-{$this->task->id}", 'submissions');

            TaskSubmissionFile::create([
                'task_submission_id' => $this->submission->id,
                'filename' => $upload->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $upload->getMimeType(),
                'size' => $upload->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        $this->uploads = [];
        $this->submission->refresh();
    }

    public function removeFile(int $fileId): void
    {
        if (!$this->canEdit()) {
            return;
        }

        $file = TaskSubmissionFile::find($fileId);

        if ($file && $file->submission->id === $this->submission->id) {
            $file->delete(); // This also deletes from storage via model event
            $this->submission->refresh();
        }
    }

    public function downloadFile(int $fileId)
    {
        $file = TaskSubmissionFile::find($fileId);

        if ($file && $file->submission->id === $this->submission->id) {
            return Storage::disk('submissions')->download($file->path, $file->filename);
        }
    }

    public function saveNotes(): void
    {
        if (!$this->canEdit()) {
            return;
        }

        if (!$this->submission) {
            $this->submission = TaskSubmission::create([
                'task_id' => $this->task->id,
                'status' => TaskSubmission::STATUS_DRAFT,
                'notes' => $this->notes,
            ]);
        } else {
            $this->submission->update(['notes' => $this->notes]);
        }

        $this->dispatch('notify', type: 'success', message: 'Notas guardadas');
    }

    public function submit(): void
    {
        if (!$this->submission || !$this->submission->canBeSubmitted()) {
            $this->dispatch('notify', type: 'error', message: 'Debes adjuntar al menos un archivo para entregar');
            return;
        }

        if (!$this->canEdit()) {
            return;
        }

        // Save notes first
        $this->submission->update(['notes' => $this->notes]);

        // Submit
        $this->submission->submit(auth()->user());
        $this->submission->refresh();

        $this->dispatch('notify', type: 'success', message: 'Entrega realizada correctamente');
        $this->dispatch('submissionUpdated');
    }

    public function unsubmit(): void
    {
        if (!$this->submission || !$this->submission->isSubmitted()) {
            return;
        }

        if (!$this->canEdit()) {
            return;
        }

        $this->submission->update([
            'status' => TaskSubmission::STATUS_DRAFT,
            'submitted_at' => null,
            'submitted_by' => null,
        ]);

        $this->submission->refresh();
        $this->dispatch('notify', type: 'info', message: 'Entrega cancelada');
        $this->dispatch('submissionUpdated');
    }

    public function showApproveForm(): void
    {
        $this->feedbackAction = 'approve';
        $this->feedback = '';
        $this->showFeedbackForm = true;
    }

    public function showRevisionForm(): void
    {
        $this->feedbackAction = 'revision';
        $this->feedback = '';
        $this->showFeedbackForm = true;
    }

    public function showRejectForm(): void
    {
        $this->feedbackAction = 'reject';
        $this->feedback = '';
        $this->showFeedbackForm = true;
    }

    public function cancelFeedback(): void
    {
        $this->showFeedbackForm = false;
        $this->feedbackAction = '';
        $this->feedback = '';
    }

    public function submitFeedback(): void
    {
        if (!$this->canReview()) {
            return;
        }

        if (!$this->submission || !$this->submission->canBeReviewed()) {
            return;
        }

        $requiresFeedback = in_array($this->feedbackAction, ['revision', 'reject']);

        if ($requiresFeedback && empty(trim($this->feedback))) {
            $this->dispatch('notify', type: 'error', message: 'Debes proporcionar retroalimentación');
            return;
        }

        $user = auth()->user();

        match ($this->feedbackAction) {
            'approve' => $this->submission->approve($user, $this->feedback ?: null),
            'revision' => $this->submission->requestRevision($user, $this->feedback),
            'reject' => $this->submission->reject($user, $this->feedback),
        };

        $this->submission->refresh();
        $this->showFeedbackForm = false;
        $this->feedbackAction = '';
        $this->feedback = '';

        $message = match ($this->feedbackAction) {
            'approve' => 'Entrega aprobada',
            'revision' => 'Se solicitó revisión',
            'reject' => 'Entrega rechazada',
            default => 'Revisión completada',
        };

        $this->dispatch('notify', type: 'success', message: $message);
        $this->dispatch('submissionUpdated');
    }

    public function canEdit(): bool
    {
        if (!$this->task) {
            return false;
        }

        $user = auth()->user();

        // Check if user is assigned to the task
        return $this->task->assignments()->where('user_id', $user->id)->exists();
    }

    public function canReview(): bool
    {
        if (!$this->task) {
            return false;
        }

        $user = auth()->user();

        // Directors or managers of the task's area can review
        // Check if user has permission to create tasks (director/manager)
        return $user->hasPermission('crear-tareas');
    }

    public function render()
    {
        return view('livewire.tasks.task-submission-manager');
    }
}
