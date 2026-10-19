<?php

namespace App\Enums;

enum Role: string
{
    /** Gère tout : voyages, classes, pièces administratives et suppressions. */
    case Admin = 'admin';

    /** Crée et modifie les voyages, consulte les classes et les pièces. */
    case Personnel = 'personnel';

    public function libelle(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Personnel => 'Personnel',
        };
    }
}
