<x-layouts.app title="Accueil">
    {{-- Héros --}}
    <section class="vs-hero pt-5">
        <x-hero-decor />

        <div class="container position-relative py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="vs-pill mb-4">
                        <x-icon name="voyage" :size="16" />
                        Sorties et voyages scolaires
                    </span>
                    <h1 class="mb-4">Organisez les voyages de vos classes, sans paperasse.</h1>
                    <p class="lead mb-4">
                        Destinations, dates, budget, places disponibles, classes concernées et pièces administratives :
                        tout est centralisé au même endroit.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="#voyages" class="btn btn-light btn-lg">Découvrir les voyages</a>
                        <a href="#api" class="btn btn-outline-light btn-lg">Utiliser l'API</a>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="vs-pill">Laravel 13</span>
                        <span class="vs-pill">API REST</span>
                        <span class="vs-pill">100 % en français</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <x-hero-illustration class="img-fluid w-100" />
                </div>
            </div>
        </div>

        <svg class="vs-wave" viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
            <path d="M0 40 C 240 0, 480 64, 720 40 S 1200 8, 1440 32 L 1440 64 L 0 64 Z" fill="#ffffff"/>
        </svg>
    </section>

    {{-- Fonctionnalités --}}
    <section id="fonctionnalites" class="py-5">
        <div class="container py-lg-4">
            <div class="text-center mx-auto mb-5" style="max-width: 640px">
                <p class="vs-eyebrow mb-2">Fonctionnalités</p>
                <h2 class="fw-bold mb-3">Tout ce qu'il faut pour préparer un voyage</h2>
                <p class="text-secondary mb-0">Une seule application pour suivre chaque voyage, de la destination aux documents à rassembler.</p>
            </div>

            <div class="row g-4">
                @foreach ([
                    ['icon' => 'voyage', 'titre' => 'Voyages', 'texte' => 'Destination, dates, prix, participation des familles, transport et nombre de places pour chaque voyage.'],
                    ['icon' => 'classe', 'titre' => 'Classes', 'texte' => 'Associez une ou plusieurs classes à un voyage et retrouvez en un clin d\'œil qui part où.'],
                    ['icon' => 'document', 'titre' => 'Pièces administratives', 'texte' => 'Listez les documents à fournir : autorisation de sortie, carte d\'identité, fiche sanitaire…'],
                ] as $fonctionnalite)
                    <div class="col-md-4">
                        <div class="card vs-card h-100 p-4">
                            <div class="vs-icon-tile mb-4">
                                <x-icon :name="$fonctionnalite['icon']" />
                            </div>
                            <h3 class="h5 fw-bold">{{ $fonctionnalite['titre'] }}</h3>
                            <p class="text-secondary mb-0">{{ $fonctionnalite['texte'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Prochains voyages --}}
    <section id="voyages" class="py-5 vs-section-muted">
        <div class="container py-lg-4">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-5">
                <div>
                    <p class="vs-eyebrow mb-2">À venir</p>
                    <h2 class="fw-bold mb-0">Prochains voyages</h2>
                </div>
                <a href="{{ route('voyages.index') }}" class="link-primary fw-semibold text-decoration-none d-inline-flex align-items-center gap-1">
                    Tous les voyages (JSON) <x-icon name="arrow-right" :size="18" />
                </a>
            </div>

            <div class="row g-4">
                @forelse ($voyages as $voyage)
                    <div class="col-md-6 col-lg-4">
                        <article class="card vs-card h-100">
                            <x-destination-banner :destination="$voyage->destination" :variant="$loop->index" class="card-img-top d-block w-100" style="height: 180px" />
                            <div class="card-body p-4 d-flex flex-column">
                                <h3 class="h5 fw-bold mb-2">{{ $voyage->titre }}</h3>
                                <p class="text-secondary small mb-3">{{ \Illuminate\Support\Str::limit($voyage->description, 110) }}</p>
                                <ul class="list-unstyled small text-secondary mb-4">
                                    <li class="d-flex align-items-center gap-2 mb-1">
                                        <x-icon name="calendar" :size="16" class="text-primary" />
                                        Du {{ $voyage->date_debut->translatedFormat('j F') }} au {{ $voyage->date_fin->translatedFormat('j F Y') }}
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <x-icon name="users" :size="16" class="text-primary" />
                                        {{ $voyage->nb_places }} places · en {{ $voyage->transport }}
                                    </li>
                                </ul>
                                <div class="mt-auto d-flex align-items-end justify-content-between">
                                    <div>
                                        <div class="small text-secondary">Participation familles</div>
                                        <div class="fs-4 fw-bold text-dark">{{ number_format($voyage->participation_familiale, 0, ',', ' ') }} €</div>
                                    </div>
                                    <span class="badge rounded-pill text-bg-light border">Coût total {{ number_format($voyage->prix, 0, ',', ' ') }} €</span>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card vs-card p-5 text-center">
                            <div class="vs-icon-tile mx-auto mb-3">
                                <x-icon name="voyage" />
                            </div>
                            <h3 class="h5 fw-bold">Aucun voyage prévu pour le moment</h3>
                            <p class="text-secondary mb-0">Créez votre premier voyage avec une requête <code>POST /api/voyages</code>.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Comment ça marche --}}
    <section id="etapes" class="py-5">
        <div class="container py-lg-4">
            <div class="text-center mx-auto mb-5" style="max-width: 640px">
                <p class="vs-eyebrow mb-2">Comment ça marche</p>
                <h2 class="fw-bold mb-0">Un voyage prêt en trois étapes</h2>
            </div>

            <div class="row g-4 text-center vs-steps">
                @foreach ([
                    ['titre' => 'Créez le voyage', 'texte' => 'Renseignez la destination, les dates, le budget et le nombre de places.'],
                    ['titre' => 'Ajoutez les classes', 'texte' => 'Choisissez les classes qui participent au voyage.'],
                    ['titre' => 'Listez les documents', 'texte' => 'Indiquez les pièces administratives que les familles doivent fournir.'],
                ] as $etape)
                    <div class="col-md-4 position-relative">
                        <span class="vs-step-number mb-3">{{ $loop->iteration }}</span>
                        <h3 class="h5 fw-bold">{{ $etape['titre'] }}</h3>
                        <p class="text-secondary mb-0 px-lg-4">{{ $etape['texte'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- API --}}
    <section id="api" class="py-5 vs-api">
        <div class="container py-lg-4">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <p class="vs-eyebrow mb-2" style="color: #7dd3fc">API REST</p>
                    <h2 class="fw-bold mb-3">Branchez vos outils sur l'API</h2>
                    <p class="mb-4">Réponses en JSON, validation et messages d'erreur en français. Toutes les routes sont préfixées par <code class="text-info">/api</code>.</p>
                    <ul class="list-group list-group-flush">
                        @foreach ([
                            ['GET', 'get', '/api/voyages', 'Lister les voyages'],
                            ['POST', 'post', '/api/voyages', 'Créer un voyage'],
                            ['GET', 'get', '/api/voyages/{id}', 'Afficher un voyage'],
                            ['PUT', 'put', '/api/voyages/{id}', 'Modifier un voyage'],
                            ['DELETE', 'delete', '/api/voyages/{id}', 'Supprimer un voyage'],
                        ] as [$methode, $classe, $url, $action])
                            <li class="list-group-item px-0 d-flex align-items-center gap-3">
                                <span class="vs-method vs-method-{{ $classe }}">{{ $methode }}</span>
                                <code class="text-light">{{ $url }}</code>
                                <span class="ms-auto small text-secondary d-none d-sm-inline">{{ $action }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-7">
                    <pre class="vs-code mb-0"><code><span class="c"># Créer un voyage</span>
<span class="k">curl</span> -X POST {{ url('/api/voyages') }} \
  -H <span class="s">"Accept: application/json"</span> \
  -H <span class="s">"Content-Type: application/json"</span> \
  -d <span class="s">'{
    "titre": "Découverte de Rome",
    "description": "Visite du Colisée et du Vatican.",
    "destination": "Rome",
    "date_debut": "2027-03-01",
    "date_fin": "2027-03-05",
    "prix": 520.50,
    "participation_familiale": 200,
    "nb_places": 35,
    "transport": "car"
  }'</span>

<span class="c"># Réponse 422 si un champ manque</span>
{ <span class="s">"message"</span>: <span class="s">"Le champ titre est obligatoire."</span> }</code></pre>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
