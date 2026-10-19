@php($id = 'vs-hero-'.\Illuminate\Support\Str::random(6))

{{-- Globe, trajectoire et cartes flottantes du héros. --}}
<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 440" role="img" aria-label="Un avion en papier fait le tour du globe">
    <defs>
        <radialGradient id="{{ $id }}-globe" cx="0.35" cy="0.3" r="0.8">
            <stop offset="0" stop-color="#ffffff" stop-opacity="0.35"/>
            <stop offset="1" stop-color="#ffffff" stop-opacity="0.06"/>
        </radialGradient>
        <filter id="{{ $id }}-shadow" x="-20%" y="-20%" width="140%" height="160%">
            <feDropShadow dx="0" dy="10" stdDeviation="12" flood-color="#0f172a" flood-opacity="0.25"/>
        </filter>
    </defs>

    {{-- Globe --}}
    <circle cx="260" cy="220" r="150" fill="url(#{{ $id }}-globe)" stroke="#ffffff" stroke-opacity="0.45" stroke-width="2"/>
    <g fill="none" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1.5">
        <ellipse cx="260" cy="220" rx="60" ry="150"/>
        <ellipse cx="260" cy="220" rx="115" ry="150"/>
        <line x1="110" y1="220" x2="410" y2="220"/>
        <path d="M128 150 Q 260 180 392 150"/>
        <path d="M128 290 Q 260 260 392 290"/>
    </g>

    {{-- Orbite --}}
    <ellipse cx="260" cy="220" rx="225" ry="78" transform="rotate(-18 260 220)" fill="none" stroke="#ffffff" stroke-opacity="0.7" stroke-width="3" stroke-linecap="round" stroke-dasharray="2 12"/>
    <circle cx="66" cy="296" r="8" fill="#ffffff"/>
    <circle cx="66" cy="296" r="16" fill="none" stroke="#ffffff" stroke-opacity="0.45" stroke-width="2"/>
    <x-paper-plane transform="translate(388 64) scale(0.62)"/>

    {{-- Carte : voyage --}}
    <g transform="translate(18 40)" filter="url(#{{ $id }}-shadow)">
        <rect width="190" height="72" rx="16" fill="#ffffff"/>
        <rect x="14" y="16" width="40" height="40" rx="12" fill="#dbeafe"/>
        <path d="M24 44 L34 26 L44 44 Z" fill="#2563eb"/>
        <circle cx="42" cy="26" r="4" fill="#06b6d4"/>
        <text x="66" y="33" font-family="'Instrument Sans', 'Segoe UI', Arial, sans-serif" font-size="15" font-weight="700" fill="#0f172a">Rome · 5 jours</text>
        <text x="66" y="53" font-family="'Instrument Sans', 'Segoe UI', Arial, sans-serif" font-size="13" fill="#64748b">35 places · en car</text>
    </g>

    {{-- Carte : pièce administrative --}}
    <g transform="translate(300 340)" filter="url(#{{ $id }}-shadow)">
        <rect width="200" height="64" rx="16" fill="#ffffff"/>
        <circle cx="34" cy="32" r="16" fill="#dcfce7"/>
        <path d="M26 32 L32 38 L43 26" fill="none" stroke="#16a34a" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
        <text x="60" y="29" font-family="'Instrument Sans', 'Segoe UI', Arial, sans-serif" font-size="14" font-weight="700" fill="#0f172a">Autorisation reçue</text>
        <text x="60" y="47" font-family="'Instrument Sans', 'Segoe UI', Arial, sans-serif" font-size="12" fill="#64748b">Pièces administratives</text>
    </g>
</svg>
