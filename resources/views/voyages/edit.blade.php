<x-layouts.app :title="'Modifier · '.$voyage->titre">
    <x-slot:header>
        <x-page-header
            title="Modifier le voyage"
            :subtitle="$voyage->titre"
            :breadcrumbs="['Voyages' => route('voyages.index'), $voyage->titre => route('voyages.show', $voyage), 'Modifier' => null]"
        />
    </x-slot:header>

    @include('voyages._form', [
        'action' => route('voyages.update', $voyage),
        'method' => 'PUT',
        'submitLabel' => 'Enregistrer les modifications',
        'cancelUrl' => route('voyages.show', $voyage),
    ])
</x-layouts.app>
