<?php

namespace Database\Seeders;

use App\Models\PieceAdministrative;
use Illuminate\Database\Seeder;

class PieceAdministrativeSeeder extends Seeder
{
    /**
     * Seed the usual administrative documents.
     */
    public function run(): void
    {
        $pieces = [
            'Autorisation parentale' => 'Signée par les deux responsables légaux.',
            'Fiche sanitaire de liaison' => 'Allergies, traitements en cours et contacts d\'urgence.',
            'Carte nationale d\'identité ou passeport' => 'En cours de validité jusqu\'au retour.',
            'Autorisation de sortie du territoire' => 'Formulaire Cerfa n° 15646, accompagné de la copie de la pièce d\'identité du parent signataire.',
            'Carte européenne d\'assurance maladie' => 'À demander à la CPAM au moins deux semaines avant le départ.',
            'Attestation d\'assurance responsabilité civile' => null,
        ];

        foreach ($pieces as $nom => $description) {
            PieceAdministrative::firstOrCreate(['nom' => $nom], ['description' => $description]);
        }
    }
}
