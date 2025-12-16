<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventCheckin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = config('nexevent.admin_email');

        if (filled($adminEmail) && User::query()->where('role', User::ROLE_ADMIN)->doesntExist()) {
            User::factory()->admin()->create([
                'name' => 'NexEvent Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
            ]);
        }

        // Create organizers and attendees
        $organizers = User::factory(5)->organizer()->create();
        $attendees = User::factory(15)->create();

        // Create events for each organizer
        foreach ($organizers as $organizer) {
            $events = Event::factory(3)->create([
                'organizer_id' => $organizer->id,
            ]);

            // Register attendees for events and create check-ins
            foreach ($events as $event) {
                $registeredAttendees = $attendees->random(rand(5, 12));
                
                foreach ($registeredAttendees as $attendee) {
                    // Create registration
                    EventRegistration::create([
                        'event_id' => $event->id,
                        'attendee_id' => $attendee->id,
                        'status' => 'registered',
                        'registered_at' => now()->subDays(rand(1, 30)),
                    ]);

                    // Randomly create check-ins (simulate 70% attendance)
                    if (rand(1, 100) <= 70) {
                        EventCheckin::create([
                            'event_id' => $event->id,
                            'attendee_id' => $attendee->id,
                            'checked_in_by' => $organizer->id,
                            'checked_in_at' => $event->start_date->subHours(rand(0, 2)),
                            'check_in_method' => 'manual',
                            'notes' => null,
                        ]);
                    }
                }
            }
        }
    }
}
