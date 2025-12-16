<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title', 'company', 'bio', 'photo_url'
    ];

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'event_session_speaker');
    }
}
