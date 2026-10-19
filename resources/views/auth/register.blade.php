<x-layouts.app title="Créer un compte">
    <section class="vs-auth py-5">
        <x-hero-decor />

        <div class="container position-relative py-lg-5">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-6">
                    <div class="section-card p-4 p-md-5" data-reveal>
                        <div class="text-center mb-4">
                            <x-logo :size="56" class="mb-3" />
                            <h1 class="h3 fw-bold mb-1">Créer un compte</h1>
                            <p class="text-secondary mb-0">Réservé au personnel de l'établissement.</p>
                        </div>

                        <div class="alert alert-info rounded-4 small d-flex gap-2 mb-4">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span>Votre compte sera <strong>validé par un administrateur</strong> avant votre première connexion.</span>
                        </div>

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Ex : Claire Martin">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email professionnelle</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required
                                        class="form-control @error('email') is-invalid @enderror" placeholder="prenom.nom@college.fr">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" id="password" name="password" autocomplete="new-password" required
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">8 caractères minimum.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmation</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Demander un compte</button>
                        </form>

                        <p class="small text-secondary text-center mt-4 mb-0">
                            Déjà un compte ? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
