<?php

namespace App\Policies;

use App\Models\User;

class RoleChangeRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }


    /**
     * Determine whether the user can create models.
     */
    public function update(User $user): bool
    {
        return $user->hasRole('admin');
    }

    
}
