<x-layouts.app :title="'Renommer · '.$classe->nom">
    <x-slot:header>
        <x-page-header
            title="Renommer la classe"
            :subtitle="$classe->nom"
            :breadcrumbs="['Classes' => route('classes.index'), $classe->nom => null]"
        />
    </x-slot:header>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form action="{{ route('classes.update', $classe) }}" method="POST" data-reveal class="section-card p-4 p-md-5" novalidate>
                @csrf
                @method('PUT')
                <label for="nom" class="form-label">Nom de la classe <span class="required-star">*</span></label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $classe->nom) }}" maxlength="50"
                    class="form-control @error('nom') is-invalid @enderror" required autofocus>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
