<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'valide_le' => 'datetime',
        ];
    }

    public function estAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    /**
     * Whether an administrator has approved the account (always true for accounts created by an admin).
     */
    public function estValide(): bool
    {
        return $this->valide_le !== null;
    }

    public function valider(): void
    {
        // valide_le n'est pas « fillable » : un formulaire d'inscription ne peut pas s'auto-valider.
        $this->forceFill(['valide_le' => now()])->save();
    }

    /**
     * Accounts waiting for an administrator's approval.
     */
    public function scopeEnAttente(Builder $query): void
    {
        $query->whereNull('valide_le');
    }
}
