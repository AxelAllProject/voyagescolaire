<x-layouts.app :title="'Modifier · '.$piece->nom">
    <x-slot:header>
        <x-page-header
            title="Modifier la pièce administrative"
            :subtitle="$piece->nom"
            :breadcrumbs="['Pièces administratives' => route('pieces-administratives.index'), $piece->nom => null]"
        />
    </x-slot:header>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <form action="{{ route('pieces-administratives.update', $piece) }}" method="POST" data-reveal class="section-card p-4 p-md-5" novalidate>
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom <span class="required-star">*</span></label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $piece->nom) }}" maxlength="255"
                        class="form-control @error('nom') is-invalid @enderror" required autofocus>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <label for="description" class="form-label">Description <span class="text-muted fw-normal">(optionnel)</span></label>
                <textarea id="description" name="description" rows="4" maxlength="1000"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $piece->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pieces-administratives.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
