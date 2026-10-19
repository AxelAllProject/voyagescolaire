<x-layouts.app title="Classes">
    <x-slot:header>
        <x-page-header
            title="Classes"
            subtitle="Les classes de l'établissement, à associer aux voyages."
            :breadcrumbs="['Classes' => null]"
        />
    </x-slot:header>

    <div class="row g-4">
        <div class="col-lg-4 order-lg-2">
            <section class="section-card p-4 sticky-sidebar">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="section-icon"><i class="bi bi-plus-lg"></i></div>
                    <h2 class="h6 fw-bold mb-0">Ajouter une classe</h2>
                </div>
                <form action="{{ route('classes.store') }}" method="POST" novalidate>
                    @csrf
                    <label for="nom" class="form-label">Nom de la classe <span class="required-star">*</span></label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" maxlength="50"
                        class="form-control @error('nom') is-invalid @enderror" placeholder="Ex : 4e B" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary w-100 mt-3">Ajouter</button>
                </form>
            </section>
        </div>

        <div class="col-lg-8 order-lg-1">
            <section class="section-card overflow-hidden">
                @if ($classes->isEmpty())
                    <div class="p-5 text-center">
                        <div class="vs-icon-tile mx-auto mb-3"><i class="bi bi-mortarboard fs-4"></i></div>
                        <h2 class="h5 fw-bold">Aucune classe</h2>
                        <p class="text-secondary mb-0">Ajoutez votre première classe avec le formulaire.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Classe</th>
                                    <th>Voyages</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $classe)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $classe->nom }}</td>
                                        <td>
                                            <span class="badge rounded-pill text-bg-light border">
                                                {{ $classe->voyages_count }} {{ $classe->voyages_count > 1 ? 'voyages' : 'voyage' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 text-nowrap">
                                            <a href="{{ route('classes.edit', $classe) }}" class="btn btn-sm btn-outline-secondary" aria-label="Renommer {{ $classe->nom }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#supprimer-classe-{{ $classe->id }}" aria-label="Supprimer {{ $classe->nom }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>

    @foreach ($classes as $classe)
        <x-confirm-delete
            :id="'supprimer-classe-'.$classe->id"
            :action="route('classes.destroy', $classe)"
            title="Supprimer cette classe ?"
            :message="'La classe « '.$classe->nom.' » sera retirée de tous ses voyages.'"
        />
    @endforeach
</x-layouts.app>
