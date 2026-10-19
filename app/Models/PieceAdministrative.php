<?php

namespace App\Models;

use Database\Factories\PieceAdministrativeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Table('pieces_administratives')]
#[Fillable(['nom', 'description'])]
class PieceAdministrative extends Model
{
    /** @use HasFactory<PieceAdministrativeFactory> */
    use HasFactory;

    /**
     * The voyages requiring this piece administrative.
     */
    public function voyages(): BelongsToMany
    {
        return $this->belongsToMany(Voyage::class);
    }
}
