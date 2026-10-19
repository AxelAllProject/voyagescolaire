<?php

namespace App\Policies;

use App\Models\User;

/**
 * Seul un administrateur gère les comptes, et jamais le sien (pour ne pas se retirer ses propres droits).
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->estAdmin();
    }

    public function update(User $user, User $compte): bool
    {
        return $user->estAdmin() && $user->isNot($compte);
    }

    public function delete(User $user, User $compte): bool
    {
        return $user->estAdmin() && $user->isNot($compte);
    }
}
