<?php

namespace App\Policies;

use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaboratoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return
            $user->hasRole(["admin","professor","monitor"]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Laboratory $laboratory): bool
    {
        return
            $user->hasRole(["admin"]) ||
            ($user->hasRole(["professor"]) && ($laboratory->user_id  == $user->id));

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return
            $user->hasRole(["admin"]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Laboratory $laboratory): bool
    {
        return
            $user->hasRole(["admin"]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Laboratory $laboratory): bool
    {
        return
            $user->hasRole(["admin"]);
    }


}
