<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the event.
     */
    public function view(?User $user, Event $event): bool
    {
        // Check visibility
        if ($event->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($event->visibility === 'private') {
            // Private events can only be viewed by organizer, admin, or registered attendees
            return $user->id === $event->organizer_id 
                || $user->hasRole(User::ROLE_ADMIN)
                || $event->registrations()->where('attendee_id', $user->id)->exists();
        }

        if ($event->visibility === 'invite_only') {
            // Invite-only events can be viewed with invite code or by organizer/admin/registered attendees
            return $user->id === $event->organizer_id 
                || $user->hasRole(User::ROLE_ADMIN)
                || $event->registrations()->where('attendee_id', $user->id)->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->hasRole([User::ROLE_ORGANIZER, User::ROLE_ADMIN]);
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id || $user->hasRole(User::ROLE_ADMIN);
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id || $user->hasRole(User::ROLE_ADMIN);
    }

    /**
     * Determine whether the user can manage check-ins for an event.
     */
    public function manageCheckin(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id || $user->hasRole(User::ROLE_ADMIN);
    }
}
