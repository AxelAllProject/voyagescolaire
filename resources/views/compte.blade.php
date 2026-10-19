<x-layouts.app title="Mon compte">
    <x-slot:header>
        <x-page-header
            title="Mon compte"
            :subtitle="auth()->user()->name.' · '.auth()->user()->role->libelle()"
            :breadcrumbs="['Mon compte' => null]"
        />
    </x-slot:header>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success shadow-sm rounded-4 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check2-circle fs-5"></i> Votre mot de passe a été modifié.
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('user-password.update') }}" class="section-card p-4 p-md-5" novalidate>
                @csrf
                @method('PUT')

                <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                    <div class="section-icon"><i class="bi bi-key"></i></div>
                    <h2 class="h5 fw-bold mb-0">Changer de mot de passe</h2>
                </div>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" autocomplete="new-password"
                        class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="form-control" required>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
