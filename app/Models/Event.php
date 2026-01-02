<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'category',
        'capacity',
        'max_attendees',
        'image_url',
        'status',
        'visibility',
        'invite_code',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
     * Get the organizer of the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get all tickets for this event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the sessions (schedule) for this event.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get the check-ins for the event.
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(EventCheckin::class);
    }

    /**
     * Get total registered attendees count.
     */
    public function getTotalRegisteredCount(): int
    {
        return $this->registrations()->count();
    }

    /**
     * Get total checked-in attendees count.
     */
    public function getTotalCheckedInCount(): int
    {
        return $this->checkins()->count();
    }

    /**
     * Get attendees who are registered but not checked in.
     */
    public function getRegisteredNotCheckedIn()
    {
        return $this->registrations()
            ->whereNotIn('attendee_id', function ($query) {
                $query->select('attendee_id')->from('event_checkins')->where('event_id', $this->id);
            })
            ->get();
    }

    /**
     * Get the count of active registrations.
     */
    public function activeRegistrationsCount(): int
    {
        return $this->registrations()
            ->where('status', 'confirmed')
            ->count();
    }

    /**
     * Check if event has available seats.
     */
    public function hasAvailableSeats(): bool
    {
        if (!$this->max_attendees && !$this->capacity) {
            return true;
        }

        $max = $this->max_attendees ?? $this->capacity;
        return $this->activeRegistrationsCount() < $max;
    }

    /**
     * Get the tags for this event.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'event_tag');
    }

    /**
     * Get the announcements for this event.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Get the resources for this event.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(EventResource::class);
    }

    /**
     * Get all wishlists for this event.
     */
    public function wishlists(): MorphMany
    {
        return $this->morphMany(Wishlist::class, 'wishlistable');
    }

    /**
     * Get the comments for this event.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * Get all comments including replies for this event.
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the reviews for this event.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the messages for this event (group chat).
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get average rating for this event.
     */
    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? (float) $avg : 0.0;
    }

    /**
     * Generate a unique invite code.
     */
    public static function generateInviteCode(): string
    {
        return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }

    /**
     * Get shareable link.
     */
    public function getShareableLinkAttribute(): string
    {
        return route('events.show', $this);
    }
}