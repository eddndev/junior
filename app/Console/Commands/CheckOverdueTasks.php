<?php

namespace App\Console\Commands;

use App\Models\NotificationSchedule;
use App\Models\Task;
use App\Models\User;
use App\Notifications\Tasks\TaskOverdueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-overdue-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica tareas vencidas y envía notificaciones';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Verificando tareas vencidas...');

        $now = Carbon::now();

        // Get overdue tasks
        $overdueTasks = Task::whereNotNull('due_date')
            ->where('due_date', '<', $now->toDateString())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['assignments.user', 'area'])
            ->get();

        $this->info("Tareas vencidas encontradas: {$overdueTasks->count()}");

        $totalSent = 0;

        foreach ($overdueTasks as $task) {
            $sent = $this->notifyTaskOverdue($task, $now);
            $totalSent += $sent;
        }

        $this->info("Total de notificaciones enviadas: {$totalSent}");

        return Command::SUCCESS;
    }

    /**
     * Send overdue notifications for a task.
     */
    protected function notifyTaskOverdue(Task $task, Carbon $now): int
    {
        $sent = 0;
        $notifiedUserIds = [];

        // Notify assigned users
        $assignedUsers = $task->assignedUsers;

        foreach ($assignedUsers as $user) {
            if ($this->shouldNotifyUser($task, $user, $now)) {
                $user->notify(new TaskOverdueNotification($task));
                $this->recordNotificationSent($task, $user, $now);
                $notifiedUserIds[] = $user->id;
                $sent++;
            }
        }

        // Notify area director
        if ($task->area_id) {
            $areaDirectors = User::whereHas('roles', function ($query) use ($task) {
                $query->where('slug', 'director-area')
                      ->where('role_user.area_id', $task->area_id);
            })->get();

            foreach ($areaDirectors as $director) {
                if (!in_array($director->id, $notifiedUserIds) && $this->shouldNotifyUser($task, $director, $now)) {
                    $director->notify(new TaskOverdueNotification($task));
                    $this->recordNotificationSent($task, $director, $now);
                    $sent++;
                }
            }
        }

        return $sent;
    }

    /**
     * Check if we should notify this user about this overdue task.
     *
     * We only send one overdue notification per day to avoid spam.
     */
    protected function shouldNotifyUser(Task $task, User $user, Carbon $now): bool
    {
        // Check if we already sent an overdue notification today
        $sentToday = NotificationSchedule::where('schedulable_type', Task::class)
            ->where('schedulable_id', $task->id)
            ->where('user_id', $user->id)
            ->where('notification_type', 'task_overdue')
            ->whereDate('sent_at', $now->toDateString())
            ->exists();

        return !$sentToday;
    }

    /**
     * Record that we sent an overdue notification.
     */
    protected function recordNotificationSent(Task $task, User $user, Carbon $now): void
    {
        NotificationSchedule::create([
            'schedulable_type' => Task::class,
            'schedulable_id' => $task->id,
            'user_id' => $user->id,
            'notification_type' => 'task_overdue',
            'scheduled_at' => $now,
            'sent_at' => $now,
            'data' => [
                'task_title' => $task->title,
                'days_overdue' => $task->due_date ? $now->diffInDays($task->due_date) : 0,
            ],
        ]);
    }
}
