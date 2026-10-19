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
        return $this->belongsToMany(Classe::class)->withTimestamps();
    }

    /**
     * The pieces administratives required for this voyage.
     */
    public function piecesAdministratives(): BelongsToMany
    {
        return $this->belongsToMany(PieceAdministrative::class)->withTimestamps();
    }

    /**
     * Sync the classes and pieces administratives present in the given data.
     *
     * @param  array<string, mixed>  $data
     */
    public function syncRelations(array $data): void
    {
        if (array_key_exists('classes', $data)) {
            $this->classes()->sync($data['classes'] ?? []);
        }

        if (array_key_exists('pieces_administratives', $data)) {
            $this->piecesAdministratives()->sync($data['pieces_administratives'] ?? []);
        }
    }

    /**
     * Number of days of the voyage, departure and return days included.
     */
    public function dureeEnJours(): int
    {
        return (int) $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    /**
     * Part of the cost paid by the school for each pupil.
     */
    public function partEtablissement(): float
    {
        return max(0, (float) $this->prix - (float) $this->participation_familiale);
    }

    /**
     * Status of the voyage: "a-venir", "en-cours" or "termine".
     */
    public function statut(): string
    {
        return match (true) {
            $this->date_debut->isAfter(today()) => 'a-venir',
            $this->date_fin->isBefore(today()) => 'termine',
            default => 'en-cours',
        };
    }
}
