<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_ORGANIZER = 'organizer';
    public const ROLE_ATTENDEE = 'attendee';

    public const AVAILABLE_ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_ORGANIZER,
        self::ROLE_ATTENDEE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user has any of the provided roles.
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return in_array($this->role, $roles, true);
    }

    /**
     * Accessor for the profile photo url.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return Storage::disk('public')->url($this->profile_photo_path);
        }

        $name = urlencode($this->name);

        return "https://ui-avatars.com/api/?name={$name}&background=6366F1&color=FFFFFF";
    }

    /**
     * Get the events organized by this user.
     */
    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Get the event registrations for this user.
     */
    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'attendee_id');
    }

    /**
     * Get the check-ins for this user.
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(EventCheckin::class, 'attendee_id');
    }

    /**
     * Get the wishlist items for this user.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the messages sent by this user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by this user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the comments by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the reviews by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the certificates for this user.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the announcements created by this user.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Get the resources uploaded by this user.
     */
    public function uploadedResources(): HasMany
    {
        return $this->hasMany(EventResource::class);
    }
}
