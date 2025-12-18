<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Comment;

class NewComment extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New comment on your event')
            ->line($this->comment->user->name . ' commented: ' . str($this->comment->body)->limit(120))
            ->action('View comment', url(route('features.events.show', $this->comment->event)));
    }

    public function toArray($notifiable)
    {
        return ['comment_id' => $this->comment->id, 'event_id' => $this->comment->event_id];
    }
}
