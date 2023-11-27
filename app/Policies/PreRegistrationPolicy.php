<?php

namespace App\Policies;

use App\Models\PreRegistration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PreRegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(["admin","professor"]);
    }
    public function viewMy(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PreRegistration $preRegistration): bool
    {

        return ( $preRegistration->user_id == $user->id || $preRegistration->login == $user->login);

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasRole(["admin","professor"]) ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PreRegistration $preRegistration): bool
    {
        return  $preRegistration->login == $user->login ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PreRegistration $preRegistration): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PreRegistration $preRegistration): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PreRegistration $preRegistration): bool
    {
        //
    }
}
