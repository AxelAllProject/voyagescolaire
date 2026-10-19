{{-- Nuages et trajectoire en pointillés derrière le héros. --}}
<svg {{ $attributes->merge(['class' => 'vs-hero-decor']) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 640" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <g class="vs-nuages" fill="#ffffff" fill-opacity="0.08">
        <ellipse cx="1180" cy="90" rx="140" ry="36"/>
        <ellipse cx="1260" cy="70" rx="90" ry="40"/>
        <ellipse cx="160" cy="540" rx="170" ry="40"/>
        <ellipse cx="250" cy="515" rx="90" ry="38"/>
        <ellipse cx="760" cy="600" rx="120" ry="26"/>
    </g>
    <path class="vs-trajet" d="M-40 470 C 220 420, 360 560, 620 500 S 980 300, 1480 260" fill="none" stroke="#ffffff" stroke-opacity="0.25" stroke-width="4" stroke-linecap="round" stroke-dasharray="2 16"/>
</svg>
