<?php

namespace App\Models;

use Database\Factories\VoyageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'titre',
    'description',
    'image',
    'destination',
    'date_debut',
    'date_fin',
    'prix',
    'participation_familiale',
    'nb_places',
    'transport',
])]
class Voyage extends Model
{
    /** @use HasFactory<VoyageFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'prix' => 'decimal:2',
            'participation_familiale' => 'decimal:2',
            'nb_places' => 'integer',
        ];
    }

    /**
     * The classes concerned by this voyage.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class);
    }

    /**
     * The pieces administratives required for this voyage.
     */
    public function piecesAdministratives(): BelongsToMany
    {
        return $this->belongsToMany(PieceAdministrative::class);
    }
}
