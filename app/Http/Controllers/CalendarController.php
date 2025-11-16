<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\CalendarEvent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the main calendar view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $areaId = $request->get('area_id');

        $areas = Area::active()->orderBy('name')->get();

        return view('calendar.index', compact('month', 'year', 'areaId', 'areas'));
    }

    /**
     * Get calendar events as JSON for the calendar view.
     *
     * This endpoint returns both calendar events and tasks with due dates,
     * formatted for display in the calendar component.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function events(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'show_tasks' => ['nullable', 'boolean'],
        ]);

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $areaId = $request->get('area_id');
        $showTasks = $request->boolean('show_tasks', true);

        // Get calendar events
        $events = CalendarEvent::with(['area', 'creator'])
            ->inDateRange($start, $end)
            ->when($areaId, fn($q) => $q->forArea($areaId))
            ->get()
            ->filter(function ($event) {
                // Filter by authorization (user can view)
                return auth()->user()->can('view', $event);
            })
            ->map(function ($event) {
                $color = $event->isMeeting() ? '#10b981' : $event->color; // Green for meetings

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d') . ($event->start_time && !$event->is_all_day ? 'T' . $event->start_time : ''),
                    'end' => $event->end_date->format('Y-m-d') . ($event->end_time && !$event->is_all_day ? 'T' . $event->end_time : ''),
                    'allDay' => $event->is_all_day,
                    'color' => $color,
                    'type' => $event->type,
                    'status' => $event->status,
                    'location' => $event->location,
                    'area' => $event->area?->name,
                    'creator' => $event->creator?->name,
                    'url' => route('calendar.show', $event),
                    'extendedProps' => [
                        'type' => $event->type,
                        'status' => $event->status,
                        'isMeeting' => $event->isMeeting(),
                        'isEvent' => $event->isEvent(),
                    ],
                ];
            });

        $result = $events;

        // Optionally include tasks with due dates
        if ($showTasks) {
            $tasks = Task::whereNotNull('due_date')
                ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
                ->when($areaId, function ($query) use ($areaId) {
                    return $query->where('area_id', $areaId);
                })
                ->where(function ($query) {
                    // Show tasks assigned to the current user or tasks from user's areas
                    $user = auth()->user();
                    $userAreaIds = $user->areas->pluck('id')->toArray();

                    $query->whereHas('assignments', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->orWhereIn('area_id', $userAreaIds);
                })
                ->get()
                ->map(function ($task) {
                    $color = '#f59e0b'; // Amber for tasks

                    // Make overdue tasks red
                    if ($task->is_overdue) {
                        $color = '#ef4444'; // Red for overdue
                    }

                    // Make completed tasks gray
                    if ($task->status === 'completed') {
                        $color = '#6b7280'; // Gray for completed
                    }

                    return [
                        'id' => 'task-' . $task->id,
                        'title' => $task->title,
                        'start' => $task->due_date->format('Y-m-d'),
                        'allDay' => true,
                        'color' => $color,
                        'type' => 'task',
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'url' => route('tasks.show', $task),
                        'extendedProps' => [
                            'type' => 'task',
                            'status' => $task->status,
                            'priority' => $task->priority,
                            'isOverdue' => $task->is_overdue,
                        ],
                    ];
                });

            $result = $events->merge($tasks);
        }

        return response()->json($result->values());
    }

    /**
     * Display the specified calendar event.
     *
     * @param  \App\Models\CalendarEvent  $calendarEvent
     * @return \Illuminate\View\View
     */
    public function show(CalendarEvent $calendarEvent): View
    {
        $this->authorize('view', $calendarEvent);

        $calendarEvent->load([
            'area',
            'creator',
            'participants.user',
            'media',
        ]);

        return view('calendar.show', compact('calendarEvent'));
    }

    /**
     * Get upcoming events (next 7 days) for dashboard widget.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upcoming(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $limit = $request->get('limit', 5);

        $events = CalendarEvent::upcoming()
            ->limit($limit)
            ->get()
            ->filter(function ($event) {
                return auth()->user()->can('view', $event);
            })
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'type' => $event->type,
                    'start_date' => $event->start_date->format('Y-m-d'),
                    'start_time' => $event->start_time,
                    'is_all_day' => $event->is_all_day,
                    'location' => $event->location,
                    'url' => route('calendar.show', $event),
                ];
            });

        return response()->json($events->values());
    }
}
