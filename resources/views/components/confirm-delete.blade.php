@props(['id', 'action', 'title', 'message'])

{{-- Fenêtre de confirmation avant une suppression. Le déclencheur cible #{{ $id }}. --}}
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-titre" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px">
                    <i class="bi bi-trash3 fs-4"></i>
                </div>
                <h2 class="h5 fw-bold" id="{{ $id }}-titre">{{ $title }}</h2>
                <p class="text-secondary mb-0">{{ $message }}</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ $action }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
