<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\Committee;
use App\Models\Election;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CandidatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param Election $election
     * @return bool
     */
    public function viewAny(User $user, Election $election): bool
    {
        return $user->isAdmin() ||
            $user->id === $election->event->user_id ||
            $user->committees()->where('event_id', $election->event_id)->exists() ||
            $user->voters()->where('event_id', $election->event_id)->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Candidate $candidate
     * @return bool
     */
    public function view(User $user, Candidate $candidate): bool
    {
        return $user->isAdmin() ||
            $user->id === $candidate->election->event->user_id ||
            $user->committees()->where('event_id', $candidate->election->event_id)->exists() ||
            $user->voters()->where('event_id', $candidate->election->event_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Election $election
     * @return bool
     */
    public function create(User $user, Election $election): bool
    {
        return $user->committees()->where('event_id', $election->event_id)->whereIn('access_level',[Committee::ACCESS_WRITE,Committee::ACCESS_ADMIN])->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Candidate $candidate
     * @return bool
     */
    public function update(User $user, Candidate $candidate): bool
    {
        return $this->create($user, $candidate->election);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Candidate $candidate
     * @return bool
     */
    public function delete(User $user, Candidate $candidate): bool
    {
        return $this->create($user, $candidate->election);
    }
}
