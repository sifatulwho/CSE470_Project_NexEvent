<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventReminderLog;
use App\Notifications\EventReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminders to attendees for upcoming events (24h and 1h windows).';

    public function handle(): int
    {
        $now = Carbon::now();

        $windows = [
            ['label' => '24h', 'target' => $now->copy()->addHours(24)],
            ['label' => '1h', 'target' => $now->copy()->addHour()],
        ];

        foreach ($windows as $w) {
            $target = $w['target'];
            // find events starting within ±15 minutes of the target
            $from = $target->copy()->subMinutes(15);
            $to = $target->copy()->addMinutes(15);

            $events = Event::where('status', 'published')
                ->whereBetween('start_date', [$from, $to])
                ->get();

            foreach ($events as $event) {
                // check if reminders already sent for this window
                $already = EventReminderLog::where('event_id', $event->id)
                    ->where('when_label', $w['label'])
                    ->exists();

                if ($already) {
                    continue;
                }

                $registrations = $event->registrations()->where('status', 'confirmed')->with('attendee')->get();

                foreach ($registrations as $reg) {
                    $user = $reg->attendee;
                    try {
                        $user->notify(new EventReminder($event, $w['label']));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send reminder: ' . $e->getMessage());
                    }
                }

                EventReminderLog::create(['event_id' => $event->id, 'when_label' => $w['label'], 'sent_at' => Carbon::now()]);
                $this->info("Reminders sent for event {$event->id} ({$w['label']}).");
            }
        }

        return 0;
    }
}
