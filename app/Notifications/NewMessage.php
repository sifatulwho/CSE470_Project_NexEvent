<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Message;

class NewMessage extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New message in chat')
            ->line($this->message->sender->name . ': ' . str($this->message->body)->limit(120))
            ->action('Open chat', url(route('features.events.show', $this->message->chat->event)));
    }

    public function toArray($notifiable)
    {
        return ['message_id' => $this->message->id, 'chat_id' => $this->message->chat_id, 'body' => $this->message->body];
    }
}
