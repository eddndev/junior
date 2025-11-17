<?php

namespace App\Console\Commands;

use App\Models\NotificationSchedule;
use App\Models\Task;
use App\Notifications\Tasks\TaskDueDateApproachingNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendTaskDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:task-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de tareas próximas a vencer (24h, 48h, 72h)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando envío de recordatorios de tareas...');

        $reminderHours = [24, 48, 72];
        $totalSent = 0;

        foreach ($reminderHours as $hours) {
            $sent = $this->sendRemindersForHours($hours);
            $totalSent += $sent;
            $this->info("  - Recordatorios de {$hours}h: {$sent} enviados");
        }

        $this->info("Total de recordatorios enviados: {$totalSent}");

        return Command::SUCCESS;
    }

    /**
     * Send reminders for tasks due in a specific number of hours.
     */
    protected function sendRemindersForHours(int $hours): int
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addHours($hours);

        // Get tasks due within the target window (with a 30-minute buffer)
        $tasks = Task::whereNotNull('due_date')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereBetween('due_date', [
                $targetTime->copy()->subMinutes(30),
                $targetTime->copy()->addMinutes(30),
            ])
            ->with(['assignments.user'])
            ->get();

        $sent = 0;

        foreach ($tasks as $task) {
            $assignedUsers = $task->assignedUsers;

            foreach ($assignedUsers as $user) {
                // Check if we already sent this reminder
                $alreadySent = NotificationSchedule::where('schedulable_type', Task::class)
                    ->where('schedulable_id', $task->id)
                    ->where('user_id', $user->id)
                    ->where('notification_type', 'task_due_date_approaching')
                    ->where('scheduled_at', '<=', $now)
                    ->where('data->hours_reminder', $hours)
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                // Send the notification
                $user->notify(new TaskDueDateApproachingNotification($task, $hours));

                // Record that we sent this reminder
                NotificationSchedule::create([
                    'schedulable_type' => Task::class,
                    'schedulable_id' => $task->id,
                    'user_id' => $user->id,
                    'notification_type' => 'task_due_date_approaching',
                    'scheduled_at' => $now,
                    'sent_at' => $now,
                    'data' => [
                        'hours_reminder' => $hours,
                        'task_title' => $task->title,
                    ],
                ]);

                $sent++;
            }
        }

        return $sent;
    }
}
