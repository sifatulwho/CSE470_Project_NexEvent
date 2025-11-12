<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'registration_id',
        'ticket_id',
        'qr_code',
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    /**
     * Get the event this ticket belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the registration this ticket belongs to.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    /**
     * Generate a unique ticket ID.
     */
    public static function generateTicketId(): string
    {
        $prefix = 'TKT';
        $timestamp = time();
        $random = strtoupper(bin2hex(random_bytes(4)));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Generate QR code data for the ticket.
     */
    public function generateQrCode(): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($this->ticket_id);
    }

    /**
     * Mark ticket as used.
     */
    public function markAsUsed(): bool
    {
        return $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }
}
