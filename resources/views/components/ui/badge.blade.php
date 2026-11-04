@props(['type' => 'info', 'label' => ''])

@php
    // Estándar de estados §20.9: color SIEMPRE acompañado de texto.
    $estilos = [
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'danger'  => 'bg-rose-50 text-rose-700 ring-rose-200',
        'info'    => 'bg-sky-50 text-sky-700 ring-sky-200',
        'neutral' => 'bg-slate-100 text-slate-600 ring-slate-200',
    ];
    $puntos = [
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-rose-500',
        'info'    => 'bg-sky-500',
        'neutral' => 'bg-slate-400',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset '.$estilos[$type]]) }}>
    <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $puntos[$type] }}" aria-hidden="true"></span>
    {{ $label ?: $slot }}
</span>
