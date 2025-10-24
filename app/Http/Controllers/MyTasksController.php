<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Task;
use Illuminate\Http\Request;

/**
 * MyTasksController
 *
 * Personal task dashboard for employees.
 * Displays only tasks assigned to the authenticated user.
 */
class MyTasksController extends Controller
{
    /**
     * Display the authenticated user's personal task dashboard.
     *
     * Shows all tasks assigned to the current user with filtering and metrics.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Build query for user's assigned tasks with eager loading
        $query = Task::with(['area', 'parentTask', 'assignments.user', 'subtasks'])
            ->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by area
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter overdue tasks
        if ($request->filled('overdue') && $request->overdue === 'true') {
            $query->overdue();
        }

        // Group by due date (optional filter)
        $groupBy = $request->get('group_by', null);

        // Order by priority and due date
        $query->orderByRaw("FIELD(status, 'in_progress', 'pending', 'completed', 'cancelled')")
              ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
              ->orderBy('due_date', 'asc')
              ->orderBy('created_at', 'desc');

        // Get tasks
        $tasks = $query->get();

        // Group tasks if requested
        $groupedTasks = null;
        if ($groupBy === 'due_date') {
            $groupedTasks = $this->groupTasksByDueDate($tasks);
        }

        // Calculate personal metrics
        $metrics = [
            'total_assigned' => Task::whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),

            'pending' => Task::whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),

            'in_progress' => Task::whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'in_progress')->count(),

            'completed_today' => Task::whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count(),

            'overdue' => Task::whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->overdue()->count(),

            'completed_this_month' => Task::whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count(),
        ];

        // Calculate completion rate (percentage)
        $totalTasks = $metrics['total_assigned'];
        $completedTasks = Task::whereHas('assignments', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'completed')->count();

        $metrics['completion_rate'] = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 1)
            : 0;

        // Get areas for filter dropdown
        $areas = $user->areas()->where('is_active', true)->orderBy('name')->get();

        return view('my-tasks.index', compact('tasks', 'groupedTasks', 'metrics', 'areas', 'groupBy'));
    }

    /**
     * Display Kanban board view for authenticated user's tasks.
     *
     * Shows tasks grouped by status in a drag-and-drop kanban board.
     */
    public function kanban(Request $request)
    {
        $user = auth()->user();

        // Build query for user's assigned tasks with eager loading
        $query = Task::with(['area', 'parentTask', 'assignments.user', 'subtasks'])
            ->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by area
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        // Order by priority and due date
        $query->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
              ->orderBy('due_date', 'asc')
              ->orderBy('created_at', 'desc');

        // Get all tasks and group by status
        $allTasks = $query->get();

        $tasksByStatus = [
            'pending' => $allTasks->where('status', 'pending'),
            'in_progress' => $allTasks->where('status', 'in_progress'),
            'completed' => $allTasks->where('status', 'completed'),
            'cancelled' => $allTasks->where('status', 'cancelled'),
        ];

        // Get areas for filter dropdown
        $areas = $user->areas()->where('is_active', true)->orderBy('name')->get();

        return view('my-tasks.kanban', compact('tasksByStatus', 'areas'));
    }

    /**
     * Quick action to mark a task as completed.
     *
     * Used for quick completion from the personal dashboard.
     */
    public function complete(Task $task)
    {
        // Authorization: user must be assigned to this task
        $user = auth()->user();

        $isAssigned = $task->assignments()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Solo puedes completar tareas asignadas a ti.');
        }

        // Mark task as completed
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Tarea completada exitosamente.');
    }

    /**
     * Quick action to change task status.
     *
     * Employees can mark tasks as "in progress" or revert to "pending".
     */
    public function updateStatus(Request $request, Task $task)
    {
        // Authorization: user must be assigned to this task
        $user = auth()->user();

        $isAssigned = $task->assignments()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Solo puedes cambiar el estado de tareas asignadas a ti.');
        }

        // Validate status
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
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
            ->with('success', 'Estado actualizado exitosamente.');
    }

    /**
     * Group tasks by due date categories.
     *
     * Categories: Overdue, Today, This Week, Later, No Due Date
     */
    private function groupTasksByDueDate($tasks)
    {
        $grouped = [
            'overdue' => collect(),
            'today' => collect(),
            'this_week' => collect(),
            'later' => collect(),
            'no_due_date' => collect(),
        ];

        foreach ($tasks as $task) {
            if (!$task->due_date) {
                $grouped['no_due_date']->push($task);
                continue;
            }

            $dueDate = $task->due_date;
            $now = now();

            if ($task->is_overdue) {
                $grouped['overdue']->push($task);
            } elseif ($dueDate->isToday()) {
                $grouped['today']->push($task);
            } elseif ($dueDate->isCurrentWeek()) {
                $grouped['this_week']->push($task);
            } else {
                $grouped['later']->push($task);
            }
        }

        // Remove empty groups
        return collect($grouped)->filter(function ($group) {
            return $group->isNotEmpty();
        });
    }
}