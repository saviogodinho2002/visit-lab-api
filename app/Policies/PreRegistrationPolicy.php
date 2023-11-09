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
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PreRegistration $preRegistration): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasRole("admin") ? Response::allow()
            : Response:: denyWithStatus(403,'Você não pode criar pré registros');;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PreRegistration $preRegistration): bool
    {
        //
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
