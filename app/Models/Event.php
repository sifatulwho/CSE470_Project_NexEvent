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
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get all registrations for this event.
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
    }
}
