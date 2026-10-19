<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un voyage scolaire — EduVoyage</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-primary-dark: #1d4ed8;
            --brand-primary-soft: #eff6ff;
            --brand-secondary: #0f172a;
            --surface-bg: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .navbar-edu {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        /* Hero Banner */
        .page-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #fff;
            padding: 2.5rem 0 4.5rem;
            position: relative;
            overflow: hidden;
        }

        .page-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.22) 0%, rgba(59, 130, 246, 0) 70%);
            pointer-events: none;
        }

        .main-content-wrapper {
            margin-top: -2.75rem;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        /* Form Section Cards */
        .section-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .section-card:focus-within {
            border-color: #bfdbfe;
            box-shadow: 0 8px 28px -4px rgba(37, 99, 235, 0.08);
        }

        .section-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background-color: var(--brand-primary-soft);
            color: var(--brand-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* Inputs & Labels */
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #334155;
            margin-bottom: 0.45rem;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control,
        .form-select {
            border-color: #cbd5e1;
            border-radius: 0.625rem;
            padding: 0.65rem 0.9rem;
            font-size: 0.9375rem;
            color: #0f172a;
            transition: all 0.15s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
            border-radius: 0.625rem;
            font-weight: 500;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            background-color: #fffafa;
        }

        .invalid-feedback {
            font-size: 0.8125rem;
            font-weight: 500;
            margin-top: 0.35rem;
        }

        /* Selectable Pill Checkboxes */
        .choice-tile {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            cursor: pointer;
            background: #fff;
            transition: all 0.15s ease;
            user-select: none;
        }

        .choice-tile:hover {
            border-color: #93c5fd;
            background-color: #f8fafc;
        }

        .choice-tile input:checked + .choice-tile-content .choice-title {
            color: var(--brand-primary);
            font-weight: 700;
        }

        .choice-tile:has(input:checked) {
            border-color: var(--brand-primary);
            background-color: var(--brand-primary-soft);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
        }

        /* Sticky Sidebar Preview */
        .sticky-sidebar {
            position: sticky;
            top: 5.5rem;
        }

        .preview-cover {
            height: 175px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-top-left-radius: calc(1rem - 1px);
            border-top-right-radius: calc(1rem - 1px);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
        }

        .preview-cover img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.8) 0%, rgba(15, 23, 42, 0.15) 100%);
        }

        /* Buttons */
        .btn-primary-pro {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 0.65rem;
            padding: 0.75rem 1.6rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary-pro:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        /* Alert Animations */
        .alert-pro {
            border-radius: 0.875rem;
            border-width: 1px;
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-edu sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark text-decoration-none" href="{{ url('/') }}">
                <span class="brand-logo">
                    <i class="bi bi-compass-fill"></i>
                </span>
                <div>
                    <span class="d-block lh-1">EduVoyage</span>
                    <small class="text-muted fw-medium" style="font-size: 0.72rem;">Portail des Voyages Scolaires</small>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('voyages.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-collection me-1"></i> Catalogue API
                </a>
                <span class="badge rounded-pill text-bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2">
                    <i class="bi bi-plus-circle-fill me-1"></i> Nouveau dossier
                </span>
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <header class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item"><span class="text-white-50">Voyages scolaires</span></li>
                    <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Création d'un voyage</li>
                </ol>
            </nav>
            <div class="row align-items-center justify-content-between gy-3">
                <div class="col-lg-8">
                    <h1 class="h2 fw-extrabold mb-2">Créer un nouveau voyage scolaire</h1>
                    <p class="text-white-50 mb-0">
                        Renseignez les informations pédagogiques, logistiques et budgétaires du séjour. Les champs marqués d'un astérisque (<span class="text-danger fw-bold">*</span>) sont obligatoires.
                    </p>
                </div>
                <div class="col-lg-auto">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 small">
                        <i class="bi bi-shield-check text-info fs-5"></i>
                        <span>Validation & sauvegarde automatique des saisies</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="main-content-wrapper pb-5">
        <div class="container">

            <!-- FLASH MESSAGES (Success / Error / Warning) -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show alert-pro shadow-sm d-flex align-items-center gap-3 p-3 mb-4" role="alert">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h6 fw-bold mb-1 text-success-emphasis">Opération réussie !</h2>
                        <p class="mb-0 small text-success-emphasis">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show alert-pro shadow-sm d-flex align-items-center gap-3 p-3 mb-4" role="alert">
                    <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h6 fw-bold mb-1 text-danger-emphasis">Une erreur est survenue</h2>
                        <p class="mb-0 small text-danger-emphasis">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <!-- GLOBAL VALIDATION ERRORS BANNER -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show alert-pro shadow-sm p-4 mb-4" role="alert" id="error-summary">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                <h2 class="h6 fw-bold text-danger-emphasis mb-0">
                                    Le formulaire contient {{ $errors->count() }} {{ $errors->count() > 1 ? 'erreurs de saisie' : 'erreur de saisie' }}
                                </h2>
                                <span class="badge text-bg-light border text-secondary">
                                    <i class="bi bi-arrow-repeat me-1"></i> Vos données saisies ont été conservées
                                </span>
                            </div>
                            <p class="small text-danger-emphasis mb-2">
                                Veuillez vérifier et corriger les champs signalés en rouge ci-dessous avant de soumettre à nouveau :
                            </p>
                            <ul class="mb-0 ps-3 small text-danger-emphasis">
                                @foreach ($errors->all() as $error)
                                    <li class="mb-1">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                </div>
            @endif

            <!-- FORM -->
            <form action="{{ route('voyages.store') }}" method="POST" id="create-voyage-form" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Left Column: Form Steps -->
                    <div class="col-lg-8">

                        <!-- SECTION 1 : Identité & Destination -->
                        <section class="section-card p-4 p-md-5 mb-4">
                            <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                                <div class="section-icon">
                                    <i class="bi bi-globe-europe-africa"></i>
                                </div>
                                <div>
                                    <span class="text-uppercase text-primary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em;">Étape 1 sur 4</span>
                                    <h2 class="h5 fw-bold mb-0">Informations générales & Destination</h2>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Titre -->
                                <div class="col-12">
                                    <label for="titre" class="form-label">
                                        Intitulé du voyage <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-bookmark-star"></i></span>
                                        <input
                                            type="text"
                                            id="titre"
                                            name="titre"
                                            class="form-control @error('titre') is-invalid @enderror"
                                            placeholder="Ex : Séjour linguistique et culturel à Londres"
                                            value="{{ old('titre') }}"
                                            maxlength="255"
                                            required
                                        >
                                        @error('titre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Choisissez un titre explicite qui sera affiché aux familles et enseignants.</div>
                                </div>

                                <!-- Destination -->
                                <div class="col-md-7">
                                    <label for="destination" class="form-label">
                                        Destination (Ville, Pays) <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                        <input
                                            type="text"
                                            id="destination"
                                            name="destination"
                                            class="form-control @error('destination') is-invalid @enderror"
                                            placeholder="Ex : Rome, Italie"
                                            value="{{ old('destination') }}"
                                            maxlength="255"
                                            required
                                        >
                                        @error('destination')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image URL / Chemin -->
                                <div class="col-md-5">
                                    <label for="image" class="form-label">
                                        Image d'illustration <span class="text-muted fw-normal">(optionnel)</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                                        <input
                                            type="text"
                                            id="image"
                                            name="image"
                                            class="form-control @error('image') is-invalid @enderror"
                                            placeholder="https://exemple.fr/photo.jpg"
                                            value="{{ old('image') }}"
                                            maxlength="255"
                                        >
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12 mt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="description" class="form-label">
                                            Programme & Objectifs pédagogiques <span class="required-star">*</span>
                                        </label>
                                        <small class="text-muted" id="description-counter">0 caractère</small>
                                    </div>
                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="5"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Décrivez le programme jour par jour, les visites prévues, le type d'hébergement (familles hôtesses, auberge...) et les objectifs pédagogiques..."
                                        required
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <!-- SECTION 2 : Dates & Logistique -->
                        <section class="section-card p-4 p-md-5 mb-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-3 mb-4 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="section-icon">
                                        <i class="bi bi-calendar-range"></i>
                                    </div>
                                    <div>
                                        <span class="text-uppercase text-primary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em;">Étape 2 sur 4</span>
                                        <h2 class="h5 fw-bold mb-0">Dates du séjour & Logistique</h2>
                                    </div>
                                </div>
                                <span id="duration-badge" class="badge rounded-pill text-bg-light border px-3 py-2 text-secondary">
                                    <i class="bi bi-clock-history me-1"></i> Sélectionnez les dates
                                </span>
                            </div>

                            <div class="row g-3">
                                <!-- Date de début -->
                                <div class="col-md-6">
                                    <label for="date_debut" class="form-label">
                                        Date de départ <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                        <input
                                            type="date"
                                            id="date_debut"
                                            name="date_debut"
                                            class="form-control @error('date_debut') is-invalid @enderror"
                                            value="{{ old('date_debut') }}"
                                            required
                                        >
                                        @error('date_debut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date de fin -->
                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label">
                                        Date de retour <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                        <input
                                            type="date"
                                            id="date_fin"
                                            name="date_fin"
                                            class="form-control @error('date_fin') is-invalid @enderror"
                                            value="{{ old('date_fin') }}"
                                            required
                                        >
                                        @error('date_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Moyen de transport -->
                                <div class="col-md-7">
                                    <label for="transport" class="form-label">
                                        Moyen de transport principal <span class="required-star">*</span>
                                    </label>
                                    @php
                                        $transportOptions = [
                                            'Autocar Grand Tourisme',
                                            'Train (TGV / Eurostar / Intercités)',
                                            'Avion (Vol régulier)',
                                            'Ferry + Autocar',
                                            'Transports en commun / Mixte',
                                        ];
                                        $oldTransport = old('transport');
                                    @endphp
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-bus-front"></i></span>
                                        <select
                                            id="transport"
                                            name="transport"
                                            class="form-select @error('transport') is-invalid @enderror"
                                            required
                                        >
                                            <option value="" disabled @selected(empty($oldTransport))>— Sélectionner un mode de transport —</option>
                                            @foreach ($transportOptions as $option)
                                                <option value="{{ $option }}" @selected($oldTransport === $option)>{{ $option }}</option>
                                            @endforeach
                                            @if (! empty($oldTransport) && ! in_array($oldTransport, $transportOptions, true))
                                                <option value="{{ $oldTransport }}" selected>{{ $oldTransport }}</option>
                                            @endif
                                        </select>
                                        @error('transport')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nombre de places -->
                                <div class="col-md-5">
                                    <label for="nb_places" class="form-label">
                                        Nombre de places élèves <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                                        <input
                                            type="number"
                                            id="nb_places"
                                            name="nb_places"
                                            class="form-control @error('nb_places') is-invalid @enderror"
                                            placeholder="Ex : 48"
                                            min="1"
                                            step="1"
                                            value="{{ old('nb_places') }}"
                                            required
                                        >
                                        <span class="input-group-text">places</span>
                                        @error('nb_places')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- SECTION 3 : Budget & Financement -->
                        <section class="section-card p-4 p-md-5 mb-4">
                            <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                                <div class="section-icon">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div>
                                    <span class="text-uppercase text-primary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em;">Étape 3 sur 4</span>
                                    <h2 class="h5 fw-bold mb-0">Budget & Participation familiale</h2>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Prix total par élève -->
                                <div class="col-md-6">
                                    <label for="prix" class="form-label">
                                        Coût total par élève <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-calculator"></i></span>
                                        <input
                                            type="number"
                                            id="prix"
                                            name="prix"
                                            class="form-control @error('prix') is-invalid @enderror"
                                            placeholder="Ex : 450.00"
                                            min="0"
                                            step="0.01"
                                            value="{{ old('prix') }}"
                                            required
                                        >
                                        <span class="input-group-text">€</span>
                                        @error('prix')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Coût réel du séjour par participant avant subventions.</div>
                                </div>

                                <!-- Participation familiale -->
                                <div class="col-md-6">
                                    <label for="participation_familiale" class="form-label">
                                        Participation demandée aux familles <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="bi bi-wallet2"></i></span>
                                        <input
                                            type="number"
                                            id="participation_familiale"
                                            name="participation_familiale"
                                            class="form-control @error('participation_familiale') is-invalid @enderror"
                                            placeholder="Ex : 320.00"
                                            min="0"
                                            step="0.01"
                                            value="{{ old('participation_familiale') }}"
                                            required
                                        >
                                        <span class="input-group-text">€</span>
                                        @error('participation_familiale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Doit être inférieure ou égale au coût total par élève.</div>
                                </div>

                                <!-- Financial Summary Callout -->
                                <div class="col-12 mt-3">
                                    <div class="p-3 rounded-3 bg-light border d-flex flex-wrap align-items-center justify-content-between gap-2" id="budget-helper-box">
                                        <div class="d-flex align-items-center gap-2 small text-secondary">
                                            <i class="bi bi-info-circle-fill text-primary"></i>
                                            <span id="subvention-text">Renseignez les montants pour calculer la part subventionnée par l'établissement.</span>
                                        </div>
                                        <span class="badge text-bg-primary bg-opacity-10 text-primary border border-primary-subtle" id="total-budget-badge">
                                            Budget global estimé : — €
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- SECTION 4 : Classes & Pièces administratives -->
                        <section class="section-card p-4 p-md-5 mb-4">
                            <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                                <div class="section-icon">
                                    <i class="bi bi-folder-check"></i>
                                </div>
                                <div>
                                    <span class="text-uppercase text-primary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em;">Étape 4 sur 4</span>
                                    <h2 class="h5 fw-bold mb-0">Classes concernées & Dossier administratif</h2>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Classes -->
                                <div class="col-12">
                                    <label class="form-label d-block">
                                        <i class="bi bi-mortarboard me-1 text-primary"></i> Classes concernées <span class="text-muted fw-normal">(optionnel)</span>
                                    </label>
                                    @php
                                        $oldClasses = array_map('intval', (array) old('classes', []));
                                    @endphp
                                    @if ($classes->isNotEmpty())
                                        <div class="row g-2">
                                            @foreach ($classes as $classe)
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="choice-tile w-100">
                                                        <input
                                                            type="checkbox"
                                                            name="classes[]"
                                                            value="{{ $classe->id }}"
                                                            class="form-check-input mt-1"
                                                            @checked(in_array($classe->id, $oldClasses, true))
                                                        >
                                                        <span class="choice-tile-content">
                                                            <span class="choice-title d-block small fw-semibold">{{ $classe->nom }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 rounded-3 bg-light border text-secondary small">
                                            <i class="bi bi-info-circle me-1"></i> Aucune classe n'est encore enregistrée dans la base de données. Vous pourrez associer des classes ultérieurement.
                                        </div>
                                    @endif
                                    @error('classes')
                                        <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('classes.*')
                                        <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pièces administratives -->
                                <div class="col-12">
                                    <label class="form-label d-block">
                                        <i class="bi bi-file-earmark-medical me-1 text-primary"></i> Pièces administratives à fournir par les familles <span class="text-muted fw-normal">(optionnel)</span>
                                    </label>
                                    @php
                                        $oldPieces = array_map('intval', (array) old('pieces_administratives', []));
                                    @endphp
                                    @if ($piecesAdministratives->isNotEmpty())
                                        <div class="row g-2">
                                            @foreach ($piecesAdministratives as $piece)
                                                <div class="col-md-6">
                                                    <label class="choice-tile w-100">
                                                        <input
                                                            type="checkbox"
                                                            name="pieces_administratives[]"
                                                            value="{{ $piece->id }}"
                                                            class="form-check-input mt-1"
                                                            @checked(in_array($piece->id, $oldPieces, true))
                                                        >
                                                        <span class="choice-tile-content">
                                                            <span class="choice-title d-block small fw-semibold">{{ $piece->nom }}</span>
                                                            @if ($piece->description)
                                                                <span class="d-block text-muted" style="font-size: 0.76rem;">{{ $piece->description }}</span>
                                                            @endif
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 rounded-3 bg-light border text-secondary small">
                                            <i class="bi bi-info-circle me-1"></i> Aucune pièce administrative type n'est encore configurée dans la base.
                                        </div>
                                    @endif
                                    @error('pieces_administratives')
                                        <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('pieces_administratives.*')
                                        <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <!-- Action Bar -->
                        <div class="section-card p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="small text-secondary">
                                <i class="bi bi-lock-fill text-success me-1"></i>
                                Formulaire sécurisé par jeton CSRF • Saisies préservées en cas d'erreur
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-auto">
                                <a href="{{ route('voyages.create') }}" class="btn btn-outline-secondary rounded-3 px-3">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                                </a>
                                <button type="submit" class="btn btn-primary btn-primary-pro text-white">
                                    <i class="bi bi-check-lg me-1"></i> Enregistrer le voyage scolaire
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Sticky Live Preview & UX Assistant -->
                    <div class="col-lg-4">
                        <div class="sticky-sidebar">
                            <!-- Live Card Preview -->
                            <div class="section-card overflow-hidden mb-4">
                                <div class="preview-cover">
                                    <img id="preview-image" src="{{ old('image') }}" alt="Aperçu du voyage" class="{{ old('image') ? '' : 'd-none' }}" onerror="this.classList.add('d-none')">
                                    <div class="preview-cover-overlay"></div>
                                    <div class="position-relative z-1 w-100">
                                        <span class="badge text-bg-light text-primary fw-semibold mb-2 shadow-sm">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                            <span id="preview-destination">{{ old('destination') ?: 'Destination à définir' }}</span>
                                        </span>
                                        <h3 class="h5 fw-bold text-white mb-0 text-truncate" id="preview-titre">
                                            {{ old('titre') ?: 'Titre de votre voyage scolaire' }}
                                        </h3>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="d-flex align-items-center justify-content-between small text-muted mb-3 pb-3 border-bottom">
                                        <span>
                                            <i class="bi bi-calendar3 text-primary me-1"></i>
                                            <span id="preview-dates">Dates non renseignées</span>
                                        </span>
                                        <span class="badge text-bg-secondary bg-opacity-10 text-secondary" id="preview-places">
                                            {{ old('nb_places') ? old('nb_places') . ' places' : '— places' }}
                                        </span>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 bg-light border text-center">
                                                <small class="text-muted d-block" style="font-size: 0.72rem;">Coût réel / élève</small>
                                                <span class="fw-bold text-dark" id="preview-prix">
                                                    {{ old('prix') !== null && old('prix') !== '' ? number_format((float) old('prix'), 2, ',', ' ') . ' €' : '— €' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 bg-primary bg-opacity-10 border border-primary-subtle text-center">
                                                <small class="text-primary d-block" style="font-size: 0.72rem;">Part famille</small>
                                                <span class="fw-bold text-primary" id="preview-participation">
                                                    {{ old('participation_familiale') !== null && old('participation_familiale') !== '' ? number_format((float) old('participation_familiale'), 2, ',', ' ') . ' €' : '— €' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="small text-secondary mb-0">
                                        <i class="bi bi-truck-front text-primary me-1"></i>
                                        Transport : <strong id="preview-transport">{{ old('transport') ?: 'Non sélectionné' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Guide & Bonnes Pratiques Card -->
                            <div class="section-card p-4">
                                <h3 class="h6 fw-bold d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-lightbulb-fill text-warning"></i>
                                    Conseils de saisie
                                </h3>
                                <ul class="list-unstyled small text-secondary mb-0 d-grid gap-2">
                                    <li class="d-flex gap-2">
                                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                                        <span><strong>Cohérence des dates :</strong> la date de retour doit être postérieure ou égale à la date de départ.</span>
                                    </li>
                                    <li class="d-flex gap-2">
                                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                                        <span><strong>Équilibre budgétaire :</strong> la participation demandée aux familles ne peut pas excéder le coût réel par élève.</span>
                                    </li>
                                    <li class="d-flex gap-2">
                                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                                        <span><strong>Saisie conservée :</strong> en cas d'oubli ou d'erreur de validation, vos champs restent pré-remplis.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container d-flex flex-wrap justify-content-between align-items-center small text-muted">
            <span>&copy; {{ date('Y') }} EduVoyage — Gestion des Voyages Scolaires</span>
            <span>Propulsé par Laravel &amp; Bootstrap 5</span>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Interactive Live Preview & Calculators -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titreInput = document.getElementById('titre');
            const destinationInput = document.getElementById('destination');
            const imageInput = document.getElementById('image');
            const descriptionInput = document.getElementById('description');
            const dateDebutInput = document.getElementById('date_debut');
            const dateFinInput = document.getElementById('date_fin');
            const transportInput = document.getElementById('transport');
            const nbPlacesInput = document.getElementById('nb_places');
            const prixInput = document.getElementById('prix');
            const participationInput = document.getElementById('participation_familiale');

            const previewTitre = document.getElementById('preview-titre');
            const previewDestination = document.getElementById('preview-destination');
            const previewImage = document.getElementById('preview-image');
            const previewDates = document.getElementById('preview-dates');
            const previewPlaces = document.getElementById('preview-places');
            const previewPrix = document.getElementById('preview-prix');
            const previewParticipation = document.getElementById('preview-participation');
            const previewTransport = document.getElementById('preview-transport');

            const durationBadge = document.getElementById('duration-badge');
            const descriptionCounter = document.getElementById('description-counter');
            const subventionText = document.getElementById('subvention-text');
            const totalBudgetBadge = document.getElementById('total-budget-badge');

            function updateDescriptionCounter() {
                const len = (descriptionInput.value || '').length;
                descriptionCounter.textContent = len + (len > 1 ? ' caractères' : ' caractère');
            }

            function updateDatesAndDuration() {
                const startVal = dateDebutInput.value;
                const endVal = dateFinInput.value;

                if (startVal && endVal) {
                    const start = new Date(startVal);
                    const end = new Date(endVal);
                    const diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;

                    const formatShort = (d) => d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
                    previewDates.textContent = formatShort(start) + ' → ' + formatShort(end);

                    if (diffDays >= 1) {
                        const nights = Math.max(0, diffDays - 1);
                        durationBadge.className = 'badge rounded-pill text-bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2';
                        durationBadge.innerHTML = '<i class="bi bi-calendar2-check me-1"></i> Durée : ' + diffDays + (diffDays > 1 ? ' jours' : ' jour') + ' / ' + nights + (nights > 1 ? ' nuits' : ' nuit');
                    } else {
                        durationBadge.className = 'badge rounded-pill text-bg-danger bg-opacity-10 text-danger border border-danger-subtle px-3 py-2';
                        durationBadge.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Date de retour antérieure au départ';
                    }
                } else {
                    previewDates.textContent = 'Dates non renseignées';
                    durationBadge.className = 'badge rounded-pill text-bg-light border px-3 py-2 text-secondary';
                    durationBadge.innerHTML = '<i class="bi bi-clock-history me-1"></i> Sélectionnez les dates';
                }
            }

            function updateBudgetSummary() {
                const prix = parseFloat(prixInput.value);
                const part = parseFloat(participationInput.value);
                const places = parseInt(nbPlacesInput.value, 10);

                if (!isNaN(prix) && prix >= 0) {
                    previewPrix.textContent = prix.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
                } else {
                    previewPrix.textContent = '— €';
                }

                if (!isNaN(part) && part >= 0) {
                    previewParticipation.textContent = part.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
                } else {
                    previewParticipation.textContent = '— €';
                }

                if (!isNaN(prix) && !isNaN(places) && places > 0) {
                    const total = prix * places;
                    totalBudgetBadge.textContent = 'Budget global estimé : ' + total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
                } else {
                    totalBudgetBadge.textContent = 'Budget global estimé : — €';
                }

                if (!isNaN(prix) && !isNaN(part)) {
                    const diff = prix - part;
                    if (diff >= 0) {
                        subventionText.innerHTML = 'Part subventionnée par l\'établissement : <strong>' + diff.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' € / élève</strong>';
                    } else {
                        subventionText.innerHTML = '<span class="text-danger fw-semibold">Attention : la participation familiale dépasse le coût réel par élève.</span>';
                    }
                }
            }

            titreInput.addEventListener('input', () => {
                previewTitre.textContent = titreInput.value.trim() || 'Titre de votre voyage scolaire';
            });

            destinationInput.addEventListener('input', () => {
                previewDestination.textContent = destinationInput.value.trim() || 'Destination à définir';
            });

            imageInput.addEventListener('input', () => {
                const url = imageInput.value.trim();
                if (url) {
                    previewImage.src = url;
                    previewImage.classList.remove('d-none');
                } else {
                    previewImage.classList.add('d-none');
                }
            });

            descriptionInput.addEventListener('input', updateDescriptionCounter);
            dateDebutInput.addEventListener('change', updateDatesAndDuration);
            dateFinInput.addEventListener('change', updateDatesAndDuration);

            transportInput.addEventListener('change', () => {
                previewTransport.textContent = transportInput.value || 'Non sélectionné';
            });

            nbPlacesInput.addEventListener('input', () => {
                const val = parseInt(nbPlacesInput.value, 10);
                previewPlaces.textContent = (!isNaN(val) && val > 0) ? val + ' places' : '— places';
                updateBudgetSummary();
            });

            prixInput.addEventListener('input', updateBudgetSummary);
            participationInput.addEventListener('input', updateBudgetSummary);

            // Initial run to reflect old() values on page load
            updateDescriptionCounter();
            updateDatesAndDuration();
            updateBudgetSummary();
        });
    </script>
</body>
</html>
