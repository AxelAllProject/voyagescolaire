<x-layouts.app :title="$voyage->titre">
    <x-slot:header>
        <x-page-header
            :title="$voyage->titre"
            :breadcrumbs="['Voyages' => route('voyages.index'), $voyage->titre => null]"
        >
            <x-slot:subtitle>
                <i class="bi bi-geo-alt-fill"></i> {{ $voyage->destination }}
            </x-slot:subtitle>
            <x-slot:actions>
                <a href="{{ route('voyages.edit', $voyage) }}" class="btn btn-light">
                    <i class="bi bi-pencil me-1"></i> Modifier
                </a>
                <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#supprimer-voyage">
                    <i class="bi bi-trash3 me-1"></i> Supprimer
                </button>
            </x-slot:actions>
        </x-page-header>
    </x-slot:header>

    <div class="row g-4">
        <div class="col-lg-8">
            <article class="section-card overflow-hidden mb-4">
                <div class="vs-cover">
                    <x-voyage-cover :voyage="$voyage" :variant="$voyage->id" />
                    <x-voyage-statut :voyage="$voyage" class="position-absolute top-0 end-0 m-3 shadow-sm fs-6" />
                </div>
                <div class="p-4 p-md-5">
                    <h2 class="h5 fw-bold mb-3">Programme & objectifs pédagogiques</h2>
                    <p class="text-secondary mb-0" style="white-space: pre-line">{{ $voyage->description }}</p>
                </div>
            </article>

            <div class="row g-4">
                <div class="col-md-6">
                    <section class="section-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon"><i class="bi bi-mortarboard"></i></div>
                            <h2 class="h6 fw-bold mb-0">Classes concernées</h2>
                        </div>
                        @forelse ($voyage->classes as $classe)
                            <span class="badge rounded-pill text-bg-light border fs-6 fw-semibold me-1 mb-2">{{ $classe->nom }}</span>
                        @empty
                            <p class="small text-secondary mb-0">Aucune classe associée. <a href="{{ route('voyages.edit', $voyage) }}">Ajouter des classes</a></p>
                        @endforelse
                    </section>
                </div>
                <div class="col-md-6">
                    <section class="section-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon"><i class="bi bi-folder-check"></i></div>
                            <h2 class="h6 fw-bold mb-0">Pièces à fournir</h2>
                        </div>
                        @if ($voyage->piecesAdministratives->isNotEmpty())
                            <ul class="list-unstyled mb-0 d-grid gap-2">
                                @foreach ($voyage->piecesAdministratives as $piece)
                                    <li class="d-flex gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                        <span>
                                            <span class="fw-semibold d-block">{{ $piece->nom }}</span>
                                            @if ($piece->description)
                                                <span class="small text-secondary">{{ $piece->description }}</span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="small text-secondary mb-0">Aucune pièce demandée. <a href="{{ route('voyages.edit', $voyage) }}">Ajouter des pièces</a></p>
                        @endif
                    </section>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <section class="section-card p-4 mb-4">
                    <h2 class="h6 fw-bold mb-3">En bref</h2>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="vs-stat">
                                <div class="vs-stat-label">Départ</div>
                                <div class="vs-stat-value fs-6">{{ $voyage->date_debut->translatedFormat('j M Y') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="vs-stat">
                                <div class="vs-stat-label">Retour</div>
                                <div class="vs-stat-value fs-6">{{ $voyage->date_fin->translatedFormat('j M Y') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="vs-stat">
                                <div class="vs-stat-label">Durée</div>
                                <div class="vs-stat-value">{{ $voyage->dureeEnJours() }} {{ $voyage->dureeEnJours() > 1 ? 'jours' : 'jour' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="vs-stat">
                                <div class="vs-stat-label">Places</div>
                                <div class="vs-stat-value">{{ $voyage->nb_places }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="vs-stat">
                                <div class="vs-stat-label">Transport</div>
                                <div class="vs-stat-value fs-6"><i class="bi bi-bus-front text-primary me-1"></i> {{ $voyage->transport }}</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="section-card p-4">
                    <h2 class="h6 fw-bold mb-3">Budget par élève</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-secondary">Coût total</span>
                            <span class="fw-semibold">{{ number_format($voyage->prix, 2, ',', ' ') }} €</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-secondary">Part de l'établissement</span>
                            <span class="fw-semibold">{{ number_format($voyage->partEtablissement(), 2, ',', ' ') }} €</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Participation familles</span>
                            <span class="fs-4 fw-bold text-primary">{{ number_format($voyage->participation_familiale, 2, ',', ' ') }} €</span>
                        </li>
                    </ul>
                    <p class="small text-secondary mb-0 mt-2">
                        Budget global estimé : <strong>{{ number_format($voyage->prix * $voyage->nb_places, 0, ',', ' ') }} €</strong>
                    </p>
                </section>
            </div>
        </div>
    </div>

    <x-confirm-delete
        id="supprimer-voyage"
        :action="route('voyages.destroy', $voyage)"
        title="Supprimer ce voyage ?"
        :message="'Le voyage « '.$voyage->titre.' » sera définitivement supprimé.'"
    />
</x-layouts.app>
