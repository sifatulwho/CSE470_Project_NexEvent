<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Session;
use App\Models\Ticket;
use App\Models\User;
use App\Models\EventRegistration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Create or reuse an organizer
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@example.com'],
            ['name' => 'Demo Organizer', 'password' => bcrypt('password'), 'role' => User::ROLE_ORGANIZER]
        );

        // Create an attendee
        $attendee = User::firstOrCreate(
            ['email' => 'attendee@example.com'],
            ['name' => 'Demo Attendee', 'password' => bcrypt('password'), 'role' => 'attendee']
        );

        // Create a published event
        $event = Event::firstOrCreate(
            ['title' => 'Demo Event'],
            [
                'description' => 'This is a demo event created by DemoSeeder.',
                'start_date' => now()->addDays(2)->setHour(10)->setMinute(0),
                'end_date' => now()->addDays(2)->setHour(16)->setMinute(0),
                'location' => 'Demo Hall',
                'max_attendees' => 100,
                'organizer_id' => $organizer->id,
                'status' => 'published',
            ]
        );

        // Create a speaker
        $speaker = Speaker::firstOrCreate(
            ['name' => 'Jane Demo'],
            ['title' => 'CTO', 'company' => 'ExampleCo', 'bio' => 'Speaker bio for demo']
        );

        // Create a session
        $session = Session::firstOrCreate(
            ['event_id' => $event->id, 'title' => 'Opening Remarks'],
            [
                'description' => 'Welcome and overview',
                'start_time' => $event->start_date->copy()->setHour(10),
                'end_time' => $event->start_date->copy()->setHour(11),
                'location' => 'Main Stage',
            ]
        );

        // Attach speaker to session if not attached
        if (!$session->speakers()->where('speaker_id', $speaker->id)->exists()) {
            $session->speakers()->attach($speaker->id);
        }

        // Create a confirmed registration for the attendee + ticket
        $registration = EventRegistration::firstOrCreate(
            ['event_id' => $event->id, 'attendee_id' => $attendee->id],
            ['status' => 'confirmed', 'registered_at' => now()]
        );

        if (!$registration->ticket) {
            $ticket = Ticket::create([
                'event_id' => $event->id,
                'registration_id' => $registration->id,
                'ticket_id' => Ticket::generateTicketId(),
            ]);
        }

        $this->command->info('Demo seeder has created organizer@example.com / attendee@example.com and a published event.');
    }
}
