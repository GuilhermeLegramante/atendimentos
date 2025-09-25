<?php

namespace App\Policies;

use App\Models\Authorization;
use App\Models\User;

class AuthorizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Authorization $authorization): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Authorization $authorization): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Authorization $authorization): bool
    {
        return $user->is_admin;
    }

    public function restore(User $user, Authorization $authorization): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, Authorization $authorization): bool
    {
        return $user->is_admin;
    }
}
