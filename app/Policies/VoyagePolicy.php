<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voyage;

/**
 * Le catalogue est public ; le personnel crée et modifie les voyages ; seul un administrateur les supprime.
 */
class VoyagePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Voyage $voyage): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Voyage $voyage): bool
    {
        return true;
    }

    public function delete(User $user, Voyage $voyage): bool
    {
        return $user->estAdmin();
    }
}
