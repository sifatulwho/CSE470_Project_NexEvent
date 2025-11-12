<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Event extends Model
{
    use HasFactory;

    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_INVITE_ONLY = 'invite_only';

    public const VISIBILITY_OPTIONS = [
        self::VISIBILITY_PUBLIC,
        self::VISIBILITY_PRIVATE,
        self::VISIBILITY_INVITE_ONLY,
    ];

    public const CATEGORY_OPTIONS = [
        'seminar',
        'workshop',
        'concert',
        'orientation',
        'conference',
        'webinar',
        'networking',
        'other',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date',
        'venue',
        'category',
        'tags',
        'visibility',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Owner relationship.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Announcements relationship.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(EventAnnouncement::class);
    }

    /**
     * Scope events visible to the given user (or public if guest).
     */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if (!$user) {
            return $query->where('visibility', self::VISIBILITY_PUBLIC);
        }

        if ($user->hasRole(User::ROLE_ADMIN)) {
            return $query;
        }

        if ($user->hasRole(User::ROLE_ORGANIZER)) {
            return $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('visibility', self::VISIBILITY_PUBLIC)
                    ->orWhere('user_id', $user->id);
            });
        }

        return $query->where(function (Builder $builder): void {
            $builder->where('visibility', self::VISIBILITY_PUBLIC);
        });
    }

    /**
     * Normalize the tags input to an array of unique strings.
     *
     * @param  array<int, string>|string|null  $tags
     */
    public static function normalizeTags(array|string|null $tags): array
    {
        if (is_string($tags)) {
            $tags = preg_split('/[,;]+/', $tags) ?: [];
        }

        $tags = Arr::where(array_map(static fn (string $tag): string => trim(strtolower($tag)), $tags ?? []), static fn (string $tag): bool => $tag !== '');

        return array_values(array_unique($tags));
    }

    /**
     * Determine if the event is visible to the provided user.
     */
    public function isVisibleTo(?User $user): bool
    {
        if ($this->visibility === self::VISIBILITY_PUBLIC) {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }

        if ($this->user_id === $user->id) {
            return true;
        }

        return false;
    }
}

