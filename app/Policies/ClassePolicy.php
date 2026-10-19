<?php

namespace App\Policies;

use App\Models\Classe;
use App\Models\User;

/**
 * Le personnel consulte les classes ; seul un administrateur les gère.
 */
class ClassePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->estAdmin();
    }

    public function update(User $user, Classe $classe): bool
    {
        return $user->estAdmin();
    }

    public function delete(User $user, Classe $classe): bool
    {
        return $user->estAdmin();
    }
}
