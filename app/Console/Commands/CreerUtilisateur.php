<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreerUtilisateur extends Command
{
    protected $signature = 'utilisateur:creer
        {--nom= : Nom affiché}
        {--email= : Adresse email de connexion}
        {--role= : admin ou personnel}
        {--mot-de-passe= : Mot de passe (sinon demandé de façon masquée)}';

    protected $description = 'Crée un compte pour un membre du personnel (pas d\'inscription publique sur le site)';

    public function handle(): int
    {
        $donnees = [
            'name' => $this->option('nom') ?? text('Nom', required: true),
            'email' => strtolower($this->option('email') ?? text('Email', required: true)),
            'role' => $this->option('role') ?? select('Rôle', [Role::Personnel->value => 'Personnel', Role::Admin->value => 'Administrateur']),
            'password' => $this->option('mot-de-passe') ?? password('Mot de passe', required: true),
        ];

        $validateur = Validator::make($donnees, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:'.implode(',', array_column(Role::cases(), 'value'))],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validateur->fails()) {
            foreach ($validateur->errors()->all() as $erreur) {
                $this->components->error($erreur);
            }

            return self::FAILURE;
        }

        // Créé par un administrateur : le compte est validé d'office.
        $user = User::create($donnees);
        $user->valider();

        $this->components->info("Compte créé : {$user->email} ({$user->role->libelle()}).");

        return self::SUCCESS;
    }
}
