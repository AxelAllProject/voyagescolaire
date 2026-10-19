<?php

namespace App\Policies;

use App\Models\PieceAdministrative;
use App\Models\User;

/**
 * Le personnel consulte les pièces administratives ; seul un administrateur les gère.
 */
class PieceAdministrativePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->estAdmin();
    }

    public function update(User $user, PieceAdministrative $piece): bool
    {
        return $user->estAdmin();
    }

    public function delete(User $user, PieceAdministrative $piece): bool
    {
        return $user->estAdmin();
    }
}
