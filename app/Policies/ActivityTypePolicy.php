<?php

namespace App\Policies;

use App\Models\ActivityType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ($user->hasRole('admin') || $user->hasRole('normal'));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ActivityType $activityType): bool
    {
        return ($user->hasRole('admin') || $user->hasRole('normal'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return ($user->hasRole('admin') || $user->hasRole('normal'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ActivityType $activityType): bool
    {
        return ($user->hasRole('admin') || $user->hasRole('normal'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ActivityType $activityType): bool
    {
        return $user->hasRole('admin');
    }
}
