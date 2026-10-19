@props(['voyage', 'variant' => 0])

{{-- Visuel d'un voyage : sa photo si elle existe, sinon la bannière illustrée de la destination. --}}
@if ($voyage->image)
    <img src="{{ $voyage->image }}" alt="{{ $voyage->destination }}" {{ $attributes }} loading="lazy">
@else
    <x-destination-banner :destination="$voyage->destination" :variant="$variant" {{ $attributes }} />
@endif
