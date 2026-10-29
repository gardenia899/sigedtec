@props(['type' => 'info', 'title' => null])

@php
    // Paleta del Design System §20.2: el color siempre va acompañado de texto.
    $estilos = [
        'success' => 'border-green-200 bg-green-50 text-green-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'danger'  => 'border-red-200 bg-red-50 text-red-800',
        'info'    => 'border-sky-200 bg-sky-50 text-sky-800',
    ];
    $puntos = [
        'success' => 'bg-green-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-red-500',
        'info'    => 'bg-sky-500',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex gap-3 rounded-lg border p-4 text-sm '.$estilos[$type]]) }} role="alert">
    <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $puntos[$type] }}" aria-hidden="true"></span>
    <div class="flex-1">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-1' : '' }}">{{ $slot }}</div>
    </div>
</div>