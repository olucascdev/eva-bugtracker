<?php

namespace App\Policies;

use App\Models\Bug;
use App\Models\User;

class BugPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtered by resource/scope, but they are allowed to see the "Bugs" section
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bug $model): bool
    {
        if ($user->isAdmin() || $user->isSupport()) {
            return true;
        }

        if ($user->isClient()) {
            return $user->company_id === $model->company_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bug $model): bool
    {
        if ($user->isAdmin() || $user->isSupport()) {
            return true;
        }

        if ($user->isClient()) {
            return $user->company_id === $model->company_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bug $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bug $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bug $model): bool
    {
        return $user->isAdmin();
    }
}
