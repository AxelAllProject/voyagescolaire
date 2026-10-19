@props(['voyage'])

@php
    [$label, $classes] = match ($voyage->statut()) {
        'a-venir' => ['À venir', 'text-bg-primary'],
        'en-cours' => ['En cours', 'text-bg-success'],
        default => ['Terminé', 'text-bg-secondary'],
    };
@endphp

<span {{ $attributes->merge(['class' => "badge rounded-pill {$classes}"]) }}>{{ $label }}</span>
