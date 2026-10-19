<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['nom', 'description'])]
class PieceAdministrative extends Model
{
    /**
     * The voyages requiring this piece administrative.
     */
    public function voyages(): BelongsToMany
    {
        return $this->belongsToMany(Voyage::class);
    }
}
