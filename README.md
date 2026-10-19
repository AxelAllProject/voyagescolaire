# EduVoyage — Application de Gestion des Voyages Scolaires

Application web et API développée avec **Laravel 13** et **Bootstrap 5.3** permettant de concevoir, planifier et administrer les voyages scolaires d'un établissement (destinations, dates, budget, classes concernées et pièces administratives requises).

---

## Fonctionnalités principales

### 1. Formulaire de création d'un voyage scolaire (`/voyages/create`)
- **Interface UI/UX moderne (Bootstrap 5.3 & Bootstrap Icons)** :
  - Découpage ergonomique en **4 étapes** :
    1. *Informations générales & Destination* (`titre`, `destination`, `image`, `description`)
    2. *Dates du séjour & Logistique* (`date_debut`, `date_fin`, `transport`, `nb_places`)
    3. *Budget & Participation familiale* (`prix`, `participation_familiale`)
    4. *Classes concernées & Dossier administratif* (`classes[]`, `pieces_administratives[]`)
  - **Aperçu temps réel (Live Preview)** en colonne latérale sticky : mise à jour instantanée de la carte du voyage, calcul automatique de la durée en jours/nuits, de la part subventionnée par l'établissement et du budget global estimé.
- **Système de gestion des erreurs de validation** :
  - Messages d'erreur personnalisés en français dans `VoyageController::store()`.
  - Bannière récapitulative globale en haut de page lorsque `$errors->any()` est vrai.
  - Mise en évidence individuelle des champs invalides via la classe Bootstrap `.is-invalid` et affichage du message sous chaque champ avec `@error('...') <div class="invalid-feedback">`.
- **Conservation des saisies (`old()`)** :
  - Toutes les valeurs saisies par l'utilisateur (`input`, `textarea`, `select`, `checkbox`) sont intégralement conservées via l'helper `old()` en cas d'erreur de validation.
- **Messages Flash (`session('success')` / `session('error')`)** :
  - Affichage d'une alerte Bootstrap dismissible confirmant l'enregistrement du voyage après redirection.

---

## Architecture & Routes

### Routes Web (`routes/web.php`)
Les routes Web bénéficient du middleware `web` (gestion de session, protection CSRF, partage automatique de `$errors` et conservation de `old()`).

| Méthode | URI | Nom de la route | Action du contrôleur | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/voyages/create` | `voyages.create` | `VoyageController@create` | Affiche le formulaire Bootstrap de création d'un voyage |
| `POST` | `/voyages` | `voyages.store` | `VoyageController@store` | Valide les données, crée le voyage et redirige avec un message flash |
| `GET` | `/voyages` | `voyages.index` | `VoyageController@index` | Liste les voyages enregistrés |
| `GET` | `/voyages/{voyage}` | `voyages.show` | `VoyageController@show` | Affiche les détails d'un voyage |
| `PUT/PATCH` | `/voyages/{voyage}` | `voyages.update` | `VoyageController@update` | Met à jour un voyage existant |
| `DELETE` | `/voyages/{voyage}` | `voyages.destroy` | `VoyageController@destroy` | Supprime un voyage |

### Routes API (`routes/api.php`)
Les routes API sont préfixées par `/api` et leurs noms par `api.voyages.*`.

---

## Règles de validation (`VoyageController::store`)

| Champ | Règles Laravel | Description |
| :--- | :--- | :--- |
| `titre` | `required \| string \| max:255` | Intitulé du voyage scolaire |
| `destination` | `required \| string \| max:255` | Ville et pays de destination |
| `image` | `nullable \| string \| max:255` | URL ou chemin de l'image d'illustration |
| `description` | `required \| string` | Programme détaillé et objectifs pédagogiques |
| `date_debut` | `required \| date` | Date de départ |
| `date_fin` | `required \| date \| after_or_equal:date_debut` | Date de retour (postérieure ou égale au départ) |
| `transport` | `required \| string \| max:255` | Moyen de transport principal |
| `nb_places` | `required \| integer \| min:1` | Capacité maximale d'élèves |
| `prix` | `required \| numeric \| min:0` | Coût réel par élève (€) |
| `participation_familiale` | `required \| numeric \| min:0 \| lte:prix` | Participation demandée aux familles (≤ coût réel) |
| `classes` | `nullable \| array` (`exists:classes,id`) | Identifiants des classes concernées |
| `pieces_administratives` | `nullable \| array` (`exists:pieces_administratives,id`) | Identifiants des pièces administratives requises |

---

## Installation & Démarrage rapide

```bash
# 1. Installer les dépendances PHP
composer install

# 2. Configurer l'environnement et la base SQLite
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate

# 3. Lancer le serveur de développement
php artisan serve
```

Accédez ensuite au formulaire sur : **[http://127.0.0.1:8000/voyages/create](http://127.0.0.1:8000/voyages/create)**

## Tests automatisés

```bash
php artisan test
```
