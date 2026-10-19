# Voyages Scolaires — API

API REST (Laravel 13) de gestion des voyages scolaires : voyages, classes concernées et pièces administratives à fournir.

## Installation

Prérequis : PHP 8.3+, Composer, Node.js.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

L'API est alors disponible sur `http://127.0.0.1:8000/api`.

## Tests

```bash
php artisan test
```

Les tests de l'API se trouvent dans `tests/Feature/VoyageApiTest.php`.

## Conventions

- Toutes les routes sont préfixées par `/api`.
- Les requêtes et réponses sont en JSON. Envoyez l'en-tête `Accept: application/json`.
- Les dates sont au format `AAAA-MM-JJ`.
- Les messages d'erreur sont en français (`APP_LOCALE=fr`, traductions dans `lang/fr`).

## Voyages

| Méthode     | URL                   | Action                          | Succès |
|-------------|-----------------------|---------------------------------|--------|
| `GET`       | `/api/voyages`        | Lister les voyages              | `200`  |
| `POST`      | `/api/voyages`        | Créer un voyage                 | `201`  |
| `GET`       | `/api/voyages/{id}`   | Afficher un voyage              | `200`  |
| `PUT`       | `/api/voyages/{id}`   | Remplacer un voyage (tous les champs) | `200`  |
| `PATCH`     | `/api/voyages/{id}`   | Modifier certains champs        | `200`  |
| `DELETE`    | `/api/voyages/{id}`   | Supprimer un voyage             | `204`  |

### Champs

| Champ                     | Type    | Règles                                   |
|---------------------------|---------|------------------------------------------|
| `titre`                   | texte   | obligatoire, 255 caractères max          |
| `description`             | texte   | obligatoire                              |
| `image`                   | texte   | facultatif, 255 caractères max           |
| `destination`             | texte   | obligatoire, 255 caractères max          |
| `date_debut`              | date    | obligatoire                              |
| `date_fin`                | date    | obligatoire, après ou égale à `date_debut` |
| `prix`                    | nombre  | obligatoire, ≥ 0                         |
| `participation_familiale` | nombre  | obligatoire, ≥ 0                         |
| `nb_places`               | entier  | obligatoire, ≥ 1                         |
| `transport`               | texte   | obligatoire, 255 caractères max          |

Avec `PATCH`, seuls les champs envoyés sont validés et modifiés. Avec `PUT`, tous les champs obligatoires doivent être envoyés.

### Exemple : créer un voyage

```bash
curl -X POST http://127.0.0.1:8000/api/voyages \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "titre": "Découverte de Rome",
    "description": "Visite du Colisée et du Vatican.",
    "destination": "Rome",
    "date_debut": "2027-03-01",
    "date_fin": "2027-03-05",
    "prix": 520.50,
    "participation_familiale": 200,
    "nb_places": 35,
    "transport": "car"
  }'
```

Réponse `201 Created` :

```json
{
  "id": 1,
  "titre": "Découverte de Rome",
  "description": "Visite du Colisée et du Vatican.",
  "image": null,
  "destination": "Rome",
  "date_debut": "2027-03-01T00:00:00.000000Z",
  "date_fin": "2027-03-05T00:00:00.000000Z",
  "prix": "520.50",
  "participation_familiale": "200.00",
  "nb_places": 35,
  "transport": "car",
  "created_at": "2026-10-19T10:00:00.000000Z",
  "updated_at": "2026-10-19T10:00:00.000000Z"
}
```

## Erreurs

| Code  | Cas                                  | Réponse                                             |
|-------|--------------------------------------|-----------------------------------------------------|
| `404` | Voyage ou route inexistant           | `{"message": "Ressource introuvable."}`              |
| `405` | Méthode HTTP non autorisée           | `{"message": "Méthode non autorisée"}`               |
| `422` | Données invalides                    | `message` + détail par champ dans `errors`           |

Exemple de réponse `422 Unprocessable Content` :

```json
{
  "message": "Le champ description est obligatoire. (et 6 erreurs en plus)",
  "errors": {
    "description": ["Le champ description est obligatoire."],
    "date_fin": ["Le champ date de fin doit être une date postérieure ou égale au date de début."],
    "nb_places": ["Le champ nombre de places est obligatoire."]
  }
}
```
