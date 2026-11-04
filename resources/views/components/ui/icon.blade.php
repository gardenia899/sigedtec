@props(['name' => 'circle'])

@php
    /**
     * Set de iconos SVG inline del sistema (SDD §20.13.5: sin librerías externas).
     * Uso: <x-ui.icon name="monitor" class="h-5 w-5 text-sky-400" />
     */
@endphp

<svg {{ $attributes->merge(['class' => 'h-5 w-5']) }}
     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('logo')
            <rect x="2.5" y="4.5" width="15.5" height="10.5" rx="2.2"/>
            <path d="M7 20.5h6M10 15v5.5"/>
            <circle cx="18.4" cy="14.6" r="2.5"/>
            <path d="M18.4 10.9v1.2M18.4 17.1v1.2M22.1 14.6h-1.2M15.5 14.6h-1.2M20.8 11.9l-.9.9M16.9 15.8l-.9.9M20.8 17.3l-.9-.9M16.9 13.4l-.9-.9"/>
            @break

        @case('home')
            <path d="M3 9.8 12 3l9 6.8V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.8Z"/>
            @break

        @case('box')
            <path d="m21 8-9-5-9 5v8l9 5 9-5V8Z"/>
            <path d="M3.3 7.6 12 12.5l8.7-4.9M12 12.5V21"/>
            @break

        @case('clipboard')
            <rect x="8" y="2.5" width="8" height="4" rx="1"/>
            <path d="M16 4.5h2a2 2 0 0 1 2 2V20a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6.5a2 2 0 0 1 2-2h2"/>
            <path d="M8.5 12.5h7M8.5 16.5h4.5"/>
            @break

        @case('swap')
            <path d="M4 8h15l-3.5-3.5M20 16H5l3.5 3.5"/>
            @break

        @case('chart')
            <path d="M3 21h18"/><path d="M6.5 21V11"/><path d="M11.5 21V4.5"/><path d="M16.5 21v-6.5"/>
            @break

        @case('folders')
            <path d="M3 7.5A2 2 0 0 1 5 5.5h3.6l1.9 2H19a2 2 0 0 1 2 2v7.5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7.5Z"/>
            <path d="M7 12h9"/>
            @break

        @case('user')
            <circle cx="12" cy="8" r="3.4"/><path d="M5.2 20a6.8 6.8 0 0 1 13.6 0"/>
            @break

        @case('users')
            <circle cx="9" cy="8" r="3.2"/><path d="M2.8 20a6.2 6.2 0 0 1 12.4 0"/>
            <path d="M16 5.2a3.2 3.2 0 0 1 0 6M18 20h3.2a5.6 5.6 0 0 0-3.4-5.1"/>
            @break

        @case('settings')
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 14.6a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5v.2a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>
            @break

        @case('search')
            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
            @break

        @case('bell')
            <path d="M18 8.5a6 6 0 1 0-12 0c0 5.9-2 7.1-2 7.1h16s-2-1.2-2-7.1"/>
            <path d="M10.2 20.4a2.1 2.1 0 0 0 3.6 0"/>
            @break

        @case('chevron-down')
            <path d="m6 9.5 6 6 6-6"/>
            @break

        @case('chevron-right')
            <path d="m9 5.5 6.5 6.5L9 18.5"/>
            @break

        @case('arrow-right')
            <path d="M4.5 12h15"/><path d="m13.5 6 6 6-6 6"/>
            @break

        @case('plus')
            <path d="M12 4.5v15M4.5 12h15"/>
            @break

        @case('monitor')
            <rect x="2.5" y="4" width="19" height="12.5" rx="2.5"/>
            <path d="M9 20.5h6M12 16.5v4"/>
            @break

        @case('wrench')
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.7-3.7a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9l-3.7 3.7Z"/>
            @break

        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"/>
            @break

        @case('logout')
            <path d="M15 17.5 20 12l-5-5.5"/><path d="M20 12H9"/>
            <path d="M12.5 3.5H6a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h6.5"/>
            @break

        @case('login')
            <path d="M15 12H3"/><path d="m9 6-6 6 6 6"/>
            <path d="M12.5 3.5H18a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2h-5.5"/>
            @break

        @case('eye')
            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
            <circle cx="12" cy="12" r="3.1"/>
            @break

        @case('eye-off')
            <path d="m3 3 18 18"/>
            <path d="M10.6 5.2A10.6 10.6 0 0 1 12 5c6.4 0 10 7 10 7a17.6 17.6 0 0 1-2.4 3.4"/>
            <path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.6 7 10 7a10.7 10.7 0 0 0 4.4-.9"/>
            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
            @break

        @case('lock')
            <rect x="4.5" y="10" width="15" height="10.5" rx="2.5"/>
            <path d="M8.5 10V7.6a3.5 3.5 0 0 1 7 0V10"/>
            @break

        @case('user-plus')
            <circle cx="9.5" cy="8" r="3.4"/><path d="M3 20a6.6 6.6 0 0 1 13 0"/>
            <path d="M18.5 6.5v6M15.5 9.5h6"/>
            @break

        @case('shield')
            <path d="M12 3l7 3v6c0 4.2-2.9 7.4-7 9-4.1-1.6-7-4.8-7-9V6l7-3Z"/>
            @break

        @case('bolt')
            <path d="M13.5 2.5 4.5 13.5H10l-1 8 9-11h-5.5l1-8Z"/>
            @break

        @case('layers')
            <path d="m12 3 9 4.8-9 4.8-9-4.8L12 3Z"/>
            <path d="m3 12.3 9 4.8 9-4.8M3 16.9l9 4.8 9-4.8"/>
            @break

        @case('bank')
            <path d="M3 9.5 12 3.5l9 6"/><path d="M5 10v8.5M19 10v8.5M9.5 10v8.5M14.5 10v8.5"/>
            <path d="M3 21h18"/>
            @break

        @case('calendar')
            <rect x="3.5" y="5" width="17" height="16" rx="2.5"/>
            <path d="M8 3v4M16 3v4M3.5 10h17"/>
            @break

        @case('clock')
            <circle cx="12" cy="12" r="8.8"/><path d="M12 7v5.3l3.4 2"/>
            @break

        @case('file-text')
            <path d="M13 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9l-6-6Z"/>
            <path d="M13 3v6h6"/><path d="M9 13h6M9 17h4"/>
            @break

        @case('building')
            <path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/>
            <path d="M16 9.5h3a2 2 0 0 1 2 2V21"/><path d="M2 21h20"/>
            <path d="M8 7h4M8 11h4M8 15h4"/>
            @break

        @case('printer')
            <path d="M7 8V3.5h10V8"/>
            <rect x="3" y="8" width="18" height="8" rx="2"/>
            <path d="M7 16h10v4.5H7V16Z"/>
            @break

        @default
            <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
