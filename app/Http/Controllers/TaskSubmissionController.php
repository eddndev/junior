<?php

namespace App\Http\Controllers;

use App\Models\TaskSubmissionFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TaskSubmissionController extends Controller
{
    /**
     * Download a submission file.
     */
    public function download(TaskSubmissionFile $file): StreamedResponse
    {
        $user = auth()->user();
        $task = $file->submission->task;

        // Check if user has access to the task
        $hasAccess = $task->assignments()->where('user_id', $user->id)->exists()
            || $user->hasPermission('ver-tareas');

        if (!$hasAccess) {
            abort(403, 'No tienes permiso para descargar este archivo');
        }

        return Storage::disk('submissions')->download($file->path, $file->filename);
    }
}
