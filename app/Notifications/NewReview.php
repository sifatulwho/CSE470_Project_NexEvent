<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Review;

class NewReview extends Notification
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New review for your event')
            ->line($this->review->user->name . ' rated ' . $this->review->rating . ' / 5')
            ->line($this->review->title)
            ->action('View review', url(route('features.events.show', $this->review->event)));
    }

    public function toArray($notifiable)
    {
        return ['review_id' => $this->review->id, 'event_id' => $this->review->event_id];
    }
}
