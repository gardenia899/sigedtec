@props(['type' => 'info', 'label' => ''])

@php
    // Estándar de estados §20.9: color SIEMPRE acompañado de texto.
    $estilos = [
        'success' => 'bg-green-100 text-green-800 border-green-200',
        'warning' => 'bg-amber-100 text-amber-800 border-amber-200',
        'danger'  => 'bg-red-100 text-red-800 border-red-200',
        'info'    => 'bg-sky-100 text-sky-800 border-sky-200',
        'neutral' => 'bg-slate-100 text-slate-600 border-slate-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-medium '.$estilos[$type]]) }}>
    {{ $label ?: $slot }}
</span>
