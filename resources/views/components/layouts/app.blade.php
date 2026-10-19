@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Organisez les voyages scolaires de votre établissement : destinations, classes et pièces administratives.">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        @fonts

        @vite(['resources/css/site.css', 'resources/js/site.js'])
    </head>
    <body>
        <nav class="navbar navbar-expand-lg sticky-top vs-navbar">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('accueil') }}">
                    <x-logo :size="36" />
                    Voyages Scolaires
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Afficher la navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('accueil')]) href="{{ route('accueil') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('voyages.*') && ! request()->routeIs('voyages.create')]) href="{{ route('voyages.index') }}">Voyages</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a @class(['nav-link', 'active' => request()->routeIs('classes.*')]) href="{{ route('classes.index') }}">Classes</a>
                            </li>
                            <li class="nav-item">
                                <a @class(['nav-link', 'active' => request()->routeIs('pieces-administratives.*')]) href="{{ route('pieces-administratives.index') }}">Pièces administratives</a>
                            </li>
                            @can('create', App\Models\Voyage::class)
                                <li class="nav-item ms-lg-2">
                                    <a class="btn btn-primary btn-sm" href="{{ route('voyages.create') }}">
                                        <i class="bi bi-plus-lg me-1"></i> Nouveau voyage
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item dropdown ms-lg-1">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle fs-5"></i>
                                    <span class="d-lg-none">{{ auth()->user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                                    <li class="px-3 py-2">
                                        <div class="fw-semibold text-dark">{{ auth()->user()->name }}</div>
                                        <div class="small text-secondary">{{ auth()->user()->role->libelle() }}</div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item rounded-3" href="{{ route('compte') }}"><i class="bi bi-key me-2"></i>Mon compte</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item rounded-3"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-primary btn-sm" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Espace personnel
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main @class(['vs-page' => isset($header)])>
            @isset($header)
                {{ $header }}

                <div class="container vs-page-body pb-5">
                    <x-flash />

                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endisset
        </main>

        <footer class="vs-footer py-5">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <x-logo :size="32" />
                    <span class="fw-semibold text-dark">Voyages Scolaires</span>
                </div>
                <ul class="nav small">
                    <li class="nav-item"><a class="nav-link link-secondary px-2" href="{{ route('voyages.index') }}">Voyages</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link link-secondary px-2" href="{{ route('classes.index') }}">Classes</a></li>
                        <li class="nav-item"><a class="nav-link link-secondary px-2" href="{{ route('pieces-administratives.index') }}">Pièces administratives</a></li>
                    @endauth
                    <li class="nav-item"><a class="nav-link link-secondary px-2" href="{{ route('accueil') }}#api">API</a></li>
                </ul>
                <p class="mb-0 small text-secondary">&copy; {{ date('Y') }} Voyages Scolaires</p>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
