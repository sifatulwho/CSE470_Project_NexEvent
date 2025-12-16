<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'max_attendees',
        'organizer_id',
        'image_url',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the organizer (user) of this event.
        'category',
        'capacity',
        'status',
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
     * Get all registrations for this event.
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
        if (!$this->max_attendees) {
            return true;
        }

        return $this->activeRegistrationsCount() < $this->max_attendees;
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
}
