<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can manage check-ins for an event.
     */
    public function manageCheckin(User $user, Event $event): bool
    {
        // Only the organizer of the event can manage check-ins
        return $user->id === $event->organizer_id || $user->hasRole(User::ROLE_ADMIN);
    }

    /**
     * Determine whether the user can view the event.
     */
    public function view(User $user, Event $event): bool
    {
        return true; // Everyone can view events
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
}
