<?php

namespace App\Policies;

use App\Models\BaseLayout;
use App\Models\User;
use App\Enums\Role;

class BaseLayoutPolicy
{
    /**
     * Determine if the user can view any base layouts.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the base layout.
     */
    public function view(?User $user, BaseLayout $baseLayout): bool
    {
        return true;
    }

    /**
     * Determine if the user can create base layouts.
     */
    public function create(User $user): bool
    {
        return $user->role === Role::ADMIN || $user->role === Role::GENERAL_USER;
    }

    /**
     * Determine if the user can update the base layout.
     */
    public function update(User $user, BaseLayout $baseLayout): bool
    {
        // Admin can update any base layout
        if ($user->role === Role::ADMIN) {
            return true;
        }

        // General user can only update their own base layout
        return $user->id === $baseLayout->userId;
    }

    /**
     * Determine if the user can delete the base layout.
     */
    public function delete(User $user, BaseLayout $baseLayout): bool
    {
        // Admin can delete any base layout
        if ($user->role === Role::ADMIN) {
            return true;
        }

        // General user can only delete their own base layout
        return $user->id === $baseLayout->userId;
    }

    /**
     * Determine if the user can restore the base layout.
     */
    public function restore(User $user, BaseLayout $baseLayout): bool
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine if the user can permanently delete the base layout.
     */
    public function forceDelete(User $user, BaseLayout $baseLayout): bool
    {
        return $user->role === Role::ADMIN;
    }
}
