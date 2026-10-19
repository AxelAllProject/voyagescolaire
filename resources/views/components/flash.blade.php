{{-- Messages flash de session (succès ou erreur). --}}
@foreach (['success' => ['success', 'bi-check2-circle', 'Opération réussie'], 'error' => ['danger', 'bi-exclamation-octagon-fill', 'Une erreur est survenue']] as $key => [$color, $icon, $heading])
    @if (session($key))
        <div class="alert alert-{{ $color }} alert-dismissible fade show shadow-sm d-flex align-items-center gap-3 p-3 mb-4 rounded-4" role="alert">
            <div class="rounded-circle bg-{{ $color }} text-white d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px">
                <i class="bi {{ $icon }} fs-5"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="h6 fw-bold mb-1 text-{{ $color }}-emphasis">{{ $heading }}</h2>
                <p class="mb-0 small text-{{ $color }}-emphasis">{{ session($key) }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
@endforeach
