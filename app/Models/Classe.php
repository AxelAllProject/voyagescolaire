<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['nom'])]
class Classe extends Model
{
    /**
     * The voyages concerning this classe.
     */
    public function voyages(): BelongsToMany
    {
        return $this->belongsToMany(Voyage::class);
    }
}
