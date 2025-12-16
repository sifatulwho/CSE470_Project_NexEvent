<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
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
}
