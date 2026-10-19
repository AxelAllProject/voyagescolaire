<x-layouts.app title="Voyages">
    <x-slot:header>
        <x-page-header
            title="Les voyages scolaires"
            subtitle="Retrouvez tous les voyages de l'établissement, à venir comme passés."
            :breadcrumbs="['Voyages' => null]"
        >
            @can('create', App\Models\Voyage::class)
                <x-slot:actions>
                    <a href="{{ route('voyages.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-lg me-1"></i> Nouveau voyage
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>
    </x-slot:header>

    {{-- Filtres --}}
    <div class="section-card p-3 p-md-4 mb-4">
        <form method="GET" action="{{ route('voyages.index') }}" class="row g-3 align-items-center">
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" value="{{ $recherche }}" class="form-control" placeholder="Rechercher un titre ou une destination" aria-label="Rechercher">
                </div>
            </div>
            <div class="col-lg-7 d-flex flex-wrap align-items-center justify-content-lg-end gap-2 vs-filter">
                <input type="hidden" name="periode" value="{{ $periode }}">
                @foreach (['a-venir' => 'À venir', 'passes' => 'Passés', 'tous' => 'Tous'] as $valeur => $libelle)
                    <a href="{{ route('voyages.index', array_filter(['periode' => $valeur, 'q' => $recherche])) }}"
                        @class(['btn', 'btn-primary' => $periode === $valeur, 'btn-outline-secondary' => $periode !== $valeur])>
                        {{ $libelle }}
                    </a>
                @endforeach
                <button type="submit" class="btn btn-dark">Rechercher</button>
            </div>
        </form>
    </div>

    @if ($voyages->isEmpty())
        <div class="section-card p-5 text-center">
            <div class="vs-icon-tile mx-auto mb-3"><i class="bi bi-airplane fs-4"></i></div>
            <h2 class="h5 fw-bold">Aucun voyage trouvé</h2>
            <p class="text-secondary mb-4">
                @if ($recherche !== '')
                    Aucun voyage ne correspond à « {{ $recherche }} ».
                @else
                    Il n'y a aucun voyage dans cette catégorie pour le moment.
                @endif
            </p>
            @can('create', App\Models\Voyage::class)
                <a href="{{ route('voyages.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Créer un voyage</a>
            @endcan
        </div>
    @else
        <p class="small text-secondary mb-3">
            {{ $voyages->total() }} {{ $voyages->total() > 1 ? 'voyages' : 'voyage' }}
        </p>

        <div class="row g-4">
            @foreach ($voyages as $voyage)
                <div class="col-md-6 col-lg-4">
                    <x-voyage-card :voyage="$voyage" :variant="$loop->index" />
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $voyages->links() }}
        </div>
    @endif
</x-layouts.app>
