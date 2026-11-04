@props(['alt' => ''])

{{-- Escena tecnológica del hero de autenticación (SVG inline, sin dependencias). --}}
<svg viewBox="0 0 560 330" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }} aria-hidden="true" role="presentation">
    <defs>
        <linearGradient id="sceneScreen" x1="196" y1="64" x2="416" y2="204" gradientUnits="userSpaceOnUse">
            <stop stop-color="#1E3A8A"/>
            <stop offset="0.55" stop-color="#2563EB"/>
            <stop offset="1" stop-color="#0EA5E9"/>
        </linearGradient>
        <linearGradient id="sceneLaptop" x1="128" y1="178" x2="282" y2="266" gradientUnits="userSpaceOnUse">
            <stop stop-color="#0B1E45"/>
            <stop offset="0.55" stop-color="#1D4ED8"/>
            <stop offset="1" stop-color="#38BDF8"/>
        </linearGradient>
        <linearGradient id="sceneDesk" x1="20" y1="292" x2="540" y2="292" gradientUnits="userSpaceOnUse">
            <stop stop-color="#0E2A55" stop-opacity="0.2"/>
            <stop offset="0.5" stop-color="#38BDF8" stop-opacity="0.55"/>
            <stop offset="1" stop-color="#0E2A55" stop-opacity="0.2"/>
        </linearGradient>
        <radialGradient id="sceneHalo" cx="50%" cy="50%" r="50%">
            <stop stop-color="#38BDF8" stop-opacity="0.28"/>
            <stop offset="1" stop-color="#38BDF8" stop-opacity="0"/>
        </radialGradient>
        <radialGradient id="sceneGlow" cx="50%" cy="50%" r="50%">
            <stop stop-color="#60A5FA" stop-opacity="0.55"/>
            <stop offset="1" stop-color="#60A5FA" stop-opacity="0"/>
        </radialGradient>
    </defs>

    <ellipse cx="300" cy="170" rx="262" ry="150" fill="url(#sceneHalo)"/>
    <ellipse cx="250" cy="250" rx="200" ry="54" fill="url(#sceneGlow)"/>

    {{-- Monitor --}}
    <rect x="196" y="62" width="222" height="142" rx="11" fill="#081A34" stroke="#1E40AF" stroke-opacity="0.55"/>
    <rect x="206" y="72" width="202" height="122" rx="6" fill="url(#sceneScreen)"/>
    <path d="M206 152c38-32 68 12 100-12s60-36 102-4v48a6 6 0 0 1-6 6H212a6 6 0 0 1-6-6v-32Z" fill="#061426" fill-opacity="0.35"/>
    <path d="M212 116c34-22 62 10 90-12s54-24 100 0" stroke="#BAE6FD" stroke-opacity="0.5" stroke-width="2"/>
    <path d="M212 132c34-20 62 10 90-10s54-20 100 2" stroke="#E0F2FE" stroke-opacity="0.28" stroke-width="1.5"/>
    <rect x="302" y="204" width="10" height="24" rx="3" fill="#123A6B"/>
    <rect x="268" y="226" width="78" height="7" rx="3.5" fill="#123A6B"/>

    {{-- Laptop --}}
    <g transform="rotate(-5 250 250)">
        <rect x="118" y="168" width="176" height="110" rx="9" fill="#081A34" stroke="#38BDF8" stroke-opacity="0.45"/>
        <rect x="127" y="177" width="158" height="92" rx="4" fill="url(#sceneLaptop)"/>
        <path d="M127 238c30-24 52 6 78-10s42-20 80 4v37H127v-31Z" fill="#061426" fill-opacity="0.38"/>
        <path d="M142 196c24-14 44 6 64-8s38-14 62 2" stroke="#BAE6FD" stroke-opacity="0.45" stroke-width="1.6"/>
        <path d="M118 278h176l16 18H102l16-18Z" fill="#12294D"/>
        <rect x="176" y="284" width="60" height="4" rx="2" fill="#1E40AF"/>
    </g>

    {{-- Escritorio --}}
    <path d="M20 296h520" stroke="url(#sceneDesk)" stroke-width="9" stroke-linecap="round"/>

    {{-- Planta --}}
    <path d="M492 258c14-16 11-40-2-50-13 9-17 33-2 50Z" fill="#0F766E" fill-opacity="0.85"/>
    <path d="M490 250c-16-6-26-22-24-38 16 3 28 18 28 36" fill="#115E59" fill-opacity="0.9"/>
    <path d="M496 252c14-8 22-24 20-40-15 5-24 21-24 38" fill="#14B8A6" fill-opacity="0.7"/>
    <rect x="468" y="256" width="48" height="40" rx="7" fill="#E2E8F0" fill-opacity="0.9"/>
    <rect x="471" y="262" width="42" height="6" rx="3" fill="#CBD5E1" fill-opacity="0.9"/>

    {{-- Reflejos --}}
    <ellipse cx="196" cy="300" rx="72" ry="6" fill="#38BDF8" fill-opacity="0.12"/>
    <ellipse cx="392" cy="302" rx="58" ry="5" fill="#38BDF8" fill-opacity="0.1"/>
</svg>
