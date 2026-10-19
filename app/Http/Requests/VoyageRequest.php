<?php

namespace App\Http\Requests;

use App\Models\Voyage;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation d'un voyage, partagée par le site (formulaires) et l'API.
 *
 * En PATCH, seuls les champs envoyés sont validés ; en POST et PUT, tous les champs obligatoires doivent être présents.
 */
class VoyageRequest extends FormRequest
{
    /**
     * Creating needs the "create" permission, updating the "update" permission on the voyage.
     */
    public function authorize(): bool
    {
        $voyage = $this->route('voyage');

        return $voyage
            ? (bool) $this->user()?->can('update', $voyage)
            : (bool) $this->user()?->can('create', Voyage::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $required = $this->isMethod('patch') ? 'sometimes' : 'required';

        // Sans prix dans la requête (PATCH partiel), on compare au prix déjà enregistré.
        $voyage = $this->route('voyage');
        $plafondParticipation = ! $this->has('prix') && $voyage ? 'lte:'.$voyage->prix : 'lte:prix';

        return [
            'titre' => [$required, 'string', 'max:255'],
            'description' => [$required, 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'destination' => [$required, 'string', 'max:255'],
            'date_debut' => [$required, 'date'],
            'date_fin' => [$required, 'date', 'after_or_equal:date_debut'],
            'prix' => [$required, 'numeric', 'min:0'],
            'participation_familiale' => [$required, 'numeric', 'min:0', $plafondParticipation],
            'nb_places' => [$required, 'integer', 'min:1'],
            'transport' => [$required, 'string', 'max:255'],
            'classes' => ['sometimes', 'array'],
            'classes.*' => ['integer', 'exists:classes,id'],
            'pieces_administratives' => ['sometimes', 'array'],
            'pieces_administratives.*' => ['integer', 'exists:pieces_administratives,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre du voyage est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du voyage et de son programme est obligatoire.',
            'image.max' => 'L\'URL ou le chemin de l\'image ne peut pas dépasser 255 caractères.',
            'destination.required' => 'La destination du voyage est obligatoire.',
            'destination.max' => 'La destination ne peut pas dépasser 255 caractères.',
            'date_debut.required' => 'La date de départ est obligatoire.',
            'date_debut.date' => 'Veuillez saisir une date de départ valide.',
            'date_fin.required' => 'La date de retour est obligatoire.',
            'date_fin.date' => 'Veuillez saisir une date de retour valide.',
            'date_fin.after_or_equal' => 'La date de retour doit être postérieure ou égale à la date de départ.',
            'prix.required' => 'Le coût total par élève est obligatoire.',
            'prix.numeric' => 'Le coût total doit être un montant valide.',
            'prix.min' => 'Le coût total ne peut pas être négatif.',
            'participation_familiale.required' => 'La participation familiale est obligatoire.',
            'participation_familiale.numeric' => 'La participation familiale doit être un montant valide.',
            'participation_familiale.min' => 'La participation familiale ne peut pas être négative.',
            'participation_familiale.lte' => 'La participation familiale ne peut pas être supérieure au coût total du voyage.',
            'nb_places.required' => 'Le nombre de places disponibles est obligatoire.',
            'nb_places.integer' => 'Le nombre de places doit être un nombre entier.',
            'nb_places.min' => 'Le voyage doit proposer au moins 1 place.',
            'transport.required' => 'Veuillez sélectionner ou indiquer un moyen de transport.',
            'transport.max' => 'Le moyen de transport ne peut pas dépasser 255 caractères.',
            'classes.array' => 'Le format des classes sélectionnées est invalide.',
            'classes.*.exists' => 'L\'une des classes sélectionnées est introuvable.',
            'pieces_administratives.array' => 'Le format des pièces administratives sélectionnées est invalide.',
            'pieces_administratives.*.exists' => 'L\'une des pièces administratives sélectionnées est introuvable.',
        ];
    }

    /**
     * The voyage attributes only, without the relations.
     *
     * @return array<string, mixed>
     */
    public function voyageData(): array
    {
        return $this->safe()->except(['classes', 'pieces_administratives']);
    }
}
