<?php

namespace App\Policies;

use App\Models\Committee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function view(User $user, Event $event): bool
    {
        return $user->id === $event->user_id ||
            $user->isAdmin() ||
            $user->committees()->where('event_id', $event->id)->exists() ||
            $user->voters()->where('event_id', $event->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->user_id || $user->isAdmin();
    }
}
