@props(['name', 'size' => 26])

{{-- Icônes au trait, colorées avec currentColor. --}}
<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('voyage')
            <path d="M3 11.5 21 4l-6 17-3.5-6.5L3 11.5Z"/>
            <path d="M11.5 14.5 21 4"/>
            @break
        @case('classe')
            <path d="M3 9.5 12 5l9 4.5-9 4.5-9-4.5Z"/>
            <path d="M7 11.5V16c0 1.5 2.2 3 5 3s5-1.5 5-3v-4.5"/>
            <path d="M21 9.5V15"/>
            @break
        @case('document')
            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/>
            <path d="M14 3v5h5"/>
            <path d="m9 14 2 2 4-4"/>
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2"/>
            <path d="M16 3v4M8 3v4M3 10h18"/>
            @break
        @case('users')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>
            @break
        @case('code')
            <path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>
            @break
        @case('arrow-right')
            <path d="M5 12h14M13 6l6 6-6 6"/>
            @break
    @endswitch
</svg>
