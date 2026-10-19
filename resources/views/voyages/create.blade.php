<x-layouts.app title="Créer un voyage">
    <x-slot:header>
        <x-page-header
            title="Créer un nouveau voyage scolaire"
            subtitle="Renseignez les informations pédagogiques, logistiques et budgétaires du séjour. Les champs marqués d'un astérisque (*) sont obligatoires."
            :breadcrumbs="['Voyages' => route('voyages.index'), 'Nouveau voyage' => null]"
        />
    </x-slot:header>

    @include('voyages._form', [
        'action' => route('voyages.store'),
        'method' => 'POST',
        'submitLabel' => 'Enregistrer le voyage',
        'cancelUrl' => route('voyages.index'),
    ])
</x-layouts.app>
