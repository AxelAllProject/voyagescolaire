{{-- Encart affiché au personnel non administrateur sur les pages de gestion. --}}
<section class="section-card p-4 sticky-sidebar">
    <div class="d-flex align-items-center gap-3 mb-2">
        <div class="section-icon"><i class="bi bi-eye"></i></div>
        <h2 class="h6 fw-bold mb-0">Consultation seule</h2>
    </div>
    <p class="small text-secondary mb-0">Seul un administrateur peut ajouter, modifier ou supprimer {{ $slot }}.</p>
</section>
