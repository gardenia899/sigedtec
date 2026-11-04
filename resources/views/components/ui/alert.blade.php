@props(['type' => 'info', 'title' => null])

@php
    // Paleta del Design System §20.2: el color siempre va acompañado de texto.
    $estilos = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'danger'  => 'border-rose-200 bg-rose-50 text-rose-800',
        'info'    => 'border-sky-200 bg-sky-50 text-sky-800',
    ];
    $iconos = [
        'success' => 'shield',
        'warning' => 'bolt',
        'danger'  => 'bell',
        'info'    => 'file-text',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex gap-3 rounded-xl border p-3.5 text-sm shadow-card '.$estilos[$type]]) }} role="alert">
    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white/70">
        <x-ui.icon :name="$iconos[$type]" class="h-4 w-4" />
    </span>
    <div class="min-w-0 flex-1">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-0.5' : '' }}">{{ $slot }}</div>
    </div>
</div>
