<?php

namespace App\Models;

use Database\Factories\ClasseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['nom'])]
class Classe extends Model
{
    /** @use HasFactory<ClasseFactory> */
    use HasFactory;

    /**
     * The voyages concerning this classe.
     */
    public function voyages(): BelongsToMany
    {
        return $this->belongsToMany(Voyage::class);
    }
}
