{{--
    Formulaire d'un voyage, partagé par la création et la modification.
    Variables : $voyage (nouveau ou existant), $classes, $piecesAdministratives, $action, $method, $submitLabel, $cancelUrl.
--}}
@php
    $transportOptions = [
        'Autocar Grand Tourisme',
        'Train (TGV / Eurostar / Intercités)',
        'Avion (Vol régulier)',
        'Ferry + Autocar',
        'Transports en commun / Mixte',
    ];
    $valeurTransport = old('transport', $voyage->transport);
    $classesCochees = array_map('intval', (array) old('classes', $voyage->classes->pluck('id')->all()));
    $piecesCochees = array_map('intval', (array) old('pieces_administratives', $voyage->piecesAdministratives->pluck('id')->all()));
    $valeurPrix = old('prix', $voyage->prix);
    $valeurParticipation = old('participation_familiale', $voyage->participation_familiale);
@endphp

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm p-4 mb-4 rounded-4" role="alert" id="error-summary">
        <div class="d-flex align-items-start gap-3">
            <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 40px; height: 40px">
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

<form action="{{ $action }}" method="POST" id="voyage-form" novalidate>
    @csrf
    @method($method)

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Étape 1 : identité et destination --}}
            <section data-reveal class="section-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                    <div class="section-icon"><i class="bi bi-globe-europe-africa"></i></div>
                    <div>
                        <span class="section-step text-uppercase text-primary fw-bold">Étape 1 sur 4</span>
                        <h2 class="h5 fw-bold mb-0">Informations générales & destination</h2>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="titre" class="form-label">Intitulé du voyage <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-bookmark-star"></i></span>
                            <input type="text" id="titre" name="titre" class="form-control @error('titre') is-invalid @enderror"
                                placeholder="Ex : Séjour linguistique et culturel à Londres" value="{{ old('titre', $voyage->titre) }}" maxlength="255" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Choisissez un titre explicite qui sera affiché aux familles et aux enseignants.</div>
                    </div>

                    <div class="col-md-7">
                        <label for="destination" class="form-label">Destination (ville, pays) <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                            <input type="text" id="destination" name="destination" class="form-control @error('destination') is-invalid @enderror"
                                placeholder="Ex : Rome, Italie" value="{{ old('destination', $voyage->destination) }}" maxlength="255" required>
                            @error('destination')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="image" class="form-label">Image d'illustration <span class="text-muted fw-normal">(optionnel)</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                            <input type="text" id="image" name="image" class="form-control @error('image') is-invalid @enderror"
                                placeholder="https://exemple.fr/photo.jpg" value="{{ old('image', $voyage->image) }}" maxlength="255">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="description" class="form-label">Programme & objectifs pédagogiques <span class="required-star">*</span></label>
                            <small class="text-muted" id="description-counter">0 caractère</small>
                        </div>
                        <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                            placeholder="Décrivez le programme jour par jour, les visites prévues, l'hébergement et les objectifs pédagogiques…" required>{{ old('description', $voyage->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Étape 2 : dates et logistique --}}
            <section data-reveal class="section-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-3 mb-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="section-icon"><i class="bi bi-calendar-range"></i></div>
                        <div>
                            <span class="section-step text-uppercase text-primary fw-bold">Étape 2 sur 4</span>
                            <h2 class="h5 fw-bold mb-0">Dates du séjour & logistique</h2>
                        </div>
                    </div>
                    <span id="duration-badge" class="badge rounded-pill text-bg-light border px-3 py-2 text-secondary">
                        <i class="bi bi-clock-history me-1"></i> Sélectionnez les dates
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="date_debut" class="form-label">Date de départ <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" id="date_debut" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                                value="{{ old('date_debut', $voyage->date_debut?->format('Y-m-d')) }}" required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="date_fin" class="form-label">Date de retour <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                            <input type="date" id="date_fin" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
                                value="{{ old('date_fin', $voyage->date_fin?->format('Y-m-d')) }}" required>
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-7">
                        <label for="transport" class="form-label">Moyen de transport principal <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-bus-front"></i></span>
                            <select id="transport" name="transport" class="form-select @error('transport') is-invalid @enderror" required>
                                <option value="" disabled @selected(empty($valeurTransport))>— Sélectionner un mode de transport —</option>
                                @foreach ($transportOptions as $option)
                                    <option value="{{ $option }}" @selected($valeurTransport === $option)>{{ $option }}</option>
                                @endforeach
                                @if (! empty($valeurTransport) && ! in_array($valeurTransport, $transportOptions, true))
                                    <option value="{{ $valeurTransport }}" selected>{{ $valeurTransport }}</option>
                                @endif
                            </select>
                            @error('transport')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="nb_places" class="form-label">Nombre de places élèves <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                            <input type="number" id="nb_places" name="nb_places" class="form-control @error('nb_places') is-invalid @enderror"
                                placeholder="Ex : 48" min="1" step="1" value="{{ old('nb_places', $voyage->nb_places) }}" required>
                            <span class="input-group-text">places</span>
                            @error('nb_places')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Étape 3 : budget --}}
            <section data-reveal class="section-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                    <div class="section-icon"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <span class="section-step text-uppercase text-primary fw-bold">Étape 3 sur 4</span>
                        <h2 class="h5 fw-bold mb-0">Budget & participation familiale</h2>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="prix" class="form-label">Coût total par élève <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-calculator"></i></span>
                            <input type="number" id="prix" name="prix" class="form-control @error('prix') is-invalid @enderror"
                                placeholder="Ex : 450.00" min="0" step="0.01" value="{{ $valeurPrix }}" required>
                            <span class="input-group-text">€</span>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Coût réel du séjour par participant, avant subventions.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="participation_familiale" class="form-label">Participation demandée aux familles <span class="required-star">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-wallet2"></i></span>
                            <input type="number" id="participation_familiale" name="participation_familiale" class="form-control @error('participation_familiale') is-invalid @enderror"
                                placeholder="Ex : 320.00" min="0" step="0.01" value="{{ $valeurParticipation }}" required>
                            <span class="input-group-text">€</span>
                            @error('participation_familiale')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Doit être inférieure ou égale au coût total par élève.</div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="p-3 rounded-3 bg-light border d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2 small text-secondary">
                                <i class="bi bi-info-circle-fill text-primary"></i>
                                <span id="subvention-text">Renseignez les montants pour calculer la part prise en charge par l'établissement.</span>
                            </div>
                            <span class="badge text-bg-primary bg-opacity-10 text-primary border border-primary-subtle" id="total-budget-badge">
                                Budget global estimé : — €
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Étape 4 : classes et pièces administratives --}}
            <section data-reveal class="section-card p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                    <div class="section-icon"><i class="bi bi-folder-check"></i></div>
                    <div>
                        <span class="section-step text-uppercase text-primary fw-bold">Étape 4 sur 4</span>
                        <h2 class="h5 fw-bold mb-0">Classes concernées & dossier administratif</h2>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label"><i class="bi bi-mortarboard me-1 text-primary"></i> Classes concernées <span class="text-muted fw-normal">(optionnel)</span></label>
                            <a href="{{ route('classes.index') }}" class="small text-decoration-none" target="_blank">Gérer les classes <i class="bi bi-box-arrow-up-right"></i></a>
                        </div>
                        @if ($classes->isNotEmpty())
                            <div class="row g-2">
                                @foreach ($classes as $classe)
                                    <div class="col-sm-6 col-md-4">
                                        <label class="choice-tile w-100">
                                            <input type="checkbox" name="classes[]" value="{{ $classe->id }}" class="form-check-input mt-1" @checked(in_array($classe->id, $classesCochees, true))>
                                            <span class="choice-title d-block small fw-semibold">{{ $classe->nom }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3 rounded-3 bg-light border text-secondary small">
                                <i class="bi bi-info-circle me-1"></i> Aucune classe enregistrée.
                                <a href="{{ route('classes.index') }}">Ajoutez des classes</a> pour pouvoir les associer au voyage.
                            </div>
                        @endif
                        @error('classes')
                            <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                        @enderror
                        @error('classes.*')
                            <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label"><i class="bi bi-file-earmark-medical me-1 text-primary"></i> Pièces à fournir par les familles <span class="text-muted fw-normal">(optionnel)</span></label>
                            <a href="{{ route('pieces-administratives.index') }}" class="small text-decoration-none" target="_blank">Gérer les pièces <i class="bi bi-box-arrow-up-right"></i></a>
                        </div>
                        @if ($piecesAdministratives->isNotEmpty())
                            <div class="row g-2">
                                @foreach ($piecesAdministratives as $piece)
                                    <div class="col-md-6">
                                        <label class="choice-tile w-100">
                                            <input type="checkbox" name="pieces_administratives[]" value="{{ $piece->id }}" class="form-check-input mt-1" @checked(in_array($piece->id, $piecesCochees, true))>
                                            <span>
                                                <span class="choice-title d-block small fw-semibold">{{ $piece->nom }}</span>
                                                @if ($piece->description)
                                                    <span class="d-block text-muted" style="font-size: 0.76rem">{{ $piece->description }}</span>
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3 rounded-3 bg-light border text-secondary small">
                                <i class="bi bi-info-circle me-1"></i> Aucune pièce administrative enregistrée.
                                <a href="{{ route('pieces-administratives.index') }}">Ajoutez des pièces</a> pour pouvoir les demander aux familles.
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

            <div data-reveal class="section-card p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="small text-secondary">
                    <i class="bi bi-lock-fill text-success me-1"></i>
                    Vos saisies sont conservées en cas d'erreur.
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <a href="{{ $cancelUrl }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> {{ $submitLabel }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Aperçu en direct --}}
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div data-reveal class="section-card overflow-hidden mb-4">
                    <div class="preview-cover">
                        <img id="preview-image" src="{{ old('image', $voyage->image) }}" alt="Aperçu du voyage" class="{{ old('image', $voyage->image) ? '' : 'd-none' }}" onerror="this.classList.add('d-none')">
                        <div class="preview-cover-overlay"></div>
                        <div class="position-relative z-1 w-100">
                            <span class="badge text-bg-light text-primary fw-semibold mb-2 shadow-sm">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                <span id="preview-destination">{{ old('destination', $voyage->destination) ?: 'Destination à définir' }}</span>
                            </span>
                            <h3 class="h5 fw-bold text-white mb-0 text-truncate" id="preview-titre">{{ old('titre', $voyage->titre) ?: 'Titre de votre voyage scolaire' }}</h3>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-between small text-muted mb-3 pb-3 border-bottom">
                            <span><i class="bi bi-calendar3 text-primary me-1"></i> <span id="preview-dates">Dates non renseignées</span></span>
                            <span class="badge text-bg-secondary bg-opacity-10 text-secondary" id="preview-places">— places</span>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 rounded-3 bg-light border text-center">
                                    <small class="text-muted d-block" style="font-size: 0.72rem">Coût réel / élève</small>
                                    <span class="fw-bold text-dark" id="preview-prix">— €</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded-3 bg-primary bg-opacity-10 border border-primary-subtle text-center">
                                    <small class="text-primary d-block" style="font-size: 0.72rem">Part famille</small>
                                    <span class="fw-bold text-primary" id="preview-participation">— €</span>
                                </div>
                            </div>
                        </div>

                        <div class="small text-secondary mb-0">
                            <i class="bi bi-truck-front text-primary me-1"></i>
                            Transport : <strong id="preview-transport">{{ $valeurTransport ?: 'Non sélectionné' }}</strong>
                        </div>
                    </div>
                </div>

                <div data-reveal class="section-card p-4">
                    <h3 class="h6 fw-bold d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-lightbulb-fill text-warning"></i> Conseils de saisie
                    </h3>
                    <ul class="list-unstyled small text-secondary mb-0 d-grid gap-2">
                        <li class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span><strong>Dates :</strong> la date de retour doit être postérieure ou égale à la date de départ.</span>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span><strong>Budget :</strong> la participation des familles ne peut pas dépasser le coût réel par élève.</span>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span><strong>Dossier :</strong> cochez les pièces que les familles devront fournir avant le départ.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const champ = (id) => document.getElementById(id);
            const euros = (n) => n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

            const titre = champ('titre');
            const destination = champ('destination');
            const image = champ('image');
            const description = champ('description');
            const dateDebut = champ('date_debut');
            const dateFin = champ('date_fin');
            const transport = champ('transport');
            const nbPlaces = champ('nb_places');
            const prix = champ('prix');
            const participation = champ('participation_familiale');

            function majCompteur() {
                const len = (description.value || '').length;
                champ('description-counter').textContent = len + (len > 1 ? ' caractères' : ' caractère');
            }

            function majDates() {
                const badge = champ('duration-badge');

                if (! dateDebut.value || ! dateFin.value) {
                    champ('preview-dates').textContent = 'Dates non renseignées';
                    badge.className = 'badge rounded-pill text-bg-light border px-3 py-2 text-secondary';
                    badge.innerHTML = '<i class="bi bi-clock-history me-1"></i> Sélectionnez les dates';
                    return;
                }

                const debut = new Date(dateDebut.value);
                const fin = new Date(dateFin.value);
                const jours = Math.round((fin - debut) / 86400000) + 1;
                const court = (d) => d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });

                champ('preview-dates').textContent = court(debut) + ' → ' + court(fin);

                if (jours >= 1) {
                    const nuits = jours - 1;
                    badge.className = 'badge rounded-pill text-bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2';
                    badge.innerHTML = '<i class="bi bi-calendar2-check me-1"></i> Durée : ' + jours + (jours > 1 ? ' jours' : ' jour') + ' / ' + nuits + (nuits > 1 ? ' nuits' : ' nuit');
                } else {
                    badge.className = 'badge rounded-pill text-bg-danger bg-opacity-10 text-danger border border-danger-subtle px-3 py-2';
                    badge.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Date de retour antérieure au départ';
                }
            }

            function majBudget() {
                const p = parseFloat(prix.value);
                const part = parseFloat(participation.value);
                const places = parseInt(nbPlaces.value, 10);

                champ('preview-prix').textContent = ! isNaN(p) && p >= 0 ? euros(p) : '— €';
                champ('preview-participation').textContent = ! isNaN(part) && part >= 0 ? euros(part) : '— €';
                champ('preview-places').textContent = ! isNaN(places) && places > 0 ? places + ' places' : '— places';
                champ('total-budget-badge').textContent = 'Budget global estimé : ' + (! isNaN(p) && places > 0 ? euros(p * places) : '— €');

                if (! isNaN(p) && ! isNaN(part)) {
                    champ('subvention-text').innerHTML = p - part >= 0
                        ? 'Part prise en charge par l\'établissement : <strong>' + euros(p - part) + ' / élève</strong>'
                        : '<span class="text-danger fw-semibold">Attention : la participation familiale dépasse le coût réel par élève.</span>';
                }
            }

            titre.addEventListener('input', () => champ('preview-titre').textContent = titre.value.trim() || 'Titre de votre voyage scolaire');
            destination.addEventListener('input', () => champ('preview-destination').textContent = destination.value.trim() || 'Destination à définir');
            image.addEventListener('input', () => {
                const apercu = champ('preview-image');
                apercu.src = image.value.trim();
                apercu.classList.toggle('d-none', ! image.value.trim());
            });
            transport.addEventListener('change', () => champ('preview-transport').textContent = transport.value || 'Non sélectionné');
            description.addEventListener('input', majCompteur);
            dateDebut.addEventListener('change', majDates);
            dateFin.addEventListener('change', majDates);
            [nbPlaces, prix, participation].forEach((el) => el.addEventListener('input', majBudget));

            majCompteur();
            majDates();
            majBudget();
        });
    </script>
@endpush
