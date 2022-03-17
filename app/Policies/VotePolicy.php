<?php

namespace App\Policies;

use App\Models\Committee;
use App\Models\Event;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotePolicy
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
        return ( $user->id === $event->user_id ||
            $user->committees()->where('event_id', $event->id)->exists() ) && $event->is_active == Event::ACTIVE;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Vote $vote
     * @return bool
     */
    public function view(User $user, Vote $vote): bool
    {
        return ($user->id === $vote->event->user_id ||
            $user->committees()->where('event_id', $vote->event->id)->exists() ) && $vote->event->is_active == Event::ACTIVE;
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
        return $user->voters()->where('event_id', $event->id)->exists() && $event->is_active === Event::ACTIVE && $event->start_date <= now() && $event->end_date >= now();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Vote $vote
     * @return bool
     */
    public function update(User $user, Vote $vote): bool
    {
        return $user->committees()->where('event_id', $vote->event->id)->whereIn('access_level',[Committee::ACCESS_WRITE,Committee::ACCESS_ADMIN])->exists() && $vote->event->end_date <= now();
    }
}
