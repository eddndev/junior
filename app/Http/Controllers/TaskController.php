<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Area;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks with pagination, search and filters.
     *
     * Directors see tasks from their areas.
     * Employees see their assigned tasks.
     */
    public function index(Request $request)
    {
        // Authorization check
        $this->authorize('viewAny', Task::class);

        $user = auth()->user();

        // Build query with eager loading for performance
        $query = Task::with(['area', 'parentTask', 'assignments.user', 'subtasks']);

        // Directors see tasks from their areas, employees see assigned tasks
        if (!$user->hasPermission('crear-tareas')) {
            // Employee: only see assigned tasks
            $query->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($request->filled('area_id')) {
            // Director: filter by specific area if provided
            $query->where('area_id', $request->area_id);
        } elseif (!$user->hasRole('super-admin')) {
            // Director: only see tasks from their areas
            $userAreaIds = $user->areas->pluck('id')->toArray();
            $query->whereIn('area_id', $userAreaIds);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function ($q) use ($request) {
                $q->where('user_id', $request->assigned_to);
            });
        }

        // Filter overdue tasks
        if ($request->filled('overdue') && $request->overdue === 'true') {
            $query->overdue();
        }

        // Filter parent tasks only (no child tasks)
        if ($request->filled('parent_only') && $request->parent_only === 'true') {
            $query->whereNull('parent_task_id');
        }

        // Order by priority and due date
        $query->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
              ->orderBy('due_date', 'asc')
              ->orderBy('created_at', 'desc');

        // Paginate results
        $tasks = $query->paginate(15)->withQueryString();

        // Calculate metrics for dashboard
        $metrics = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::overdue()->count(),
        ];

        // Get areas and users for filters
        $areas = Area::active()->orderBy('name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'metrics', 'areas', 'users'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        // Authorization check
        $this->authorize('create', Task::class);

        $user = auth()->user();

        // Get areas the user has access to
        $areas = $user->hasRole('super-admin')
            ? Area::active()->orderBy('name')->get()
            : $user->areas()->where('is_active', true)->orderBy('name')->get();

        // Get users for assignment
        $users = User::where('is_active', true)->orderBy('name')->get();

        // Get parent tasks (for creating subtasks via parent_task_id)
        $parentTasks = Task::whereNull('parent_task_id')
            ->whereIn('area_id', $areas->pluck('id'))
            ->orderBy('title')
            ->get();

        return view('tasks.create', compact('areas', 'users', 'parentTasks'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Authorization check
        $this->authorize('create', Task::class);

        // Create the task
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'area_id' => $request->area_id,
            'parent_task_id' => $request->parent_task_id,
            'priority' => $request->priority,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
        ]);

        // Assign users to the task (polymorphic)
        if ($request->filled('assigned_users')) {
            foreach ($request->assigned_users as $userId) {
                TaskAssignment::create([
                    'assignable_type' => Task::class,
                    'assignable_id' => $task->id,
                    'user_id' => $userId,
                    'assigned_at' => now(),
                ]);
            }
        }

        // Handle file attachments if provided
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $task->addMedia($file)
                     ->toMediaCollection('attachments');
            }
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Display the specified task with all details.
     */
    public function show(Task $task)
    {
        // Authorization check
        $this->authorize('view', $task);

        // Eager load relationships
        $task->load([
            'area',
            'parentTask',
            'childTasks.assignments.user',
            'subtasks.assignments.user',
            'assignments.user',
            'media',
        ]);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        // Authorization check
        $this->authorize('update', $task);

        $user = auth()->user();

        // Eager load relationships
        $task->load(['assignments.user', 'area']);

        // Get areas the user has access to
        $areas = $user->hasRole('super-admin')
            ? Area::active()->orderBy('name')->get()
            : $user->areas()->where('is_active', true)->orderBy('name')->get();

        // Get users for assignment
        $users = User::where('is_active', true)->orderBy('name')->get();

        // Get parent tasks (for reassigning parent)
        $parentTasks = Task::whereNull('parent_task_id')
            ->where('id', '!=', $task->id) // Exclude current task
            ->whereIn('area_id', $areas->pluck('id'))
            ->orderBy('title')
            ->get();

        // Get currently assigned users
        $assignedUserIds = $task->assignments->pluck('user_id')->toArray();

        return view('tasks.edit', compact('task', 'areas', 'users', 'parentTasks', 'assignedUserIds'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        // Authorization check
        $this->authorize('update', $task);

        // Update basic task information
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'area_id' => $request->area_id,
            'parent_task_id' => $request->parent_task_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        // Sync assigned users (polymorphic)
        if ($request->has('assigned_users')) {
            // Remove all current assignments
            $task->assignments()->delete();

            // Add new assignments
            if ($request->filled('assigned_users')) {
                foreach ($request->assigned_users as $userId) {
                    TaskAssignment::create([
                        'assignable_type' => Task::class,
                        'assignable_id' => $task->id,
                        'user_id' => $userId,
                        'assigned_at' => now(),
                    ]);
                }
            }
        }

        // Handle file attachments if provided
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $task->addMedia($file)
                     ->toMediaCollection('attachments');
            }
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Soft delete the specified task.
     */
    public function destroy(Task $task)
    {
        // Authorization check
        $this->authorize('delete', $task);

        // Soft delete the task
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tarea eliminada exitosamente.');
    }

    /**
     * Mark a task as completed.
     */
    public function complete(Task $task)
    {
        // Authorization check
        $this->authorize('complete', $task);

        // Mark task as completed
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Tarea marcada como completada.');
    }

    /**
     * Update the status of a task (workflow management).
     */
    public function updateStatus(Request $request, Task $task)
    {
        // Authorization check
        $this->authorize('update', $task);

        // Validate status
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        // Update status
        $data = ['status' => $request->status];

        // If marking as completed, set completed_at timestamp
        if ($request->status === 'completed') {
            $data['completed_at'] = now();
        } else {
            // If changing from completed to another status, clear completed_at
            $data['completed_at'] = null;
        }

        $task->update($data);

        return redirect()
            ->back()
            ->with('success', 'Estado de la tarea actualizado.');
    }

    /**
     * Reassign a task to different users.
     */
    public function reassign(Request $request, Task $task)
    {
        // Authorization check
        $this->authorize('reassign', $task);

        // Validate assigned users
        $request->validate([
            'assigned_users' => 'required|array|min:1',
            'assigned_users.*' => 'exists:users,id',
        ]);

        // Remove all current assignments
        $task->assignments()->delete();

        // Add new assignments
        foreach ($request->assigned_users as $userId) {
            TaskAssignment::create([
                'assignable_type' => Task::class,
                'assignable_id' => $task->id,
                'user_id' => $userId,
                'assigned_at' => now(),
            ]);
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarea reasignada exitosamente.');
    }

    /**
     * Restore a soft deleted task.
     */
    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);

        // Authorization check
        $this->authorize('restore', $task);

        // Restore the task
        $task->restore();

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarea restaurada exitosamente.');
    }
}
