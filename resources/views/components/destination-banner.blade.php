@props(['destination', 'variant' => 0])

@php
    $palettes = [
        ['#1e3a8a', '#06b6d4'],
        ['#4c1d95', '#2563eb'],
        ['#0e7490', '#10b981'],
    ];
    [$from, $to] = $palettes[$variant % count($palettes)];
    $id = 'vs-dest-'.\Illuminate\Support\Str::random(6);
@endphp

{{-- Bannière illustrée d'une destination : ciel en dégradé, soleil, collines et trajectoire. --}}
<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 180" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Destination : {{ $destination }}">
    <defs>
        <linearGradient id="{{ $id }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="{{ $from }}"/>
            <stop offset="1" stop-color="{{ $to }}"/>
        </linearGradient>
    </defs>
    <rect width="400" height="180" fill="url(#{{ $id }})"/>
    <circle cx="320" cy="58" r="26" fill="#ffffff" fill-opacity="0.25"/>
    <circle cx="320" cy="58" r="16" fill="#ffffff" fill-opacity="0.5"/>
    <g fill="#ffffff" fill-opacity="0.12">
        <ellipse cx="90" cy="44" rx="56" ry="14"/>
        <ellipse cx="118" cy="36" rx="34" ry="16"/>
    </g>
    <path d="M0 150 C 60 110, 120 120, 180 140 S 300 110, 400 132 L 400 180 L 0 180 Z" fill="#ffffff" fill-opacity="0.16"/>
    <path d="M0 166 C 80 140, 160 150, 240 162 S 340 150, 400 158 L 400 180 L 0 180 Z" fill="#ffffff" fill-opacity="0.22"/>
    <path d="M24 120 C 110 120, 160 70, 250 64" fill="none" stroke="#ffffff" stroke-opacity="0.7" stroke-width="3" stroke-linecap="round" stroke-dasharray="2 10"/>
    <x-paper-plane transform="translate(236 34) scale(0.32)"/>
    <text x="24" y="98" font-family="'Instrument Sans', 'Segoe UI', Arial, sans-serif" font-size="26" font-weight="700" fill="#ffffff">{{ $destination }}</text>
</svg>
