<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Speaker;
use App\Models\EventRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\RegistrationConfirmed;
use App\Notifications\EventReminder;
use Carbon\Carbon;

class ScheduleAndNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_speaker_and_session()
    {
        $organizer = User::factory()->organizer()->create();
        $event = Event::factory()->create(['organizer_id' => $organizer->id, 'status' => 'published']);

        $this->actingAs($organizer)
            ->post(route('speakers.store'), ['name' => 'Jane Doe'])
            ->assertRedirect(route('speakers.index'));

        $speaker = Speaker::first();
        $this->assertNotNull($speaker);

        $this->actingAs($organizer)
            ->post(route('events.sessions.store', $event), [
                'title' => 'Opening Keynote',
                'start_time' => Carbon::now()->format('Y-m-d H:i'),
                'end_time' => Carbon::now()->addHour()->format('Y-m-d H:i'),
                'speakers' => [$speaker->id],
            ])->assertRedirect(route('events.show', $event));

        $this->assertDatabaseHas('event_sessions', ['title' => 'Opening Keynote', 'event_id' => $event->id]);
    }

    public function test_registration_sends_confirmation_notification()
    {
        Notification::fake();

        $attendee = User::factory()->create(['role' => User::ROLE_ATTENDEE]);
        $organizer = User::factory()->organizer()->create();
        $event = Event::factory()->create(['organizer_id' => $organizer->id, 'status' => 'published']);

        $this->actingAs($attendee)
            ->post(route('registrations.store', $event))
            ->assertRedirect();

        $reg = EventRegistration::first();
        $this->assertNotNull($reg);

        Notification::assertSentTo($attendee, RegistrationConfirmed::class);
    }

    public function test_send_reminders_command_sends_notifications()
    {
        Notification::fake();

        $attendee = User::factory()->create(['role' => User::ROLE_ATTENDEE]);
        $organizer = User::factory()->organizer()->create();
        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
            'status' => 'published',
            'start_date' => Carbon::now()->addHours(24)->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->addHours(26)->format('Y-m-d H:i:s'),
        ]);

        EventRegistration::create(['event_id' => $event->id, 'attendee_id' => $attendee->id, 'status' => 'confirmed', 'registered_at' => now()]);

        $this->artisan('events:send-reminders')->assertExitCode(0);

        Notification::assertSentTo($attendee, EventReminder::class);
    }
}
