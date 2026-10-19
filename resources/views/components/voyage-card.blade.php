@props(['voyage', 'variant' => 0])

<article class="card vs-card h-100" data-tilt>
    <a href="{{ route('voyages.show', $voyage) }}" class="vs-card-link d-flex flex-column h-100">
        <div class="vs-card-cover position-relative">
            <x-voyage-cover :voyage="$voyage" :variant="$variant" />
            <x-voyage-statut :voyage="$voyage" class="position-absolute top-0 end-0 m-3 shadow-sm" />
        </div>
        <div class="card-body p-4 d-flex flex-column">
            <h3 class="h5 fw-bold mb-2">{{ $voyage->titre }}</h3>
            <p class="text-secondary small mb-3">{{ \Illuminate\Support\Str::limit($voyage->description, 110) }}</p>
            <ul class="list-unstyled small text-secondary mb-4">
                <li class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-calendar3 text-primary"></i>
                    Du {{ $voyage->date_debut->translatedFormat('j F') }} au {{ $voyage->date_fin->translatedFormat('j F Y') }}
                </li>
                <li class="d-flex align-items-center gap-2">
                    <i class="bi bi-people text-primary"></i>
                    {{ $voyage->nb_places }} places · {{ $voyage->transport }}
                </li>
            </ul>
            <div class="mt-auto d-flex align-items-end justify-content-between gap-2">
                <div>
                    <div class="small text-secondary">Participation familles</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($voyage->participation_familiale, 0, ',', ' ') }} €</div>
                </div>
                <span class="badge rounded-pill text-bg-light border">Coût total {{ number_format($voyage->prix, 0, ',', ' ') }} €</span>
            </div>
        </div>
    </a>
</article>
