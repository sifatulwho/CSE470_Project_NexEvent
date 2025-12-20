<?php

namespace App\Notifications;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationConfirmed extends Notification
{
    use Queueable;

    protected $registration;

    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $event = $this->registration->event;

        return (new MailMessage)
            ->subject("Registration confirmed: {$event->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You're confirmed for '{$event->title}'.")
            ->line("Date: " . $event->start_date->format('M d, Y H:i'))
            ->action('View Your Ticket', url(route('registrations.show', $this->registration)))
            ->line('Thanks for using NexEvent!');
    }

    public function toDatabase($notifiable)
    {
        $event = $this->registration->event;
        return [
            'type' => 'registration_confirmed',
            'registration_id' => $this->registration->id,
            'event_id' => $event->id,
            'message' => "Registration confirmed for: {$event->title}",
        ];
    }
}
