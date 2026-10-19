@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

{{-- En-tête en dégradé des pages intérieures. $breadcrumbs : [libellé => url], la dernière entrée est la page courante. --}}
<header class="vs-page-header">
    <x-hero-decor />

    <div class="container position-relative">
        @if ($breadcrumbs)
            <nav aria-label="Fil d'Ariane" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('accueil') }}">Accueil</a></li>
                    @foreach ($breadcrumbs as $label => $url)
                        @if ($loop->last)
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">{{ $label }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif

        <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
            <div>
                <h1 class="h2 mb-2">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mb-0 text-white-50" style="max-width: 640px">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="d-flex flex-wrap gap-2">{{ $actions }}</div>
            @endisset
        </div>
    </div>
</header>
