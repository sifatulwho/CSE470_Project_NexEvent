<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventRegistration extends Model
{
    use HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'attendee_id',
        'status',
        'registered_at',
        'cancelled_at',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the event this registration belongs to.
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'checked_in_at' => 'datetime',
        ];
    }

    /**
     * Get the event.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the attendee (user) of this registration.
     * Get the attendee.
     */
    public function attendee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }

    /**
     * Get the ticket for this registration.
     */
    public function ticket(): HasOne
    {
        // ticket table stores foreign key as `registration_id`
        return $this->hasOne(Ticket::class, 'registration_id');
    }

    /**
     * Cancel this registration.
     */
    public function cancel(string $reason = null): bool
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'notes' => $reason,
        ]);

        return true;
    }

    /**
     * Check if registration is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if registration is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
