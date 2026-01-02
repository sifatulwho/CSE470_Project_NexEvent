<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class EventResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'original_filename',
    ];

    /**
     * Get the event that owns the resource.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who uploaded the resource.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all wishlists for this resource.
     */
    public function wishlists(): MorphMany
    {
        return $this->morphMany(Wishlist::class, 'wishlistable');
    }

    /**
     * Get the file URL.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
