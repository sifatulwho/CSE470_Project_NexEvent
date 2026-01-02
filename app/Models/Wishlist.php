<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wishlistable_id',
        'wishlistable_type',
    ];

    /**
     * Get the user who added this to wishlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent wishlistable model (Event or EventResource).
     */
    public function wishlistable(): MorphTo
    {
        return $this->morphTo();
    }
}
