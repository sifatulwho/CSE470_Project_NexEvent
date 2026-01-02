<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'certificate_number',
        'file_path',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    /**
     * Get the event that the certificate is for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who received the certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the certificate file URL.
     */
    public function getFileUrlAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }
        return Storage::disk('public')->url($this->file_path);
    }
}
