<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'title',
        'content',
        'is_important',
    ];

    protected function casts(): array
    {
        return [
            'is_important' => 'boolean',
        ];
    }

    /**
     * Get the event that owns the announcement.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who created the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
