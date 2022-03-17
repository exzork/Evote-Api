<?php

namespace App\Policies;

use App\Models\Committee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommitteePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function viewAny(User $user, Event $event): bool
    {
        return $user->isAdmin() ||
            $user->id === $event->user_id ||
            $user->committees()->where('event_id', $event->id)->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Committee $committee
     * @return bool
     */
    public function view(User $user, Committee $committee): bool
    {
        return $this->viewAny($user, $committee->event);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function create(User $user, Event $event): bool
    {
        return $user->id === $event->user_id ||
            $user->committees()->where('event_id', $event->id)->where('access_level',Committee::ACCESS_ADMIN)->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Committee $committee
     * @return bool
     */
    public function update(User $user, Committee $committee): bool
    {
        return $this->create($user, $committee->event);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Committee $committee
     * @return bool
     */
    public function delete(User $user, Committee $committee): bool
    {
        return $this->create($user, $committee->event);
    }
}
