<x-layouts.app title="Connexion">
    <section class="vs-auth py-5">
        <x-hero-decor />

        <div class="container position-relative py-lg-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="section-card p-4 p-md-5">
                        <div class="text-center mb-4">
                            <x-logo :size="56" class="mb-3" />
                            <h1 class="h3 fw-bold mb-1">Espace personnel</h1>
                            <p class="text-secondary mb-0">Connectez-vous pour gérer les voyages de l'établissement.</p>
                        </div>

                        @if (session('status') === 'inscription-en-attente')
                            <div class="alert alert-success rounded-4 small d-flex gap-2" role="status">
                                <i class="bi bi-hourglass-split fs-5"></i>
                                <span><strong>Inscription enregistrée.</strong> Un administrateur doit valider votre compte avant votre première connexion.</span>
                            </div>
                        @elseif (session('status') === 'compte-en-attente')
                            <div class="alert alert-warning rounded-4 small d-flex gap-2" role="status">
                                <i class="bi bi-hourglass-split fs-5"></i>
                                <span>Votre compte est en attente de validation par un administrateur.</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" autofocus required
                                        class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" id="password" name="password" autocomplete="current-password" required
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" @checked(old('remember'))>
                                <label class="form-check-label small" for="remember">Rester connecté</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>

                        <p class="small text-secondary text-center mt-4 mb-0">
                            Pas encore de compte ? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Créer un compte</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
