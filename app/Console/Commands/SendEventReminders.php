<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use App\Models\NotificationSchedule;
use App\Notifications\Calendar\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de eventos próximos (1 hora y 1 día antes)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando envío de recordatorios de eventos...');

        $reminderMinutes = [60, 1440]; // 1 hour, 1 day
        $totalSent = 0;

        foreach ($reminderMinutes as $minutes) {
            $sent = $this->sendRemindersForMinutes($minutes);
            $totalSent += $sent;
            $label = $minutes === 60 ? '1 hora' : '1 día';
            $this->info("  - Recordatorios de {$label}: {$sent} enviados");
        }

        $this->info("Total de recordatorios enviados: {$totalSent}");

        return Command::SUCCESS;
    }

    /**
     * Send reminders for events starting in a specific number of minutes.
     */
    protected function sendRemindersForMinutes(int $minutes): int
    {
        $now = Carbon::now();
        $targetDate = $now->copy()->addMinutes($minutes);

        // Get events starting within the target window (with a 15-minute buffer)
        $events = CalendarEvent::where('status', 'scheduled')
            ->where('start_date', $targetDate->toDateString())
            ->when(!empty($events), function ($query) use ($targetDate) {
                // For events with specific times
                return $query->where(function ($q) use ($targetDate) {
                    $q->where('is_all_day', true)
                      ->orWhereBetween('start_time', [
                          $targetDate->copy()->subMinutes(15)->format('H:i:s'),
                          $targetDate->copy()->addMinutes(15)->format('H:i:s'),
                      ]);
                });
            })
            ->with('users')
            ->get();

        // More precise filtering for timed events
        $filteredEvents = $events->filter(function ($event) use ($targetDate, $minutes) {
            if ($event->is_all_day) {
                // For all-day events, only send 1-day reminder
                return $minutes === 1440;
            }

            // Calculate actual start datetime
            $eventStart = Carbon::parse($event->start_date->toDateString() . ' ' . $event->start_time);
            $diffMinutes = Carbon::now()->diffInMinutes($eventStart, false);

            // Check if within 15 minutes of target reminder time
            return abs($diffMinutes - $minutes) <= 15;
        });

        $sent = 0;

        foreach ($filteredEvents as $event) {
            $participants = $event->users;

            foreach ($participants as $user) {
                // Check if we already sent this reminder
                $alreadySent = NotificationSchedule::where('schedulable_type', CalendarEvent::class)
                    ->where('schedulable_id', $event->id)
                    ->where('user_id', $user->id)
                    ->where('notification_type', 'event_reminder')
                    ->where('data->minutes_reminder', $minutes)
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                // Send the notification
                $user->notify(new EventReminderNotification($event, $minutes));

                // Record that we sent this reminder
                NotificationSchedule::create([
                    'schedulable_type' => CalendarEvent::class,
                    'schedulable_id' => $event->id,
                    'user_id' => $user->id,
                    'notification_type' => 'event_reminder',
                    'scheduled_at' => $now,
                    'sent_at' => $now,
                    'data' => [
                        'minutes_reminder' => $minutes,
                        'event_title' => $event->title,
                    ],
                ]);

                $sent++;
            }
        }

        return $sent;
    }
}
