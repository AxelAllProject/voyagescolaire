@props(['size' => 40])

@php($id = 'vs-logo-'.\Illuminate\Support\Str::random(6))

<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 160 160" role="img" aria-label="Logo Voyages Scolaires">
    <defs>
        <linearGradient id="{{ $id }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#2563eb"/>
            <stop offset="1" stop-color="#06b6d4"/>
        </linearGradient>
    </defs>
    <rect width="160" height="160" rx="36" fill="url(#{{ $id }})"/>
    <path class="vs-trajet" d="M26 122 C 52 132, 70 118, 78 102" fill="none" stroke="#ffffff" stroke-opacity="0.55" stroke-width="4" stroke-linecap="round" stroke-dasharray="2 10"/>
    <x-paper-plane />
</svg>
