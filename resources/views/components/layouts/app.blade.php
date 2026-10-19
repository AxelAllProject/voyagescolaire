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
                        <li class="nav-item"><a class="nav-link" href="#fonctionnalites">Fonctionnalités</a></li>
                        <li class="nav-item"><a class="nav-link" href="#voyages">Prochains voyages</a></li>
                        <li class="nav-item"><a class="nav-link" href="#etapes">Comment ça marche</a></li>
                        <li class="nav-item"><a class="nav-link" href="#api">API</a></li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('voyages.index') }}">Voir l'API</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        <footer class="vs-footer py-5">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <x-logo :size="32" />
                    <span class="fw-semibold text-dark">Voyages Scolaires</span>
                </div>
                <p class="mb-0 small text-secondary">&copy; {{ date('Y') }} Voyages Scolaires · Construit avec Laravel et Bootstrap</p>
            </div>
        </footer>
    </body>
</html>
