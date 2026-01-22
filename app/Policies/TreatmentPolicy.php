<?php

namespace App\Policies;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TreatmentPolicy
{
    /**
     * Admin sempre pode tudo.
     */
    public function before(User $user, string $ability): bool|null
    {
        return $user->is_admin ? true : null;
    }

    /**
     * Pode listar tratamentos.
     */
    public function viewAny(User $user): bool
    {
        // Se for perfil somente visualização e não gerente → bloqueia
        if ($user->view_people && ! $user->is_manager) {
            return false;
        }

        return true;
    }

    public function view(User $user, Treatment $treatment): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return ! $user->view_people || $user->is_manager;
    }

    public function update(User $user, Treatment $treatment): bool
    {
        return ! $user->view_people || $user->is_manager;
    }

    public function delete(User $user, Treatment $treatment): bool
    {
        return ! $user->view_people || $user->is_manager;
    }
}
