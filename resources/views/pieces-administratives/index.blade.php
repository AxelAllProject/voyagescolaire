<x-layouts.app title="Pièces administratives">
    <x-slot:header>
        <x-page-header
            title="Pièces administratives"
            subtitle="Les documents que les familles peuvent avoir à fournir avant un voyage."
            :breadcrumbs="['Pièces administratives' => null]"
        />
    </x-slot:header>

    @php($peutGerer = auth()->user()->can('create', App\Models\PieceAdministrative::class))

    <div class="row g-4">
        <div class="col-lg-4 order-lg-2">
            @unless ($peutGerer)
                <x-lecture-seule>les pièces administratives</x-lecture-seule>
            @else
            <section class="section-card p-4 sticky-sidebar">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="section-icon"><i class="bi bi-plus-lg"></i></div>
                    <h2 class="h6 fw-bold mb-0">Ajouter une pièce</h2>
                </div>
                <form action="{{ route('pieces-administratives.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom <span class="required-star">*</span></label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" maxlength="255"
                            class="form-control @error('nom') is-invalid @enderror" placeholder="Ex : Fiche sanitaire de liaison" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="description" class="form-label">Description <span class="text-muted fw-normal">(optionnel)</span></label>
                    <textarea id="description" name="description" rows="3" maxlength="1000"
                        class="form-control @error('description') is-invalid @enderror" placeholder="Précisions pour les familles">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary w-100 mt-3">Ajouter</button>
                </form>
            </section>
            @endunless
        </div>

        <div class="col-lg-8 order-lg-1">
            @if ($pieces->isEmpty())
                <section class="section-card p-5 text-center">
                    <div class="vs-icon-tile mx-auto mb-3"><i class="bi bi-file-earmark-text fs-4"></i></div>
                    <h2 class="h5 fw-bold">Aucune pièce administrative</h2>
                    <p class="text-secondary mb-0">
                        {{ $peutGerer ? 'Ajoutez votre première pièce avec le formulaire.' : 'Aucune pièce n\'a encore été enregistrée.' }}
                    </p>
                </section>
            @else
                <div class="d-grid gap-3">
                    @foreach ($pieces as $piece)
                        <section class="section-card p-4 d-flex align-items-start gap-3">
                            <div class="section-icon"><i class="bi bi-file-earmark-text"></i></div>
                            <div class="flex-grow-1">
                                <h2 class="h6 fw-bold mb-1">{{ $piece->nom }}</h2>
                                @if ($piece->description)
                                    <p class="small text-secondary mb-2">{{ $piece->description }}</p>
                                @endif
                                <span class="badge rounded-pill text-bg-light border">
                                    Demandée pour {{ $piece->voyages_count }} {{ $piece->voyages_count > 1 ? 'voyages' : 'voyage' }}
                                </span>
                            </div>
                            @if ($peutGerer)
                                <div class="text-nowrap">
                                    <a href="{{ route('pieces-administratives.edit', $piece) }}" class="btn btn-sm btn-outline-secondary" aria-label="Modifier {{ $piece->nom }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#supprimer-piece-{{ $piece->id }}" aria-label="Supprimer {{ $piece->nom }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            @endif
                        </section>

                        @if ($peutGerer)
                            <x-confirm-delete
                                :id="'supprimer-piece-'.$piece->id"
                                :action="route('pieces-administratives.destroy', $piece)"
                                title="Supprimer cette pièce ?"
                                :message="'La pièce « '.$piece->nom.' » ne sera plus demandée pour aucun voyage.'"
                            />
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
