<?php

namespace App\Policies;

use App\Models\Committee;
use App\Models\Event;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoterPolicy
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
     * @param Voter $voter
     * @return bool
     */
    public function view(User $user, Voter $voter): bool
    {
        return $user->isAdmin() ||
            $user->id === $voter->event_id ||
            $user->committees()->where('event_id', $voter->event_id)->exists();
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
        return $user->committees()->where('event_id', $event->id)->whereIn('access_level', [Committee::ACCESS_WRITE, Committee::ACCESS_ADMIN])->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Voter $voter
     * @return bool
     */
    public function update(User $user, Voter $voter): bool
    {
        return $this->create($user, $voter->event);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Voter $voter
     * @return bool
     */
    public function delete(User $user, Voter $voter): bool
    {
        return $this->create($user, $voter->event);
    }
}
