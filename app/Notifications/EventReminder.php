<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification
{
    use Queueable;

    protected $event;
    protected $whenLabel;

    public function __construct(Event $event, string $whenLabel = '')
    {
        $this->event = $event;
        $this->whenLabel = $whenLabel;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $event = $this->event;

        return (new MailMessage)
            ->subject("Reminder: {$event->title} ({$this->whenLabel})")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a reminder that '{$event->title}' is coming up {$this->whenLabel}.")
            ->line('Starts at: ' . $event->start_date->format('M d, Y H:i'))
            ->action('View Event', url(route('events.show', $event)))
            ->line('See you there!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'event_reminder',
            'event_id' => $this->event->id,
            'message' => "Reminder: {$this->event->title} ({$this->whenLabel})",
        ];
    }
}
